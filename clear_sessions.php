<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Clear all sessions
$sessionPath = storage_path('framework/sessions');
if (is_dir($sessionPath)) {
    $files = glob($sessionPath . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "Cleared " . count($files) . " session files.\n";
} else {
    echo "Session directory not found.\n";
}

echo "\nAll sessions cleared! Please refresh your browser and log in again.\n";
