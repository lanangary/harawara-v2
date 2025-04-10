<?php

$simpleTxtPath = __DIR__ . '/../simple.txt';
$themeDir = __DIR__ . '/../www/app/themes/';
$defaultThemeName = 'balinale';

// Check if simple.txt exists
if (!file_exists($simpleTxtPath)) {
    die("Error: simple.txt file not found.\n");
}

// Read the theme name from simple.txt
$content = file_get_contents($simpleTxtPath);
preg_match('/theme_name\s*=\s*(\w+)/', $content, $matches);

if (!isset($matches[1])) {
    die("Error: Theme name not found in simple.txt.\n");
}

$newThemeName = $matches[1];

// Paths for renaming
$currentThemePath = $themeDir . $defaultThemeName;
$newThemePath = $themeDir . $newThemeName;

// Rename the theme directory
if (is_dir($currentThemePath)) {
    if (rename($currentThemePath, $newThemePath)) {
        echo "Theme renamed to '{$newThemeName}' successfully.\n";
    } else {
        die("Error: Failed to rename theme folder.\n");
    }
} else {
    die("Error: Default theme folder '{$defaultThemeName}' does not exist.\n");
}

// Update other references if needed (e.g., file contents, paths, etc.)
// Add additional logic here if required.

