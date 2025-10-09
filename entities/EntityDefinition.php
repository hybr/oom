<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * EntityDefinition Entity
 * Defines all entities in the system for authorization purposes
 */
class EntityDefinition extends BaseEntity {
    protected $table = 'entity_definitions';
    protected $fillable = ['name', 'description'];

    /**
     * Get process authorizations for this entity
     */
    public function getProcessAuthorizations($entityId) {
        $sql = "SELECT epa.*, pop.name as position_name,
                d.name as department_name
                FROM entity_process_authorizations epa
                LEFT JOIN popular_organization_positions pop ON epa.popular_position_id = pop.id
                LEFT JOIN popular_organization_departments d ON pop.department_id = d.id
                WHERE epa.entity_id = ? AND epa.deleted_at IS NULL
                ORDER BY epa.action ASC";
        return $this->query($sql, [$entityId]);
    }

    /**
     * Get instance authorizations for this entity
     */
    public function getInstanceAuthorizations($entityId) {
        $sql = "SELECT eia.*, pop.name as position_name, p.first_name, p.last_name
                FROM entity_instance_authorizations eia
                LEFT JOIN popular_organization_positions pop ON eia.assigned_position_id = pop.id
                LEFT JOIN persons p ON eia.assigned_person_id = p.id
                WHERE eia.entity_id = ? AND eia.deleted_at IS NULL
                ORDER BY eia.entity_record_id ASC, eia.action ASC";
        return $this->query($sql, [$entityId]);
    }

    /**
     * Get entity by name
     */
    public function getByName($name) {
        $sql = "SELECT * FROM entity_definitions
                WHERE name = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$name]);
    }

    /**
     * Check if entity name exists
     */
    public function nameExists($name, $exceptId = null) {
        $sql = "SELECT id FROM entity_definitions WHERE name = ? AND deleted_at IS NULL";
        $params = [$name];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return !empty($result);
    }

    /**
     * Get all entities with authorization counts
     */
    public function getAllWithCounts() {
        $sql = "SELECT ed.*,
                COUNT(DISTINCT epa.id) as process_auth_count,
                COUNT(DISTINCT eia.id) as instance_auth_count
                FROM entity_definitions ed
                LEFT JOIN entity_process_authorizations epa ON ed.id = epa.entity_id AND epa.deleted_at IS NULL
                LEFT JOIN entity_instance_authorizations eia ON ed.id = eia.entity_id AND eia.deleted_at IS NULL
                WHERE ed.deleted_at IS NULL
                GROUP BY ed.id
                ORDER BY ed.name ASC";
        return $this->query($sql);
    }

    /**
     * Get entities by action
     */
    public function getByAction($action) {
        $sql = "SELECT DISTINCT ed.*
                FROM entity_definitions ed
                JOIN entity_process_authorizations epa ON ed.id = epa.entity_id
                WHERE epa.action = ? AND ed.deleted_at IS NULL AND epa.deleted_at IS NULL
                ORDER BY ed.name ASC";
        return $this->query($sql, [$action]);
    }

    /**
     * Get entities accessible by position
     */
    public function getAccessibleByPosition($positionId) {
        $sql = "SELECT DISTINCT ed.*, epa.action
                FROM entity_definitions ed
                JOIN entity_process_authorizations epa ON ed.id = epa.entity_id
                WHERE epa.popular_position_id = ?
                AND ed.deleted_at IS NULL AND epa.deleted_at IS NULL
                ORDER BY ed.name ASC, epa.action ASC";
        return $this->query($sql, [$positionId]);
    }

    /**
     * Check if position can perform action on entity
     */
    public function canPerform($entityName, $action, $positionId) {
        $entity = $this->getByName($entityName);
        if (!$entity) {
            return false;
        }

        $sql = "SELECT COUNT(*) as count
                FROM entity_process_authorizations
                WHERE entity_id = ? AND action = ? AND popular_position_id = ?
                AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$entity['id'], $action, $positionId]);

        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Get all actions for entity
     */
    public function getActionsForEntity($entityId) {
        $sql = "SELECT DISTINCT action FROM entity_process_authorizations
                WHERE entity_id = ? AND deleted_at IS NULL
                ORDER BY action ASC";
        return $this->query($sql, [$entityId]);
    }

    /**
     * Get positions authorized for entity action
     */
    public function getAuthorizedPositions($entityId, $action) {
        $sql = "SELECT pop.*, d.name as department_name
                FROM popular_organization_positions pop
                JOIN entity_process_authorizations epa ON pop.id = epa.popular_position_id
                LEFT JOIN popular_organization_departments d ON pop.department_id = d.id
                WHERE epa.entity_id = ? AND epa.action = ?
                AND pop.deleted_at IS NULL AND epa.deleted_at IS NULL
                ORDER BY d.name ASC, pop.name ASC";
        return $this->query($sql, [$entityId, $action]);
    }

    /**
     * Search entities by name or description
     */
    public function searchEntities($term, $limit = 50) {
        $sql = "SELECT * FROM entity_definitions
                WHERE (name LIKE ? OR description LIKE ?)
                AND deleted_at IS NULL
                ORDER BY name ASC
                LIMIT ?";
        return $this->query($sql, ["%$term%", "%$term%", $limit]);
    }

    /**
     * Get entities without authorizations
     */
    public function getWithoutAuthorizations() {
        $sql = "SELECT ed.*
                FROM entity_definitions ed
                LEFT JOIN entity_process_authorizations epa ON ed.id = epa.entity_id AND epa.deleted_at IS NULL
                WHERE ed.deleted_at IS NULL
                GROUP BY ed.id
                HAVING COUNT(epa.id) = 0
                ORDER BY ed.name ASC";
        return $this->query($sql);
    }

    /**
     * Initialize entity definitions from database tables
     */
    public function initializeFromDatabase() {
        // This would scan the database for tables and create entity definitions
        // Placeholder for future implementation
        return [];
    }

    /**
     * Bulk create entity definitions
     */
    public function bulkCreate($entities) {
        $created = 0;
        foreach ($entities as $entity) {
            if (!$this->nameExists($entity['name'])) {
                $this->create($entity);
                $created++;
            }
        }
        return $created;
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_entities,
                    COUNT(DISTINCT epa.id) as total_process_authorizations,
                    COUNT(DISTINCT eia.id) as total_instance_authorizations,
                    COUNT(DISTINCT epa.action) as unique_actions
                FROM entity_definitions ed
                LEFT JOIN entity_process_authorizations epa ON ed.id = epa.entity_id AND epa.deleted_at IS NULL
                LEFT JOIN entity_instance_authorizations eia ON ed.id = eia.entity_id AND eia.deleted_at IS NULL
                WHERE ed.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:200' . ($id ? "|unique:entity_definitions,name,$id" : '|unique:entity_definitions,name'),
            'description' => 'max:500',
        ];

        return $this->validate($data, $rules);
    }
}
