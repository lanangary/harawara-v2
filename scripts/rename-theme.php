<?php

$defaultThemeName = 'balinale';
$themeDir = __DIR__ . '/../www/app/themes/';

// Read the theme name from the file created during composer install
$themeNameFile = __DIR__ . '/../theme-name.txt';
$newThemeName = is_file($themeNameFile) ? trim(file_get_contents($themeNameFile)) : $defaultThemeName;

if ($newThemeName === $defaultThemeName) {
    echo "Theme name is already '{$defaultThemeName}'. No renaming needed.\n";
    exit;
}

$currentThemePath = $themeDir . $defaultThemeName;
$newThemePath = $themeDir . $newThemeName;

if (is_dir($currentThemePath)) {
    if (rename($currentThemePath, $newThemePath)) {
        echo "Theme renamed to '{$newThemeName}' successfully.\n";
    } else {
        echo "Error: Failed to rename theme folder.\n";
    }
} else {
    echo "Error: Default theme folder '{$defaultThemeName}' does not exist.\n";
}