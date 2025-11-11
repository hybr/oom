<?php
/**
 * Simple PSR-4 Autoloader
 */

namespace V4L\Core;

class Autoloader
{
    private static array $namespaces = [];

    /**
     * Register the autoloader
     */
    public static function register(): void
    {
        spl_autoload_register([self::class, 'load']);

        // Register base namespace
        self::addNamespace('V4L', LIB_PATH);
    }

    /**
     * Add a namespace mapping
     */
    public static function addNamespace(string $namespace, string $path): void
    {
        self::$namespaces[$namespace] = $path;
    }

    /**
     * Load a class file
     */
    private static function load(string $className): void
    {
        // Convert namespace separators to directory separators
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);

        // Try each registered namespace
        foreach (self::$namespaces as $namespace => $path) {
            $namespace = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);

            if (strpos($className, $namespace) === 0) {
                $classPath = str_replace($namespace, $path, $className);
                $file = $classPath . '.php';

                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        }
    }
}
