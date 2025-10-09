<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * PopularOrganizationTeam Entity
 * Teams within departments
 */
class PopularOrganizationTeam extends BaseEntity {
    protected $table = 'popular_organization_teams';
    protected $fillable = ['name', 'department_id'];

    /**
     * Get department for this team
     */
    public function getDepartment($teamId) {
        $sql = "SELECT d.* FROM popular_organization_departments d
                JOIN popular_organization_teams t ON t.department_id = d.id
                WHERE t.id = ? AND d.deleted_at IS NULL";
        return $this->queryOne($sql, [$teamId]);
    }

    /**
     * Get teams by department
     */
    public function getByDepartment($departmentId) {
        return $this->all(['department_id' => $departmentId], 'name ASC');
    }

    /**
     * Get positions in this team
     */
    public function getPositions($teamId) {
        $sql = "SELECT * FROM popular_organization_positions
                WHERE team_id = ? AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql, [$teamId]);
    }

    /**
     * Get position count
     */
    public function getPositionCount($teamId) {
        $sql = "SELECT COUNT(*) as count FROM popular_organization_positions
                WHERE team_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$teamId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get with full details
     */
    public function getWithDetails($teamId) {
        $sql = "SELECT t.*, d.name as department_name
                FROM popular_organization_teams t
                LEFT JOIN popular_organization_departments d ON t.department_id = d.id
                WHERE t.id = ? AND t.deleted_at IS NULL";
        return $this->queryOne($sql, [$teamId]);
    }

    /**
     * Get all with department info
     */
    public function getAllWithDepartment() {
        $sql = "SELECT t.*, d.name as department_name,
                COUNT(p.id) as position_count
                FROM popular_organization_teams t
                LEFT JOIN popular_organization_departments d ON t.department_id = d.id
                LEFT JOIN popular_organization_positions p ON t.id = p.team_id AND p.deleted_at IS NULL
                WHERE t.deleted_at IS NULL
                GROUP BY t.id
                ORDER BY d.name ASC, t.name ASC";
        return $this->query($sql);
    }

    /**
     * Get most used teams
     */
    public function getMostUsed($limit = 10) {
        $sql = "SELECT t.*, d.name as department_name, COUNT(p.id) as position_count
                FROM popular_organization_teams t
                LEFT JOIN popular_organization_departments d ON t.department_id = d.id
                LEFT JOIN popular_organization_positions p ON t.id = p.team_id AND p.deleted_at IS NULL
                WHERE t.deleted_at IS NULL
                GROUP BY t.id
                ORDER BY position_count DESC, t.name ASC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Search by name
     */
    public function searchByName($term, $limit = 50) {
        $sql = "SELECT t.*, d.name as department_name
                FROM popular_organization_teams t
                LEFT JOIN popular_organization_departments d ON t.department_id = d.id
                WHERE t.name LIKE ? AND t.deleted_at IS NULL
                ORDER BY t.name ASC
                LIMIT ?";
        return $this->query($sql, ["%$term%", $limit]);
    }

    /**
     * Check if name is unique within department
     */
    public function isNameUniqueInDepartment($name, $departmentId, $exceptId = null) {
        $sql = "SELECT id FROM popular_organization_teams
                WHERE name = ? AND department_id = ? AND deleted_at IS NULL";
        $params = [$name, $departmentId];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return empty($result);
    }

    /**
     * Get teams with open vacancies
     */
    public function getWithOpenVacancies() {
        $sql = "SELECT DISTINCT t.*, d.name as department_name
                FROM popular_organization_teams t
                LEFT JOIN popular_organization_departments d ON t.department_id = d.id
                JOIN popular_organization_positions p ON t.id = p.team_id
                JOIN organization_vacancies v ON p.id = v.popular_position_id
                WHERE v.status = 'Open' AND t.deleted_at IS NULL AND p.deleted_at IS NULL AND v.deleted_at IS NULL
                ORDER BY d.name ASC, t.name ASC";
        return $this->query($sql);
    }

    /**
     * Can delete check
     */
    public function canDelete($teamId) {
        if ($this->getPositionCount($teamId) > 0) {
            return ['can_delete' => false, 'reason' => 'Team has positions'];
        }

        return ['can_delete' => true];
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_teams,
                    COUNT(DISTINCT department_id) as unique_departments,
                    COUNT(DISTINCT p.id) as total_positions
                FROM popular_organization_teams t
                LEFT JOIN popular_organization_positions p ON t.id = p.team_id AND p.deleted_at IS NULL
                WHERE t.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:200',
            'department_id' => 'required|integer',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $team = $this->getWithDetails($id);
        if (!$team) {
            return 'N/A';
        }
        return $team['name'] . ' (' . ($team['department_name'] ?? 'No Dept') . ')';
    }
}
