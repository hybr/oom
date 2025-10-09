<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * JobOffer Entity
 * Job offers made to selected candidates
 */
class JobOffer extends BaseEntity {
    protected $table = 'job_offers';
    protected $fillable = [
        'application_id', 'offered_by', 'offer_date', 'position_title',
        'salary_offered', 'joining_date', 'status'
    ];

    /**
     * Get application
     */
    public function getApplication($offerId) {
        $sql = "SELECT va.* FROM vacancy_applications va
                JOIN job_offers jo ON jo.application_id = va.id
                WHERE jo.id = ? AND va.deleted_at IS NULL";
        return $this->queryOne($sql, [$offerId]);
    }

    /**
     * Get person who offered
     */
    public function getOfferedBy($offerId) {
        $sql = "SELECT p.* FROM persons p
                JOIN job_offers jo ON jo.offered_by = p.id
                WHERE jo.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$offerId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($offerId) {
        $sql = "SELECT jo.*,
                p.first_name as offered_by_first_name, p.last_name as offered_by_last_name,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                va.application_date,
                ov.opening_date, pop.name as position_name, o.short_name as organization_name
                FROM job_offers jo
                LEFT JOIN persons p ON jo.offered_by = p.id
                LEFT JOIN vacancy_applications va ON jo.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE jo.id = ? AND jo.deleted_at IS NULL";
        return $this->queryOne($sql, [$offerId]);
    }

    /**
     * Get offer by application
     */
    public function getByApplication($applicationId) {
        $sql = "SELECT * FROM job_offers
                WHERE application_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$applicationId]);
    }

    /**
     * Get offers by status
     */
    public function getByStatus($status) {
        $sql = "SELECT jo.*,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                pop.name as position_name, o.short_name as organization_name
                FROM job_offers jo
                LEFT JOIN vacancy_applications va ON jo.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE jo.status = ? AND jo.deleted_at IS NULL
                ORDER BY jo.offer_date DESC";
        return $this->query($sql, [$status]);
    }

    /**
     * Get pending offers
     */
    public function getPendingOffers($organizationId = null) {
        $sql = "SELECT jo.*,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                pop.name as position_name, o.short_name as organization_name
                FROM job_offers jo
                LEFT JOIN vacancy_applications va ON jo.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE jo.status = 'Offered' AND jo.deleted_at IS NULL";

        $params = [];

        if ($organizationId) {
            $sql .= " AND o.id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY jo.offer_date ASC";
        return $this->query($sql, $params);
    }

    /**
     * Accept offer
     */
    public function accept($offerId) {
        return $this->update($offerId, ['status' => 'Accepted']);
    }

    /**
     * Decline offer
     */
    public function decline($offerId) {
        return $this->update($offerId, ['status' => 'Declined']);
    }

    /**
     * Expire offer
     */
    public function expire($offerId) {
        return $this->update($offerId, ['status' => 'Expired']);
    }

    /**
     * Get employment contract for offer
     */
    public function getEmploymentContract($offerId) {
        $sql = "SELECT * FROM employment_contracts
                WHERE job_offer_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$offerId]);
    }

    /**
     * Check if offer has contract
     */
    public function hasContract($offerId) {
        $contract = $this->getEmploymentContract($offerId);
        return !empty($contract);
    }

    /**
     * Get offers in date range
     */
    public function getInDateRange($startDate, $endDate) {
        $sql = "SELECT jo.*,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                pop.name as position_name, o.short_name as organization_name
                FROM job_offers jo
                LEFT JOIN vacancy_applications va ON jo.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE jo.offer_date BETWEEN ? AND ?
                AND jo.deleted_at IS NULL
                ORDER BY jo.offer_date DESC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    /**
     * Get offers expiring soon
     */
    public function getExpiringSoon($days = 7) {
        // Assuming offers expire after a certain period
        // This is a placeholder - you may need to add an expiry_date column
        return [];
    }

    /**
     * Get average salary by position
     */
    public function getAverageSalaryByPosition($positionId) {
        $sql = "SELECT AVG(jo.salary_offered) as average_salary
                FROM job_offers jo
                JOIN vacancy_applications va ON jo.application_id = va.id
                JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                WHERE ov.popular_position_id = ? AND jo.salary_offered IS NOT NULL
                AND jo.deleted_at IS NULL";
        $result = $this->queryOne($sql, [$positionId]);
        return $result['average_salary'] ?? 0;
    }

    /**
     * Get offers by organization
     */
    public function getByOrganization($organizationId, $status = null) {
        $sql = "SELECT jo.*,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                pop.name as position_name
                FROM job_offers jo
                LEFT JOIN vacancy_applications va ON jo.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ov.organization_id = ? AND jo.deleted_at IS NULL";

        $params = [$organizationId];

        if ($status) {
            $sql .= " AND jo.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY jo.offer_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get statistics
     */
    public function getStatistics($organizationId = null) {
        $sql = "SELECT
                    COUNT(*) as total_offers,
                    COUNT(CASE WHEN status = 'Offered' THEN 1 END) as offered_count,
                    COUNT(CASE WHEN status = 'Accepted' THEN 1 END) as accepted_count,
                    COUNT(CASE WHEN status = 'Declined' THEN 1 END) as declined_count,
                    COUNT(CASE WHEN status = 'Expired' THEN 1 END) as expired_count,
                    AVG(salary_offered) as average_salary,
                    MIN(salary_offered) as min_salary,
                    MAX(salary_offered) as max_salary
                FROM job_offers jo";

        $params = [];

        if ($organizationId) {
            $sql .= " JOIN vacancy_applications va ON jo.application_id = va.id
                      JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                      WHERE ov.organization_id = ? AND jo.deleted_at IS NULL";
            $params[] = $organizationId;
        } else {
            $sql .= " WHERE jo.deleted_at IS NULL";
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'application_id' => 'required|integer',
            'offered_by' => 'required|integer',
            'offer_date' => 'required|date',
            'position_title' => 'required|min:2|max:200',
            'salary_offered' => 'numeric',
            'joining_date' => 'date',
            'status' => 'required',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $offer = $this->getWithDetails($id);
        if (!$offer) {
            return 'N/A';
        }
        return $offer['position_title'] . ' - ' . $offer['applicant_first_name'] . ' ' . $offer['applicant_last_name'];
    }
}
