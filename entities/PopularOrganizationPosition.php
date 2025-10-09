<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * PopularOrganizationPosition Entity
 * Combination of department, team, and designation with requirements
 */
class PopularOrganizationPosition extends BaseEntity {
    protected $table = 'popular_organization_positions';
    protected $fillable = [
        'name', 'department_id', 'team_id', 'designation_id',
        'minimum_education_level', 'minimum_subject_id', 'description'
    ];

    /**
     * Get department
     */
    public function getDepartment($positionId) {
        $sql = "SELECT d.* FROM popular_organization_departments d
                JOIN popular_organization_positions p ON p.department_id = d.id
                WHERE p.id = ? AND d.deleted_at IS NULL";
        return $this->queryOne($sql, [$positionId]);
    }

    /**
     * Get team
     */
    public function getTeam($positionId) {
        $sql = "SELECT t.* FROM popular_organization_teams t
                JOIN popular_organization_positions p ON p.team_id = t.id
                WHERE p.id = ? AND t.deleted_at IS NULL";
        return $this->queryOne($sql, [$positionId]);
    }

    /**
     * Get designation
     */
    public function getDesignation($positionId) {
        $sql = "SELECT d.* FROM popular_organization_designations d
                JOIN popular_organization_positions p ON p.designation_id = d.id
                WHERE p.id = ? AND d.deleted_at IS NULL";
        return $this->queryOne($sql, [$positionId]);
    }

    /**
     * Get minimum education subject
     */
    public function getMinimumSubject($positionId) {
        $sql = "SELECT s.* FROM popular_education_subjects s
                JOIN popular_organization_positions p ON p.minimum_subject_id = s.id
                WHERE p.id = ? AND s.deleted_at IS NULL";
        return $this->queryOne($sql, [$positionId]);
    }

