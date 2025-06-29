<?php

/**
 * Theme Setup Script
 * Handles first-time theme naming and all reference updates
 * Run this script after composer install to set up your theme
 */

class ThemeSetup
{
    private $defaultThemeName = 'balinale';
    private $projectRoot;
    private $themeDir;
    private $newThemeName;
    private $humanThemeName;
    private $currentThemeName;

    public function __construct()
    {
        $this->projectRoot = __DIR__ . '/../';
        $this->themeDir = $this->projectRoot . 'www/app/themes/';
        $this->detectCurrentTheme();
    }

    private function detectCurrentTheme()
    {
        // Look for existing theme folders
        $themeFolders = glob($this->themeDir . '*', GLOB_ONLYDIR);
        
        if (empty($themeFolders)) {
            echo "❌ No theme folders found in {$this->themeDir}\n";
            exit(1);
        }

        // Get the first theme folder (excluding .gitkeep)
        $currentThemePath = $themeFolders[0];
        $this->currentThemeName = basename($currentThemePath);

        echo "🔍 Detected current theme: {$this->currentThemeName}\n";
    }

    public function run()
    {
        echo "🎨 Theme Setup Script\n";
        echo "===================\n\n";

        // Get theme name from user
        $this->getThemeName();

        // Validate current state
        if (!$this->validateSetup()) {
            return;
        }

        // Update all references
        $this->updateReferences();

        // Rename theme folder (last step)
        $this->renameThemeFolder();

        // Run additional setup steps
        $this->runAdditionalSetup();

        echo "\n✅ Theme setup completed successfully!\n";
        echo "Theme name: {$this->newThemeName}\n";
        echo "Human readable name: {$this->humanThemeName}\n";
    }

    private function getThemeName()
    {
        echo "Enter your theme name (e.g., 'mycompany', 'clientname'): ";
        $input = trim(fgets(STDIN));
        
        if (empty($input)) {
            echo "❌ Theme name cannot be empty. Using current theme name '{$this->currentThemeName}'.\n";
            $this->newThemeName = $this->currentThemeName;
        } else {
            // Sanitize theme name (lowercase, alphanumeric + hyphens)
            $this->newThemeName = strtolower(preg_replace('/[^a-zA-Z0-9-]/', '', $input));
        }

        echo "Enter human readable theme name (e.g., 'My Company Theme'): ";
        $humanInput = trim(fgets(STDIN));
        $this->humanThemeName = $humanInput ?: ucfirst($this->newThemeName) . ' Theme';

        echo "\n📝 Theme Configuration:\n";
        echo "  - Current folder: {$this->currentThemeName}\n";
        echo "  - New folder name: {$this->newThemeName}\n";
        echo "  - Display name: {$this->humanThemeName}\n\n";
    }

    private function validateSetup()
    {
        $currentThemePath = $this->themeDir . $this->currentThemeName;
        $newThemePath = $this->themeDir . $this->newThemeName;

        // Check if current theme exists
        if (!is_dir($currentThemePath)) {
            echo "❌ Error: Current theme folder '{$this->currentThemeName}' not found.\n";
            echo "   Expected path: {$currentThemePath}\n";
            return false;
        }

        // Check if new theme name already exists (and it's different)
        if (is_dir($newThemePath) && $this->newThemeName !== $this->currentThemeName) {
            echo "❌ Error: Theme folder '{$this->newThemeName}' already exists.\n";
            return false;
        }

        return true;
    }

    private function updateReferences()
    {
        echo "🔄 Updating theme references...\n";

        // 1. Update composer.json
        $this->updateComposerJson();

        // 2. Update package.json
        $this->updatePackageJson();

        // 3. Update style.css (before renaming folder)
        $this->updateStyleCss();

        // 4. Update wp-config.php
        $this->updateWpConfig();

        // 5. Update webpack-related files
        $this->updateWebpackReferences();

        // 6. Clear webpack caches
        $this->clearWebpackCaches();

        // 7. Update database references
        $this->updateDatabase();

        echo "✅ All references updated successfully.\n\n";
    }

