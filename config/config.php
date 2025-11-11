<?php
/**
 * V4L (Vocal 4 Local) - Configuration File
 *
 * Central configuration for the application
 */

// Error reporting - set to 0 in production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('UTC');

// Environment detection
define('ENVIRONMENT', getenv('APP_ENV') ?: 'development');
define('IS_DEVELOPMENT', ENVIRONMENT === 'development');
define('IS_PRODUCTION', ENVIRONMENT === 'production');

// Base paths
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('LIB_PATH', ROOT_PATH . '/lib');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('TEMPLATE_PATH', ROOT_PATH . '/templates');
define('METADATA_PATH', ROOT_PATH . '/metadata');

// Base URL configuration
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', $protocol . $host);

// Database configuration
define('DB_TYPE', getenv('DB_TYPE') ?: (IS_PRODUCTION ? 'pgsql' : 'sqlite'));

if (DB_TYPE === 'sqlite') {
    define('DB_PATH', ROOT_PATH . '/data/v4l.db');
    define('DB_DSN', 'sqlite:' . DB_PATH);
    define('DB_USER', null);
    define('DB_PASS', null);
} else {
    // PostgreSQL configuration
    define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
    define('DB_PORT', getenv('DB_PORT') ?: '5432');
    define('DB_NAME', getenv('DB_NAME') ?: 'v4l');
    define('DB_USER', getenv('DB_USER') ?: 'postgres');
    define('DB_PASS', getenv('DB_PASS') ?: '');
    define('DB_DSN', 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME);
}

// Session configuration
define('SESSION_LIFETIME', 86400); // 24 hours
define('SESSION_NAME', 'V4L_SESSION');

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_MIN_LENGTH', 8);

// File upload configuration
define('UPLOAD_MAX_SIZE', 10 * 1024 * 1024); // 10MB
define('UPLOAD_ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf']);
define('UPLOAD_PATH', ROOT_PATH . '/data/uploads');

// API configuration
define('API_RATE_LIMIT', 100); // requests per minute
define('API_VERSION', 'v1');

// Pagination
define('DEFAULT_PAGE_SIZE', 20);
define('MAX_PAGE_SIZE', 100);

// Application settings
define('APP_NAME', 'V4L - Vocal 4 Local');
define('APP_VERSION', '1.0.0');
define('APP_DESCRIPTION', 'Your Community, Your Marketplace');

// Cache settings
define('CACHE_ENABLED', IS_PRODUCTION);
define('CACHE_LIFETIME', 3600); // 1 hour

// Multi-organization support
define('MULTI_ORG_ENABLED', true);
define('DEFAULT_SUBDOMAIN', 'www');

// Authentication
define('AUTH_TOKEN_EXPIRY', 3600); // 1 hour
define('REFRESH_TOKEN_EXPIRY', 2592000); // 30 days

// Email configuration (if needed)
define('SMTP_HOST', getenv('SMTP_HOST') ?: '');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USER', getenv('SMTP_USER') ?: '');
define('SMTP_PASS', getenv('SMTP_PASS') ?: '');
define('SMTP_FROM', getenv('SMTP_FROM') ?: 'noreply@v4l.local');

// Logging
define('LOG_PATH', ROOT_PATH . '/logs');
define('LOG_LEVEL', IS_DEVELOPMENT ? 'DEBUG' : 'ERROR');

// Create necessary directories if they don't exist
$directories = [
    ROOT_PATH . '/data',
    UPLOAD_PATH,
    LOG_PATH,
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}
