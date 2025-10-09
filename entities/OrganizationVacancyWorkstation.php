<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * OrganizationVacancyWorkstation Entity
 * Links vacancies with workstations
 */
class OrganizationVacancyWorkstation extends BaseEntity {
    protected $table = 'organization_vacancy_workstations';
    protected $fillable = ['organization_vacancy_id', 'organization_workstation_id'];

    /**
     * Get vacancy
     */
    public function getVacancy($id) {
        $sql = "SELECT ov.* FROM organization_vacancies ov
                JOIN organization_vacancy_workstations ovw ON ovw.organization_vacancy_id = ov.id
                WHERE ovw.id = ? AND ov.deleted_at IS NULL";
        return $this->queryOne($sql, [$id]);
    }

    /**
     * Get workstation
     */
    public function getWorkstation($id) {
        $sql = "SELECT w.* FROM workstations w
                JOIN organization_vacancy_workstations ovw ON ovw.organization_workstation_id = w.id
                WHERE ovw.id = ? AND w.deleted_at IS NULL";
        return $this->queryOne($sql, [$id]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($id) {
        $sql = "SELECT ovw.*,
                ov.opening_date, ov.closing_date, ov.status as vacancy_status,
                pop.name as position_name,
                o.short_name as organization_name,
                w.floor, w.room, w.workstation_number,
                obu.name as building_name,
                ob.name as branch_name
                FROM organization_vacancy_workstations ovw
                LEFT JOIN organization_vacancies ov ON ovw.organization_vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                LEFT JOIN workstations w ON ovw.organization_workstation_id = w.id
                LEFT JOIN organization_buildings obu ON w.organization_building_id = obu.id
                LEFT JOIN organization_branches ob ON obu.organization_branch_id = ob.id
                WHERE ovw.id = ? AND ovw.deleted_at IS NULL";
        return $this->queryOne($sql, [$id]);
    }

    /**
     * Get by vacancy
     */
    public function getByVacancy($vacancyId) {
        $sql = "SELECT ovw.*, w.floor, w.room, w.workstation_number,
                obu.name as building_name, ob.name as branch_name
                FROM organization_vacancy_workstations ovw
                LEFT JOIN workstations w ON ovw.organization_workstation_id = w.id
                LEFT JOIN organization_buildings obu ON w.organization_building_id = obu.id
                LEFT JOIN organization_branches ob ON obu.organization_branch_id = ob.id
                WHERE ovw.organization_vacancy_id = ? AND ovw.deleted_at IS NULL
                ORDER BY ob.name ASC, obu.name ASC, w.floor ASC, w.room ASC";
        return $this->query($sql, [$vacancyId]);
    }

    /**
     * Get by workstation
     */
    public function getByWorkstation($workstationId) {
        $sql = "SELECT ovw.*, ov.opening_date, ov.closing_date, ov.status,
                pop.name as position_name, o.short_name as organization_name
                FROM organization_vacancy_workstations ovw
                LEFT JOIN organization_vacancies ov ON ovw.organization_vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE ovw.organization_workstation_id = ? AND ovw.deleted_at IS NULL
                ORDER BY ov.opening_date DESC";
        return $this->query($sql, [$workstationId]);
    }

    /**
     * Check if workstation is already assigned to vacancy
     */
    public function isAssigned($vacancyId, $workstationId, $exceptId = null) {
        $sql = "SELECT id FROM organization_vacancy_workstations
                WHERE organization_vacancy_id = ? AND organization_workstation_id = ?
                AND deleted_at IS NULL";
        $params = [$vacancyId, $workstationId];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return !empty($result);
    }

    /**
     * Get all with details
     */
    public function getAllWithDetails($limit = null, $offset = null) {
        $sql = "SELECT ovw.*,
                ov.opening_date, ov.status as vacancy_status,
                pop.name as position_name,
                o.short_name as organization_name,
                w.floor, w.room, w.workstation_number,
                obu.name as building_name
                FROM organization_vacancy_workstations ovw
                LEFT JOIN organization_vacancies ov ON ovw.organization_vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                LEFT JOIN workstations w ON ovw.organization_workstation_id = w.id
                LEFT JOIN organization_buildings obu ON w.organization_building_id = obu.id
                WHERE ovw.deleted_at IS NULL
                ORDER BY o.short_name ASC, ov.opening_date DESC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->query($sql, [$limit, $offset ?? 0]);
        }

        return $this->query($sql);
    }

    /**
     * Bulk assign workstations to vacancy
     */
    public function bulkAssign($vacancyId, $workstationIds) {
        $assigned = 0;
        foreach ($workstationIds as $workstationId) {
            if (!$this->isAssigned($vacancyId, $workstationId)) {
                $this->create([
                    'organization_vacancy_id' => $vacancyId,
                    'organization_workstation_id' => $workstationId
                ]);
                $assigned++;
            }
        }
        return $assigned;
    }

    /**
     * Remove workstation from vacancy
     */
    public function removeFromVacancy($vacancyId, $workstationId) {
        $sql = "SELECT id FROM organization_vacancy_workstations
                WHERE organization_vacancy_id = ? AND organization_workstation_id = ?
                AND deleted_at IS NULL";
        $record = $this->queryOne($sql, [$vacancyId, $workstationId]);

        if ($record) {
            return $this->delete($record['id']);
        }

        return false;
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_assignments,
                    COUNT(DISTINCT organization_vacancy_id) as unique_vacancies,
                    COUNT(DISTINCT organization_workstation_id) as unique_workstations
                FROM organization_vacancy_workstations
                WHERE deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'organization_vacancy_id' => 'required|integer',
            'organization_workstation_id' => 'required|integer',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $record = $this->getWithDetails($id);
        if (!$record) {
            return 'N/A';
        }
        return $record['position_name'] . ' - ' . $record['building_name'] . ' Floor ' . $record['floor'];
    }
}
