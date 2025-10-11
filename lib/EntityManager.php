<?php
/**
 * Entity Manager - Metadata-driven entity framework
 * Reads entity definitions from meta database and provides entity operations
 */

class EntityManager
{
    private static $entities = null;
    private static $attributes = null;
    private static $relationships = null;

    /**
     * Load all entity definitions from meta database
     */
    public static function loadEntities()
    {
        if (self::$entities === null) {
            $sql = "SELECT * FROM entity_definition WHERE is_active = 1";
            self::$entities = Database::fetchAll($sql, [], 'meta');
        }
        return self::$entities;
    }

    /**
     * Get entity definition by code
     */
    public static function getEntity($code)
    {
        $entities = self::loadEntities();
        foreach ($entities as $entity) {
            if ($entity['code'] === $code) {
                return $entity;
            }
        }
        return null;
    }

    /**
     * Get entity definition by ID
     */
    public static function getEntityById($id)
    {
        $entities = self::loadEntities();
        foreach ($entities as $entity) {
            if ($entity['id'] === $id) {
                return $entity;
            }
        }
        return null;
    }

    /**
     * Load all attributes for an entity
     */
    public static function getAttributes($entityId)
    {
        if (!isset(self::$attributes[$entityId])) {
            $sql = "SELECT * FROM entity_attribute WHERE entity_id = ? ORDER BY display_order";
            self::$attributes[$entityId] = Database::fetchAll($sql, [$entityId], 'meta');
        }
        return self::$attributes[$entityId];
    }

    /**
     * Get relationships for an entity
     * Returns relationships where this entity is either from_entity or to_entity
     */
    public static function getRelationships($entityId)
    {
        if (!isset(self::$relationships[$entityId])) {
            $sql = "SELECT * FROM entity_relationship WHERE from_entity_id = ? OR to_entity_id = ?";
            self::$relationships[$entityId] = Database::fetchAll($sql, [$entityId, $entityId], 'meta');
        }
        return self::$relationships[$entityId];
    }

    /**
     * Get functions for an entity
     */
    public static function getFunctions($entityId)
    {
        $sql = "SELECT * FROM entity_function WHERE entity_id = ? AND is_active = 1";
        return Database::fetchAll($sql, [$entityId], 'meta');
    }

    /**
     * Get function handlers for a function
     */
    public static function getFunctionHandlers($functionId)
    {
        $sql = "SELECT * FROM entity_function_handler WHERE function_id = ? AND is_active = 1";
        return Database::fetchAll($sql, [$functionId], 'meta');
    }

    /**
     * Get validation rules for an entity
     */
    public static function getValidationRules($entityId)
    {
        $sql = "SELECT * FROM entity_validation_rule WHERE entity_id = ?";
        return Database::fetchAll($sql, [$entityId], 'meta');
    }

    /**
     * Ensure operational database table exists for entity
     */
    public static function ensureTable($entityCode)
    {
        $entity = self::getEntity($entityCode);
        if (!$entity) {
            throw new Exception("Entity not found: {$entityCode}");
        }

        $tableName = $entity['table_name'];
        $attributes = self::getAttributes($entity['id']);

        // Check if table exists
        $driver = Config::get('database.default.driver');
        if ($driver === 'sqlite') {
            $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name=?";
        } elseif ($driver === 'mysql') {
            $sql = "SHOW TABLES LIKE ?";
        } elseif ($driver === 'pgsql') {
            $sql = "SELECT tablename FROM pg_tables WHERE tablename=?";
        }

        $result = Database::fetchOne($sql, [$tableName]);
        if ($result) {
            return; // Table already exists
        }

        // Create table
        $createSql = self::generateCreateTableSQL($entity, $attributes);
        Database::execute($createSql);
    }

    /**
     * Generate CREATE TABLE SQL from entity metadata
     */
    private static function generateCreateTableSQL($entity, $attributes)
    {
        $tableName = $entity['table_name'];
        $driver = Config::get('database.default.driver');

        $columns = [];

        // Add standard columns
        $columns[] = "id TEXT PRIMARY KEY";
        $columns[] = "created_at TEXT DEFAULT (datetime('now'))";
        $columns[] = "updated_at TEXT DEFAULT (datetime('now'))";
        $columns[] = "deleted_at TEXT";
        $columns[] = "version_no INTEGER DEFAULT 1";
        $columns[] = "changed_by TEXT";

        // Add entity-specific columns
        foreach ($attributes as $attr) {
            if ($attr['is_system'] == 1) {
                continue; // Skip system attributes
            }

            $colDef = $attr['code'] . ' ';

            // Map data types
            switch ($attr['data_type']) {
                case 'text':
                    $colDef .= 'TEXT';
                    break;
                case 'number':
                    $colDef .= 'REAL';
                    break;
                case 'integer':
                    $colDef .= 'INTEGER';
                    break;
                case 'boolean':
                    $colDef .= 'INTEGER DEFAULT 0';
                    break;
                case 'date':
                case 'datetime':
                    $colDef .= 'TEXT';
                    break;
                case 'json':
                    $colDef .= 'TEXT';
                    break;
                default:
                    $colDef .= 'TEXT';
            }

            // Add constraints
            if ($attr['is_required'] == 1) {
                $colDef .= ' NOT NULL';
            }

            if ($attr['default_value']) {
                $colDef .= " DEFAULT '{$attr['default_value']}'";
            }

            $columns[] = $colDef;
        }

        $columnsSql = implode(",\n    ", $columns);

        $sql = "CREATE TABLE IF NOT EXISTS {$tableName} (\n    {$columnsSql}\n)";

        return $sql;
    }

