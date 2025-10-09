<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * VacancyApplication Entity
 * Job applications submitted by candidates
 */
class VacancyApplication extends BaseEntity {
    protected $table = 'vacancy_applications';
    protected $fillable = [
        'vacancy_id', 'applicant_id', 'application_date',
        'status', 'resume_url', 'cover_letter'
    ];

    /**
     * Get vacancy
     */
    public function getVacancy($applicationId) {
        $sql = "SELECT ov.* FROM organization_vacancies ov
                JOIN vacancy_applications va ON va.vacancy_id = ov.id
                WHERE va.id = ? AND ov.deleted_at IS NULL";
        return $this->queryOne($sql, [$applicationId]);
    }

    /**
     * Get applicant
     */
    public function getApplicant($applicationId) {
        $sql = "SELECT p.* FROM persons p
                JOIN vacancy_applications va ON va.applicant_id = p.id
                WHERE va.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$applicationId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($applicationId) {
        $sql = "SELECT va.*,
                p.first_name, p.middle_name, p.last_name, p.date_of_birth,
                ov.opening_date, ov.closing_date, ov.status as vacancy_status,
                pop.name as position_name,
                o.short_name as organization_name
                FROM vacancy_applications va
                LEFT JOIN persons p ON va.applicant_id = p.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE va.id = ? AND va.deleted_at IS NULL";
        return $this->queryOne($sql, [$applicationId]);
    }

    /**
     * Get applications by vacancy
     */
    public function getByVacancy($vacancyId, $status = null) {
        $sql = "SELECT va.*, p.first_name, p.last_name
                FROM vacancy_applications va
                LEFT JOIN persons p ON va.applicant_id = p.id
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
     * Get applications by applicant
     */
    public function getByApplicant($applicantId, $status = null) {
        $sql = "SELECT va.*, ov.opening_date, ov.closing_date,
                pop.name as position_name, o.short_name as organization_name
                FROM vacancy_applications va
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE va.applicant_id = ? AND va.deleted_at IS NULL";

        $params = [$applicantId];

        if ($status) {
            $sql .= " AND va.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY va.application_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get by status
     */
    public function getByStatus($status) {
        $sql = "SELECT va.*, p.first_name, p.last_name,
                pop.name as position_name, o.short_name as organization_name
                FROM vacancy_applications va
                LEFT JOIN persons p ON va.applicant_id = p.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE va.status = ? AND va.deleted_at IS NULL
                ORDER BY va.application_date DESC";
        return $this->query($sql, [$status]);
    }

    /**
     * Shortlist application
     */
    public function shortlist($applicationId) {
        return $this->update($applicationId, ['status' => 'Shortlisted']);
    }

    /**
     * Reject application
     */
    public function reject($applicationId) {
        return $this->update($applicationId, ['status' => 'Rejected']);
    }

    /**
     * Select application
     */
    public function select($applicationId) {
        return $this->update($applicationId, ['status' => 'Selected']);
    }

    /**
     * Withdraw application
     */
    public function withdraw($applicationId) {
        return $this->update($applicationId, ['status' => 'Withdrawn']);
    }

    /**
     * Get reviews for application
     */
    public function getReviews($applicationId) {
        $sql = "SELECT ar.*, p.first_name, p.last_name
                FROM application_reviews ar
                LEFT JOIN persons p ON ar.reviewed_by = p.id
                WHERE ar.application_id = ? AND ar.deleted_at IS NULL
                ORDER BY ar.review_date DESC";
        return $this->query($sql, [$applicationId]);
    }

    /**
     * Get interviews for application
     */
    public function getInterviews($applicationId) {
        $sql = "SELECT ai.*, ist.name as stage_name, p.first_name, p.last_name
                FROM application_interviews ai
                LEFT JOIN interview_stages ist ON ai.stage_id = ist.id
                LEFT JOIN persons p ON ai.interviewer_id = p.id
                WHERE ai.application_id = ? AND ai.deleted_at IS NULL
                ORDER BY ai.scheduled_date ASC";
        return $this->query($sql, [$applicationId]);
    }

    /**
     * Get job offer for application
     */
    public function getJobOffer($applicationId) {
        $sql = "SELECT * FROM job_offers
                WHERE application_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$applicationId]);
    }

    /**
     * Check if person has already applied
     */
    public function hasApplied($vacancyId, $applicantId, $exceptId = null) {
        $sql = "SELECT id FROM vacancy_applications
                WHERE vacancy_id = ? AND applicant_id = ? AND deleted_at IS NULL";
        $params = [$vacancyId, $applicantId];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return !empty($result);
    }

    /**
     * Get applications in date range
     */
    public function getInDateRange($startDate, $endDate) {
        $sql = "SELECT va.*, p.first_name, p.last_name,
                pop.name as position_name, o.short_name as organization_name
                FROM vacancy_applications va
                LEFT JOIN persons p ON va.applicant_id = p.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE va.application_date BETWEEN ? AND ?
                AND va.deleted_at IS NULL
                ORDER BY va.application_date DESC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    /**
     * Get statistics
     */
    public function getStatistics($vacancyId = null) {
        $sql = "SELECT
                    COUNT(*) as total_applications,
                    COUNT(CASE WHEN status = 'Applied' THEN 1 END) as applied_count,
                    COUNT(CASE WHEN status = 'Shortlisted' THEN 1 END) as shortlisted_count,
                    COUNT(CASE WHEN status = 'Rejected' THEN 1 END) as rejected_count,
                    COUNT(CASE WHEN status = 'Selected' THEN 1 END) as selected_count,
                    COUNT(CASE WHEN status = 'Withdrawn' THEN 1 END) as withdrawn_count
                FROM vacancy_applications
                WHERE deleted_at IS NULL";

        $params = [];

        if ($vacancyId) {
            $sql .= " AND vacancy_id = ?";
            $params[] = $vacancyId;
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'vacancy_id' => 'required|integer',
            'applicant_id' => 'required|integer',
            'application_date' => 'required|date',
            'status' => 'required',
            'resume_url' => 'url',
            'cover_letter' => 'max:2000',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $application = $this->getWithDetails($id);
        if (!$application) {
            return 'N/A';
        }
        return $application['first_name'] . ' ' . $application['last_name'] . ' - ' . $application['position_name'];
    }
}
