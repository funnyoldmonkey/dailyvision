<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

class SetupController
{
    public function index(): void
    {
        $db = Database::getInstance();

        // Create settings table
        $db->exec("CREATE TABLE IF NOT EXISTS settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            key_name TEXT UNIQUE,
            key_value TEXT
        )");

        // Seed settings
        $settings = [
            'gemini_api_key' => 'YOUR_API_KEY_HERE',
            'ai_model_name' => 'YOUR_AI_MODEL_HERE'
        ];

        foreach ($settings as $key => $value) {
            $stmt = $db->prepare("INSERT OR REPLACE INTO settings (key_name, key_value) VALUES (?, ?)");
            $stmt->execute([$key, $value]);
        }

        echo "<h1>Daily Vision Setup</h1>";
        echo "<p style='color: green;'>✅ Database initialized and seeded successfully!</p>";
        echo "<p><a href='" . base_url() . "'>Go to App</a></p>";
    }
}
