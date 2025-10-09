<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * PopularOrganizationDepartment Entity
 * Master list of common organizational departments
 */
class PopularOrganizationDepartment extends BaseEntity {
    protected $table = 'popular_organization_departments';
    protected $fillable = ['name'];

    /**
     * Get teams in this department
     */
    public function getTeams($departmentId) {
        $sql = "SELECT * FROM popular_organization_teams
                WHERE department_id = ? AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql, [$departmentId]);
    }

    /**
     * Get positions in this department
     */
    public function getPositions($departmentId) {
        $sql = "SELECT * FROM popular_organization_positions
                WHERE department_id = ? AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql, [$departmentId]);
    }

    /**
     * Get team count
     */
    public function getTeamCount($departmentId) {
        $sql = "SELECT COUNT(*) as count FROM popular_organization_teams
                WHERE department_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$departmentId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get position count
     */
    public function getPositionCount($departmentId) {
        $sql = "SELECT COUNT(*) as count FROM popular_organization_positions
                WHERE department_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$departmentId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get most used departments
     */
    public function getMostUsed($limit = 10) {
        $sql = "SELECT d.*, COUNT(DISTINCT p.id) as position_count
                FROM popular_organization_departments d
                LEFT JOIN popular_organization_positions p ON d.id = p.department_id AND p.deleted_at IS NULL
                WHERE d.deleted_at IS NULL
                GROUP BY d.id
                ORDER BY position_count DESC, d.name ASC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Search by name
     */
    public function searchByName($term, $limit = 50) {
        return $this->search('name', $term, $limit);
    }

    /**
     * Get by first letter
     */
    public function getByFirstLetter($letter) {
        $sql = "SELECT * FROM popular_organization_departments
                WHERE name LIKE ? AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql, [$letter . '%']);
    }

    /**
     * Check if name is unique
     */
    public function isNameUnique($name, $exceptId = null) {
        $sql = "SELECT id FROM popular_organization_departments WHERE name = ? AND deleted_at IS NULL";
        $params = [$name];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return empty($result);
    }

    /**
     * Get with team and position counts
     */
    public function getAllWithCounts() {
        $sql = "SELECT d.*,
                COUNT(DISTINCT t.id) as team_count,
                COUNT(DISTINCT p.id) as position_count
                FROM popular_organization_departments d
                LEFT JOIN popular_organization_teams t ON d.id = t.department_id AND t.deleted_at IS NULL
                LEFT JOIN popular_organization_positions p ON d.id = p.department_id AND p.deleted_at IS NULL
                WHERE d.deleted_at IS NULL
                GROUP BY d.id
                ORDER BY d.name ASC";
        return $this->query($sql);
    }

    /**
     * Get departments with open vacancies
     */
    public function getWithOpenVacancies() {
        $sql = "SELECT DISTINCT d.*
                FROM popular_organization_departments d
                JOIN popular_organization_positions p ON d.id = p.department_id
                JOIN organization_vacancies v ON p.id = v.popular_position_id
                WHERE v.status = 'Open' AND d.deleted_at IS NULL AND p.deleted_at IS NULL AND v.deleted_at IS NULL
                ORDER BY d.name ASC";
        return $this->query($sql);
    }

    /**
     * Get vacancy count by department
     */
    public function getVacancyCount($departmentId) {
        $sql = "SELECT COUNT(*) as count
                FROM organization_vacancies v
                JOIN popular_organization_positions p ON v.popular_position_id = p.id
                WHERE p.department_id = ? AND v.status = 'Open'
                AND v.deleted_at IS NULL AND p.deleted_at IS NULL";
        $result = $this->queryOne($sql, [$departmentId]);
        return $result['count'] ?? 0;
    }

    /**
     * Can delete check
     */
    public function canDelete($departmentId) {
        if ($this->getTeamCount($departmentId) > 0) {
            return ['can_delete' => false, 'reason' => 'Department has teams'];
        }

        if ($this->getPositionCount($departmentId) > 0) {
            return ['can_delete' => false, 'reason' => 'Department has positions'];
        }

        return ['can_delete' => true];
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_departments,
                    COUNT(DISTINCT t.id) as total_teams,
                    COUNT(DISTINCT p.id) as total_positions
                FROM popular_organization_departments d
                LEFT JOIN popular_organization_teams t ON d.id = t.department_id AND t.deleted_at IS NULL
                LEFT JOIN popular_organization_positions p ON d.id = p.department_id AND p.deleted_at IS NULL
                WHERE d.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:200' . ($id ? "|unique:popular_organization_departments,name,$id" : '|unique:popular_organization_departments,name'),
        ];

        return $this->validate($data, $rules);
    }
}
