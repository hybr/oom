<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * PopularSkill Entity
 * Master list of skills and competencies
 */
class PopularSkill extends BaseEntity {
    protected $table = 'popular_skills';
    protected $fillable = ['name'];

    /**
     * Get all persons with this skill
     */
    public function getPersonsWithSkill($skillId) {
        $sql = "SELECT p.*, ps.level, ps.institution, ps.marks
                FROM person_skills ps
                JOIN persons p ON ps.person_id = p.id
                WHERE ps.skill_id = ? AND ps.deleted_at IS NULL
                ORDER BY ps.level DESC, p.first_name ASC";
        return $this->query($sql, [$skillId]);
    }

    /**
     * Get count of persons with this skill
     */
    public function getPersonCount($skillId) {
        $sql = "SELECT COUNT(DISTINCT person_id) as count
                FROM person_skills
                WHERE skill_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$skillId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get most in-demand skills
     */
    public function getMostInDemand($limit = 10) {
        $sql = "SELECT s.*, COUNT(DISTINCT ps.person_id) as person_count
                FROM popular_skills s
                LEFT JOIN person_skills ps ON s.id = ps.skill_id AND ps.deleted_at IS NULL
                WHERE s.deleted_at IS NULL
                GROUP BY s.id
                ORDER BY person_count DESC, s.name ASC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Get skills by first letter
     */
    public function getByFirstLetter($letter) {
        $sql = "SELECT * FROM popular_skills
                WHERE name LIKE ? AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql, [$letter . '%']);
    }

    /**
     * Search skills by name
     */
    public function searchByName($term, $limit = 50) {
        return $this->search('name', $term, $limit);
    }

    /**
     * Get skills with experience levels
     */
    public function getWithLevels($skillId) {
        $sql = "SELECT level, COUNT(*) as count
                FROM person_skills
                WHERE skill_id = ? AND deleted_at IS NULL
                GROUP BY level
                ORDER BY level DESC";
        return $this->query($sql, [$skillId]);
    }

    /**
     * Get average skill level
     */
    public function getAverageLevel($skillId) {
        $sql = "SELECT AVG(level) as average_level
                FROM person_skills
                WHERE skill_id = ? AND level IS NOT NULL AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$skillId]);
        return $result['average_level'] ?? 0;
    }

    /**
     * Get top experts in skill
     */
    public function getTopExperts($skillId, $limit = 10) {
        $sql = "SELECT p.first_name, p.last_name, ps.level, ps.institution, ps.marks
                FROM person_skills ps
                JOIN persons p ON ps.person_id = p.id
                WHERE ps.skill_id = ? AND ps.deleted_at IS NULL
                ORDER BY ps.level DESC, ps.marks DESC
                LIMIT ?";
        return $this->query($sql, [$skillId, $limit]);
    }

    /**
     * Get skills grouped by category
     */
    public function getGroupedByCategory() {
        // Group by first letter for now
        $sql = "SELECT SUBSTR(name, 1, 1) as category, COUNT(*) as count
                FROM popular_skills
                WHERE deleted_at IS NULL
                GROUP BY category
                ORDER BY category ASC";
        return $this->query($sql);
    }

    /**
     * Get skills with statistics
     */
    public function getAllWithStats($limit = null, $offset = null) {
        $sql = "SELECT s.*,
                COUNT(DISTINCT ps.person_id) as person_count,
                AVG(ps.level) as average_level
                FROM popular_skills s
                LEFT JOIN person_skills ps ON s.id = ps.skill_id AND ps.deleted_at IS NULL
                WHERE s.deleted_at IS NULL
                GROUP BY s.id
                ORDER BY s.name ASC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->query($sql, [$limit, $offset ?? 0]);
        }

        return $this->query($sql);
    }

    /**
     * Get related skills (skills that people often have together)
     */
    public function getRelatedSkills($skillId, $limit = 10) {
        $sql = "SELECT s.*, COUNT(*) as co_occurrence
                FROM popular_skills s
                JOIN person_skills ps1 ON s.id = ps1.skill_id
                WHERE ps1.person_id IN (
                    SELECT person_id FROM person_skills WHERE skill_id = ? AND deleted_at IS NULL
                )
                AND s.id != ?
                AND s.deleted_at IS NULL AND ps1.deleted_at IS NULL
                GROUP BY s.id
                ORDER BY co_occurrence DESC
                LIMIT ?";
        return $this->query($sql, [$skillId, $skillId, $limit]);
    }

    /**
     * Check if skill name is unique
     */
    public function isNameUnique($name, $exceptId = null) {
        $sql = "SELECT id FROM popular_skills WHERE name = ? AND deleted_at IS NULL";
        $params = [$name];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return empty($result);
    }

    /**
     * Get trending skills (most added recently)
     */
    public function getTrendingSkills($days = 30, $limit = 10) {
        $sql = "SELECT s.*, COUNT(*) as recent_count
                FROM popular_skills s
                JOIN person_skills ps ON s.id = ps.skill_id
                WHERE ps.created_at >= date('now', '-' || ? || ' days')
                AND s.deleted_at IS NULL AND ps.deleted_at IS NULL
                GROUP BY s.id
                ORDER BY recent_count DESC
                LIMIT ?";
        return $this->query($sql, [$days, $limit]);
    }

    /**
     * Get skill statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_skills,
                    COUNT(DISTINCT ps.person_id) as total_people_with_skills
                FROM popular_skills s
                LEFT JOIN person_skills ps ON s.id = ps.skill_id AND ps.deleted_at IS NULL
                WHERE s.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate skill data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:200' . ($id ? "|unique:popular_skills,name,$id" : '|unique:popular_skills,name'),
        ];

        return $this->validate($data, $rules);
    }
}
