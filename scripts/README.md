# Scripts Directory

This directory contains utility scripts for managing the WordPress theme setup.

## Theme Setup

### `setup-theme.php` - Main Theme Setup Script

This is the **primary script** for setting up your theme after a fresh `composer install`.

#### What it does:

1. **Prompts for theme name** - Asks for both folder name and human-readable name
2. **Updates all references** in the correct order:
    - `composer.json` (including autoload paths)
    - `package.json`
    - `style.css` (theme header)
    - `wp-config.php`
    - `webpack.mix.js`
    - `.env` file
    - `.prettierignore`
    - `phpcs.xml`
    - Other config files (`.eslintrc.js`, `browserslist`, etc.)
3. **Clears webpack caches** - Removes cached files to prevent build issues
4. **Renames theme folder** - Moves from current theme to your chosen name
5. **Uses environment config** - Reads database settings from `.env` file

#### Usage:

```bash
# Run after composer install (automatic)
composer install

# Or run manually
php scripts/setup-theme.php
```

#### Example:

```
🔍 Detected current theme: localme
🎨 Theme Setup Script
===================

Enter your theme name (e.g., 'mycompany', 'clientname'): harawara
Enter human readable theme name (e.g., 'My Company Theme'): Harawara Theme

📝 Theme Configuration:
  - Current folder: localme
  - New folder name: harawara
  - Display name: Harawara Theme

🔄 Updating theme references...
  ✅ Updated composer.json
  ✅ Updated package.json
  ✅ Updated style.css
  ✅ Updated wp-config.php
  ✅ Updated webpack.mix.js
  ✅ Updated .env file
  ✅ Updated .prettierignore
  🧹 Clearing webpack caches...
    ✅ Cleared node_modules/.cache
    ✅ Cleared existing dist folder
  ✅ Updated database theme references
✅ All references updated successfully.

✅ Theme folder renamed: localme → harawara

✅ Theme setup completed successfully!
Theme name: harawara
Human readable name: Harawara Theme
```

#### Cache Clearing

The script automatically clears:

-   `node_modules/.cache/` (webpack cache)
-   `node_modules/.cache/webpack/` (webpack-specific cache)
-   `node_modules/.cache/babel-loader/` (Babel cache)
-   `node_modules/.cache/sass-loader/` (Sass cache)
-   Existing `dist/` folders in the new theme location

This ensures a clean build after theme renaming.

## Legacy Scripts (Deprecated)

The following scripts are kept for reference but are no longer used:

-   `set-theme-name.php` - Old theme name setting
-   `rename-theme.php` - Old theme renaming
-   `rename-theme-from-simple.php` - Old simple.txt based renaming
-   `update-theme-references.php` - Old reference updating

## Database Configuration

The setup script automatically reads database configuration from your `.env` file:

```env
DB_HOST=localhost
DB_NAME=your_database
DB_USER=your_username
DB_PASSWORD=your_password
DB_PREFIX=wp_
```

If the `.env` file doesn't exist or database connection fails, the script will skip database updates and show a warning message.