    /**
     * Create a new entity record
     */
    public static function create($entityCode, $data)
    {
        $entity = self::getEntity($entityCode);
        if (!$entity) {
            throw new Exception("Entity not found: {$entityCode}");
        }

        self::ensureTable($entityCode);

        $tableName = $entity['table_name'];
        $attributes = self::getAttributes($entity['id']);

        // Generate UUID
        $id = Auth::generateUuid();
        $data['id'] = $id;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['version_no'] = 1;

        // Build INSERT query
        $columns = [];
        $placeholders = [];
        $values = [];

        foreach ($data as $key => $value) {
            $columns[] = $key;
            $placeholders[] = '?';
            $values[] = $value;
        }

        $columnsSql = implode(', ', $columns);
        $placeholdersSql = implode(', ', $placeholders);

        $sql = "INSERT INTO {$tableName} ({$columnsSql}) VALUES ({$placeholdersSql})";
        Database::execute($sql, $values);

        return $id;
    }

    /**
     * Read entity record by ID
     */
    public static function read($entityCode, $id)
    {
        $entity = self::getEntity($entityCode);
        if (!$entity) {
            throw new Exception("Entity not found: {$entityCode}");
        }

        $tableName = $entity['table_name'];
        $sql = "SELECT * FROM {$tableName} WHERE id = ? AND deleted_at IS NULL";
        return Database::fetchOne($sql, [$id]);
    }

    /**
     * Update entity record
     */
    public static function update($entityCode, $id, $data)
    {
        $entity = self::getEntity($entityCode);
        if (!$entity) {
            throw new Exception("Entity not found: {$entityCode}");
        }

        $tableName = $entity['table_name'];
        $data['updated_at'] = date('Y-m-d H:i:s');

        // Build UPDATE query
        $setParts = [];
        $values = [];

        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $setParts[] = "{$key} = ?";
                $values[] = $value;
            }
        }

        $values[] = $id;

        $setSql = implode(', ', $setParts);
        $sql = "UPDATE {$tableName} SET {$setSql} WHERE id = ?";

        return Database::execute($sql, $values);
    }

    /**
     * Delete entity record (soft delete)
     */
    public static function delete($entityCode, $id)
    {
        $entity = self::getEntity($entityCode);
        if (!$entity) {
            throw new Exception("Entity not found: {$entityCode}");
        }

        $tableName = $entity['table_name'];
        $sql = "UPDATE {$tableName} SET deleted_at = datetime('now') WHERE id = ?";

        return Database::execute($sql, [$id]);
    }

    /**
     * Search entity records with filters
     */
    public static function search($entityCode, $filters = [], $limit = 100, $offset = 0)
    {
        $entity = self::getEntity($entityCode);
        if (!$entity) {
            throw new Exception("Entity not found: {$entityCode}");
        }

        $tableName = $entity['table_name'];
        $sql = "SELECT * FROM {$tableName} WHERE deleted_at IS NULL";
        $params = [];

        // Apply filters
        foreach ($filters as $key => $value) {
            $sql .= " AND {$key} = ?";
            $params[] = $value;
        }

        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return Database::fetchAll($sql, $params);
    }

    /**
     * Count entity records
     */
    public static function count($entityCode, $filters = [])
    {
        $entity = self::getEntity($entityCode);
        if (!$entity) {
            throw new Exception("Entity not found: {$entityCode}");
        }

        $tableName = $entity['table_name'];
        $sql = "SELECT COUNT(*) as cnt FROM {$tableName} WHERE deleted_at IS NULL";
        $params = [];

        // Apply filters
        foreach ($filters as $key => $value) {
            $sql .= " AND {$key} = ?";
            $params[] = $value;
        }

        $result = Database::fetchOne($sql, $params);
        return (int) $result['cnt'];
    }
}
