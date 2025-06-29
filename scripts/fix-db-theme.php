<?php

/**
 * Quick Database Theme Fix Script
 * Fixes WordPress database theme references and restores Appearance > Themes menu
 * 
 * Can be run independently or called by setup-theme.php
 * 
 * Usage:
 *   php scripts/fix-db-theme.php [theme-name]
 *   php scripts/fix-db-theme.php mytheme
 */

// Get theme name from command line argument, environment variable, or use default
$themeName = $argv[1] ?? getenv('THEME_NAME') ?? 'balinale';

// Load database configuration from db.txt or .env file
$projectRoot = __DIR__ . '/../';
$dbConfig = [
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'wordpress',
    'DB_USER' => 'root',
    'DB_PASSWORD' => '',
    'DB_PREFIX' => 'wp_'
];

function parseDbConfigFile($filePath, &$dbConfig) {
    if (file_exists($filePath)) {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (preg_match('/^(DB_HOST|DB_NAME|DB_USER|DB_PASSWORD|DB_PREFIX)=(.*)$/', $line, $matches)) {
                $dbConfig[$matches[1]] = trim($matches[2], '"');
            }
        }
    }
}

// 1. Try db.txt
parseDbConfigFile($projectRoot . 'db.txt', $dbConfig);
// 2. Try .env if not set
parseDbConfigFile($projectRoot . '.env', $dbConfig);

$host = $dbConfig['DB_HOST'];
$database = $dbConfig['DB_NAME'];
$username = $dbConfig['DB_USER'];
$password = $dbConfig['DB_PASSWORD'];
$prefix = $dbConfig['DB_PREFIX'];

echo "🔧 Fixing WordPress database theme references...\n";
echo "Theme: {$themeName}\n";
echo "Database: {$database}\n";
echo "Table prefix: {$prefix}\n";
echo "Host: {$host}\n\n";

try {
    $mysqli = new mysqli($host, $username, $password, $database);

    if ($mysqli->connect_error) {
        echo "⚠️  Database connection failed: {$mysqli->connect_error}\n";
        echo "   This is normal if you don't have a local database running.\n";
        echo "   You can manually update your WordPress database with these SQL commands:\n\n";
        echo "   UPDATE {$prefix}options SET option_value = '{$themeName}' WHERE option_name IN ('template', 'stylesheet');\n";
        echo "   UPDATE {$prefix}options SET option_value = REPLACE(option_value, 'asdrock', '{$themeName}') WHERE option_name IN ('siteurl', 'home') AND option_value LIKE '%asdrock%';\n";
        echo "   UPDATE {$prefix}options SET option_value = '" . ucfirst($themeName) . " Theme' WHERE option_name = 'blogname' AND option_value LIKE '%asdrock%';\n\n";
        echo "   Or run this script again when your database is accessible.\n";
        return;
    }

    echo "✅ Connected to database successfully.\n";

    // First, check what theme is currently set
    $checkQuery = "SELECT option_name, option_value FROM {$prefix}options WHERE option_name IN ('template', 'stylesheet')";
    $result = $mysqli->query($checkQuery);
    
    if ($result) {
        echo "📊 Current theme settings:\n";
        while ($row = $result->fetch_assoc()) {
            echo "   {$row['option_name']}: {$row['option_value']}\n";
        }
    }

    // Update template and stylesheet options
    $updateQuery = "
        UPDATE {$prefix}options 
        SET option_value = ? 
        WHERE option_name IN ('template', 'stylesheet')
    ";

    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param('s', $themeName);
    
    if ($stmt->execute()) {
        $affectedRows = $stmt->affected_rows;
        echo "✅ Updated {$affectedRows} database records.\n";
        
        if ($affectedRows > 0) {
            echo "✅ Theme references updated to '{$themeName}'.\n";
        } else {
            echo "ℹ️  No records were updated (theme might already be set correctly).\n";
        }
    } else {
        echo "❌ Database update failed: {$stmt->error}\n";
    }

    // Also update site URL and blog name if they reference the old theme
    $siteUrlQuery = "
        UPDATE {$prefix}options 
        SET option_value = REPLACE(option_value, 'balinale', ?) 
        WHERE option_name IN ('siteurl', 'home') AND option_value LIKE '%balinale%'
    ";

    $stmt2 = $mysqli->prepare($siteUrlQuery);
    $stmt2->bind_param('s', $themeName);
    
    if ($stmt2->execute()) {
        $affectedRows2 = $stmt2->affected_rows;
        if ($affectedRows2 > 0) {
            echo "✅ Updated {$affectedRows2} site URL references.\n";
        }
    }

    // Update blog name if it references the old theme
    $blogNameQuery = "
        UPDATE {$prefix}options 
        SET option_value = ? 
        WHERE option_name = 'blogname' AND option_value LIKE '%balinale%'
    ";

    $stmt3 = $mysqli->prepare($blogNameQuery);
    $newBlogName = ucfirst($themeName) . ' Theme';
    $stmt3->bind_param('s', $newBlogName);
    
    if ($stmt3->execute()) {
        $affectedRows3 = $stmt3->affected_rows;
        if ($affectedRows3 > 0) {
            echo "✅ Updated blog name to '{$newBlogName}'.\n";
        }
    }

    // Also check if there are any other theme-related options that might be causing issues
    $checkOtherQuery = "SELECT option_name, option_value FROM {$prefix}options WHERE option_name LIKE '%theme%' OR option_value LIKE '%balinale%' OR option_value LIKE '%asdrock%'";
    $otherResult = $mysqli->query($checkOtherQuery);
    
    if ($otherResult && $otherResult->num_rows > 0) {
        echo "\n🔍 Found other theme-related options:\n";
        while ($row = $otherResult->fetch_assoc()) {
            echo "   {$row['option_name']}: {$row['option_value']}\n";
        }
    }

    $stmt->close();
    $stmt2->close();
    $stmt3->close();
    $mysqli->close();

    echo "\n✅ Database fix completed!\n";
    echo "You should now be able to see the Appearance > Themes menu.\n";
    echo "Try refreshing your WordPress admin page.\n";

} catch (Exception $e) {
    echo "⚠️  Database connection failed: {$e->getMessage()}\n";
    echo "   This is normal if you don't have a local database running.\n";
    echo "   You can manually update your WordPress database with these SQL commands:\n\n";
    echo "   UPDATE {$prefix}options SET option_value = '{$themeName}' WHERE option_name IN ('template', 'stylesheet');\n";
    echo "   UPDATE {$prefix}options SET option_value = REPLACE(option_value, 'balinale', '{$themeName}') WHERE option_name IN ('siteurl', 'home') AND option_value LIKE '%balinale%';\n";
    echo "   UPDATE {$prefix}options SET option_value = '" . ucfirst($themeName) . " Theme' WHERE option_name = 'blogname' AND option_value LIKE '%balinale%';\n\n";
    echo "   Or run this script again when your database is accessible.\n";
} 