<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * EntityInstanceAuthorization Entity
 * Per-record authorization overrides
 */
class EntityInstanceAuthorization extends BaseEntity {
    protected $table = 'entity_instance_authorizations';
    protected $fillable = [
        'entity_id', 'entity_record_id', 'action', 'assigned_position_id',
        'assigned_person_id', 'valid_from', 'valid_to', 'status'
    ];

    /**
     * Get entity definition
     */
    public function getEntity($authId) {
        $sql = "SELECT ed.* FROM entity_definitions ed
                JOIN entity_instance_authorizations eia ON eia.entity_id = ed.id
                WHERE eia.id = ? AND ed.deleted_at IS NULL";
        return $this->queryOne($sql, [$authId]);
    }

    /**
     * Get assigned position
     */
    public function getPosition($authId) {
        $sql = "SELECT pop.* FROM popular_organization_positions pop
                JOIN entity_instance_authorizations eia ON eia.assigned_position_id = pop.id
                WHERE eia.id = ? AND pop.deleted_at IS NULL";
        return $this->queryOne($sql, [$authId]);
    }

    /**
     * Get assigned person
     */
    public function getPerson($authId) {
        $sql = "SELECT p.* FROM persons p
                JOIN entity_instance_authorizations eia ON eia.assigned_person_id = p.id
                WHERE eia.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$authId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($authId) {
        $sql = "SELECT eia.*,
                ed.name as entity_name,
                pop.name as position_name,
                d.name as department_name,
                p.first_name, p.last_name
                FROM entity_instance_authorizations eia
                LEFT JOIN entity_definitions ed ON eia.entity_id = ed.id
                LEFT JOIN popular_organization_positions pop ON eia.assigned_position_id = pop.id
                LEFT JOIN popular_organization_departments d ON pop.department_id = d.id
                LEFT JOIN persons p ON eia.assigned_person_id = p.id
                WHERE eia.id = ? AND eia.deleted_at IS NULL";
        return $this->queryOne($sql, [$authId]);
    }

    /**
     * Get authorizations by entity and record
     */
    public function getByEntityRecord($entityId, $recordId) {
        $sql = "SELECT eia.*,
                pop.name as position_name,
                p.first_name, p.last_name
                FROM entity_instance_authorizations eia
                LEFT JOIN popular_organization_positions pop ON eia.assigned_position_id = pop.id
                LEFT JOIN persons p ON eia.assigned_person_id = p.id
                WHERE eia.entity_id = ? AND eia.entity_record_id = ?
                AND eia.deleted_at IS NULL
                ORDER BY eia.action ASC";
        return $this->query($sql, [$entityId, $recordId]);
    }

    /**
     * Get authorizations by person
     */
    public function getByPerson($personId) {
        $sql = "SELECT eia.*,
                ed.name as entity_name,
                pop.name as position_name
                FROM entity_instance_authorizations eia
                LEFT JOIN entity_definitions ed ON eia.entity_id = ed.id
                LEFT JOIN popular_organization_positions pop ON eia.assigned_position_id = pop.id
                WHERE eia.assigned_person_id = ? AND eia.deleted_at IS NULL
                ORDER BY ed.name ASC, eia.entity_record_id ASC";
        return $this->query($sql, [$personId]);
    }

    /**
     * Get authorizations by position
     */
    public function getByPosition($positionId) {
        $sql = "SELECT eia.*,
                ed.name as entity_name,
                p.first_name, p.last_name
                FROM entity_instance_authorizations eia
                LEFT JOIN entity_definitions ed ON eia.entity_id = ed.id
                LEFT JOIN persons p ON eia.assigned_person_id = p.id
                WHERE eia.assigned_position_id = ? AND eia.deleted_at IS NULL
                ORDER BY ed.name ASC, eia.entity_record_id ASC";
        return $this->query($sql, [$positionId]);
    }

    /**
     * Get active authorizations
     */
    public function getActive($entityId = null, $recordId = null) {
        $today = date('Y-m-d');

        $sql = "SELECT eia.*,
                ed.name as entity_name,
                pop.name as position_name,
                p.first_name, p.last_name
                FROM entity_instance_authorizations eia
                LEFT JOIN entity_definitions ed ON eia.entity_id = ed.id
                LEFT JOIN popular_organization_positions pop ON eia.assigned_position_id = pop.id
                LEFT JOIN persons p ON eia.assigned_person_id = p.id
                WHERE eia.status = 'Active'
                AND (eia.valid_from IS NULL OR eia.valid_from <= ?)
                AND (eia.valid_to IS NULL OR eia.valid_to >= ?)
                AND eia.deleted_at IS NULL";

        $params = [$today, $today];

        if ($entityId) {
            $sql .= " AND eia.entity_id = ?";
            $params[] = $entityId;
        }

        if ($recordId) {
            $sql .= " AND eia.entity_record_id = ?";
            $params[] = $recordId;
        }

        $sql .= " ORDER BY ed.name ASC, eia.entity_record_id ASC";
        return $this->query($sql, $params);
    }

    /**
     * Check if person can perform action on specific record
     */
    public function canPerformOnRecord($entityId, $recordId, $action, $personId) {
        $today = date('Y-m-d');

        $sql = "SELECT COUNT(*) as count
                FROM entity_instance_authorizations
                WHERE entity_id = ? AND entity_record_id = ? AND action = ?
                AND assigned_person_id = ? AND status = 'Active'
                AND (valid_from IS NULL OR valid_from <= ?)
                AND (valid_to IS NULL OR valid_to >= ?)
                AND deleted_at IS NULL";

        $result = $this->queryOne($sql, [$entityId, $recordId, $action, $personId, $today, $today]);

        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Grant instance authorization
     */
    public function grant($entityId, $recordId, $action, $positionId = null, $personId = null, $validFrom = null, $validTo = null) {
        $data = [
            'entity_id' => $entityId,
            'entity_record_id' => $recordId,
            'action' => $action,
            'assigned_position_id' => $positionId,
            'assigned_person_id' => $personId,
            'valid_from' => $validFrom,
            'valid_to' => $validTo,
            'status' => 'Active'
        ];

        $authId = $this->create($data);

        if ($authId) {
            return ['success' => true, 'authorization_id' => $authId];
        }

        return ['success' => false, 'message' => 'Failed to grant authorization'];
    }

    /**
     * Revoke authorization
     */
    public function revoke($authId) {
        return $this->update($authId, ['status' => 'Revoked']);
    }

    /**
     * Activate authorization
     */
    public function activate($authId) {
        return $this->update($authId, ['status' => 'Active']);
    }

    /**
     * Get expiring authorizations
     */
    public function getExpiringSoon($days = 7) {
        $today = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime("+$days days"));

        $sql = "SELECT eia.*,
                ed.name as entity_name,
                pop.name as position_name,
                p.first_name, p.last_name
                FROM entity_instance_authorizations eia
                LEFT JOIN entity_definitions ed ON eia.entity_id = ed.id
                LEFT JOIN popular_organization_positions pop ON eia.assigned_position_id = pop.id
                LEFT JOIN persons p ON eia.assigned_person_id = p.id
                WHERE eia.status = 'Active'
                AND eia.valid_to BETWEEN ? AND ?
                AND eia.deleted_at IS NULL
                ORDER BY eia.valid_to ASC";

        return $this->query($sql, [$today, $futureDate]);
    }

    /**
     * Get expired authorizations
     */
    public function getExpired() {
        $today = date('Y-m-d');

        $sql = "SELECT eia.*,
                ed.name as entity_name,
                pop.name as position_name,
                p.first_name, p.last_name
                FROM entity_instance_authorizations eia
                LEFT JOIN entity_definitions ed ON eia.entity_id = ed.id
                LEFT JOIN popular_organization_positions pop ON eia.assigned_position_id = pop.id
                LEFT JOIN persons p ON eia.assigned_person_id = p.id
                WHERE eia.status = 'Active'
                AND eia.valid_to < ?
                AND eia.deleted_at IS NULL
                ORDER BY eia.valid_to DESC";

        return $this->query($sql, [$today]);
    }

    /**
     * Auto-revoke expired authorizations
     */
    public function autoRevokeExpired() {
        $today = date('Y-m-d');

        $sql = "UPDATE entity_instance_authorizations
                SET status = 'Revoked', updated_at = datetime('now')
                WHERE status = 'Active'
                AND valid_to < ?
                AND deleted_at IS NULL";

        // Note: This is direct SQL update, not using the update() method
        // You may want to use the update() method for each record instead
        return $this->db->execute($sql, [$today]);
    }

    /**
     * Get all with details
     */
    public function getAllWithDetails($limit = null, $offset = null) {
        $sql = "SELECT eia.*,
                ed.name as entity_name,
                pop.name as position_name,
                p.first_name, p.last_name
                FROM entity_instance_authorizations eia
                LEFT JOIN entity_definitions ed ON eia.entity_id = ed.id
                LEFT JOIN popular_organization_positions pop ON eia.assigned_position_id = pop.id
                LEFT JOIN persons p ON eia.assigned_person_id = p.id
                WHERE eia.deleted_at IS NULL
                ORDER BY ed.name ASC, eia.entity_record_id ASC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->query($sql, [$limit, $offset ?? 0]);
        }

        return $this->query($sql);
    }

