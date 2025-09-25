<?php

require_once 'config/database.php';

abstract class BaseEntity {
    protected $db;
    protected $table;
    protected $attributes = [];
    protected $fillable = [];

    public function __construct() {
        $this->db = DatabaseConfig::getInstance();
    }

    public function __get($name) {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    public function __set($name, $value) {
        if (in_array($name, $this->fillable)) {
            $this->attributes[$name] = $value;
        }
    }

    public function fill(array $data) {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }

    public function save() {
        if (isset($this->attributes['id']) && $this->attributes['id']) {
            return $this->update();
        } else {
            return $this->create();
        }
    }

    protected function create() {
        $fields = array_keys($this->attributes);
        $placeholders = ':' . implode(', :', $fields);
        $fieldsList = implode(', ', $fields);

        $sql = "INSERT INTO {$this->table} ({$fieldsList}) VALUES ({$placeholders})";
        $this->db->execute($sql, $this->attributes);

        $this->attributes['id'] = $this->db->getConnection()->lastInsertId();
        return $this;
    }

    protected function update() {
        $fields = array_filter(array_keys($this->attributes), function($field) {
            return $field !== 'id';
        });

        $setClause = implode(', ', array_map(function($field) {
            return "$field = :$field";
        }, $fields));

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";
        $this->db->execute($sql, $this->attributes);

        return $this;
    }

    public function delete() {
        if (isset($this->attributes['id'])) {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            return $this->db->execute($sql, ['id' => $this->attributes['id']]);
        }
        return false;
    }

    public static function find($id) {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE id = :id";
        $data = $instance->db->fetch($sql, ['id' => $id]);

        if ($data) {
            $instance->fill($data);
            return $instance;
        }
        return null;
    }

    public static function all() {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table}";
        $results = $instance->db->fetchAll($sql);

        $entities = [];
        foreach ($results as $data) {
            $entity = new static();
            $entity->fill($data);
            $entities[] = $entity;
        }
        return $entities;
    }

    public static function where($field, $operator, $value) {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE {$field} {$operator} :{$field}";
        $results = $instance->db->fetchAll($sql, [$field => $value]);

        $entities = [];
        foreach ($results as $data) {
            $entity = new static();
            $entity->fill($data);
            $entities[] = $entity;
        }
        return $entities;
    }

    public function toArray() {
        return $this->attributes;
    }

    public function toJson() {
        return json_encode($this->attributes);
    }

    public static function createTable() {
        $instance = new static();
        $schema = $instance->getSchema();
        $instance->db->execute($schema);
    }

    abstract protected function getSchema();
}