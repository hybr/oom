<?php
/**
 * Metadata Loader
 *
 * Loads entity definitions, attributes, relationships, and validation rules from database
 */

namespace V4L\Core;

class MetadataLoader
{
    private static ?array $entities = null;
    private static array $cache = [];

    /**
     * Load all entity definitions
     */
    public static function loadEntities(): array
    {
        if (self::$entities !== null && CACHE_ENABLED) {
            return self::$entities;
        }

        $sql = "SELECT * FROM entity_definition WHERE is_active = 1 ORDER BY code";
        self::$entities = Database::fetchAll($sql);

        return self::$entities;
    }

    /**
     * Get entity by code
     */
    public static function getEntity(string $code): ?array
    {
        $cacheKey = "entity_$code";

        if (isset(self::$cache[$cacheKey]) && CACHE_ENABLED) {
            return self::$cache[$cacheKey];
        }

        $sql = "SELECT * FROM entity_definition WHERE code = :code AND is_active = 1";
        $entity = Database::fetchOne($sql, [':code' => $code]);

        if ($entity) {
            // Load attributes
            $entity['attributes'] = self::getEntityAttributes($entity['id']);

            // Load relationships
            $entity['relationships'] = self::getEntityRelationships($entity['id']);

            // Load validation rules
            $entity['validation_rules'] = self::getEntityValidationRules($entity['id']);

            self::$cache[$cacheKey] = $entity;
        }

        return $entity;
    }

    /**
     * Get entity by ID
     */
    public static function getEntityById(string $entityId): ?array
    {
        $cacheKey = "entity_id_$entityId";

        if (isset(self::$cache[$cacheKey]) && CACHE_ENABLED) {
            return self::$cache[$cacheKey];
        }

        $sql = "SELECT * FROM entity_definition WHERE id = :id AND is_active = 1";
        $entity = Database::fetchOne($sql, [':id' => $entityId]);

        if ($entity) {
            $entity['attributes'] = self::getEntityAttributes($entity['id']);
            $entity['relationships'] = self::getEntityRelationships($entity['id']);
            $entity['validation_rules'] = self::getEntityValidationRules($entity['id']);
            self::$cache[$cacheKey] = $entity;
        }

        return $entity;
    }

    /**
     * Get entity attributes
     */
    public static function getEntityAttributes(string $entityId): array
    {
        $sql = "SELECT * FROM entity_attribute
                WHERE entity_id = :entity_id
                ORDER BY display_order, code";

        return Database::fetchAll($sql, [':entity_id' => $entityId]);
    }

    /**
     * Get entity relationships
     */
    public static function getEntityRelationships(string $entityId): array
    {
        $sql = "SELECT er.*,
                       er.from_entity_id as source_entity_id,
                       er.to_entity_id as target_entity_id,
                       er.relation_type as relationship_type,
                       er.relation_name as relationship_code,
                       er.fk_field as source_attribute_code,
                       er.fk_field as target_attribute_code,
                       e1.code as source_entity_code,
                       e1.name as source_entity_name,
                       e2.code as target_entity_code,
                       e2.name as target_entity_name
                FROM entity_relationship er
                LEFT JOIN entity_definition e1 ON er.from_entity_id = e1.id
                LEFT JOIN entity_definition e2 ON er.to_entity_id = e2.id
                WHERE er.from_entity_id = :entity_id OR er.to_entity_id = :entity_id
                ORDER BY er.relation_type";

        return Database::fetchAll($sql, [':entity_id' => $entityId]);
    }

    /**
     * Get entity validation rules
     */
    public static function getEntityValidationRules(string $entityId): array
    {
        $sql = "SELECT vr.*, ea.code as attribute_code, ea.name as attribute_name
                FROM entity_validation_rule vr
                LEFT JOIN entity_attribute ea ON vr.attribute_id = ea.id
                WHERE vr.entity_id = :entity_id
                ORDER BY vr.rule_name";

        return Database::fetchAll($sql, [':entity_id' => $entityId]);
    }

    /**
     * Get label attribute for entity
     */
    public static function getLabelAttribute(string $entityCode): ?array
    {
        $entity = self::getEntity($entityCode);
        if (!$entity) {
            return null;
        }

        foreach ($entity['attributes'] as $attr) {
            if ($attr['is_label']) {
                return $attr;
            }
        }

        // Fallback to first non-system attribute
        foreach ($entity['attributes'] as $attr) {
            if (!$attr['is_system']) {
                return $attr;
            }
        }

        return null;
    }

