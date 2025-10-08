<?php

/**
 * Database class - handles database connections and queries
 */
class Database {
    private static $instance = null;
    private $pdo;
    private $config;

    public function __construct() {
        $this->config = config('database');
        $this->connect();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect() {
        $connection = $this->config['connection'];
        $config = $this->config['connections'][$connection];

        try {
            if ($connection === 'sqlite') {
                $this->pdo = new PDO(
                    'sqlite:' . $config['database'],
                    null,
                    null,
                    $config['options']
                );
                // Enable foreign keys for SQLite
                $this->pdo->exec('PRAGMA foreign_keys = ON');
            } else if ($connection === 'mysql') {
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                    $config['host'],
                    $config['port'],
                    $config['database'],
                    $config['charset']
                );
                $this->pdo = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            }
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            throw new Exception('Database connection failed');
        }
    }

    public function getPDO() {
        return $this->pdo;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log('Query failed: ' . $e->getMessage() . ' | SQL: ' . $sql);
            throw $e;
        }
    }

    public function select($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function selectOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    public function insert($sql, $params = []) {
        $this->query($sql, $params);
        return $this->pdo->lastInsertId();
    }

    public function update($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function delete($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    public function commit() {
        return $this->pdo->commit();
    }

    public function rollBack() {
        return $this->pdo->rollBack();
    }

    public function tableExists($tableName) {
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name=?";
        $result = $this->selectOne($sql, [$tableName]);
        return !empty($result);
    }

    public function createTable($sql) {
        return $this->pdo->exec($sql);
    }
}
