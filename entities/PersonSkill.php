<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * PersonSkill Entity
 * Represents skills possessed by a person
 */
class PersonSkill extends BaseEntity {
    protected $table = 'person_skills';
    protected $fillable = [
        'person_id', 'skill_id', 'institution', 'level',
        'start_date', 'complete_date', 'marks_type', 'marks'
    ];

    /**
     * Get person for this skill record
     */
    public function getPerson($skillRecordId) {
        $sql = "SELECT p.* FROM persons p
                JOIN person_skills ps ON ps.person_id = p.id
                WHERE ps.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$skillRecordId]);
    }

    /**
     * Get skill details
     */
    public function getSkill($skillRecordId) {
        $sql = "SELECT s.* FROM popular_skills s
                JOIN person_skills ps ON ps.skill_id = s.id
                WHERE ps.id = ? AND s.deleted_at IS NULL";
        return $this->queryOne($sql, [$skillRecordId]);
    }

    /**
     * Get full details with joins
     */
    public function getWithDetails($skillRecordId) {
        $sql = "SELECT ps.*, s.name as skill_name, p.first_name, p.last_name
                FROM person_skills ps
                JOIN popular_skills s ON ps.skill_id = s.id
                JOIN persons p ON ps.person_id = p.id
                WHERE ps.id = ? AND ps.deleted_at IS NULL";
        return $this->queryOne($sql, [$skillRecordId]);
    }

    /**
     * Get skills by person
     */
    public function getByPerson($personId) {
        $sql = "SELECT ps.*, s.name as skill_name
                FROM person_skills ps
                JOIN popular_skills s ON ps.skill_id = s.id
                WHERE ps.person_id = ? AND ps.deleted_at IS NULL
                ORDER BY ps.level DESC, s.name ASC";
        return $this->query($sql, [$personId]);
    }

    /**
     * Get persons by skill
     */
    public function getBySkill($skillId) {
        $sql = "SELECT ps.*, p.first_name, p.last_name
                FROM person_skills ps
                JOIN persons p ON ps.person_id = p.id
                WHERE ps.skill_id = ? AND ps.deleted_at IS NULL
                ORDER BY ps.level DESC, p.first_name ASC";
        return $this->query($sql, [$skillId]);
    }

    /**
     * Get top experts in a skill
     */
    public function getTopExperts($skillId, $limit = 10) {
        $sql = "SELECT ps.*, p.first_name, p.last_name
                FROM person_skills ps
                JOIN persons p ON ps.person_id = p.id
                WHERE ps.skill_id = ? AND ps.deleted_at IS NULL
                ORDER BY ps.level DESC, ps.marks DESC
                LIMIT ?";
        return $this->query($sql, [$skillId, $limit]);
    }

    /**
     * Get skills by level
     */
    public function getByLevel($level) {
        $sql = "SELECT ps.*, s.name as skill_name, p.first_name, p.last_name
                FROM person_skills ps
                JOIN popular_skills s ON ps.skill_id = s.id
                JOIN persons p ON ps.person_id = p.id
                WHERE ps.level = ? AND ps.deleted_at IS NULL
                ORDER BY s.name ASC";
        return $this->query($sql, [$level]);
    }

    /**
     * Get skills acquired at institution
     */
    public function getByInstitution($institution) {
        $sql = "SELECT ps.*, s.name as skill_name, p.first_name, p.last_name
                FROM person_skills ps
                JOIN popular_skills s ON ps.skill_id = s.id
                JOIN persons p ON ps.person_id = p.id
                WHERE ps.institution LIKE ? AND ps.deleted_at IS NULL
                ORDER BY ps.complete_date DESC";
        return $this->query($sql, ["%$institution%"]);
    }

    /**
     * Get completed skill trainings
     */
    public function getCompleted($personId = null) {
        $sql = "SELECT ps.*, s.name as skill_name, p.first_name, p.last_name
                FROM person_skills ps
                JOIN popular_skills s ON ps.skill_id = s.id
                JOIN persons p ON ps.person_id = p.id
                WHERE ps.complete_date IS NOT NULL AND ps.deleted_at IS NULL";

        $params = [];
        if ($personId) {
            $sql .= " AND ps.person_id = ?";
            $params[] = $personId;
        }

        $sql .= " ORDER BY ps.complete_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get ongoing skill trainings
     */
    public function getOngoing($personId = null) {
        $sql = "SELECT ps.*, s.name as skill_name, p.first_name, p.last_name
                FROM person_skills ps
                JOIN popular_skills s ON ps.skill_id = s.id
                JOIN persons p ON ps.person_id = p.id
                WHERE ps.complete_date IS NULL AND ps.deleted_at IS NULL";

        $params = [];
        if ($personId) {
            $sql .= " AND ps.person_id = ?";
            $params[] = $personId;
        }

        $sql .= " ORDER BY ps.start_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Check if person has skill
     */
    public function hasSkill($personId, $skillId) {
        $sql = "SELECT COUNT(*) as count
                FROM person_skills
                WHERE person_id = ? AND skill_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$personId, $skillId]);
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Get skill level for person
     */
    public function getSkillLevel($personId, $skillId) {
        $sql = "SELECT level FROM person_skills
                WHERE person_id = ? AND skill_id = ? AND deleted_at IS NULL
                ORDER BY level DESC LIMIT 1";
        $result = $this->queryOne($sql, [$personId, $skillId]);
        return $result['level'] ?? 0;
    }

    /**
     * Get average skill level for person
     */
    public function getAverageLevel($personId) {
        $sql = "SELECT AVG(level) as average_level
                FROM person_skills
                WHERE person_id = ? AND level IS NOT NULL AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$personId]);
        return $result['average_level'] ?? 0;
    }

    /**
     * Get skills in date range
     */
    public function getInDateRange($startDate, $endDate) {
        $sql = "SELECT ps.*, s.name as skill_name, p.first_name, p.last_name
                FROM person_skills ps
                JOIN popular_skills s ON ps.skill_id = s.id
                JOIN persons p ON ps.person_id = p.id
                WHERE ps.start_date BETWEEN ? AND ?
                AND ps.deleted_at IS NULL
                ORDER BY ps.start_date DESC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    /**
     * Get most common skills
     */
    public function getMostCommonSkills($limit = 10) {
        $sql = "SELECT s.name, COUNT(*) as person_count, AVG(ps.level) as avg_level
                FROM person_skills ps
                JOIN popular_skills s ON ps.skill_id = s.id
                WHERE ps.deleted_at IS NULL
                GROUP BY s.id
                ORDER BY person_count DESC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Get statistics by skill
     */
    public function getStatisticsBySkill($skillId) {
        $sql = "SELECT
                    COUNT(*) as total_persons,
                    AVG(level) as average_level,
                    MAX(level) as max_level,
                    MIN(level) as min_level,
                    COUNT(CASE WHEN complete_date IS NOT NULL THEN 1 END) as completed_count
                FROM person_skills
                WHERE skill_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$skillId]);
    }

    /**
     * Get overall statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_records,
                    COUNT(DISTINCT person_id) as unique_persons,
                    COUNT(DISTINCT skill_id) as unique_skills,
                    AVG(level) as overall_average_level
                FROM person_skills
                WHERE deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate skill data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'person_id' => 'required|integer',
            'skill_id' => 'required|integer',
            'institution' => 'max:200',
            'level' => 'numeric',
            'start_date' => 'date',
            'complete_date' => 'date',
            'marks_type' => 'max:50',
            'marks' => 'numeric',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $record = $this->getWithDetails($id);
        if (!$record) {
            return 'N/A';
        }
        return $record['skill_name'] . ' (Level: ' . $record['level'] . ')';
    }
}
