<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * Workstation Entity
 * Individual workstations within buildings
 */
class Workstation extends BaseEntity {
    protected $table = 'workstations';
    protected $fillable = ['organization_building_id', 'floor', 'room', 'workstation_number'];

    /**
     * Get building
     */
    public function getBuilding($workstationId) {
        $sql = "SELECT obu.* FROM organization_buildings obu
                JOIN workstations w ON w.organization_building_id = obu.id
                WHERE w.id = ? AND obu.deleted_at IS NULL";
        return $this->queryOne($sql, [$workstationId]);
    }

    /**
     * Get branch
     */
    public function getBranch($workstationId) {
        $sql = "SELECT ob.* FROM organization_branches ob
                JOIN organization_buildings obu ON obu.organization_branch_id = ob.id
                JOIN workstations w ON w.organization_building_id = obu.id
                WHERE w.id = ? AND ob.deleted_at IS NULL";
        return $this->queryOne($sql, [$workstationId]);
    }

    /**
     * Get organization
     */
    public function getOrganization($workstationId) {
        $sql = "SELECT o.* FROM organizations o
                JOIN organization_branches ob ON ob.organization_id = o.id
                JOIN organization_buildings obu ON obu.organization_branch_id = ob.id
                JOIN workstations w ON w.organization_building_id = obu.id
                WHERE w.id = ? AND o.deleted_at IS NULL";
        return $this->queryOne($sql, [$workstationId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($workstationId) {
        $sql = "SELECT w.*,
                obu.name as building_name,
                ob.name as branch_name, ob.code as branch_code,
                o.short_name as organization_name,
                pa.city, pa.state
                FROM workstations w
                LEFT JOIN organization_buildings obu ON w.organization_building_id = obu.id
                LEFT JOIN organization_branches ob ON obu.organization_branch_id = ob.id
                LEFT JOIN organizations o ON ob.organization_id = o.id
                LEFT JOIN postal_addresses pa ON obu.postal_address_id = pa.id
                WHERE w.id = ? AND w.deleted_at IS NULL";
        return $this->queryOne($sql, [$workstationId]);
    }

    /**
     * Get workstations by building
     */
    public function getByBuilding($buildingId) {
        return $this->all(['organization_building_id' => $buildingId], 'floor ASC, room ASC, workstation_number ASC');
    }

    /**
     * Get workstations by floor
     */
    public function getByFloor($buildingId, $floor) {
        $sql = "SELECT * FROM workstations
                WHERE organization_building_id = ? AND floor = ? AND deleted_at IS NULL
                ORDER BY room ASC, workstation_number ASC";
        return $this->query($sql, [$buildingId, $floor]);
    }

    /**
     * Get workstations by room
     */
    public function getByRoom($buildingId, $floor, $room) {
        $sql = "SELECT * FROM workstations
                WHERE organization_building_id = ? AND floor = ? AND room = ? AND deleted_at IS NULL
                ORDER BY workstation_number ASC";
        return $this->query($sql, [$buildingId, $floor, $room]);
    }

    /**
     * Check if workstation number exists
     */
    public function workstationExists($buildingId, $floor, $room, $workstationNumber, $exceptId = null) {
        $sql = "SELECT id FROM workstations
                WHERE organization_building_id = ? AND floor = ? AND room = ? AND workstation_number = ?
                AND deleted_at IS NULL";
        $params = [$buildingId, $floor, $room, $workstationNumber];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return !empty($result);
    }

    /**
     * Get vacancies for workstation
     */
    public function getVacancies($workstationId) {
        $sql = "SELECT ov.*, pop.name as position_name, o.short_name as organization_name
                FROM organization_vacancies ov
                JOIN organization_vacancy_workstations ovw ON ov.id = ovw.organization_vacancy_id
                JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                JOIN organizations o ON ov.organization_id = o.id
                WHERE ovw.organization_workstation_id = ?
                AND ov.deleted_at IS NULL AND ovw.deleted_at IS NULL
                ORDER BY ov.opening_date DESC";
        return $this->query($sql, [$workstationId]);
    }

    /**
     * Check if workstation has vacancies
     */
    public function hasVacancies($workstationId, $status = null) {
        $sql = "SELECT COUNT(*) as count
                FROM organization_vacancy_workstations ovw
                JOIN organization_vacancies ov ON ovw.organization_vacancy_id = ov.id
                WHERE ovw.organization_workstation_id = ?
                AND ov.deleted_at IS NULL AND ovw.deleted_at IS NULL";

        $params = [$workstationId];

        if ($status) {
            $sql .= " AND ov.status = ?";
            $params[] = $status;
        }

        $result = $this->queryOne($sql, $params);
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Get all with details
     */
    public function getAllWithDetails($limit = null, $offset = null) {
        $sql = "SELECT w.*,
                obu.name as building_name,
                ob.name as branch_name,
                o.short_name as organization_name
                FROM workstations w
                LEFT JOIN organization_buildings obu ON w.organization_building_id = obu.id
                LEFT JOIN organization_branches ob ON obu.organization_branch_id = ob.id
                LEFT JOIN organizations o ON ob.organization_id = o.id
                WHERE w.deleted_at IS NULL
                ORDER BY o.short_name ASC, ob.name ASC, obu.name ASC, w.floor ASC, w.room ASC, w.workstation_number ASC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->query($sql, [$limit, $offset ?? 0]);
        }

        return $this->query($sql);
    }

    /**
     * Get workstations with open vacancies
     */
    public function getWithOpenVacancies() {
        $sql = "SELECT DISTINCT w.*, obu.name as building_name,
                ob.name as branch_name, o.short_name as organization_name
                FROM workstations w
                LEFT JOIN organization_buildings obu ON w.organization_building_id = obu.id
                LEFT JOIN organization_branches ob ON obu.organization_branch_id = ob.id
                LEFT JOIN organizations o ON ob.organization_id = o.id
                JOIN organization_vacancy_workstations ovw ON w.id = ovw.organization_workstation_id
                JOIN organization_vacancies ov ON ovw.organization_vacancy_id = ov.id
                WHERE ov.status = 'Open'
                AND w.deleted_at IS NULL AND ovw.deleted_at IS NULL AND ov.deleted_at IS NULL
                ORDER BY o.short_name ASC, w.floor ASC, w.room ASC";
        return $this->query($sql);
    }

    /**
     * Get workstation identifier string
     */
    public function getIdentifier($workstationId) {
        $workstation = $this->find($workstationId);
        if (!$workstation) {
            return 'N/A';
        }

        return "Floor {$workstation['floor']}, Room {$workstation['room']}, WS-{$workstation['workstation_number']}";
    }

    /**
     * Search workstations
     */
    public function searchWorkstations($term, $limit = 50) {
        $sql = "SELECT w.*, obu.name as building_name, ob.name as branch_name, o.short_name as organization_name
                FROM workstations w
                LEFT JOIN organization_buildings obu ON w.organization_building_id = obu.id
                LEFT JOIN organization_branches ob ON obu.organization_branch_id = ob.id
                LEFT JOIN organizations o ON ob.organization_id = o.id
                WHERE (obu.name LIKE ? OR ob.name LIKE ? OR o.short_name LIKE ?)
                AND w.deleted_at IS NULL
                ORDER BY o.short_name ASC, w.floor ASC, w.room ASC
                LIMIT ?";
        return $this->query($sql, ["%$term%", "%$term%", "%$term%", $limit]);
    }

    /**
     * Get statistics by building
     */
    public function getStatisticsByBuilding($buildingId) {
        $sql = "SELECT
                    COUNT(*) as total_workstations,
                    COUNT(DISTINCT floor) as floor_count,
                    COUNT(DISTINCT CONCAT(floor, '-', room)) as room_count
                FROM workstations
                WHERE organization_building_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$buildingId]);
    }

    /**
     * Get overall statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_workstations,
                    COUNT(DISTINCT organization_building_id) as unique_buildings,
                    COUNT(DISTINCT ovw.id) as workstations_with_vacancies
                FROM workstations w
                LEFT JOIN organization_vacancy_workstations ovw ON w.id = ovw.organization_workstation_id AND ovw.deleted_at IS NULL
                WHERE w.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'organization_building_id' => 'required|integer',
            'floor' => 'required|integer',
            'room' => 'required|max:50',
            'workstation_number' => 'required|max:50',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        return $this->getIdentifier($id);
    }
}