    /**
     * Get required attributes for entity
     */
    public static function getRequiredAttributes(string $entityCode): array
    {
        $entity = self::getEntity($entityCode);
        if (!$entity) {
            return [];
        }

        return array_filter($entity['attributes'], fn($attr) => $attr['is_required']);
    }

    /**
     * Get form fields for entity (excluding system fields)
     */
    public static function getFormFields(string $entityCode): array
    {
        $entity = self::getEntity($entityCode);
        if (!$entity) {
            return [];
        }

        return array_filter($entity['attributes'], fn($attr) => !$attr['is_system']);
    }

    /**
     * Get display columns for list view
     */
    public static function getDisplayColumns(string $entityCode, int $limit = 5): array
    {
        $entity = self::getEntity($entityCode);
        if (!$entity) {
            return [];
        }

        $columns = array_filter($entity['attributes'], fn($attr) => !$attr['is_system']);

        // Sort by display_order
        usort($columns, fn($a, $b) => $a['display_order'] <=> $b['display_order']);

        return array_slice($columns, 0, $limit);
    }

    /**
     * Validate data against entity definition
     */
    public static function validateData(string $entityCode, array $data): array
    {
        $errors = [];
        $entity = self::getEntity($entityCode);

        if (!$entity) {
            return ['entity' => 'Entity not found'];
        }

        foreach ($entity['attributes'] as $attr) {
            $code = $attr['code'];
            $value = $data[$code] ?? null;

            // Check required fields
            if ($attr['is_required'] && empty($value)) {
                $errors[$code] = "{$attr['name']} is required";
                continue;
            }

            // Skip validation if value is empty and not required
            if (empty($value)) {
                continue;
            }

            // Validate data type
            $typeError = self::validateDataType($attr['data_type'], $value);
            if ($typeError) {
                $errors[$code] = $typeError;
                continue;
            }

            // Validate min/max for numbers
            if (in_array($attr['data_type'], ['INTEGER', 'DECIMAL', 'NUMBER'])) {
                if ($attr['min_value'] !== null && $value < $attr['min_value']) {
                    $errors[$code] = "{$attr['name']} must be at least {$attr['min_value']}";
                }
                if ($attr['max_value'] !== null && $value > $attr['max_value']) {
                    $errors[$code] = "{$attr['name']} must be at most {$attr['max_value']}";
                }
            }

            // Validate string length
            if ($attr['data_type'] === 'TEXT' && $attr['max_value'] !== null) {
                if (strlen($value) > $attr['max_value']) {
                    $errors[$code] = "{$attr['name']} must not exceed {$attr['max_value']} characters";
                }
            }

            // Validate regex pattern
            if ($attr['validation_regex'] && !preg_match($attr['validation_regex'], $value)) {
                $errors[$code] = "{$attr['name']} format is invalid";
            }

            // Validate enum values
            if ($attr['enum_values']) {
                $enumValues = json_decode($attr['enum_values'], true);
                if ($enumValues && !in_array($value, $enumValues)) {
                    $errors[$code] = "{$attr['name']} must be one of: " . implode(', ', $enumValues);
                }
            }
        }

        return $errors;
    }

    /**
     * Validate data type
     */
    private static function validateDataType(string $type, $value): ?string
    {
        switch ($type) {
            case 'INTEGER':
                if (!is_numeric($value) || (int)$value != $value) {
                    return 'Must be a valid integer';
                }
                break;
            case 'DECIMAL':
            case 'NUMBER':
                if (!is_numeric($value)) {
                    return 'Must be a valid number';
                }
                break;
            case 'BOOLEAN':
                if (!in_array($value, [0, 1, '0', '1', true, false, 'true', 'false'], true)) {
                    return 'Must be a boolean value';
                }
                break;
            case 'DATE':
                if (!strtotime($value)) {
                    return 'Must be a valid date';
                }
                break;
            case 'DATETIME':
            case 'TIMESTAMP':
                if (!strtotime($value)) {
                    return 'Must be a valid date and time';
                }
                break;
            case 'EMAIL':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return 'Must be a valid email address';
                }
                break;
            case 'URL':
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    return 'Must be a valid URL';
                }
                break;
            case 'UUID':
                if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value)) {
                    return 'Must be a valid UUID';
                }
                break;
        }

        return null;
    }

    /**
     * Clear cache
     */
    public static function clearCache(): void
    {
        self::$entities = null;
        self::$cache = [];
    }

    /**
     * Get table name for entity
     */
    public static function getTableName(string $entityCode): ?string
    {
        $entity = self::getEntity($entityCode);
        return $entity ? $entity['table_name'] : null;
    }
}
