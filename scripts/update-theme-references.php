<?php

$simpleTxtPath = __DIR__ . '/../simple.txt';
$composerJsonPath = __DIR__ . '/../composer.json';
$themeStylePath = __DIR__ . '/../www/app/themes/localme/style.css';
$wpConfigPath = __DIR__ . '/../www/wp-config.php';
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

// Update composer.json
if (file_exists($composerJsonPath)) {
    $composerContent = file_get_contents($composerJsonPath);
    $updatedComposerContent = str_replace($defaultThemeName, $newThemeName, $composerContent);
    file_put_contents($composerJsonPath, $updatedComposerContent);
    echo "Updated composer.json references.\n";
}

// Update style.css
if (file_exists($themeStylePath)) {
    $styleContent = file_get_contents($themeStylePath);
    $updatedStyleContent = preg_replace('/Theme Name:\s*' . preg_quote($defaultThemeName, '/') . '/', 'Theme Name: ' . $newThemeName, $styleContent);
    file_put_contents($themeStylePath, $updatedStyleContent);
    echo "Updated style.css references.\n";
}

// Update wp-config.php (if needed)
if (file_exists($wpConfigPath)) {
    $wpConfigContent = file_get_contents($wpConfigPath);
    $updatedWpConfigContent = str_replace($defaultThemeName, $newThemeName, $wpConfigContent);
    file_put_contents($wpConfigPath, $updatedWpConfigContent);
    echo "Updated wp-config.php references.\n";
}

// Update database references
$mysqli = new mysqli('localhost', 'root', '', 'wordpress'); // Update with your DB credentials

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error . "\n");
}

$updateQuery = "
    UPDATE wp_options
    SET option_value = '$newThemeName'
    WHERE option_name IN ('template', 'stylesheet')
";

if ($mysqli->query($updateQuery) === TRUE) {
    echo "Updated database references.\n";
} else {
    echo "Error updating database: " . $mysqli->error . "\n";
}

$mysqli->close();

echo "All references updated successfully.\n";
