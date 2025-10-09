<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * OrganizationBuilding Entity
 * Buildings within organization branches
 */
class OrganizationBuilding extends BaseEntity {
    protected $table = 'organization_buildings';
    protected $fillable = ['organization_branch_id', 'postal_address_id', 'name'];

    /**
     * Get branch
     */
    public function getBranch($buildingId) {
        $sql = "SELECT ob.* FROM organization_branches ob
                JOIN organization_buildings obu ON obu.organization_branch_id = ob.id
                WHERE obu.id = ? AND ob.deleted_at IS NULL";
        return $this->queryOne($sql, [$buildingId]);
    }

    /**
     * Get organization
     */
    public function getOrganization($buildingId) {
        $sql = "SELECT o.* FROM organizations o
                JOIN organization_branches ob ON ob.organization_id = o.id
                JOIN organization_buildings obu ON obu.organization_branch_id = ob.id
                WHERE obu.id = ? AND o.deleted_at IS NULL";
        return $this->queryOne($sql, [$buildingId]);
    }

    /**
     * Get postal address
     */
    public function getPostalAddress($buildingId) {
        $sql = "SELECT pa.* FROM postal_addresses pa
                JOIN organization_buildings obu ON obu.postal_address_id = pa.id
                WHERE obu.id = ? AND pa.deleted_at IS NULL";
        return $this->queryOne($sql, [$buildingId]);
    }

    /**
     * Get workstations in building
     */
    public function getWorkstations($buildingId) {
        $sql = "SELECT * FROM workstations
                WHERE organization_building_id = ? AND deleted_at IS NULL
                ORDER BY floor ASC, room ASC, workstation_number ASC";
        return $this->query($sql, [$buildingId]);
    }