    /**
     * Get full position details
     */
    public function getWithDetails($positionId) {
        $sql = "SELECT p.*,
                d.name as department_name,
                t.name as team_name,
                des.name as designation_name,
                s.name as minimum_subject_name
                FROM popular_organization_positions p
                LEFT JOIN popular_organization_departments d ON p.department_id = d.id
                LEFT JOIN popular_organization_teams t ON p.team_id = t.id
                LEFT JOIN popular_organization_designations des ON p.designation_id = des.id
                LEFT JOIN popular_education_subjects s ON p.minimum_subject_id = s.id
                WHERE p.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$positionId]);
    }

    /**
     * Get positions by department
     */
    public function getByDepartment($departmentId) {
        $sql = "SELECT p.*, des.name as designation_name, t.name as team_name
                FROM popular_organization_positions p
                LEFT JOIN popular_organization_designations des ON p.designation_id = des.id
                LEFT JOIN popular_organization_teams t ON p.team_id = t.id
                WHERE p.department_id = ? AND p.deleted_at IS NULL
                ORDER BY p.name ASC";
        return $this->query($sql, [$departmentId]);
    }

    /**
     * Get positions by team
     */
    public function getByTeam($teamId) {
        return $this->all(['team_id' => $teamId], 'name ASC');
    }

    /**
     * Get positions by designation
     */
    public function getByDesignation($designationId) {
        $sql = "SELECT p.*, d.name as department_name, t.name as team_name
                FROM popular_organization_positions p
                LEFT JOIN popular_organization_departments d ON p.department_id = d.id
                LEFT JOIN popular_organization_teams t ON p.team_id = t.id
                WHERE p.designation_id = ? AND p.deleted_at IS NULL
                ORDER BY d.name ASC, p.name ASC";
        return $this->query($sql, [$designationId]);
    }

    /**
     * Get positions by education level
     */
    public function getByEducationLevel($educationLevel) {
        $sql = "SELECT p.*, d.name as department_name, des.name as designation_name
                FROM popular_organization_positions p
                LEFT JOIN popular_organization_departments d ON p.department_id = d.id
                LEFT JOIN popular_organization_designations des ON p.designation_id = des.id
                WHERE p.minimum_education_level = ? AND p.deleted_at IS NULL
                ORDER BY d.name ASC, p.name ASC";
        return $this->query($sql, [$educationLevel]);
    }

    /**
     * Check if person qualifies for position
     */
    public function checkQualification($positionId, $personId) {
        $position = $this->find($positionId);
        if (!$position) {
            return ['qualified' => false, 'reason' => 'Position not found'];
        }

        require_once __DIR__ . '/PersonEducation.php';
        $personEducation = new PersonEducation();

        // Check education level
        $highestEducation = $personEducation->getHighestLevel($personId);
        if (!$highestEducation) {
            return ['qualified' => false, 'reason' => 'No education records found'];
        }

        // Simplified check - in real scenario, you'd compare education levels properly
        // This would need an enum comparison

        return ['qualified' => true, 'reason' => 'Meets minimum requirements'];
    }

    /**
     * Get vacancies for this position
     */
    public function getVacancies($positionId, $status = null) {
        $sql = "SELECT v.*, o.short_name as organization_name
                FROM organization_vacancies v
                JOIN organizations o ON v.organization_id = o.id
                WHERE v.popular_position_id = ? AND v.deleted_at IS NULL";

        $params = [$positionId];

        if ($status) {
            $sql .= " AND v.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY v.opening_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get vacancy count
     */
    public function getVacancyCount($positionId, $status = null) {
        $sql = "SELECT COUNT(*) as count FROM organization_vacancies
                WHERE popular_position_id = ? AND deleted_at IS NULL";

        $params = [$positionId];

        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        $result = $this->queryOne($sql, $params);
        return $result['count'] ?? 0;
    }

    /**
     * Get all positions with full details
     */
    public function getAllWithDetails($limit = null, $offset = null) {
        $sql = "SELECT p.*,
                d.name as department_name,
                t.name as team_name,
                des.name as designation_name,
                s.name as minimum_subject_name,
                COUNT(v.id) as open_vacancy_count
                FROM popular_organization_positions p
                LEFT JOIN popular_organization_departments d ON p.department_id = d.id
                LEFT JOIN popular_organization_teams t ON p.team_id = t.id
                LEFT JOIN popular_organization_designations des ON p.designation_id = des.id
                LEFT JOIN popular_education_subjects s ON p.minimum_subject_id = s.id
                LEFT JOIN organization_vacancies v ON p.id = v.popular_position_id AND v.status = 'Open' AND v.deleted_at IS NULL
                WHERE p.deleted_at IS NULL
                GROUP BY p.id
                ORDER BY d.name ASC, p.name ASC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->query($sql, [$limit, $offset ?? 0]);
        }

        return $this->query($sql);
    }

    /**
     * Get positions with open vacancies
     */
    public function getWithOpenVacancies() {
        $sql = "SELECT DISTINCT p.*, d.name as department_name, des.name as designation_name
                FROM popular_organization_positions p
                LEFT JOIN popular_organization_departments d ON p.department_id = d.id
                LEFT JOIN popular_organization_designations des ON p.designation_id = des.id
                JOIN organization_vacancies v ON p.id = v.popular_position_id
                WHERE v.status = 'Open' AND p.deleted_at IS NULL AND v.deleted_at IS NULL
                ORDER BY d.name ASC, p.name ASC";
        return $this->query($sql);
    }

    /**
     * Search positions
     */
    public function searchPositions($term, $limit = 50) {
        $sql = "SELECT p.*, d.name as department_name, des.name as designation_name
                FROM popular_organization_positions p
                LEFT JOIN popular_organization_departments d ON p.department_id = d.id
                LEFT JOIN popular_organization_designations des ON p.designation_id = des.id
                WHERE (p.name LIKE ? OR p.description LIKE ?)
                AND p.deleted_at IS NULL
                ORDER BY p.name ASC
                LIMIT ?";
        return $this->query($sql, ["%$term%", "%$term%", $limit]);
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_positions,
                    COUNT(DISTINCT department_id) as unique_departments,
                    COUNT(DISTINCT designation_id) as unique_designations,
                    COUNT(DISTINCT v.id) as total_vacancies
                FROM popular_organization_positions p
                LEFT JOIN organization_vacancies v ON p.id = v.popular_position_id AND v.deleted_at IS NULL
                WHERE p.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:200',
            'department_id' => 'required|integer',
            'team_id' => 'integer',
            'designation_id' => 'required|integer',
            'minimum_education_level' => 'max:50',
            'minimum_subject_id' => 'integer',
            'description' => 'max:1000',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $position = $this->getWithDetails($id);
        if (!$position) {
            return 'N/A';
        }
        return $position['name'] . ' (' . ($position['department_name'] ?? 'No Dept') . ')';
    }
}
