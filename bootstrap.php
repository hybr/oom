<?php

// Bootstrap file - initializes the application

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Define paths
define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
define('ENTITIES_PATH', ROOT_PATH . '/entities');
define('LIB_PATH', ROOT_PATH . '/lib');
define('SERVICES_PATH', ROOT_PATH . '/services');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('LOGS_PATH', ROOT_PATH . '/logs');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// Load environment variables from .env file
if (file_exists(ROOT_PATH . '/.env')) {
    $lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!getenv($name)) {
            putenv("$name=$value");
        }
    }
}

// Load configuration
function config($key, $default = null) {
    static $config = [];

    $parts = explode('.', $key);
    $file = array_shift($parts);

    if (!isset($config[$file])) {
        $configFile = CONFIG_PATH . '/' . $file . '.php';
        if (file_exists($configFile)) {
            $config[$file] = require $configFile;
        } else {
            return $default;
        }
    }

    $value = $config[$file];
    foreach ($parts as $part) {
        if (isset($value[$part])) {
            $value = $value[$part];
        } else {
            return $default;
        }
    }

    return $value;
}

// Autoloader
spl_autoload_register(function ($class) {
    $paths = [
        LIB_PATH,
        ENTITIES_PATH,
        SERVICES_PATH,
    ];

    foreach ($paths as $path) {
        $file = $path . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Session configuration
ini_set('session.cookie_httponly', config('app.session.http_only', true));
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Lax');

if (!session_id()) {
    session_name(config('app.session.name', 'V4L_SESSION'));
    session_start();
}

// Timezone
date_default_timezone_set(config('app.timezone', 'UTC'));

// Helper functions
function escape($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function old($key, $default = '') {
    return $_SESSION['old'][$key] ?? $default;
}

function error($key) {
    return $_SESSION['errors'][$key] ?? null;
}

function clearOldInput() {
    unset($_SESSION['old'], $_SESSION['errors']);
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function auth() {
    static $auth = null;
    if ($auth === null) {
        $auth = new Auth();
    }
    return $auth;
}

function db() {
    static $db = null;
    if ($db === null) {
        $db = new Database();
    }
    return $db;
}

function success($message) {
    $_SESSION['success'] = $message;
}

function flash($key) {
    $value = $_SESSION[$key] ?? null;
    unset($_SESSION[$key]);
    return $value;
}

function asset($path) {
    // Auto-detect the base URL from the current request
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    $baseUrl = $protocol . '://' . $host;

    return $baseUrl . '/assets/' . ltrim($path, '/');
}

function url($path = '') {
    // Auto-detect the base URL from the current request
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    $baseUrl = $protocol . '://' . $host;

    return $baseUrl . '/' . ltrim($path, '/');
}

function json_response($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