    /**
     * Get workstation count
     */
    public function getWorkstationCount($buildingId) {
        $sql = "SELECT COUNT(*) as count FROM workstations
                WHERE organization_building_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$buildingId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get buildings by branch
     */
    public function getByBranch($branchId) {
        $sql = "SELECT obu.*, pa.city, pa.state
                FROM organization_buildings obu
                LEFT JOIN postal_addresses pa ON obu.postal_address_id = pa.id
                WHERE obu.organization_branch_id = ? AND obu.deleted_at IS NULL
                ORDER BY obu.name ASC";
        return $this->query($sql, [$branchId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($buildingId) {
        $sql = "SELECT obu.*,
                ob.name as branch_name, ob.code as branch_code,
                o.short_name as organization_name,
                pa.first_street, pa.city, pa.state, pa.pin,
                pa.latitude, pa.longitude
                FROM organization_buildings obu
                LEFT JOIN organization_branches ob ON obu.organization_branch_id = ob.id
                LEFT JOIN organizations o ON ob.organization_id = o.id
                LEFT JOIN postal_addresses pa ON obu.postal_address_id = pa.id
                WHERE obu.id = ? AND obu.deleted_at IS NULL";
        return $this->queryOne($sql, [$buildingId]);
    }

    /**
     * Get all with details
     */
    public function getAllWithDetails() {
        $sql = "SELECT obu.*,
                ob.name as branch_name,
                o.short_name as organization_name,
                pa.city, pa.state,
                COUNT(w.id) as workstation_count
                FROM organization_buildings obu
                LEFT JOIN organization_branches ob ON obu.organization_branch_id = ob.id
                LEFT JOIN organizations o ON ob.organization_id = o.id
                LEFT JOIN postal_addresses pa ON obu.postal_address_id = pa.id
                LEFT JOIN workstations w ON obu.id = w.organization_building_id AND w.deleted_at IS NULL
                WHERE obu.deleted_at IS NULL
                GROUP BY obu.id
                ORDER BY o.short_name ASC, ob.name ASC, obu.name ASC";
        return $this->query($sql);
    }

    /**
     * Get buildings by city
     */
    public function getByCity($city) {
        $sql = "SELECT obu.*, ob.name as branch_name, o.short_name as organization_name
                FROM organization_buildings obu
                LEFT JOIN organization_branches ob ON obu.organization_branch_id = ob.id
                LEFT JOIN organizations o ON ob.organization_id = o.id
                JOIN postal_addresses pa ON obu.postal_address_id = pa.id
                WHERE pa.city = ? AND obu.deleted_at IS NULL
                ORDER BY o.short_name ASC, obu.name ASC";
        return $this->query($sql, [$city]);
    }

    /**
     * Get buildings within radius
     */
    public function getBuildingsWithinRadius($latitude, $longitude, $radiusKm = 10) {
        $sql = "SELECT obu.*, ob.name as branch_name, o.short_name as organization_name,
                pa.city, pa.state, pa.latitude, pa.longitude,
                (6371 * acos(cos(radians(?)) * cos(radians(pa.latitude)) *
                cos(radians(pa.longitude) - radians(?)) + sin(radians(?)) *
                sin(radians(pa.latitude)))) AS distance
                FROM organization_buildings obu
                LEFT JOIN organization_branches ob ON obu.organization_branch_id = ob.id
                LEFT JOIN organizations o ON ob.organization_id = o.id
                JOIN postal_addresses pa ON obu.postal_address_id = pa.id
                WHERE pa.latitude IS NOT NULL AND pa.longitude IS NOT NULL
                AND obu.deleted_at IS NULL
                HAVING distance <= ?
                ORDER BY distance ASC";

        return $this->query($sql, [$latitude, $longitude, $latitude, $radiusKm]);
    }

    /**
     * Get floors in building
     */
    public function getFloors($buildingId) {
        $sql = "SELECT DISTINCT floor FROM workstations
                WHERE organization_building_id = ? AND deleted_at IS NULL
                ORDER BY floor ASC";
        return $this->query($sql, [$buildingId]);
    }

    /**
     * Get rooms in building
     */
    public function getRooms($buildingId, $floor = null) {
        $sql = "SELECT DISTINCT floor, room FROM workstations
                WHERE organization_building_id = ? AND deleted_at IS NULL";

        $params = [$buildingId];

        if ($floor !== null) {
            $sql .= " AND floor = ?";
            $params[] = $floor;
        }

        $sql .= " ORDER BY floor ASC, room ASC";
        return $this->query($sql, $params);
    }

    /**
     * Search buildings
     */
    public function searchBuildings($term, $limit = 50) {
        $sql = "SELECT obu.*, ob.name as branch_name, o.short_name as organization_name, pa.city
                FROM organization_buildings obu
                LEFT JOIN organization_branches ob ON obu.organization_branch_id = ob.id
                LEFT JOIN organizations o ON ob.organization_id = o.id
                LEFT JOIN postal_addresses pa ON obu.postal_address_id = pa.id
                WHERE (obu.name LIKE ? OR o.short_name LIKE ? OR pa.city LIKE ?)
                AND obu.deleted_at IS NULL
                ORDER BY o.short_name ASC, obu.name ASC
                LIMIT ?";
        return $this->query($sql, ["%$term%", "%$term%", "%$term%", $limit]);
    }

    /**
     * Can delete check
     */
    public function canDelete($buildingId) {
        if ($this->getWorkstationCount($buildingId) > 0) {
            return ['can_delete' => false, 'reason' => 'Building has workstations'];
        }

        return ['can_delete' => true];
    }

    /**
     * Get building statistics
     */
    public function getBuildingStatistics($buildingId) {
        $sql = "SELECT
                    COUNT(DISTINCT floor) as floor_count,
                    COUNT(DISTINCT CONCAT(floor, '-', room)) as room_count,
                    COUNT(*) as workstation_count
                FROM workstations
                WHERE organization_building_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$buildingId]);
    }

    /**
     * Get overall statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_buildings,
                    COUNT(DISTINCT organization_branch_id) as unique_branches,
                    COUNT(DISTINCT w.id) as total_workstations
                FROM organization_buildings obu
                LEFT JOIN workstations w ON obu.id = w.organization_building_id AND w.deleted_at IS NULL
                WHERE obu.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'organization_branch_id' => 'required|integer',
            'postal_address_id' => 'required|integer',
            'name' => 'required|min:2|max:200',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $building = $this->getWithDetails($id);
        if (!$building) {
            return 'N/A';
        }
        return $building['name'] . ' - ' . ($building['city'] ?? 'Unknown');
    }
}
