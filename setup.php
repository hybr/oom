<?php
/**
 * V4L Setup Script
 * Run this script to set up V4L for the first time
 */

echo "=================================================\n";
echo "   V4L (Vocal 4 Local) - Setup Script\n";
echo "=================================================\n\n";

// Check PHP version
echo "[1/5] Checking PHP version...\n";
if (version_compare(PHP_VERSION, '8.1.0', '<')) {
    echo "ERROR: PHP 8.1 or higher is required. Current version: " . PHP_VERSION . "\n";
    exit(1);
}
echo "✓ PHP version: " . PHP_VERSION . "\n\n";

// Check required extensions
echo "[2/5] Checking required extensions...\n";
$required = ['pdo', 'json', 'mbstring'];
foreach ($required as $ext) {
    if (!extension_loaded($ext)) {
        echo "ERROR: Required extension '$ext' is not loaded.\n";
        exit(1);
    }
    echo "✓ Extension '$ext' is loaded\n";
}
echo "\n";

// Create directories
echo "[3/5] Creating directories...\n";
$dirs = ['database', 'logs', 'cache'];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✓ Created directory: $dir\n";
        } else {
            echo "ERROR: Failed to create directory: $dir\n";
            exit(1);
        }
    } else {
        echo "✓ Directory exists: $dir\n";
    }
}
echo "\n";

// Copy .env.example to .env if not exists
echo "[4/5] Setting up environment configuration...\n";
if (!file_exists('.env')) {
    if (copy('.env.example', '.env')) {
        echo "✓ Created .env from .env.example\n";
        echo "  Please edit .env with your configuration.\n";
    } else {
        echo "WARNING: Could not create .env file. Please copy .env.example manually.\n";
    }
} else {
    echo "✓ .env file already exists\n";
}
echo "\n";

// Initialize meta database
echo "[5/5] Initializing meta database...\n";
require_once 'database/init-meta-db.php';

echo "\n";
echo "=================================================\n";
echo "   Setup Complete!\n";
echo "=================================================\n\n";

echo "Next steps:\n";
echo "1. Configure your web server to point to the 'public' directory\n";
echo "2. Visit your site in a browser\n";
echo "3. Create an account at /auth/signup\n";
echo "4. Start managing entities!\n\n";

echo "For detailed instructions, see QUICK_START.md\n\n";
