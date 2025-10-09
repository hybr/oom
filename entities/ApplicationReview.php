<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * ApplicationReview Entity
 * Reviews and evaluations of job applications
 */
class ApplicationReview extends BaseEntity {
    protected $table = 'application_reviews';
    protected $fillable = ['application_id', 'reviewed_by', 'review_date', 'review_notes', 'status'];

    /**
     * Get application
     */
    public function getApplication($reviewId) {
        $sql = "SELECT va.* FROM vacancy_applications va
                JOIN application_reviews ar ON ar.application_id = va.id
                WHERE ar.id = ? AND va.deleted_at IS NULL";
        return $this->queryOne($sql, [$reviewId]);
    }

    /**
     * Get reviewer
     */
    public function getReviewer($reviewId) {
        $sql = "SELECT p.* FROM persons p
                JOIN application_reviews ar ON ar.reviewed_by = p.id
                WHERE ar.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$reviewId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($reviewId) {
        $sql = "SELECT ar.*,
                p.first_name as reviewer_first_name, p.last_name as reviewer_last_name,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                va.application_date, va.status as application_status,
                ov.opening_date, pop.name as position_name, o.short_name as organization_name
                FROM application_reviews ar
                LEFT JOIN persons p ON ar.reviewed_by = p.id
                LEFT JOIN vacancy_applications va ON ar.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE ar.id = ? AND ar.deleted_at IS NULL";
        return $this->queryOne($sql, [$reviewId]);
    }

    /**
     * Get reviews by application
     */
    public function getByApplication($applicationId) {
        $sql = "SELECT ar.*, p.first_name, p.last_name
                FROM application_reviews ar
                LEFT JOIN persons p ON ar.reviewed_by = p.id
                WHERE ar.application_id = ? AND ar.deleted_at IS NULL
                ORDER BY ar.review_date DESC";
        return $this->query($sql, [$applicationId]);
    }

    /**
     * Get reviews by reviewer
     */
    public function getByReviewer($reviewerId) {
        $sql = "SELECT ar.*,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                pop.name as position_name, o.short_name as organization_name
                FROM application_reviews ar
                LEFT JOIN vacancy_applications va ON ar.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE ar.reviewed_by = ? AND ar.deleted_at IS NULL
                ORDER BY ar.review_date DESC";
        return $this->query($sql, [$reviewerId]);
    }

    /**
     * Get by status
     */
    public function getByStatus($status) {
        $sql = "SELECT ar.*,
                p.first_name as reviewer_first_name, p.last_name as reviewer_last_name,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                pop.name as position_name
                FROM application_reviews ar
                LEFT JOIN persons p ON ar.reviewed_by = p.id
                LEFT JOIN vacancy_applications va ON ar.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ar.status = ? AND ar.deleted_at IS NULL
                ORDER BY ar.review_date DESC";
        return $this->query($sql, [$status]);
    }

    /**
     * Approve review
     */
    public function approve($reviewId) {
        return $this->update($reviewId, ['status' => 'Approved']);
    }

    /**
     * Reject review
     */
    public function reject($reviewId) {
        return $this->update($reviewId, ['status' => 'Rejected']);
    }

    /**
     * Get pending reviews
     */
    public function getPendingReviews($reviewerId = null) {
        $sql = "SELECT ar.*,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                pop.name as position_name, o.short_name as organization_name
                FROM application_reviews ar
                LEFT JOIN vacancy_applications va ON ar.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE ar.status = 'Pending' AND ar.deleted_at IS NULL";

        $params = [];

        if ($reviewerId) {
            $sql .= " AND ar.reviewed_by = ?";
            $params[] = $reviewerId;
        }

        $sql .= " ORDER BY ar.review_date ASC";
        return $this->query($sql, $params);
    }

    /**
     * Get reviews in date range
     */
    public function getInDateRange($startDate, $endDate) {
        $sql = "SELECT ar.*,
                p.first_name as reviewer_first_name, p.last_name as reviewer_last_name,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                pop.name as position_name
                FROM application_reviews ar
                LEFT JOIN persons p ON ar.reviewed_by = p.id
                LEFT JOIN vacancy_applications va ON ar.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ar.review_date BETWEEN ? AND ?
                AND ar.deleted_at IS NULL
                ORDER BY ar.review_date DESC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    /**
     * Check if application has been reviewed
     */
    public function hasBeenReviewed($applicationId) {
        $sql = "SELECT COUNT(*) as count FROM application_reviews
                WHERE application_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$applicationId]);
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Get review count by reviewer
     */
    public function getReviewCountByReviewer($reviewerId) {
        $sql = "SELECT COUNT(*) as count FROM application_reviews
                WHERE reviewed_by = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$reviewerId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get statistics
     */
    public function getStatistics($reviewerId = null) {
        $sql = "SELECT
                    COUNT(*) as total_reviews,
                    COUNT(CASE WHEN status = 'Pending' THEN 1 END) as pending_count,
                    COUNT(CASE WHEN status = 'Approved' THEN 1 END) as approved_count,
                    COUNT(CASE WHEN status = 'Rejected' THEN 1 END) as rejected_count,
                    COUNT(DISTINCT application_id) as unique_applications,
                    COUNT(DISTINCT reviewed_by) as unique_reviewers
                FROM application_reviews
                WHERE deleted_at IS NULL";

        $params = [];

        if ($reviewerId) {
            $sql .= " AND reviewed_by = ?";
            $params[] = $reviewerId;
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'application_id' => 'required|integer',
            'reviewed_by' => 'required|integer',
            'review_date' => 'required|date',
            'review_notes' => 'max:2000',
            'status' => 'required',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $review = $this->getWithDetails($id);
        if (!$review) {
            return 'N/A';
        }
        return 'Review by ' . $review['reviewer_first_name'] . ' ' . $review['reviewer_last_name'] . ' - ' . $review['status'];
    }
}
