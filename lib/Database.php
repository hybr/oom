<?php
/**
 * Database Connection Manager
 * Handles both meta and operational database connections
 */

class Database
{
    private static $metaConnection = null;
    private static $defaultConnection = null;

    /**
     * Get meta database connection (SQLite)
     */
    public static function meta()
    {
        if (self::$metaConnection === null) {
            $config = Config::get('database.meta');
            $dsn = 'sqlite:' . $config['path'];

            try {
                self::$metaConnection = new PDO($dsn);
                self::$metaConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$metaConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$metaConnection->exec('PRAGMA foreign_keys = ON');
            } catch (PDOException $e) {
                self::logError('Meta database connection failed: ' . $e->getMessage());
                throw $e;
            }
        }

        return self::$metaConnection;
    }

    /**
     * Get default operational database connection
     */
    public static function connection($name = 'default')
    {
        if (self::$defaultConnection === null) {
            $config = Config::get("database.{$name}");
            $driver = $config['driver'];

            try {
                if ($driver === 'sqlite') {
                    $dsn = 'sqlite:' . $config['database'];
                    self::$defaultConnection = new PDO($dsn);
                    self::$defaultConnection->exec('PRAGMA foreign_keys = ON');
                } elseif ($driver === 'mysql') {
                    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname=" . basename($config['database']) . ";charset=utf8mb4";
                    self::$defaultConnection = new PDO($dsn, $config['username'], $config['password']);
                } elseif ($driver === 'pgsql') {
                    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname=" . basename($config['database']);
                    self::$defaultConnection = new PDO($dsn, $config['username'], $config['password']);
                }

                self::$defaultConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$defaultConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                self::logError('Default database connection failed: ' . $e->getMessage());
                throw $e;
            }
        }

        return self::$defaultConnection;
    }

    /**
     * Execute a query with parameters
     */
    public static function query($sql, $params = [], $connection = 'default')
    {
        $db = $connection === 'meta' ? self::meta() : self::connection();

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            self::logError("Query failed: {$sql} | Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Fetch all results
     */
    public static function fetchAll($sql, $params = [], $connection = 'default')
    {
        $stmt = self::query($sql, $params, $connection);
        return $stmt->fetchAll();
    }

    /**
     * Fetch single result
     */
    public static function fetchOne($sql, $params = [], $connection = 'default')
    {
        $stmt = self::query($sql, $params, $connection);
        return $stmt->fetch();
    }

    /**
     * Execute an insert/update/delete query
     */
    public static function execute($sql, $params = [], $connection = 'default')
    {
        $stmt = self::query($sql, $params, $connection);
        return $stmt->rowCount();
    }

    /**
     * Get last insert ID
     */
    public static function lastInsertId($connection = 'default')
    {
        $db = $connection === 'meta' ? self::meta() : self::connection();
        return $db->lastInsertId();
    }

    /**
     * Begin transaction
     */
    public static function beginTransaction($connection = 'default')
    {
        $db = $connection === 'meta' ? self::meta() : self::connection();
        return $db->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public static function commit($connection = 'default')
    {
        $db = $connection === 'meta' ? self::meta() : self::connection();
        return $db->commit();
    }

    /**
     * Rollback transaction
     */
    public static function rollback($connection = 'default')
    {
        $db = $connection === 'meta' ? self::meta() : self::connection();
        return $db->rollBack();
    }

    /**
     * Log database errors
     */
    private static function logError($message)
    {
        $logPath = Config::get('logging.path');
        $logDir = dirname($logPath);

        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] DATABASE ERROR: {$message}\n";
        file_put_contents($logPath, $logMessage, FILE_APPEND);
    }

    /**
     * Close connections
     */
    public static function close()
    {
        self::$metaConnection = null;
        self::$defaultConnection = null;
    }
}
