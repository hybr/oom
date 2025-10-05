<?php

namespace App;

use PDO;
use PDOException;

/**
 * Database connection and query builder class
 */
class Database
{
    private static ?PDO $connection = null;
    private static array $config = [];

    /**
     * Initialize database configuration
     */
    public static function init(array $config): void
    {
        self::$config = $config;
    }

    /**
     * Get PDO connection instance (Singleton pattern)
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            self::connect();
        }
        return self::$connection;
    }

    /**
     * Establish database connection
     */
    private static function connect(): void
    {
        $driver = self::$config['connection'] ?? 'sqlite';
        $config = self::$config[$driver] ?? [];

        try {
            switch ($driver) {
                case 'sqlite':
                    $dsn = "sqlite:{$config['database']}";
                    self::$connection = new PDO($dsn);
                    // Enable foreign keys for SQLite
                    self::$connection->exec('PRAGMA foreign_keys = ON');
                    break;

                case 'mysql':
                    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
                    self::$connection = new PDO($dsn, $config['username'], $config['password']);
                    break;

                case 'pgsql':
                    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
                    self::$connection = new PDO($dsn, $config['username'], $config['password']);
                    break;

                default:
                    throw new PDOException("Unsupported database driver: {$driver}");
            }

            // Set PDO attributes
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        } catch (PDOException $e) {
            throw new PDOException("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Execute a query with parameters
     */
    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Fetch all rows
     */
    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    /**
     * Fetch single row
     */
    public static function fetchOne(string $sql, array $params = []): ?array
    {
        $result = self::query($sql, $params)->fetch();
        return $result ?: null;
    }

    /**
     * Insert record and return last insert ID
     */
    public static function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        self::query($sql, $data);

        return (int)self::getConnection()->lastInsertId();
    }

    /**
     * Update records
     */
    public static function update(string $table, array $data, string $where, array $params = []): int
    {
        $set = [];
        foreach (array_keys($data) as $key) {
            $set[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $set);

        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        $stmt = self::query($sql, array_merge($data, $params));

        return $stmt->rowCount();
    }

    /**
     * Delete records
     */
    public static function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Begin transaction
     */
    public static function beginTransaction(): bool
    {
        return self::getConnection()->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public static function commit(): bool
    {
        return self::getConnection()->commit();
    }

    /**
     * Rollback transaction
     */
    public static function rollback(): bool
    {
        return self::getConnection()->rollBack();
    }

    /**
     * Check if table exists
     */
    public static function tableExists(string $table): bool
    {
        $driver = self::$config['connection'] ?? 'sqlite';

        if ($driver === 'sqlite') {
            $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name=:table";
        } else if ($driver === 'mysql') {
            $sql = "SHOW TABLES LIKE :table";
        } else {
            $sql = "SELECT table_name FROM information_schema.tables WHERE table_name=:table";
        }

        $result = self::fetchOne($sql, ['table' => $table]);
        return $result !== null;
    }

    /**
     * Execute raw SQL
     */
    public static function exec(string $sql): int
    {
        return self::getConnection()->exec($sql);
    }
}
