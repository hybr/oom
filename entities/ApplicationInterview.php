<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * ApplicationInterview Entity
 * Interviews scheduled for job applications
 */
class ApplicationInterview extends BaseEntity {
    protected $table = 'application_interviews';
    protected $fillable = [
        'application_id', 'stage_id', 'interviewer_id', 'scheduled_date',
        'actual_date', 'feedback_notes', 'rating', 'status'
    ];

    /**
     * Get application
     */
    public function getApplication($interviewId) {
        $sql = "SELECT va.* FROM vacancy_applications va
                JOIN application_interviews ai ON ai.application_id = va.id
                WHERE ai.id = ? AND va.deleted_at IS NULL";
        return $this->queryOne($sql, [$interviewId]);
    }

    /**
     * Get interview stage
     */
    public function getStage($interviewId) {
        $sql = "SELECT ist.* FROM interview_stages ist
                JOIN application_interviews ai ON ai.stage_id = ist.id
                WHERE ai.id = ? AND ist.deleted_at IS NULL";
        return $this->queryOne($sql, [$interviewId]);
    }

    /**
     * Get interviewer
     */
    public function getInterviewer($interviewId) {
        $sql = "SELECT p.* FROM persons p
                JOIN application_interviews ai ON ai.interviewer_id = p.id
                WHERE ai.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$interviewId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($interviewId) {
        $sql = "SELECT ai.*,
                ist.name as stage_name, ist.order_number,
                p.first_name as interviewer_first_name, p.last_name as interviewer_last_name,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                va.application_date,
                ov.opening_date, pop.name as position_name, o.short_name as organization_name
                FROM application_interviews ai
                LEFT JOIN interview_stages ist ON ai.stage_id = ist.id
                LEFT JOIN persons p ON ai.interviewer_id = p.id
                LEFT JOIN vacancy_applications va ON ai.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE ai.id = ? AND ai.deleted_at IS NULL";
        return $this->queryOne($sql, [$interviewId]);
    }

    /**
     * Get interviews by application
     */
    public function getByApplication($applicationId) {
        $sql = "SELECT ai.*, ist.name as stage_name, ist.order_number,
                p.first_name as interviewer_first_name, p.last_name as interviewer_last_name
                FROM application_interviews ai
                LEFT JOIN interview_stages ist ON ai.stage_id = ist.id
                LEFT JOIN persons p ON ai.interviewer_id = p.id
                WHERE ai.application_id = ? AND ai.deleted_at IS NULL
                ORDER BY ist.order_number ASC, ai.scheduled_date ASC";
        return $this->query($sql, [$applicationId]);
    }

    /**
     * Get interviews by interviewer
     */
    public function getByInterviewer($interviewerId) {
        $sql = "SELECT ai.*,
                ist.name as stage_name,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                pop.name as position_name, o.short_name as organization_name
                FROM application_interviews ai
                LEFT JOIN interview_stages ist ON ai.stage_id = ist.id
                LEFT JOIN vacancy_applications va ON ai.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                LEFT JOIN organizations o ON ov.organization_id = o.id
                WHERE ai.interviewer_id = ? AND ai.deleted_at IS NULL
                ORDER BY ai.scheduled_date DESC";
        return $this->query($sql, [$interviewerId]);
    }

    /**
     * Get by status
     */
    public function getByStatus($status) {
        $sql = "SELECT ai.*,
                ist.name as stage_name,
                p.first_name as interviewer_first_name, p.last_name as interviewer_last_name,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                pop.name as position_name
                FROM application_interviews ai
                LEFT JOIN interview_stages ist ON ai.stage_id = ist.id
                LEFT JOIN persons p ON ai.interviewer_id = p.id
                LEFT JOIN vacancy_applications va ON ai.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ai.status = ? AND ai.deleted_at IS NULL
                ORDER BY ai.scheduled_date ASC";
        return $this->query($sql, [$status]);
    }

    /**
     * Get scheduled interviews
     */
    public function getScheduled($interviewerId = null) {
        $sql = "SELECT ai.*,
                ist.name as stage_name,
                p.first_name as interviewer_first_name, p.last_name as interviewer_last_name,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                pop.name as position_name
                FROM application_interviews ai
                LEFT JOIN interview_stages ist ON ai.stage_id = ist.id
                LEFT JOIN persons p ON ai.interviewer_id = p.id
                LEFT JOIN vacancy_applications va ON ai.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ai.status = 'Scheduled' AND ai.deleted_at IS NULL";

        $params = [];

        if ($interviewerId) {
            $sql .= " AND ai.interviewer_id = ?";
            $params[] = $interviewerId;
        }

        $sql .= " ORDER BY ai.scheduled_date ASC";
        return $this->query($sql, $params);
    }

    /**
     * Complete interview
     */
    public function complete($interviewId, $feedbackNotes, $rating) {
        return $this->update($interviewId, [
            'status' => 'Completed',
            'actual_date' => date('Y-m-d H:i:s'),
            'feedback_notes' => $feedbackNotes,
            'rating' => $rating
        ]);
    }

    /**
     * Cancel interview
     */
    public function cancel($interviewId) {
        return $this->update($interviewId, ['status' => 'Cancelled']);
    }

    /**
     * Reschedule interview
     */
    public function reschedule($interviewId, $newScheduledDate) {
        return $this->update($interviewId, [
            'scheduled_date' => $newScheduledDate,
            'status' => 'Scheduled'
        ]);
    }

    /**
     * Get upcoming interviews (next 7 days)
     */
    public function getUpcoming($days = 7, $interviewerId = null) {
        $sql = "SELECT ai.*,
                ist.name as stage_name,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                pop.name as position_name
                FROM application_interviews ai
                LEFT JOIN interview_stages ist ON ai.stage_id = ist.id
                LEFT JOIN vacancy_applications va ON ai.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ai.status = 'Scheduled'
                AND ai.scheduled_date <= date('now', '+' || ? || ' days')
                AND ai.deleted_at IS NULL";

        $params = [$days];

        if ($interviewerId) {
            $sql .= " AND ai.interviewer_id = ?";
            $params[] = $interviewerId;
        }

        $sql .= " ORDER BY ai.scheduled_date ASC";
        return $this->query($sql, $params);
    }

    /**
     * Get average rating by interviewer
     */
    public function getAverageRating($interviewerId) {
        $sql = "SELECT AVG(rating) as average_rating
                FROM application_interviews
                WHERE interviewer_id = ? AND rating IS NOT NULL
                AND status = 'Completed' AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$interviewerId]);
        return $result['average_rating'] ?? 0;
    }

    /**
     * Get interviews in date range
     */
    public function getInDateRange($startDate, $endDate) {
        $sql = "SELECT ai.*,
                ist.name as stage_name,
                p.first_name as interviewer_first_name, p.last_name as interviewer_last_name,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name,
                pop.name as position_name
                FROM application_interviews ai
                LEFT JOIN interview_stages ist ON ai.stage_id = ist.id
                LEFT JOIN persons p ON ai.interviewer_id = p.id
                LEFT JOIN vacancy_applications va ON ai.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                LEFT JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                LEFT JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ai.scheduled_date BETWEEN ? AND ?
                AND ai.deleted_at IS NULL
                ORDER BY ai.scheduled_date ASC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    /**
     * Get statistics
     */
    public function getStatistics($interviewerId = null) {
        $sql = "SELECT
                    COUNT(*) as total_interviews,
                    COUNT(CASE WHEN status = 'Scheduled' THEN 1 END) as scheduled_count,
                    COUNT(CASE WHEN status = 'Completed' THEN 1 END) as completed_count,
                    COUNT(CASE WHEN status = 'Cancelled' THEN 1 END) as cancelled_count,
                    AVG(CASE WHEN status = 'Completed' THEN rating END) as average_rating,
                    COUNT(DISTINCT application_id) as unique_applications
                FROM application_interviews
                WHERE deleted_at IS NULL";

        $params = [];

        if ($interviewerId) {
            $sql .= " AND interviewer_id = ?";
            $params[] = $interviewerId;
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'application_id' => 'required|integer',
            'stage_id' => 'required|integer',
            'interviewer_id' => 'required|integer',
            'scheduled_date' => 'required|date',
            'actual_date' => 'date',
            'feedback_notes' => 'max:2000',
            'rating' => 'integer',
            'status' => 'required',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $interview = $this->getWithDetails($id);
        if (!$interview) {
            return 'N/A';
        }
        return $interview['stage_name'] . ' - ' . $interview['applicant_first_name'] . ' ' . $interview['applicant_last_name'];
    }
}
