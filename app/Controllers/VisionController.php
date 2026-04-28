<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

class VisionController
{
    public function save(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['image'])) {
            json(['error' => 'Invalid data provided']);
        }

        $imageData = $input['image'];
        // Remove prefix if exists
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
        }
        $binaryData = base64_decode($imageData);

        $id = bin2hex(random_bytes(6)); // 12 characters unique ID
        $fileName = $id . '.jpg';
        $filePath = 'visions/' . $fileName;
        $fullPath = __DIR__ . '/../../public/' . $filePath;

        if (!file_put_contents($fullPath, $binaryData)) {
            $error = error_get_last();
            json([
                'error' => 'Failed to save vision image. Check folder permissions.',
                'debug' => $error ? $error['message'] : 'No error message',
                'path' => $fullPath
            ]);
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO visions (id, image_path, verse_text, verse_reference, devotional_summary, full_devotion, vibe_color, unique_font) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $id,
            $filePath,
            $input['verseText'] ?? '',
            $input['verseReference'] ?? '',
            $input['devotionalSummary'] ?? '',
            $input['fullDevotion'] ?? '',
            $input['vibeColor'] ?? '',
            $input['uniqueFont'] ?? ''
        ]);

        json([
            'id' => $id,
            'url' => base_url('v/' . $id)
        ]);
    }

    public function view(string $id): void
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM visions WHERE id = ?");
        $stmt->execute([$id]);
        $vision = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$vision) {
            header("HTTP/1.0 404 Not Found");
            echo "Vision not found.";
            return;
        }

        // Get image dimensions for better OG preview
        $imagePath = __DIR__ . '/../../public/' . $vision['image_path'];
        $width = 1080;
        $height = 1350;
        if (file_exists($imagePath)) {
            $size = getimagesize($imagePath);
            if ($size) {
                $width = $size[0];
                $height = $size[1];
            }
        }

        view('vision_view', [
            'vision' => $vision,
            'title' => '"' . $vision['verse_text'] . '" | Daily Vision',
            'og_title' => $vision['verse_reference'] . ': ' . $vision['verse_text'],
            'og_description' => $vision['devotional_summary'],
            'og_image' => base_url($vision['image_path']),
            'og_image_width' => $width,
            'og_image_height' => $height,
            'og_url' => base_url('v/' . $id)
        ]);
    }

    public function gallery(): void
    {
        $db = Database::getInstance();
        
        // Simple Cleanup: Delete visions older than 30 days
        $db->exec("DELETE FROM visions WHERE created_at < datetime('now', '-30 days')");
        // Note: In a production app, we would also delete the physical files. 
        // For simplicity, we just filter the DB here.

        $stmt = $db->query("SELECT * FROM visions ORDER BY created_at DESC LIMIT 50");
        $visions = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        view('gallery', ['visions' => $visions]);
    }
}
