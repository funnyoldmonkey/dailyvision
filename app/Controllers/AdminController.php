<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Session;

class AdminController
{
    public function index(): void
    {
        if (!Session::get('is_boss')) {
            $this->showLogin();
            return;
        }

        $db = Database::getInstance();
        
        // Get settings
        $stmt = $db->query("SELECT * FROM settings");
        $settings = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $settings[$row['key_name']] = $row['key_value'];
        }

        // Get visions for management
        $stmt = $db->query("SELECT * FROM visions ORDER BY created_at DESC");
        $visions = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        view('admin.dashboard', [
            'settings' => $settings,
            'visions' => $visions,
            'title' => 'Boss Panel | Daily Vision'
        ]);
    }

    private function showLogin(?string $error = null): void
    {
        view('admin.login', [
            'error' => $error,
            'title' => 'Boss Access | Daily Vision'
        ]);
    }

    public function login(): void
    {
        $password = $_POST['password'] ?? '';
        $db = Database::getInstance();
        
        $stmt = $db->prepare("SELECT key_value FROM settings WHERE key_name = 'boss_password'");
        $stmt->execute();
        $storedHash = $stmt->fetchColumn();

        if (hash('sha256', $password) === $storedHash) {
            Session::set('is_boss', true);
            redirect('boss');
        } else {
            $this->showLogin('Incorrect answer. Access denied.');
        }
    }

    public function logout(): void
    {
        Session::remove('is_boss');
        redirect('boss');
    }

    public function updateSettings(): void
    {
        if (!Session::get('is_boss')) {
            redirect('boss');
        }

        $apiKey = $_POST['gemini_api_key'] ?? '';
        $modelName = $_POST['ai_model_name'] ?? '';

        $db = Database::getInstance();
        
        $stmt = $db->prepare("UPDATE settings SET key_value = ? WHERE key_name = 'gemini_api_key'");
        $stmt->execute([$apiKey]);

        $stmt = $db->prepare("UPDATE settings SET key_value = ? WHERE key_name = 'ai_model_name'");
        $stmt->execute([$modelName]);

        redirect('boss?success=settings');
    }

    public function deleteVisions(): void
    {
        if (!Session::get('is_boss')) {
            redirect('boss');
        }

        $visionIds = $_POST['vision_ids'] ?? [];
        $deleteAll = isset($_POST['delete_all']);

        $db = Database::getInstance();

        if ($deleteAll) {
            $stmt = $db->query("SELECT id, image_path FROM visions");
            $visions = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($visions as $v) {
                $this->cleanupVision($v);
            }
            $db->exec("DELETE FROM visions");
        } elseif (!empty($visionIds)) {
            foreach ($visionIds as $id) {
                $stmt = $db->prepare("SELECT id, image_path FROM visions WHERE id = ?");
                $stmt->execute([$id]);
                $v = $stmt->fetch(\PDO::FETCH_ASSOC);
                if ($v) {
                    $this->cleanupVision($v);
                    $stmt = $db->prepare("DELETE FROM visions WHERE id = ?");
                    $stmt->execute([$id]);
                }
            }
        }

        redirect('boss?success=deleted');
    }

    private function cleanupVision(array $vision): void
    {
        if (!empty($vision['image_path'])) {
            $fullPath = __DIR__ . '/../../public/' . $vision['image_path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
}
