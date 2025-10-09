<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * InterviewStage Entity
 * Defines interview stages for an organization
 */
class InterviewStage extends BaseEntity {
    protected $table = 'interview_stages';
    protected $fillable = ['organization_id', 'name', 'order_number'];

    /**
     * Get organization
     */
    public function getOrganization($stageId) {
        $sql = "SELECT o.* FROM organizations o
                JOIN interview_stages ist ON ist.organization_id = o.id
                WHERE ist.id = ? AND o.deleted_at IS NULL";
        return $this->queryOne($sql, [$stageId]);
    }

    /**
     * Get with organization details
     */
    public function getWithDetails($stageId) {
        $sql = "SELECT ist.*, o.short_name as organization_name
                FROM interview_stages ist
                LEFT JOIN organizations o ON ist.organization_id = o.id
                WHERE ist.id = ? AND ist.deleted_at IS NULL";
        return $this->queryOne($sql, [$stageId]);
    }

    /**
     * Get stages by organization
     */
    public function getByOrganization($organizationId) {
        return $this->all(['organization_id' => $organizationId], 'order_number ASC');
    }

    /**
     * Get interviews for stage
     */
    public function getInterviews($stageId) {
        $sql = "SELECT ai.*,
                p.first_name as interviewer_first_name, p.last_name as interviewer_last_name,
                ap.first_name as applicant_first_name, ap.last_name as applicant_last_name
                FROM application_interviews ai
                LEFT JOIN persons p ON ai.interviewer_id = p.id
                LEFT JOIN vacancy_applications va ON ai.application_id = va.id
                LEFT JOIN persons ap ON va.applicant_id = ap.id
                WHERE ai.stage_id = ? AND ai.deleted_at IS NULL
                ORDER BY ai.scheduled_date DESC";
        return $this->query($sql, [$stageId]);
    }

    /**
     * Get interview count
     */
    public function getInterviewCount($stageId) {
        $sql = "SELECT COUNT(*) as count FROM application_interviews
                WHERE stage_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$stageId]);
        return $result['count'] ?? 0;
    }

    /**
     * Reorder stages
     */
    public function reorder($organizationId, $stageOrderArray) {
        // $stageOrderArray should be like: [stageId => orderNumber, ...]
        foreach ($stageOrderArray as $stageId => $orderNumber) {
            $this->update($stageId, ['order_number' => $orderNumber]);
        }
        return true;
    }

    /**
     * Get next stage
     */
    public function getNextStage($stageId) {
        $stage = $this->find($stageId);
        if (!$stage) return null;

        $sql = "SELECT * FROM interview_stages
                WHERE organization_id = ? AND order_number > ?
                AND deleted_at IS NULL
                ORDER BY order_number ASC
                LIMIT 1";
        return $this->queryOne($sql, [$stage['organization_id'], $stage['order_number']]);
    }

    /**
     * Get previous stage
     */
    public function getPreviousStage($stageId) {
        $stage = $this->find($stageId);
        if (!$stage) return null;

        $sql = "SELECT * FROM interview_stages
                WHERE organization_id = ? AND order_number < ?
                AND deleted_at IS NULL
                ORDER BY order_number DESC
                LIMIT 1";
        return $this->queryOne($sql, [$stage['organization_id'], $stage['order_number']]);
    }

    /**
     * Check if order number exists
     */
    public function orderExists($organizationId, $orderNumber, $exceptId = null) {
        $sql = "SELECT id FROM interview_stages
                WHERE organization_id = ? AND order_number = ? AND deleted_at IS NULL";
        $params = [$organizationId, $orderNumber];

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
    public function getAllWithDetails() {
        $sql = "SELECT ist.*, o.short_name as organization_name,
                COUNT(ai.id) as interview_count
                FROM interview_stages ist
                LEFT JOIN organizations o ON ist.organization_id = o.id
                LEFT JOIN application_interviews ai ON ist.id = ai.stage_id AND ai.deleted_at IS NULL
                WHERE ist.deleted_at IS NULL
                GROUP BY ist.id
                ORDER BY o.short_name ASC, ist.order_number ASC";
        return $this->query($sql);
    }

    /**
     * Can delete check
     */
    public function canDelete($stageId) {
        if ($this->getInterviewCount($stageId) > 0) {
            return ['can_delete' => false, 'reason' => 'Stage has interviews'];
        }

        return ['can_delete' => true];
    }

    /**
     * Get statistics by organization
     */
    public function getStatisticsByOrganization($organizationId) {
        $sql = "SELECT
                    COUNT(*) as total_stages,
                    COUNT(DISTINCT ai.id) as total_interviews,
                    COUNT(DISTINCT ai.application_id) as unique_applications
                FROM interview_stages ist
                LEFT JOIN application_interviews ai ON ist.id = ai.stage_id AND ai.deleted_at IS NULL
                WHERE ist.organization_id = ? AND ist.deleted_at IS NULL";
        return $this->queryOne($sql, [$organizationId]);
    }

    /**
     * Get overall statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_stages,
                    COUNT(DISTINCT organization_id) as unique_organizations,
                    COUNT(DISTINCT ai.id) as total_interviews
                FROM interview_stages ist
                LEFT JOIN application_interviews ai ON ist.id = ai.stage_id AND ai.deleted_at IS NULL
                WHERE ist.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'organization_id' => 'required|integer',
            'name' => 'required|min:2|max:200',
            'order_number' => 'required|integer',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $stage = $this->getWithDetails($id);
        if (!$stage) {
            return 'N/A';
        }
        return $stage['name'] . ' (Stage ' . $stage['order_number'] . ')';
    }
}
