<?php

namespace Entities;

use App\Database;
use DateTime;

/**
 * Base Entity abstract class with CRUD operations, validation, and audit trail
 */
abstract class BaseEntity
{
    // Core attributes (all entities inherit these)
    protected ?int $id = null;
    protected ?string $created_at = null;
    protected ?int $created_by = null;
    protected ?string $updated_at = null;
    protected ?int $updated_by = null;
    protected ?string $deleted_at = null; // Soft delete
    protected int $version = 1; // Optimistic locking

    // Validation errors
    protected array $errors = [];

    /**
     * Get table name (must be implemented by child classes)
     */
    abstract public static function getTableName(): string;

    /**
     * Get validation rules (override in child classes)
     */
    protected function getValidationRules(): array
    {
        return [];
    }

    /**
     * Get fillable attributes (override in child classes)
     */
    protected function getFillableAttributes(): array
    {
        return [];
    }

    /**
     * Magic getter
     */
    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new \Exception("Property {$name} does not exist on " . static::class);
    }

    /**
     * Magic setter
     */
    public function __set(string $name, $value): void
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            throw new \Exception("Property {$name} does not exist on " . static::class);
        }
    }

    /**
     * Fill entity with data
     */
    public function fill(array $data): self
    {
        $fillable = $this->getFillableAttributes();

        foreach ($data as $key => $value) {
            if (in_array($key, $fillable) && property_exists($this, $key)) {
                // Get property type using reflection
                $reflection = new \ReflectionProperty($this, $key);
                $type = $reflection->getType();

                // Convert value to appropriate type
                if ($type && !$type->allowsNull() && $value === '') {
                    // Don't set empty string for non-nullable types
                    continue;
                } elseif ($type && $type->allowsNull() && $value === '') {
                    // Convert empty string to null for nullable types
                    $this->$key = null;
                } elseif ($type) {
                    $typeName = $type->getName();
                    // Type conversion based on property type
                    if ($typeName === 'int' && $value !== null && $value !== '') {
                        $this->$key = (int)$value;
                    } elseif ($typeName === 'float' && $value !== null && $value !== '') {
                        $this->$key = (float)$value;
                    } elseif ($typeName === 'bool' && $value !== null) {
                        $this->$key = (bool)$value;
                    } else {
                        $this->$key = $value;
                    }
                } else {
                    $this->$key = $value;
                }
            }
        }

        return $this;
    }

    /**
     * Validate entity data
     */
    public function validate(): bool
    {
        $this->errors = [];
        $rules = $this->getValidationRules();

        foreach ($rules as $field => $fieldRules) {
            $value = $this->$field ?? null;

            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $this->errors[$field][] = "{$field} is required";
                } elseif (str_starts_with($rule, 'min:')) {
                    $min = (int)substr($rule, 4);
                    if (strlen($value) < $min) {
                        $this->errors[$field][] = "{$field} must be at least {$min} characters";
                    }
                } elseif (str_starts_with($rule, 'max:')) {
                    $max = (int)substr($rule, 4);
                    if (strlen($value) > $max) {
                        $this->errors[$field][] = "{$field} must not exceed {$max} characters";
                    }
                } elseif ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "{$field} must be a valid email address";
                } elseif ($rule === 'numeric' && !is_numeric($value)) {
                    $this->errors[$field][] = "{$field} must be numeric";
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Get validation errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Save entity (insert or update)
     */
    public function save(?int $userId = null): bool
    {
        if (!$this->validate()) {
            return false;
        }

        try {
            if ($this->id === null) {
                return $this->insert($userId);
            } else {
                return $this->update($userId);
            }
        } catch (\Exception $e) {
            $this->errors['save'] = [$e->getMessage()];
            return false;
        }
    }

    /**
     * Insert new record
     */
    protected function insert(?int $userId = null): bool
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->created_by = $userId;
        $this->updated_at = $this->created_at;
        $this->updated_by = $userId;

        $data = $this->toArrayForDatabase(true);
        unset($data['id']); // Don't include ID in insert

        $this->id = Database::insert(static::getTableName(), $data);

        $this->logAudit('create', $userId);

        return true;
    }

    /**
     * Update existing record
     */
    protected function update(?int $userId = null): bool
    {
        // Optimistic locking check
        $current = static::find($this->id);
        if ($current && $current->version !== $this->version) {
            $this->errors['version'] = ['Record has been modified by another user'];
            return false;
        }

        $this->updated_at = date('Y-m-d H:i:s');
        $this->updated_by = $userId;
        $this->version++;

        $data = $this->toArrayForDatabase(true);
        unset($data['id'], $data['created_at'], $data['created_by']);

        Database::update(
            static::getTableName(),
            $data,
            'id = :id',
            ['id' => $this->id]
        );

        $this->logAudit('update', $userId);

        return true;
    }

    /**
     * Delete record (soft delete)
     */
    public function delete(?int $userId = null): bool
    {
        $this->deleted_at = date('Y-m-d H:i:s');

        $result = Database::update(
            static::getTableName(),
            ['deleted_at' => $this->deleted_at],
            'id = :id',
            ['id' => $this->id]
        );

        if ($result) {
            $this->logAudit('delete', $userId);
        }

        return $result > 0;
    }

    /**
     * Hard delete record
     */
    public function forceDelete(): bool
    {
        return Database::delete(
            static::getTableName(),
            'id = :id',
            ['id' => $this->id]
        ) > 0;
    }

    /**
     * Restore soft-deleted record
     */
    public function restore(?int $userId = null): bool
    {
        $this->deleted_at = null;

        $result = Database::update(
            static::getTableName(),
            ['deleted_at' => null],
            'id = :id',
            ['id' => $this->id]
        );

        if ($result) {
            $this->logAudit('restore', $userId);
        }

        return $result > 0;
    }

    /**
     * Convert entity to array
     */
    public function toArray(bool $excludeNull = false): array
    {
        $data = [];
        $reflection = new \ReflectionClass($this);

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($this);

            if ($excludeNull && $value === null) {
                continue;
            }

            $data[$property->getName()] = $value;
        }

        unset($data['errors']);
        return $data;
    }

    /**
     * Convert entity to array for database operations (includes sensitive fields)
     * Override this method in child classes if needed, but default behavior is same as toArray
     */
    protected function toArrayForDatabase(bool $excludeNull = false): array
    {
        $data = [];
        $reflection = new \ReflectionClass($this);

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($this);

            if ($excludeNull && $value === null) {
                continue;
            }

            $data[$property->getName()] = $value;
        }

        unset($data['errors']);
        return $data;
    }

    /**
     * Convert entity to JSON
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Find record by ID
     */
    public static function find(int $id): ?static
    {
        $sql = "SELECT * FROM " . static::getTableName() . " WHERE id = :id AND deleted_at IS NULL";
        $data = Database::fetchOne($sql, ['id' => $id]);

        if ($data) {
            return static::hydrate($data);
        }

        return null;
    }

    /**
     * Find record by ID including soft-deleted
     */
    public static function findWithTrashed(int $id): ?static
    {
        $sql = "SELECT * FROM " . static::getTableName() . " WHERE id = :id";
        $data = Database::fetchOne($sql, ['id' => $id]);

        if ($data) {
            return static::hydrate($data);
        }

        return null;
    }

    /**
     * Get all records
     */
    public static function all(int $limit = 100, int $offset = 0): array
    {
        $sql = "SELECT * FROM " . static::getTableName() . " WHERE deleted_at IS NULL LIMIT :limit OFFSET :offset";
        $data = Database::fetchAll($sql, ['limit' => $limit, 'offset' => $offset]);

        return array_map(fn($row) => static::hydrate($row), $data);
    }

    /**
     * Find records by condition
     */
    public static function where(string $condition, array $params = [], int $limit = 100): array
    {
        $sql = "SELECT * FROM " . static::getTableName() . " WHERE {$condition} AND deleted_at IS NULL LIMIT :limit";
        $params['limit'] = $limit;

        $data = Database::fetchAll($sql, $params);

        return array_map(fn($row) => static::hydrate($row), $data);
    }

    /**
     * Count records
     */
    public static function count(string $condition = '1=1', array $params = []): int
    {
        $sql = "SELECT COUNT(*) as count FROM " . static::getTableName() . " WHERE {$condition} AND deleted_at IS NULL";
        $result = Database::fetchOne($sql, $params);

        return (int)($result['count'] ?? 0);
    }

    /**
     * Hydrate entity from array
     */
    protected static function hydrate(array $data): static
    {
        $entity = new static();

        foreach ($data as $key => $value) {
            if (property_exists($entity, $key)) {
                $entity->$key = $value;
            }
        }

        return $entity;
    }

    /**
     * Log audit trail
     */
    protected function logAudit(string $action, ?int $userId = null): void
    {
        if (!Database::tableExists('audit_log')) {
            return;
        }

        $data = [
            'entity_type' => static::class,
            'entity_id' => $this->id,
            'action' => $action,
            'user_id' => $userId,
            'changes' => json_encode($this->toArray()),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        Database::insert('audit_log', $data);
    }

    /**
     * Get entity history
     */
    public function getHistory(): array
    {
        if (!Database::tableExists('audit_log')) {
            return [];
        }

        $sql = "SELECT * FROM audit_log WHERE entity_type = :type AND entity_id = :id ORDER BY created_at DESC";
        return Database::fetchAll($sql, [
            'type' => static::class,
            'id' => $this->id,
        ]);
    }

    /**
     * Search records (override in child classes for custom search)
     */
    public static function search(string $query, array $fields = [], int $limit = 50): array
    {
        if (empty($fields)) {
            return [];
        }

        $conditions = [];
        $params = [];

        foreach ($fields as $index => $field) {
            $conditions[] = "{$field} LIKE :search{$index}";
            $params["search{$index}"] = "%{$query}%";
        }

        $condition = '(' . implode(' OR ', $conditions) . ')';
        return static::where($condition, $params, $limit);
    }
}
