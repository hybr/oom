<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * EntityProcessAuthorization Entity
 * Defines which positions can perform which actions on entities
 */
class EntityProcessAuthorization extends BaseEntity {
    protected $table = 'entity_process_authorizations';
    protected $fillable = ['entity_id', 'action', 'popular_position_id', 'remarks'];

    /**
     * Get entity definition
     */
    public function getEntity($authId) {
        $sql = "SELECT ed.* FROM entity_definitions ed
                JOIN entity_process_authorizations epa ON epa.entity_id = ed.id
                WHERE epa.id = ? AND ed.deleted_at IS NULL";
        return $this->queryOne($sql, [$authId]);
    }

    /**
     * Get position
     */
    public function getPosition($authId) {
        $sql = "SELECT pop.* FROM popular_organization_positions pop
                JOIN entity_process_authorizations epa ON epa.popular_position_id = pop.id
                WHERE epa.id = ? AND pop.deleted_at IS NULL";
        return $this->queryOne($sql, [$authId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($authId) {
        $sql = "SELECT epa.*,
                ed.name as entity_name, ed.description as entity_description,
                pop.name as position_name,
                d.name as department_name,
                des.name as designation_name
                FROM entity_process_authorizations epa
                LEFT JOIN entity_definitions ed ON epa.entity_id = ed.id
                LEFT JOIN popular_organization_positions pop ON epa.popular_position_id = pop.id
                LEFT JOIN popular_organization_departments d ON pop.department_id = d.id
                LEFT JOIN popular_organization_designations des ON pop.designation_id = des.id
                WHERE epa.id = ? AND epa.deleted_at IS NULL";
        return $this->queryOne($sql, [$authId]);
    }

    /**
     * Get authorizations by entity
     */
    public function getByEntity($entityId) {
        $sql = "SELECT epa.*, pop.name as position_name,
                d.name as department_name
                FROM entity_process_authorizations epa
                LEFT JOIN popular_organization_positions pop ON epa.popular_position_id = pop.id
                LEFT JOIN popular_organization_departments d ON pop.department_id = d.id
                WHERE epa.entity_id = ? AND epa.deleted_at IS NULL
                ORDER BY epa.action ASC, d.name ASC, pop.name ASC";
        return $this->query($sql, [$entityId]);
    }

    /**
     * Get authorizations by position
     */
    public function getByPosition($positionId) {
        $sql = "SELECT epa.*, ed.name as entity_name
                FROM entity_process_authorizations epa
                LEFT JOIN entity_definitions ed ON epa.entity_id = ed.id
                WHERE epa.popular_position_id = ? AND epa.deleted_at IS NULL
                ORDER BY ed.name ASC, epa.action ASC";
        return $this->query($sql, [$positionId]);
    }

    /**
     * Get by action
     */
    public function getByAction($action) {
        $sql = "SELECT epa.*,
                ed.name as entity_name,
                pop.name as position_name,
                d.name as department_name
                FROM entity_process_authorizations epa
                LEFT JOIN entity_definitions ed ON epa.entity_id = ed.id
                LEFT JOIN popular_organization_positions pop ON epa.popular_position_id = pop.id
                LEFT JOIN popular_organization_departments d ON pop.department_id = d.id
                WHERE epa.action = ? AND epa.deleted_at IS NULL
                ORDER BY ed.name ASC, d.name ASC, pop.name ASC";
        return $this->query($sql, [$action]);
    }

    /**
     * Check if position can perform action on entity
     */
    public function canPerform($entityId, $action, $positionId) {
        $sql = "SELECT COUNT(*) as count
                FROM entity_process_authorizations
                WHERE entity_id = ? AND action = ? AND popular_position_id = ?
                AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$entityId, $action, $positionId]);

        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Check if person can perform action (through their position)
     */
    public function personCanPerform($entityName, $action, $personId) {
        // This would need to check person's current position/role
        // Placeholder for complex authorization logic
        require_once __DIR__ . '/EntityDefinition.php';
        $entityDef = new EntityDefinition();
        $entity = $entityDef->getByName($entityName);

        if (!$entity) return false;

        // Would need to get person's position and check authorization
        // For now, return false - needs implementation
        return false;
    }

    /**
     * Grant authorization
     */
    public function grant($entityId, $action, $positionId, $remarks = '') {
        // Check if already exists
        if ($this->authorizationExists($entityId, $action, $positionId)) {
            return ['success' => false, 'message' => 'Authorization already exists'];
        }

        $authId = $this->create([
            'entity_id' => $entityId,
            'action' => $action,
            'popular_position_id' => $positionId,
            'remarks' => $remarks
        ]);

        if ($authId) {
            return ['success' => true, 'authorization_id' => $authId];
        }

        return ['success' => false, 'message' => 'Failed to grant authorization'];
    }

    /**
     * Revoke authorization
     */
    public function revoke($entityId, $action, $positionId) {
        $sql = "SELECT id FROM entity_process_authorizations
                WHERE entity_id = ? AND action = ? AND popular_position_id = ?
                AND deleted_at IS NULL";
        $auth = $this->queryOne($sql, [$entityId, $action, $positionId]);

        if ($auth) {
            return $this->delete($auth['id']);
        }

        return false;
    }

    /**
     * Check if authorization exists
     */
    public function authorizationExists($entityId, $action, $positionId, $exceptId = null) {
        $sql = "SELECT id FROM entity_process_authorizations
                WHERE entity_id = ? AND action = ? AND popular_position_id = ?
                AND deleted_at IS NULL";
        $params = [$entityId, $action, $positionId];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return !empty($result);
    }

    /**
     * Get all actions
     */
    public function getAllActions() {
        // These should match ENUM_PROCESS_ACTIONS from ER diagram
        return [
            'REQUEST',
            'FEASIBILITY_ANALYSIS',
            'APPROVE',
            'DESIGN',
            'DEVELOP',
            'TEST',
            'IMPLEMENT',
            'SUPPORT'
        ];
    }

    /**
     * Bulk grant authorizations
     */
    public function bulkGrant($entityId, $actionPositionPairs) {
        $granted = 0;
        foreach ($actionPositionPairs as $pair) {
            if (!$this->authorizationExists($entityId, $pair['action'], $pair['position_id'])) {
                $this->create([
                    'entity_id' => $entityId,
                    'action' => $pair['action'],
                    'popular_position_id' => $pair['position_id'],
                    'remarks' => $pair['remarks'] ?? ''
                ]);
                $granted++;
            }
        }
        return $granted;
    }

    /**
     * Get action distribution
     */
    public function getActionDistribution() {
        $sql = "SELECT action, COUNT(*) as count
                FROM entity_process_authorizations
                WHERE deleted_at IS NULL
                GROUP BY action
                ORDER BY count DESC";
        return $this->query($sql);
    }

    /**
     * Get all with details
     */
    public function getAllWithDetails($limit = null, $offset = null) {
        $sql = "SELECT epa.*,
                ed.name as entity_name,
                pop.name as position_name,
                d.name as department_name
                FROM entity_process_authorizations epa
                LEFT JOIN entity_definitions ed ON epa.entity_id = ed.id
                LEFT JOIN popular_organization_positions pop ON epa.popular_position_id = pop.id
                LEFT JOIN popular_organization_departments d ON pop.department_id = d.id
                WHERE epa.deleted_at IS NULL
                ORDER BY ed.name ASC, epa.action ASC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->query($sql, [$limit, $offset ?? 0]);
        }

        return $this->query($sql);
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_authorizations,
                    COUNT(DISTINCT entity_id) as unique_entities,
                    COUNT(DISTINCT popular_position_id) as unique_positions,
                    COUNT(DISTINCT action) as unique_actions
                FROM entity_process_authorizations
                WHERE deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'entity_id' => 'required|integer',
            'action' => 'required|max:50',
            'popular_position_id' => 'required|integer',
            'remarks' => 'max:500',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $auth = $this->getWithDetails($id);
        if (!$auth) {
            return 'N/A';
        }
        return $auth['entity_name'] . ' - ' . $auth['action'] . ' (' . $auth['position_name'] . ')';
    }
}
