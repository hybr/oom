<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * OrganizationVacancy Entity
 * Job openings/vacancies in organizations
 */
class OrganizationVacancy extends BaseEntity {
    protected $table = 'organization_vacancies';
    protected $fillable = [
        'organization_id', 'popular_position_id', 'opening_date',
        'closing_date', 'status', 'created_by'
    ];

    /**
     * Get organization
     */
    public function getOrganization($vacancyId) {
        $sql = "SELECT o.* FROM organizations o
                JOIN organization_vacancies ov ON ov.organization_id = o.id
                WHERE ov.id = ? AND o.deleted_at IS NULL";
        return $this->queryOne($sql, [$vacancyId]);
    }

    /**
     * Get position
     */
    public function getPosition($vacancyId) {
        $sql = "SELECT p.* FROM popular_organization_positions p
                JOIN organization_vacancies ov ON ov.popular_position_id = p.id
                WHERE ov.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$vacancyId]);
    }

    /**
     * Get creator
     */
    public function getCreator($vacancyId) {
        $sql = "SELECT p.* FROM persons p
                JOIN organization_vacancies ov ON ov.created_by = p.id
                WHERE ov.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$vacancyId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($vacancyId) {
        $sql = "SELECT ov.*,
                o.short_name as organization_name,
                pop.name as position_name,
                d.name as department_name,
                des.name as designation_name,
                p.first_name as creator_first_name, p.last_name as creator_last_name
                FROM organization_vacancies ov
                LEFT JOIN organizations o ON ov.organization_id = o.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN popular_organization_departments d ON pop.department_id = d.id
                LEFT JOIN popular_organization_designations des ON pop.designation_id = des.id
                LEFT JOIN persons p ON ov.created_by = p.id
                WHERE ov.id = ? AND ov.deleted_at IS NULL";
        return $this->queryOne($sql, [$vacancyId]);
    }

    /**
     * Get applications
     */
    public function getApplications($vacancyId, $status = null) {
        $sql = "SELECT va.*, p.first_name, p.last_name, p.date_of_birth
                FROM vacancy_applications va
                JOIN persons p ON va.applicant_id = p.id
                WHERE va.vacancy_id = ? AND va.deleted_at IS NULL";

        $params = [$vacancyId];

        if ($status) {
            $sql .= " AND va.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY va.application_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get application count
     */
    public function getApplicationCount($vacancyId, $status = null) {
        $sql = "SELECT COUNT(*) as count FROM vacancy_applications
                WHERE vacancy_id = ? AND deleted_at IS NULL";

        $params = [$vacancyId];

        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        $result = $this->queryOne($sql, $params);
        return $result['count'] ?? 0;
    }

    /**
     * Get workstations
     */
    public function getWorkstations($vacancyId) {
        $sql = "SELECT w.*, obu.name as building_name,  ob.name as branch_name
                FROM workstations w
                JOIN organization_vacancy_workstations ovw ON w.id = ovw.organization_workstation_id
                JOIN organization_buildings obu ON w.organization_building_id = obu.id
                JOIN organization_branches ob ON obu.organization_branch_id = ob.id
                WHERE ovw.organization_vacancy_id = ?
                AND w.deleted_at IS NULL AND ovw.deleted_at IS NULL
                ORDER BY w.floor ASC, w.room ASC";
        return $this->query($sql, [$vacancyId]);
    }

    /**
     * Get by organization
     */
    public function getByOrganization($organizationId, $status = null) {
        $sql = "SELECT ov.*, pop.name as position_name
                FROM organization_vacancies ov
                JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ov.organization_id = ? AND ov.deleted_at IS NULL";

        $params = [$organizationId];

        if ($status) {
            $sql .= " AND ov.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY ov.opening_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get open vacancies
     */
    public function getOpenVacancies($organizationId = null) {
        $sql = "SELECT ov.*, o.short_name as organization_name, pop.name as position_name
                FROM organization_vacancies ov
                JOIN organizations o ON ov.organization_id = o.id
                JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ov.status = 'Open' AND ov.deleted_at IS NULL";

        $params = [];

        if ($organizationId) {
            $sql .= " AND ov.organization_id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY ov.opening_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get closing soon (within days)
     */
    public function getClosingSoon($days = 7) {
        $sql = "SELECT ov.*, o.short_name as organization_name, pop.name as position_name
                FROM organization_vacancies ov
                JOIN organizations o ON ov.organization_id = o.id
                JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ov.status = 'Open'
                AND ov.closing_date <= date('now', '+' || ? || ' days')
                AND ov.deleted_at IS NULL
                ORDER BY ov.closing_date ASC";
        return $this->query($sql, [$days]);
    }

    /**
     * Close vacancy
     */
    public function closeVacancy($vacancyId) {
        return $this->update($vacancyId, ['status' => 'Closed']);
    }

    /**
     * Reopen vacancy
     */
    public function reopenVacancy($vacancyId, $newClosingDate = null) {
        $data = ['status' => 'Open'];
        if ($newClosingDate) {
            $data['closing_date'] = $newClosingDate;
        }
        return $this->update($vacancyId, $data);
    }

    /**
     * Put vacancy on hold
     */
    public function putOnHold($vacancyId) {
        return $this->update($vacancyId, ['status' => 'On Hold']);
    }

    /**
     * Get vacancies by status
     */
    public function getByStatus($status) {
        $sql = "SELECT ov.*, o.short_name as organization_name, pop.name as position_name
                FROM organization_vacancies ov
                JOIN organizations o ON ov.organization_id = o.id
                JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ov.status = ? AND ov.deleted_at IS NULL
                ORDER BY ov.opening_date DESC";
        return $this->query($sql, [$status]);
    }

    /**
     * Get vacancies in date range
     */
    public function getInDateRange($startDate, $endDate) {
        $sql = "SELECT ov.*, o.short_name as organization_name, pop.name as position_name
                FROM organization_vacancies ov
                JOIN organizations o ON ov.organization_id = o.id
                JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ov.opening_date BETWEEN ? AND ?
                AND ov.deleted_at IS NULL
                ORDER BY ov.opening_date DESC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    /**
     * Get statistics
     */
    public function getStatistics($organizationId = null) {
        $sql = "SELECT
                    COUNT(*) as total_vacancies,
                    COUNT(CASE WHEN status = 'Open' THEN 1 END) as open_count,
                    COUNT(CASE WHEN status = 'Closed' THEN 1 END) as closed_count,
                    COUNT(CASE WHEN status = 'On Hold' THEN 1 END) as on_hold_count,
                    COUNT(DISTINCT va.id) as total_applications
                FROM organization_vacancies ov
                LEFT JOIN vacancy_applications va ON ov.id = va.vacancy_id AND va.deleted_at IS NULL
                WHERE ov.deleted_at IS NULL";

        $params = [];

        if ($organizationId) {
            $sql .= " AND ov.organization_id = ?";
            $params[] = $organizationId;
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'organization_id' => 'required|integer',
            'popular_position_id' => 'required|integer',
            'opening_date' => 'required|date',
            'closing_date' => 'date',
            'status' => 'required',
            'created_by' => 'required|integer',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $vacancy = $this->getWithDetails($id);
        if (!$vacancy) {
            return 'N/A';
        }
        return $vacancy['position_name'] . ' at ' . $vacancy['organization_name'];
    }
}
