<?php

$themeNameFile = __DIR__ . '/../theme-name.txt';

echo "Enter desired theme folder name (or press Enter to use default 'balinale'): ";
$input = trim(fgets(STDIN));
$themeName = $input ?: 'balinale';

if (file_put_contents($themeNameFile, $themeName) === false) {
    die("Error: Failed to write theme name to 'theme-name.txt'.\n");
}

echo "Theme name '{$themeName}' has been saved to 'theme-name.txt'.\n";
