<?php
/**
 * Bootstrap file for V4L (Vocal 4 Local)
 * Initializes core application components
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define base paths
define('BASE_PATH', __DIR__);
define('LIB_PATH', BASE_PATH . '/lib');
define('CONFIG_PATH', BASE_PATH . '/config');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('DATABASE_PATH', BASE_PATH . '/database');

// Autoloader for classes
spl_autoload_register(function ($class) {
    $file = LIB_PATH . '/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Load configuration
require_once CONFIG_PATH . '/app.php';

// Error handling based on environment
if (Config::get('app.debug')) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Set timezone
date_default_timezone_set(Config::get('app.timezone', 'UTC'));
