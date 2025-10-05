<?php

/**
 * Bootstrap file - Initialize the application
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load environment variables from .env file
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
            putenv("{$name}={$value}");
        }
    }
}

// Composer autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Manual autoloader for entities and lib
spl_autoload_register(function ($class) {
    $prefixes = [
        'App\\' => __DIR__ . '/lib/',
        'Entities\\' => __DIR__ . '/entities/',
        'Processes\\' => __DIR__ . '/processes/',
        'Services\\' => __DIR__ . '/services/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

// Load configuration files
$config = [];
$config['app'] = require __DIR__ . '/config/app.php';
$config['database'] = require __DIR__ . '/config/database.php';
$config['websocket'] = require __DIR__ . '/config/websocket.php';

// Initialize Database
\App\Database::init($config['database']);

// Error handling based on environment
if ($config['app']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Set timezone
date_default_timezone_set($config['app']['timezone'] ?? 'UTC');

// Helper functions
function view(string $file, array $data = []): void
{
    extract($data);
    require __DIR__ . '/views/' . $file . '.php';
}

function config(string $key, $default = null)
{
    global $config;
    $keys = explode('.', $key);
    $value = $config;

    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return $default;
        }
        $value = $value[$k];
    }

    return $value;
}

function redirect(string $url, int $code = 302): void
{
    header("Location: {$url}", true, $code);
    exit;
}

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

function url(string $path = ''): string
{
    $baseUrl = config('app.url', 'http://localhost');
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}

function old(string $key, $default = ''): string
{
    return $_SESSION['_old'][$key] ?? $default;
}

function errors(string $key = null)
{
    if ($key === null) {
        return $_SESSION['_errors'] ?? [];
    }
    return $_SESSION['_errors'][$key] ?? null;
}

function session(string $key, $default = null)
{
    return $_SESSION[$key] ?? $default;
}

function auth(): ?object
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    static $user = null;
    if ($user === null) {
        $user = \Entities\Person::find($_SESSION['user_id']);
    }

    return $user;
}

function csrf_token(): string
{
    if (!isset($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf(): bool
{
    $token = $_POST['_csrf_token'] ?? $_GET['_csrf_token'] ?? '';
    return hash_equals($_SESSION['_csrf_token'] ?? '', $token);
}

// Clear old input and errors after use
if (isset($_SESSION['_old'])) {
    unset($_SESSION['_old']);
}
if (isset($_SESSION['_errors'])) {
    unset($_SESSION['_errors']);
}
