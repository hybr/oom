<?php
/**
 * Database Connection Handler
 *
 * Manages database connections and provides query methods
 */

namespace V4L\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private static ?PDO $connection = null;
    private static array $queryLog = [];

    /**
     * Get database connection (singleton)
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            try {
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                self::$connection = new PDO(DB_DSN, DB_USER, DB_PASS, $options);

                // Set SQLite-specific pragmas
                if (DB_TYPE === 'sqlite') {
                    self::$connection->exec('PRAGMA foreign_keys = ON');
                    self::$connection->exec('PRAGMA journal_mode = WAL');
                }

            } catch (PDOException $e) {
                self::logError('Database connection failed: ' . $e->getMessage());
                throw new \Exception('Database connection failed. Please check configuration.');
            }
        }

        return self::$connection;
    }

    /**
     * Execute a query and return the statement
     */
    public static function query(string $sql, array $params = []): PDOStatement
    {
        $connection = self::getConnection();

        try {
            $stmt = $connection->prepare($sql);
            $stmt->execute($params);

            if (IS_DEVELOPMENT) {
                self::$queryLog[] = [
                    'sql' => $sql,
                    'params' => $params,
                    'time' => microtime(true)
                ];
            }

            return $stmt;
        } catch (PDOException $e) {
            self::logError('Query failed: ' . $e->getMessage() . ' | SQL: ' . $sql);
            throw new \Exception('Database query failed: ' . $e->getMessage());
        }
    }

    /**
     * Fetch all rows from query
     */
    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Fetch single row from query
     */
    public static function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = self::query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Fetch single value from query
     */
    public static function fetchValue(string $sql, array $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchColumn();
    }

    /**
     * Insert a record and return the ID
     */
    public static function insert(string $table, array $data): string
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ":$col", $columns);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $params = [];
        foreach ($data as $key => $value) {
            $params[":$key"] = $value;
        }

        self::query($sql, $params);

        // Return the last insert ID
        if (DB_TYPE === 'sqlite') {
            return self::getConnection()->lastInsertId();
        } else {
            // For PostgreSQL with UUID, return the ID from the data
            return $data['id'] ?? self::getConnection()->lastInsertId();
        }
    }

    /**
     * Update records
     */
    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $setParts = [];
        $params = [];

        foreach ($data as $key => $value) {
            $setParts[] = "$key = :set_$key";
            $params[":set_$key"] = $value;
        }

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            implode(', ', $setParts),
            $where
        );

        $params = array_merge($params, $whereParams);
        $stmt = self::query($sql, $params);

        return $stmt->rowCount();
    }

    /**
     * Delete records
     */
    public static function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM $table WHERE $where";
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
        if (DB_TYPE === 'sqlite') {
            $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name = :table";
        } else {
            $sql = "SELECT table_name FROM information_schema.tables WHERE table_name = :table";
        }

        $result = self::fetchOne($sql, [':table' => $table]);
        return $result !== null;
    }

    /**
     * Get query log (development only)
     */
    public static function getQueryLog(): array
    {
        return self::$queryLog;
    }

    /**
     * Log error to file
     */
    private static function logError(string $message): void
    {
        $logFile = LOG_PATH . '/database_' . date('Y-m-d') . '.log';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    /**
     * Generate UUID v4
     */
    public static function generateUuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
