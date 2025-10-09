<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * OrganizationBranch Entity
 * Branches of an organization
 */
class OrganizationBranch extends BaseEntity {
    protected $table = 'organization_branches';
    protected $fillable = ['organization_id', 'name', 'code'];

    /**
     * Get organization
     */
    public function getOrganization($branchId) {
        $sql = "SELECT o.* FROM organizations o
                JOIN organization_branches ob ON ob.organization_id = o.id
                WHERE ob.id = ? AND o.deleted_at IS NULL";
        return $this->queryOne($sql, [$branchId]);
    }

    /**
     * Get buildings in this branch
     */
    public function getBuildings($branchId) {
        $sql = "SELECT ob.*, pa.city, pa.state, pa.country_id
                FROM organization_buildings ob
                LEFT JOIN postal_addresses pa ON ob.postal_address_id = pa.id
                WHERE ob.organization_branch_id = ? AND ob.deleted_at IS NULL
                ORDER BY ob.name ASC";
        return $this->query($sql, [$branchId]);
    }

    /**
     * Get building count
     */
    public function getBuildingCount($branchId) {
        $sql = "SELECT COUNT(*) as count FROM organization_buildings
                WHERE organization_branch_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$branchId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get branches by organization
     */
    public function getByOrganization($organizationId) {
        return $this->all(['organization_id' => $organizationId], 'name ASC');
    }

    /**
     * Get branch with full details
     */
    public function getWithDetails($branchId) {
        $sql = "SELECT ob.*, o.short_name as organization_name
                FROM organization_branches ob
                LEFT JOIN organizations o ON ob.organization_id = o.id
                WHERE ob.id = ? AND ob.deleted_at IS NULL";
        return $this->queryOne($sql, [$branchId]);
    }

    /**
     * Get all branches with details
     */
    public function getAllWithDetails() {
        $sql = "SELECT ob.*, o.short_name as organization_name,
                COUNT(DISTINCT obu.id) as building_count
                FROM organization_branches ob
                LEFT JOIN organizations o ON ob.organization_id = o.id
                LEFT JOIN organization_buildings obu ON ob.id = obu.organization_branch_id AND obu.deleted_at IS NULL
                WHERE ob.deleted_at IS NULL
                GROUP BY ob.id
                ORDER BY o.short_name ASC, ob.name ASC";
        return $this->query($sql);
    }

    /**
     * Get branch by code
     */
    public function getByCode($code, $organizationId = null) {
        $sql = "SELECT ob.*, o.short_name as organization_name
                FROM organization_branches ob
                LEFT JOIN organizations o ON ob.organization_id = o.id
                WHERE ob.code = ? AND ob.deleted_at IS NULL";

        $params = [$code];

        if ($organizationId) {
            $sql .= " AND ob.organization_id = ?";
            $params[] = $organizationId;
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Check if code is unique within organization
     */
    public function isCodeUnique($code, $organizationId, $exceptId = null) {
        $sql = "SELECT id FROM organization_branches
                WHERE code = ? AND organization_id = ? AND deleted_at IS NULL";
        $params = [$code, $organizationId];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return empty($result);
    }

    /**
     * Get all workstations in branch
     */
    public function getAllWorkstations($branchId) {
        $sql = "SELECT w.*, obu.name as building_name
                FROM workstations w
                JOIN organization_buildings obu ON w.organization_building_id = obu.id
                WHERE obu.organization_branch_id = ? AND w.deleted_at IS NULL
                ORDER BY obu.name ASC, w.floor ASC, w.room ASC, w.workstation_number ASC";
        return $this->query($sql, [$branchId]);
    }

    /**
     * Get workstation count
     */
    public function getWorkstationCount($branchId) {
        $sql = "SELECT COUNT(*) as count
                FROM workstations w
                JOIN organization_buildings obu ON w.organization_building_id = obu.id
                WHERE obu.organization_branch_id = ? AND w.deleted_at IS NULL";
        $result = $this->queryOne($sql, [$branchId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get vacancy count in branch
     */
    public function getVacancyCount($branchId) {
        $sql = "SELECT COUNT(DISTINCT ov.id) as count
                FROM organization_vacancies ov
                JOIN organization_vacancy_workstations ovw ON ov.id = ovw.organization_vacancy_id
                JOIN workstations w ON ovw.organization_workstation_id = w.id
                JOIN organization_buildings obu ON w.organization_building_id = obu.id
                WHERE obu.organization_branch_id = ?
                AND ov.status = 'Open'
                AND ov.deleted_at IS NULL AND ovw.deleted_at IS NULL AND w.deleted_at IS NULL";
        $result = $this->queryOne($sql, [$branchId]);
        return $result['count'] ?? 0;
    }

    /**
     * Search branches
     */
    public function searchBranches($term, $limit = 50) {
        $sql = "SELECT ob.*, o.short_name as organization_name
                FROM organization_branches ob
                LEFT JOIN organizations o ON ob.organization_id = o.id
                WHERE (ob.name LIKE ? OR ob.code LIKE ? OR o.short_name LIKE ?)
                AND ob.deleted_at IS NULL
                ORDER BY o.short_name ASC, ob.name ASC
                LIMIT ?";
        return $this->query($sql, ["%$term%", "%$term%", "%$term%", $limit]);
    }

    /**
     * Can delete check
     */
    public function canDelete($branchId) {
        if ($this->getBuildingCount($branchId) > 0) {
            return ['can_delete' => false, 'reason' => 'Branch has buildings'];
        }

        return ['can_delete' => true];
    }

    /**
     * Get branch statistics
     */
    public function getBranchStatistics($branchId) {
        $sql = "SELECT
                    COUNT(DISTINCT obu.id) as building_count,
                    COUNT(DISTINCT w.id) as workstation_count,
                    COUNT(DISTINCT ov.id) as vacancy_count
                FROM organization_branches ob
                LEFT JOIN organization_buildings obu ON ob.id = obu.organization_branch_id AND obu.deleted_at IS NULL
                LEFT JOIN workstations w ON obu.id = w.organization_building_id AND w.deleted_at IS NULL
                LEFT JOIN organization_vacancy_workstations ovw ON w.id = ovw.organization_workstation_id AND ovw.deleted_at IS NULL
                LEFT JOIN organization_vacancies ov ON ovw.organization_vacancy_id = ov.id AND ov.deleted_at IS NULL
                WHERE ob.id = ? AND ob.deleted_at IS NULL";
        return $this->queryOne($sql, [$branchId]);
    }

    /**
     * Get overall statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_branches,
                    COUNT(DISTINCT organization_id) as unique_organizations,
                    COUNT(DISTINCT obu.id) as total_buildings,
                    COUNT(DISTINCT w.id) as total_workstations
                FROM organization_branches ob
                LEFT JOIN organization_buildings obu ON ob.id = obu.organization_branch_id AND obu.deleted_at IS NULL
                LEFT JOIN workstations w ON obu.id = w.organization_building_id AND w.deleted_at IS NULL
                WHERE ob.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'organization_id' => 'required|integer',
            'name' => 'required|min:2|max:200',
            'code' => 'required|min:1|max:50',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $branch = $this->getWithDetails($id);
        if (!$branch) {
            return 'N/A';
        }
        return $branch['name'] . ' (' . $branch['code'] . ')';
    }
}
