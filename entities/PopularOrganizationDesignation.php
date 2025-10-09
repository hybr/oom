<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * PopularOrganizationDesignation Entity
 * Job titles/designations (Manager, Director, Analyst, etc.)
 */
class PopularOrganizationDesignation extends BaseEntity {
    protected $table = 'popular_organization_designations';
    protected $fillable = ['name'];

    /**
     * Get positions with this designation
     */
    public function getPositions($designationId) {
        $sql = "SELECT p.*, d.name as department_name, t.name as team_name
                FROM popular_organization_positions p
                LEFT JOIN popular_organization_departments d ON p.department_id = d.id
                LEFT JOIN popular_organization_teams t ON p.team_id = t.id
                WHERE p.designation_id = ? AND p.deleted_at IS NULL
                ORDER BY d.name ASC, p.name ASC";
        return $this->query($sql, [$designationId]);
    }

    /**
     * Get position count
     */
    public function getPositionCount($designationId) {
        $sql = "SELECT COUNT(*) as count FROM popular_organization_positions
                WHERE designation_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$designationId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get most used designations
     */
    public function getMostUsed($limit = 10) {
        $sql = "SELECT d.*, COUNT(p.id) as position_count
                FROM popular_organization_designations d
                LEFT JOIN popular_organization_positions p ON d.id = p.designation_id AND p.deleted_at IS NULL
                WHERE d.deleted_at IS NULL
                GROUP BY d.id
                ORDER BY position_count DESC, d.name ASC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Get by first letter
     */
    public function getByFirstLetter($letter) {
        $sql = "SELECT * FROM popular_organization_designations
                WHERE name LIKE ? AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql, [$letter . '%']);
    }

    /**
     * Search by name
     */
    public function searchByName($term, $limit = 50) {
        return $this->search('name', $term, $limit);
    }

    /**
     * Check if name is unique
     */
    public function isNameUnique($name, $exceptId = null) {
        $sql = "SELECT id FROM popular_organization_designations WHERE name = ? AND deleted_at IS NULL";
        $params = [$name];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return empty($result);
    }

    /**
     * Get with position count
     */
    public function getAllWithCounts() {
        $sql = "SELECT d.*, COUNT(p.id) as position_count
                FROM popular_organization_designations d
                LEFT JOIN popular_organization_positions p ON d.id = p.designation_id AND p.deleted_at IS NULL
                WHERE d.deleted_at IS NULL
                GROUP BY d.id
                ORDER BY d.name ASC";
        return $this->query($sql);
    }

    /**
     * Get designations with open vacancies
     */
    public function getWithOpenVacancies() {
        $sql = "SELECT DISTINCT d.*
                FROM popular_organization_designations d
                JOIN popular_organization_positions p ON d.id = p.designation_id
                JOIN organization_vacancies v ON p.id = v.popular_position_id
                WHERE v.status = 'Open' AND d.deleted_at IS NULL AND p.deleted_at IS NULL AND v.deleted_at IS NULL
                ORDER BY d.name ASC";
        return $this->query($sql);
    }

    /**
     * Get vacancy count by designation
     */
    public function getVacancyCount($designationId) {
        $sql = "SELECT COUNT(*) as count
                FROM organization_vacancies v
                JOIN popular_organization_positions p ON v.popular_position_id = p.id
                WHERE p.designation_id = ? AND v.status = 'Open'
                AND v.deleted_at IS NULL AND p.deleted_at IS NULL";
        $result = $this->queryOne($sql, [$designationId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get by seniority level (if you categorize them)
     */
    public function getBySeniorityLevel($level) {
        // This is a placeholder for future enhancement
        // Could add a seniority_level column later
        return [];
    }

    /**
     * Can delete check
     */
    public function canDelete($designationId) {
        if ($this->getPositionCount($designationId) > 0) {
            return ['can_delete' => false, 'reason' => 'Designation has positions'];
        }

        return ['can_delete' => true];
    }

    /**
     * Get related designations (often used together)
     */
    public function getRelatedDesignations($designationId, $limit = 10) {
        $sql = "SELECT d.*, COUNT(*) as co_occurrence
                FROM popular_organization_designations d
                JOIN popular_organization_positions p1 ON d.id = p1.designation_id
                WHERE p1.department_id IN (
                    SELECT department_id FROM popular_organization_positions
                    WHERE designation_id = ? AND deleted_at IS NULL
                )
                AND d.id != ?
                AND d.deleted_at IS NULL AND p1.deleted_at IS NULL
                GROUP BY d.id
                ORDER BY co_occurrence DESC
                LIMIT ?";
        return $this->query($sql, [$designationId, $designationId, $limit]);
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_designations,
                    COUNT(DISTINCT p.id) as total_positions,
                    COUNT(DISTINCT v.id) as total_vacancies
                FROM popular_organization_designations d
                LEFT JOIN popular_organization_positions p ON d.id = p.designation_id AND p.deleted_at IS NULL
                LEFT JOIN organization_vacancies v ON p.id = v.popular_position_id AND v.deleted_at IS NULL
                WHERE d.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:200' . ($id ? "|unique:popular_organization_designations,name,$id" : '|unique:popular_organization_designations,name'),
        ];

        return $this->validate($data, $rules);
    }
}
