<?php

// Path to the simple.txt file
$simpleTxtPath = __DIR__ . '/../simple.txt';

// Read the theme name from simple.txt
if (!file_exists($simpleTxtPath)) {
    die("Error: simple.txt file not found.\n");
}

$content = file_get_contents($simpleTxtPath);
preg_match('/theme_name\s*=\s*(\w+)/', $content, $matches);

if (!isset($matches[1])) {
    die("Error: Theme name not found in simple.txt.\n");
}

$themeName = $matches[1];

// Generate the auth.json content
$authJson = [
    "http-basic" => [
        "connect.advancedcustomfields.com" => [
            "username" => "b3JkZXJfaWQ9NTQzMDd8dHlwZT1kZXZlbG9wZXJ8ZGF0ZT0yMDE1LTA0LTE3IDAzOjAwOjA1",
            "password" => "https://{$themeName}.local.box/"
        ]
    ]
];

// Write the auth.json file
$authJsonPath = __DIR__ . '/../auth.json';
file_put_contents($authJsonPath, json_encode($authJson, JSON_PRETTY_PRINT));

echo "auth.json file has been generated successfully.\n";
