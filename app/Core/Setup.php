<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;

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
    'ai_model_name' => 'gemma-4-26b-a4b-it'
];

foreach ($settings as $key => $value) {
    $stmt = $db->prepare("INSERT OR REPLACE INTO settings (key_name, key_value) VALUES (?, ?)");
    $stmt->execute([$key, $value]);
}

echo "Database initialized and seeded successfully.\n";