    /**
     * Get by status
     */
    public function getByStatus($status) {
        $sql = "SELECT eia.*,
                ed.name as entity_name,
                pop.name as position_name,
                p.first_name, p.last_name
                FROM entity_instance_authorizations eia
                LEFT JOIN entity_definitions ed ON eia.entity_id = ed.id
                LEFT JOIN popular_organization_positions pop ON eia.assigned_position_id = pop.id
                LEFT JOIN persons p ON eia.assigned_person_id = p.id
                WHERE eia.status = ? AND eia.deleted_at IS NULL
                ORDER BY ed.name ASC";
        return $this->query($sql, [$status]);
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_authorizations,
                    COUNT(CASE WHEN status = 'Active' THEN 1 END) as active_count,
                    COUNT(CASE WHEN status = 'Revoked' THEN 1 END) as revoked_count,
                    COUNT(DISTINCT entity_id) as unique_entities,
                    COUNT(DISTINCT assigned_person_id) as unique_persons,
                    COUNT(DISTINCT assigned_position_id) as unique_positions
                FROM entity_instance_authorizations
                WHERE deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'entity_id' => 'required|integer',
            'entity_record_id' => 'required',
            'action' => 'required|max:50',
            'assigned_position_id' => 'integer',
            'assigned_person_id' => 'integer',
            'valid_from' => 'date',
            'valid_to' => 'date',
            'status' => 'required',
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

        $assignee = $auth['first_name']
            ? $auth['first_name'] . ' ' . $auth['last_name']
            : $auth['position_name'];

        return $auth['entity_name'] . ' #' . $auth['entity_record_id'] . ' - ' . $auth['action'] . ' (' . $assignee . ')';
    }
}
