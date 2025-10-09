<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * EmploymentContract Entity
 * Employment contracts for accepted job offers
 */
class EmploymentContract extends BaseEntity {
    protected $table = 'employment_contracts';
    protected $fillable = [
        'job_offer_id', 'organization_id', 'employee_id', 'start_date',
        'end_date', 'contract_terms', 'status'
    ];

    /**
     * Get job offer
     */
    public function getJobOffer($contractId) {
        $sql = "SELECT jo.* FROM job_offers jo
                JOIN employment_contracts ec ON ec.job_offer_id = jo.id
                WHERE ec.id = ? AND jo.deleted_at IS NULL";
        return $this->queryOne($sql, [$contractId]);
    }

    /**
     * Get organization
     */
    public function getOrganization($contractId) {
        $sql = "SELECT o.* FROM organizations o
                JOIN employment_contracts ec ON ec.organization_id = o.id
                WHERE ec.id = ? AND o.deleted_at IS NULL";
        return $this->queryOne($sql, [$contractId]);
    }

    /**
     * Get employee
     */
    public function getEmployee($contractId) {
        $sql = "SELECT p.* FROM persons p
                JOIN employment_contracts ec ON ec.employee_id = p.id
                WHERE ec.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$contractId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($contractId) {
        $sql = "SELECT ec.*,
                p.first_name as employee_first_name, p.middle_name as employee_middle_name,
                p.last_name as employee_last_name,
                o.short_name as organization_name,
                jo.position_title, jo.salary_offered,
                pop.name as position_name
                FROM employment_contracts ec
                LEFT JOIN persons p ON ec.employee_id = p.id
                LEFT JOIN organizations o ON ec.organization_id = o.id
                LEFT JOIN job_offers jo ON ec.job_offer_id = jo.id
                LEFT JOIN vacancy_applications va ON jo.application_id = va.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ec.id = ? AND ec.deleted_at IS NULL";
        return $this->queryOne($sql, [$contractId]);
    }

    /**
     * Get contracts by organization
     */
    public function getByOrganization($organizationId, $status = null) {
        $sql = "SELECT ec.*,
                p.first_name as employee_first_name, p.last_name as employee_last_name,
                jo.position_title
                FROM employment_contracts ec
                LEFT JOIN persons p ON ec.employee_id = p.id
                LEFT JOIN job_offers jo ON ec.job_offer_id = jo.id
                WHERE ec.organization_id = ? AND ec.deleted_at IS NULL";

        $params = [$organizationId];

        if ($status) {
            $sql .= " AND ec.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY ec.start_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get contracts by employee
     */
    public function getByEmployee($employeeId, $status = null) {
        $sql = "SELECT ec.*,
                o.short_name as organization_name,
                jo.position_title
                FROM employment_contracts ec
                LEFT JOIN organizations o ON ec.organization_id = o.id
                LEFT JOIN job_offers jo ON ec.job_offer_id = jo.id
                WHERE ec.employee_id = ? AND ec.deleted_at IS NULL";

        $params = [$employeeId];

        if ($status) {
            $sql .= " AND ec.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY ec.start_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get active contracts
     */
    public function getActiveContracts($organizationId = null) {
        $sql = "SELECT ec.*,
                p.first_name as employee_first_name, p.last_name as employee_last_name,
                o.short_name as organization_name,
                jo.position_title
                FROM employment_contracts ec
                LEFT JOIN persons p ON ec.employee_id = p.id
                LEFT JOIN organizations o ON ec.organization_id = o.id
                LEFT JOIN job_offers jo ON ec.job_offer_id = jo.id
                WHERE ec.status = 'Active' AND ec.deleted_at IS NULL";

        $params = [];

        if ($organizationId) {
            $sql .= " AND ec.organization_id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY ec.start_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get expiring soon contracts
     */
    public function getExpiringSoon($days = 30, $organizationId = null) {
        $sql = "SELECT ec.*,
                p.first_name as employee_first_name, p.last_name as employee_last_name,
                o.short_name as organization_name,
                jo.position_title
                FROM employment_contracts ec
                LEFT JOIN persons p ON ec.employee_id = p.id
                LEFT JOIN organizations o ON ec.organization_id = o.id
                LEFT JOIN job_offers jo ON ec.job_offer_id = jo.id
                WHERE ec.status = 'Active'
                AND ec.end_date IS NOT NULL
                AND ec.end_date <= date('now', '+' || ? || ' days')
                AND ec.deleted_at IS NULL";

        $params = [$days];

        if ($organizationId) {
            $sql .= " AND ec.organization_id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY ec.end_date ASC";
        return $this->query($sql, $params);
    }

    /**
     * Terminate contract
     */
    public function terminate($contractId, $endDate = null) {
        $data = ['status' => 'Terminated'];
        if ($endDate) {
            $data['end_date'] = $endDate;
        } else {
            $data['end_date'] = date('Y-m-d');
        }
        return $this->update($contractId, $data);
    }

    /**
     * Complete contract
     */
    public function complete($contractId) {
        return $this->update($contractId, ['status' => 'Completed']);
    }

    /**
     * Get contract duration in days
     */
    public function getDuration($contractId) {
        $contract = $this->find($contractId);
        if (!$contract || !$contract['start_date']) {
            return 0;
        }

        $startDate = new DateTime($contract['start_date']);
        $endDate = $contract['end_date']
            ? new DateTime($contract['end_date'])
            : new DateTime();

        $interval = $startDate->diff($endDate);
        return $interval->days;
    }

    /**
     * Check if contract is active
     */
    public function isActive($contractId) {
        $contract = $this->find($contractId);
        if (!$contract) return false;

        if ($contract['status'] !== 'Active') return false;

        $today = new DateTime();
        $startDate = new DateTime($contract['start_date']);

        if ($today < $startDate) return false;

        if ($contract['end_date']) {
            $endDate = new DateTime($contract['end_date']);
            if ($today > $endDate) return false;
        }

        return true;
    }

    /**
     * Get contracts by status
     */
    public function getByStatus($status) {
        $sql = "SELECT ec.*,
                p.first_name as employee_first_name, p.last_name as employee_last_name,
                o.short_name as organization_name,
                jo.position_title
                FROM employment_contracts ec
                LEFT JOIN persons p ON ec.employee_id = p.id
                LEFT JOIN organizations o ON ec.organization_id = o.id
                LEFT JOIN job_offers jo ON ec.job_offer_id = jo.id
                WHERE ec.status = ? AND ec.deleted_at IS NULL
                ORDER BY ec.start_date DESC";
        return $this->query($sql, [$status]);
    }

    /**
     * Get contracts starting in date range
     */
    public function getStartingInRange($startDate, $endDate) {
        $sql = "SELECT ec.*,
                p.first_name as employee_first_name, p.last_name as employee_last_name,
                o.short_name as organization_name,
                jo.position_title
                FROM employment_contracts ec
                LEFT JOIN persons p ON ec.employee_id = p.id
                LEFT JOIN organizations o ON ec.organization_id = o.id
                LEFT JOIN job_offers jo ON ec.job_offer_id = jo.id
                WHERE ec.start_date BETWEEN ? AND ?
                AND ec.deleted_at IS NULL
                ORDER BY ec.start_date ASC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    /**
     * Get contracts ending in date range
     */
    public function getEndingInRange($startDate, $endDate) {
        $sql = "SELECT ec.*,
                p.first_name as employee_first_name, p.last_name as employee_last_name,
                o.short_name as organization_name,
                jo.position_title
                FROM employment_contracts ec
                LEFT JOIN persons p ON ec.employee_id = p.id
                LEFT JOIN organizations o ON ec.organization_id = o.id
                LEFT JOIN job_offers jo ON ec.job_offer_id = jo.id
                WHERE ec.end_date BETWEEN ? AND ?
                AND ec.deleted_at IS NULL
                ORDER BY ec.end_date ASC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    /**
     * Get contract by job offer
     */
    public function getByJobOffer($jobOfferId) {
        $sql = "SELECT * FROM employment_contracts
                WHERE job_offer_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$jobOfferId]);
    }

    /**
     * Get statistics
     */
    public function getStatistics($organizationId = null) {
        $sql = "SELECT
                    COUNT(*) as total_contracts,
                    COUNT(CASE WHEN status = 'Active' THEN 1 END) as active_count,
                    COUNT(CASE WHEN status = 'Completed' THEN 1 END) as completed_count,
                    COUNT(CASE WHEN status = 'Terminated' THEN 1 END) as terminated_count,
                    COUNT(DISTINCT employee_id) as unique_employees,
                    COUNT(DISTINCT organization_id) as unique_organizations
                FROM employment_contracts
                WHERE deleted_at IS NULL";

        $params = [];

        if ($organizationId) {
            $sql .= " AND organization_id = ?";
            $params[] = $organizationId;
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Get employee count by organization
     */
    public function getEmployeeCount($organizationId, $status = 'Active') {
        $sql = "SELECT COUNT(DISTINCT employee_id) as count
                FROM employment_contracts
                WHERE organization_id = ? AND status = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$organizationId, $status]);
        return $result['count'] ?? 0;
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'job_offer_id' => 'required|integer',
            'organization_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'date',
            'contract_terms' => 'max:5000',
            'status' => 'required',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $contract = $this->getWithDetails($id);
        if (!$contract) {
            return 'N/A';
        }
        return $contract['employee_first_name'] . ' ' . $contract['employee_last_name'] . ' - ' . $contract['organization_name'];
    }
}
