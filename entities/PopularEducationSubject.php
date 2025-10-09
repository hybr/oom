<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * PopularEducationSubject Entity
 * Master list of education subjects/majors
 */
class PopularEducationSubject extends BaseEntity {
    protected $table = 'popular_education_subjects';
    protected $fillable = ['name'];

    /**
     * Get all person education records using this subject
     */
    public function getPersonEducationRecords($subjectId) {
        $sql = "SELECT pes.*, pe.institution, pe.education_level, p.first_name, p.last_name
                FROM person_education_subjects pes
                JOIN person_education pe ON pes.person_education_id = pe.id
                JOIN persons p ON pe.person_id = p.id
                WHERE pes.subject_id = ? AND pes.deleted_at IS NULL
                ORDER BY pe.complete_date DESC";
        return $this->query($sql, [$subjectId]);
    }

    /**
     * Get count of persons who studied this subject
     */
    public function getPersonCount($subjectId) {
        $sql = "SELECT COUNT(DISTINCT pe.person_id) as count
                FROM person_education_subjects pes
                JOIN person_education pe ON pes.person_education_id = pe.id
                WHERE pes.subject_id = ? AND pes.deleted_at IS NULL";
        $result = $this->queryOne($sql, [$subjectId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get most popular subjects (by number of students)
     */
    public function getMostPopular($limit = 10) {
        $sql = "SELECT s.*, COUNT(DISTINCT pe.person_id) as student_count
                FROM popular_education_subjects s
                LEFT JOIN person_education_subjects pes ON s.id = pes.subject_id AND pes.deleted_at IS NULL
                LEFT JOIN person_education pe ON pes.person_education_id = pe.id AND pe.deleted_at IS NULL
                WHERE s.deleted_at IS NULL
                GROUP BY s.id
                ORDER BY student_count DESC, s.name ASC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Get subjects by first letter
     */
    public function getByFirstLetter($letter) {
        $sql = "SELECT * FROM popular_education_subjects
                WHERE name LIKE ? AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql, [$letter . '%']);
    }

    /**
     * Search subjects by name
     */
    public function searchByName($term, $limit = 50) {
        return $this->search('name', $term, $limit);
    }

    /**
     * Get subjects that qualify for specific positions
     */
    public function getForPositions() {
        $sql = "SELECT DISTINCT s.*
                FROM popular_education_subjects s
                JOIN popular_organization_positions p ON s.id = p.minimum_subject_id
                WHERE s.deleted_at IS NULL
                ORDER BY s.name ASC";
        return $this->query($sql);
    }

    /**
     * Get average marks for subject across all students
     */
    public function getAverageMarks($subjectId) {
        $sql = "SELECT AVG(marks) as average_marks, marks_type
                FROM person_education_subjects
                WHERE subject_id = ? AND marks IS NOT NULL AND deleted_at IS NULL
                GROUP BY marks_type";
        return $this->query($sql, [$subjectId]);
    }

    /**
     * Get top performers in subject
     */
    public function getTopPerformers($subjectId, $limit = 10) {
        $sql = "SELECT p.first_name, p.last_name, pes.marks, pes.marks_type, pe.institution
                FROM person_education_subjects pes
                JOIN person_education pe ON pes.person_education_id = pe.id
                JOIN persons p ON pe.person_id = p.id
                WHERE pes.subject_id = ? AND pes.marks IS NOT NULL AND pes.deleted_at IS NULL
                ORDER BY pes.marks DESC
                LIMIT ?";
        return $this->query($sql, [$subjectId, $limit]);
    }

    /**
     * Get subjects grouped by category (if you have categories)
     */
    public function getGroupedByCategory() {
        // This is a placeholder for future categorization
        // For now, group by first letter
        $sql = "SELECT SUBSTR(name, 1, 1) as category, COUNT(*) as count
                FROM popular_education_subjects
                WHERE deleted_at IS NULL
                GROUP BY category
                ORDER BY category ASC";
        return $this->query($sql);
    }

    /**
     * Get subjects with statistics
     */
    public function getAllWithStats($limit = null, $offset = null) {
        $sql = "SELECT s.*,
                COUNT(DISTINCT pe.person_id) as student_count,
                COUNT(DISTINCT pe.institution) as institution_count
                FROM popular_education_subjects s
                LEFT JOIN person_education_subjects pes ON s.id = pes.subject_id AND pes.deleted_at IS NULL
                LEFT JOIN person_education pe ON pes.person_education_id = pe.id AND pe.deleted_at IS NULL
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
     * Check if subject name is unique
     */
    public function isNameUnique($name, $exceptId = null) {
        $sql = "SELECT id FROM popular_education_subjects WHERE name = ? AND deleted_at IS NULL";
        $params = [$name];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return empty($result);
    }

    /**
     * Get subjects by education level
     */
    public function getByEducationLevel($educationLevel) {
        $sql = "SELECT DISTINCT s.*
                FROM popular_education_subjects s
                JOIN person_education_subjects pes ON s.id = pes.subject_id
                JOIN person_education pe ON pes.person_education_id = pe.id
                WHERE pe.education_level = ? AND s.deleted_at IS NULL
                ORDER BY s.name ASC";
        return $this->query($sql, [$educationLevel]);
    }

    /**
     * Get subject statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_subjects,
                    COUNT(DISTINCT pes.person_education_id) as total_enrollments
                FROM popular_education_subjects s
                LEFT JOIN person_education_subjects pes ON s.id = pes.subject_id AND pes.deleted_at IS NULL
                WHERE s.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate subject data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:200' . ($id ? "|unique:popular_education_subjects,name,$id" : '|unique:popular_education_subjects,name'),
        ];

        return $this->validate($data, $rules);
    }
}
