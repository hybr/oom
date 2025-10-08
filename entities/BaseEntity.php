<?php

/**
 * BaseEntity class - base class for all entities
 */
abstract class BaseEntity {
    protected $db;
    protected $table;
    protected $fillable = [];
    protected $hidden = [];

    // Core attributes (all entities)
    protected $id;
    protected $created_at;
    protected $updated_at;
    protected $deleted_at;

    public function __construct() {
        $this->db = db();
    }

    /**
     * Find entity by ID
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? AND deleted_at IS NULL";
        return $this->db->selectOne($sql, [$id]);
    }

    /**
     * Get all entities
     */
    public function all($conditions = [], $orderBy = 'id DESC', $limit = null, $offset = null) {
        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL";

        $params = [];
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $sql .= " AND $key = ?";
                $params[] = $value;
            }
        }

        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }

        if ($limit) {
            $sql .= " LIMIT $limit";
        }

        if ($offset) {
            $sql .= " OFFSET $offset";
        }

        return $this->db->select($sql, $params);
    }

    /**
     * Count entities
     */
    public function count($conditions = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE deleted_at IS NULL";

        $params = [];
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $sql .= " AND $key = ?";
                $params[] = $value;
            }
        }

        $result = $this->db->selectOne($sql, $params);
        return $result['total'] ?? 0;
    }

    /**
     * Create new entity
     */
    public function create($data) {
        $data = $this->filterFillable($data);

        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');

        $sql = sprintf(
            "INSERT INTO %s (%s, created_at, updated_at) VALUES (%s, datetime('now'), datetime('now'))",
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        return $this->db->insert($sql, array_values($data));
    }

    /**
     * Update entity
     */
    public function update($id, $data) {
        $data = $this->filterFillable($data);

        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "$column = ?";
        }

        $sql = sprintf(
            "UPDATE %s SET %s, updated_at = datetime('now') WHERE id = ?",
            $this->table,
            implode(', ', $setParts)
        );

        $params = array_values($data);
        $params[] = $id;

        return $this->db->update($sql, $params);
    }

    /**
     * Delete entity (soft delete)
     */
    public function delete($id) {
        $sql = "UPDATE {$this->table} SET deleted_at = datetime('now') WHERE id = ?";
        return $this->db->update($sql, [$id]);
    }

    /**
     * Hard delete entity
     */
    public function forceDelete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->delete($sql, [$id]);
    }

    /**
     * Restore soft deleted entity
     */
    public function restore($id) {
        $sql = "UPDATE {$this->table} SET deleted_at = NULL WHERE id = ?";
        return $this->db->update($sql, [$id]);
    }

    /**
     * Filter data to only include fillable fields
     */
    protected function filterFillable($data) {
        if (empty($this->fillable)) {
            return $data;
        }

        return array_filter($data, function ($key) {
            return in_array($key, $this->fillable);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Search entities
     */
    public function search($column, $term, $limit = 50) {
        $sql = "SELECT * FROM {$this->table} WHERE $column LIKE ? AND deleted_at IS NULL LIMIT $limit";
        return $this->db->select($sql, ["%$term%"]);
    }

    /**
     * Get entity with related data
     */
    public function with($id, $relations = []) {
        $entity = $this->find($id);
        if (!$entity) {
            return null;
        }

        foreach ($relations as $relation) {
            if (method_exists($this, $relation)) {
                $entity[$relation] = $this->$relation($id);
            }
        }

        return $entity;
    }

    /**
     * Validate entity data
     */
    public function validate($data, $rules, $messages = []) {
        $validator = new Validator($data);
        return $validator->validate($rules, $messages);
    }

    /**
     * Get validation errors
     */
    public function errors() {
        return $_SESSION['errors'] ?? [];
    }

    /**
     * Get table name
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * Execute custom query
     */
    protected function query($sql, $params = []) {
        return $this->db->select($sql, $params);
    }

    /**
     * Execute custom query (single result)
     */
    protected function queryOne($sql, $params = []) {
        return $this->db->selectOne($sql, $params);
    }

    /**
     * Get paginated results
     */
    public function paginate($page = 1, $perPage = 25, $conditions = []) {
        $offset = ($page - 1) * $perPage;

        $totalRecords = $this->count($conditions);
        $totalPages = ceil($totalRecords / $perPage);

        $data = $this->all($conditions, 'id DESC', $perPage, $offset);

        return [
            'data' => $data,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_records' => $totalRecords,
            'total_pages' => $totalPages,
        ];
    }

    /**
     * Get label for display (override in child classes)
     */
    public function getLabel($id) {
        $record = $this->find($id);
        if (!$record) {
            return 'N/A';
        }

        // Try common name fields
        foreach (['name', 'title', 'short_name', 'username', 'first_name'] as $field) {
            if (isset($record[$field])) {
                return $record[$field];
            }
        }

        return "#{$id}";
    }

    /**
     * Get select options for dropdowns
     */
    public function getSelectOptions($labelField = 'name', $valueField = 'id') {
        $records = $this->all();
        $options = [];

        foreach ($records as $record) {
            $options[$record[$valueField]] = $record[$labelField];
        }

        return $options;
    }
}
