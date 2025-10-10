<?php
/**
 * Application Configuration
 * V4L (Vocal 4 Local)
 */

class Config
{
    private static $config = [];
    private static $loaded = false;

    /**
     * Load environment variables from .env file
     */
    public static function load()
    {
        if (self::$loaded) {
            return;
        }

        $envFile = BASE_PATH . '/.env';

        if (!file_exists($envFile)) {
            // Use .env.example as fallback for development
            $envFile = BASE_PATH . '/.env.example';
        }

        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                // Skip comments
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }

                // Parse KEY=VALUE
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);

                    // Remove quotes if present
                    $value = trim($value, '"\'');

                    putenv("$key=$value");
                    $_ENV[$key] = $value;
                }
            }
        }

        // Build config array
        self::$config = [
            'app' => [
                'name' => self::env('APP_NAME', 'V4L'),
                'env' => self::env('APP_ENV', 'production'),
                'debug' => self::env('APP_DEBUG', 'false') === 'true',
                'url' => self::env('APP_URL', 'http://localhost'),
                'timezone' => self::env('APP_TIMEZONE', 'UTC'),
            ],
            'database' => [
                'meta' => [
                    'driver' => 'sqlite',
                    'path' => BASE_PATH . '/' . self::env('META_DB_PATH', 'database/meta.sqlite'),
                ],
                'default' => [
                    'driver' => self::env('DB_CONNECTION', 'sqlite'),
                    'host' => self::env('DB_HOST', 'localhost'),
                    'port' => self::env('DB_PORT', '5432'),
                    'database' => BASE_PATH . '/' . self::env('DB_DATABASE', 'database/v4l.sqlite'),
                    'username' => self::env('DB_USERNAME', ''),
                    'password' => self::env('DB_PASSWORD', ''),
                ],
            ],
            'session' => [
                'lifetime' => (int) self::env('SESSION_LIFETIME', '120'),
                'secure' => self::env('SESSION_SECURE', 'false') === 'true',
                'http_only' => self::env('SESSION_HTTP_ONLY', 'true') === 'true',
            ],
            'security' => [
                'csrf_token_name' => self::env('CSRF_TOKEN_NAME', 'csrf_token'),
                'encryption_key' => self::env('ENCRYPTION_KEY', ''),
            ],
            'websocket' => [
                'host' => self::env('WEBSOCKET_HOST', 'localhost'),
                'port' => (int) self::env('WEBSOCKET_PORT', '8080'),
            ],
            'logging' => [
                'level' => self::env('LOG_LEVEL', 'debug'),
                'path' => BASE_PATH . '/' . self::env('LOG_PATH', 'logs/app.log'),
            ],
            'cache' => [
                'driver' => self::env('CACHE_DRIVER', 'file'),
                'path' => BASE_PATH . '/' . self::env('CACHE_PATH', 'cache/'),
            ],
            'upload' => [
                'max_size' => (int) self::env('MAX_UPLOAD_SIZE', '10485760'),
                'allowed_extensions' => explode(',', self::env('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,gif,pdf')),
            ],
        ];

        self::$loaded = true;
    }

    /**
     * Get environment variable
     */
    private static function env($key, $default = null)
    {
        $value = getenv($key);
        return $value !== false ? $value : $default;
    }

    /**
     * Get configuration value using dot notation
     */
    public static function get($key, $default = null)
    {
        if (!self::$loaded) {
            self::load();
        }

        $keys = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    /**
     * Set configuration value
     */
    public static function set($key, $value)
    {
        if (!self::$loaded) {
            self::load();
        }

        $keys = explode('.', $key);
        $config = &self::$config;

        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }

        $config = $value;
    }
}

// Auto-load configuration
Config::load();