    private function updateComposerJson()
    {
        $composerPath = $this->projectRoot . 'composer.json';
        if (file_exists($composerPath)) {
            $content = file_get_contents($composerPath);
            $composerData = json_decode($content, true);
            
            if ($composerData) {
                // Update the name field
                $composerData['name'] = 'juicebox/' . $this->newThemeName;
                
                // Update autoload paths - handle both psr-4 and psr-0 if they exist
                $autoloadTypes = ['psr-4', 'psr-0'];
                foreach ($autoloadTypes as $type) {
                    if (isset($composerData['autoload'][$type])) {
                        foreach ($composerData['autoload'][$type] as $namespace => $path) {
                            // Replace theme name in the path, regardless of namespace
                            $newPath = str_replace($this->currentThemeName, $this->newThemeName, $path);
                            $newPath = str_replace($this->defaultThemeName, $this->newThemeName, $newPath);
                            
                            // Also handle any __THEMENAME__ placeholders
                            $newPath = str_replace('__THEMENAME__', $this->newThemeName, $newPath);
                            
                            $composerData['autoload'][$type][$namespace] = $newPath;
                        }
                    }
                }
                
                // Also handle autoload-dev if it exists
                if (isset($composerData['autoload-dev'])) {
                    foreach ($autoloadTypes as $type) {
                        if (isset($composerData['autoload-dev'][$type])) {
                            foreach ($composerData['autoload-dev'][$type] as $namespace => $path) {
                                $newPath = str_replace($this->currentThemeName, $this->newThemeName, $path);
                                $newPath = str_replace($this->defaultThemeName, $this->newThemeName, $newPath);
                                $newPath = str_replace('__THEMENAME__', $this->newThemeName, $newPath);
                                
                                $composerData['autoload-dev'][$type][$namespace] = $newPath;
                            }
                        }
                    }
                }
                
                // Convert back to JSON and do additional string replacements
                $updatedContent = json_encode($composerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                
                // Final cleanup - replace any remaining references
                $updatedContent = str_replace($this->currentThemeName, $this->newThemeName, $updatedContent);
                $updatedContent = str_replace($this->defaultThemeName, $this->newThemeName, $updatedContent);
                $updatedContent = str_replace('__THEMENAME__', $this->newThemeName, $updatedContent);
                
                file_put_contents($composerPath, $updatedContent);
                echo "  ✅ Updated composer.json (including PSR-4 autoload paths)\n";
            } else {
                echo "  ⚠️  Failed to parse composer.json\n";
            }
        }
    }

    private function updatePackageJson()
    {
        $packagePath = $this->projectRoot . 'package.json';
        if (file_exists($packagePath)) {
            $content = file_get_contents($packagePath);
            $packageData = json_decode($content, true);
            
            if ($packageData) {
                // Update the name field
                $packageData['name'] = $this->newThemeName;
                
                // Also replace any __THEMENAME__ placeholders
                $updatedContent = json_encode($packageData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                $updatedContent = str_replace('__THEMENAME__', $this->newThemeName, $updatedContent);
                
                file_put_contents($packagePath, $updatedContent);
                echo "  ✅ Updated package.json\n";
            } else {
                echo "  ⚠️  Failed to parse package.json\n";
            }
        }
    }

    private function updateStyleCss()
    {
        $stylePath = $this->themeDir . $this->currentThemeName . '/style.css';
        if (file_exists($stylePath)) {
            $content = file_get_contents($stylePath);
            $updated = preg_replace(
                '/Theme Name:\s*.*/',
                'Theme Name: ' . $this->humanThemeName,
                $content
            );
            file_put_contents($stylePath, $updated);
            echo "  ✅ Updated style.css\n";
        }
    }

    private function updateWpConfig()
    {
        $wpConfigPath = $this->projectRoot . 'www/wp-config.php';
        if (file_exists($wpConfigPath)) {
            $content = file_get_contents($wpConfigPath);
            $updated = str_replace($this->currentThemeName, $this->newThemeName, $content);
            $updated = str_replace($this->defaultThemeName, $this->newThemeName, $updated);
            file_put_contents($wpConfigPath, $updated);
            echo "  ✅ Updated wp-config.php\n";
        }
    }

    private function updateWebpackReferences()
    {
        // Update webpack.mix.js if it has hardcoded theme names
        $webpackPath = $this->projectRoot . 'webpack.mix.js';
        if (file_exists($webpackPath)) {
            $content = file_get_contents($webpackPath);
            $updated = str_replace($this->currentThemeName, $this->newThemeName, $content);
            $updated = str_replace($this->defaultThemeName, $this->newThemeName, $updated);
            file_put_contents($webpackPath, $updated);
            echo "  ✅ Updated webpack.mix.js\n";
        }

        // Update any .env files that might reference the theme
        $envPath = $this->projectRoot . '.env';
        if (file_exists($envPath)) {
            $content = file_get_contents($envPath);
            $updated = str_replace($this->currentThemeName, $this->newThemeName, $content);
            $updated = str_replace($this->defaultThemeName, $this->newThemeName, $updated);
            file_put_contents($envPath, $updated);
            echo "  ✅ Updated .env file\n";
        }

        // Update any other config files that might reference the theme
        $this->updateConfigFiles();
    }

    private function updateConfigFiles()
    {
        // Update .prettierignore if it exists
        $prettierPath = $this->projectRoot . '.prettierignore';
        if (file_exists($prettierPath)) {
            $content = file_get_contents($prettierPath);
            $updated = str_replace($this->currentThemeName, $this->newThemeName, $content);
            $updated = str_replace($this->defaultThemeName, $this->newThemeName, $updated);
            file_put_contents($prettierPath, $updated);
            echo "  ✅ Updated .prettierignore\n";
        }

        // Update phpcs.xml if it exists
        $phpcsPath = $this->projectRoot . 'phpcs.xml';
        if (file_exists($phpcsPath)) {
            $content = file_get_contents($phpcsPath);
            $updated = str_replace($this->currentThemeName, $this->newThemeName, $content);
            $updated = str_replace($this->defaultThemeName, $this->newThemeName, $updated);
            file_put_contents($phpcsPath, $updated);
            echo "  ✅ Updated phpcs.xml\n";
        }

        // Update any other common config files
        $configFiles = [
            '.eslintrc.js',
            '.eslintrc.json',
            'browserslist',
            'babel.config.js',
            'postcss.config.js'
        ];

        foreach ($configFiles as $configFile) {
            $configPath = $this->projectRoot . $configFile;
            if (file_exists($configPath)) {
                $content = file_get_contents($configPath);
                $updated = str_replace($this->currentThemeName, $this->newThemeName, $content);
                $updated = str_replace($this->defaultThemeName, $this->newThemeName, $updated);
                file_put_contents($configPath, $updated);
                echo "  ✅ Updated {$configFile}\n";
            }
        }
    }

    private function updateDatabase()
    {
        echo "  🔄 Updating database theme references...\n";
        
        // Load environment variables from db.txt or .env file
        $envPath = $this->projectRoot . '.env';
        $dbTxtPath = $this->projectRoot . 'db.txt';
        $dbConfig = [
            'DB_HOST' => 'localhost',
            'DB_NAME' => 'wordpress',
            'DB_USER' => 'root',
            'DB_PASSWORD' => '',
            'DB_PREFIX' => 'wp_'
        ];
        $parseDbConfigFile = function($filePath, &$dbConfig) {
            if (file_exists($filePath)) {
                $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (preg_match('/^(DB_HOST|DB_NAME|DB_USER|DB_PASSWORD|DB_PREFIX)=(.*)$/', $line, $matches)) {
                        $dbConfig[$matches[1]] = trim($matches[2], '"');
                    }
                }
            }
        };
        // 1. Try db.txt
        $parseDbConfigFile($dbTxtPath, $dbConfig);
        // 2. Try .env if not set
        $parseDbConfigFile($envPath, $dbConfig);

        $host = $dbConfig['DB_HOST'];
        $database = $dbConfig['DB_NAME'];
        $username = $dbConfig['DB_USER'];
        $password = $dbConfig['DB_PASSWORD'];
        $prefix = $dbConfig['DB_PREFIX'];

        echo "    📊 Database config: {$database} (prefix: {$prefix})\n";

        try {
            $mysqli = new mysqli($host, $username, $password, $database);

            if ($mysqli->connect_error) {
                echo "    ⚠️  Database connection failed: {$mysqli->connect_error}\n";
                echo "       Skipping database updates. You may need to update manually.\n";
                echo "       Expected database: {$database}\n";
                return;
            }

            echo "    ✅ Connected to database: {$database}\n";

            // First, check what theme is currently set
            $checkQuery = "SELECT option_name, option_value FROM {$prefix}options WHERE option_name IN ('template', 'stylesheet')";
            $result = $mysqli->query($checkQuery);
            
            if ($result) {
                echo "    📊 Current theme settings:\n";
                while ($row = $result->fetch_assoc()) {
                    echo "       {$row['option_name']}: {$row['option_value']}\n";
                }
            }

            // Update template and stylesheet options
            $updateQuery = "
                UPDATE {$prefix}options 
                SET option_value = ? 
                WHERE option_name IN ('template', 'stylesheet')
            ";

            $stmt = $mysqli->prepare($updateQuery);
            $stmt->bind_param('s', $this->newThemeName);
            
            if ($stmt->execute()) {
                $affectedRows = $stmt->affected_rows;
                echo "    ✅ Updated {$affectedRows} database records.\n";
                
                if ($affectedRows > 0) {
                    echo "    ✅ Theme references updated to '{$this->newThemeName}'.\n";
                } else {
                    echo "    ℹ️  No records were updated (theme might already be set correctly).\n";
                }
            } else {
                echo "    ⚠️  Database update failed: {$stmt->error}\n";
            }

            $stmt->close();
            $mysqli->close();

        } catch (Exception $e) {
            echo "    ⚠️  Database error: {$e->getMessage()}\n";
            echo "       Skipping database updates. You may need to update manually.\n";
        }
    }

    private function clearWebpackCaches()
    {
        echo "  🧹 Clearing webpack caches...\n";

        // Remove node_modules/.cache if it exists
        $cachePath = $this->projectRoot . 'node_modules/.cache';
        if (is_dir($cachePath)) {
            $this->removeDirectory($cachePath);
            echo "    ✅ Cleared node_modules/.cache\n";
        }

        // Remove webpack cache files
        $webpackCacheFiles = [
            'node_modules/.cache/webpack',
            'node_modules/.cache/babel-loader',
            'node_modules/.cache/sass-loader'
        ];

        foreach ($webpackCacheFiles as $cacheFile) {
            $cachePath = $this->projectRoot . $cacheFile;
            if (is_dir($cachePath)) {
                $this->removeDirectory($cachePath);
                echo "    ✅ Cleared {$cacheFile}\n";
            }
        }

        // Remove any existing dist folders in the new theme location
        $newDistPath = $this->themeDir . $this->newThemeName . '/dist';
        if (is_dir($newDistPath)) {
            $this->removeDirectory($newDistPath);
            echo "    ✅ Cleared existing dist folder\n";
        }

        // Also clear the old theme's dist folder if it exists
        $oldDistPath = $this->themeDir . $this->currentThemeName . '/dist';
        if (is_dir($oldDistPath)) {
            $this->removeDirectory($oldDistPath);
            echo "    ✅ Cleared old theme dist folder\n";
        }
    }

    private function removeDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    private function renameThemeFolder()
    {
        if ($this->newThemeName === $this->currentThemeName) {
            echo "ℹ️  Theme name unchanged. No folder renaming needed.\n";
            return;
        }

        $currentPath = $this->themeDir . $this->currentThemeName;
        $newPath = $this->themeDir . $this->newThemeName;

        if (rename($currentPath, $newPath)) {
            echo "✅ Theme folder renamed: {$this->currentThemeName} → {$this->newThemeName}\n";
        } else {
            echo "❌ Failed to rename theme folder. Please rename manually:\n";
            echo "   From: {$currentPath}\n";
            echo "   To: {$newPath}\n";
        }
    }

    private function runAdditionalSetup()
    {
        echo "\n🔄 Running additional setup steps...\n";

        // 1. Run database fix script
        $this->runDatabaseFixScript();

        // 2. Run composer dump-autoload
        $this->runComposerDumpAutoload();

        echo "✅ Additional setup steps completed.\n";
    }

    private function runDatabaseFixScript()
    {
        echo "  🔄 Running database fix script...\n";
        
        $dbFixScript = $this->projectRoot . 'scripts/fix-db-theme.php';
        
        if (file_exists($dbFixScript)) {
            // Run the database fix script with theme name as argument
            $output = [];
            $returnCode = 0;
            
            exec("php \"{$dbFixScript}\" \"{$this->newThemeName}\"", $output, $returnCode);
            
            if ($returnCode === 0) {
                echo "    ✅ Database fix script completed successfully.\n";
            } else {
                echo "    ⚠️  Database fix script completed with warnings.\n";
            }
            
            // Show output if any
            if (!empty($output)) {
                foreach ($output as $line) {
                    echo "      {$line}\n";
                }
            }
        } else {
            echo "    ⚠️  Database fix script not found at: {$dbFixScript}\n";
            echo "       You may need to run 'php scripts/fix-db-theme.php' manually.\n";
        }
    }

    private function runComposerDumpAutoload()
    {
        echo "  🔄 Running composer dump-autoload...\n";
        
        $composerPath = $this->projectRoot . 'composer.json';
        
        if (file_exists($composerPath)) {
            $output = [];
            $returnCode = 0;
            
            // Change to project root directory
            $originalDir = getcwd();
            chdir($this->projectRoot);
            
            exec('composer dump-autoload', $output, $returnCode);
            
            // Change back to original directory
            chdir($originalDir);
            
            if ($returnCode === 0) {
                echo "    ✅ Composer autoloader regenerated successfully.\n";
            } else {
                echo "    ⚠️  Composer dump-autoload completed with warnings.\n";
            }
            
            // Show output if any
            if (!empty($output)) {
                foreach ($output as $line) {
                    echo "      {$line}\n";
                }
            }
        } else {
            echo "    ⚠️  composer.json not found. Skipping composer dump-autoload.\n";
        }
    }
}

// Run the setup
$setup = new ThemeSetup();
$setup->run(); 