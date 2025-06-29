<?php

echo "🔍 Debugging .env file reading...\n";

$envPath = __DIR__ . '/../.env';
echo "Looking for .env at: {$envPath}\n";

if (file_exists($envPath)) {
    echo "✅ .env file found!\n";
    $envContent = file_get_contents($envPath);
    echo "File content length: " . strlen($envContent) . " characters\n";
    
    // Try to read specific values
    preg_match('/DB_NAME=(.+)/', $envContent, $dbName);
    preg_match('/DB_PREFIX=(.+)/', $envContent, $dbPrefix);
    preg_match('/WP_THEME=(.+)/', $envContent, $wpTheme);
    
    echo "DB_NAME: " . ($dbName[1] ?? 'NOT FOUND') . "\n";
    echo "DB_PREFIX: " . ($dbPrefix[1] ?? 'NOT FOUND') . "\n";
    echo "WP_THEME: " . ($wpTheme[1] ?? 'NOT FOUND') . "\n";
    
} else {
    echo "❌ .env file not found!\n";
} 