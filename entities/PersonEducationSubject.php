<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * PersonEducationSubject Entity
 * Links education records with subjects and marks
 */
class PersonEducationSubject extends BaseEntity {
    protected $table = 'person_education_subjects';
    protected $fillable = ['person_education_id', 'subject_id', 'marks_type', 'marks'];

    /**
     * Get person education record
     */
    public function getPersonEducation($id) {
        $sql = "SELECT pe.* FROM person_education pe
                JOIN person_education_subjects pes ON pes.person_education_id = pe.id
                WHERE pes.id = ? AND pe.deleted_at IS NULL";
        return $this->queryOne($sql, [$id]);
    }

    /**
     * Get subject details
     */
    public function getSubject($id) {
        $sql = "SELECT s.* FROM popular_education_subjects s
                JOIN person_education_subjects pes ON pes.subject_id = s.id
                WHERE pes.id = ? AND s.deleted_at IS NULL";
        return $this->queryOne($sql, [$id]);
    }

    /**
     * Get full details with joins
     */
    public function getWithDetails($id) {
        $sql = "SELECT pes.*, s.name as subject_name,
                pe.institution, pe.education_level,
                p.first_name, p.last_name
                FROM person_education_subjects pes
                JOIN popular_education_subjects s ON pes.subject_id = s.id
                JOIN person_education pe ON pes.person_education_id = pe.id
                JOIN persons p ON pe.person_id = p.id
                WHERE pes.id = ? AND pes.deleted_at IS NULL";
        return $this->queryOne($sql, [$id]);
    }

    /**
     * Get subjects by person education
     */
    public function getByPersonEducation($personEducationId) {
        $sql = "SELECT pes.*, s.name as subject_name
                FROM person_education_subjects pes
                JOIN popular_education_subjects s ON pes.subject_id = s.id
                WHERE pes.person_education_id = ? AND pes.deleted_at IS NULL
                ORDER BY s.name ASC";
        return $this->query($sql, [$personEducationId]);
    }

    /**
     * Get by subject
     */
    public function getBySubject($subjectId) {
        $sql = "SELECT pes.*, pe.institution, pe.education_level,
                p.first_name, p.last_name
                FROM person_education_subjects pes
                JOIN person_education pe ON pes.person_education_id = pe.id
                JOIN persons p ON pe.person_id = p.id
                WHERE pes.subject_id = ? AND pes.deleted_at IS NULL
                ORDER BY pes.marks DESC";
        return $this->query($sql, [$subjectId]);
    }

    /**
     * Get top performers in subject
     */
    public function getTopPerformersBySubject($subjectId, $limit = 10) {
        $sql = "SELECT pes.*, p.first_name, p.last_name, pe.institution
                FROM person_education_subjects pes
                JOIN person_education pe ON pes.person_education_id = pe.id
                JOIN persons p ON pe.person_id = p.id
                WHERE pes.subject_id = ? AND pes.marks IS NOT NULL
                AND pes.deleted_at IS NULL
                ORDER BY pes.marks DESC
                LIMIT ?";
        return $this->query($sql, [$subjectId, $limit]);
    }

    /**
     * Get average marks for person across all subjects
     */
    public function getAverageMarksByPerson($personId) {
        $sql = "SELECT AVG(pes.marks) as average_marks, pes.marks_type
                FROM person_education_subjects pes
                JOIN person_education pe ON pes.person_education_id = pe.id
                WHERE pe.person_id = ? AND pes.marks IS NOT NULL
                AND pes.deleted_at IS NULL
                GROUP BY pes.marks_type";
        return $this->query($sql, [$personId]);
    }

    /**
     * Get average marks for subject
     */
    public function getAverageMarksBySubject($subjectId) {
        $sql = "SELECT AVG(marks) as average_marks, marks_type
                FROM person_education_subjects
                WHERE subject_id = ? AND marks IS NOT NULL
                AND deleted_at IS NULL
                GROUP BY marks_type";
        return $this->query($sql, [$subjectId]);
    }

    /**
     * Get subjects by marks range
     */
    public function getByMarksRange($minMarks, $maxMarks, $marksType = null) {
        $sql = "SELECT pes.*, s.name as subject_name, p.first_name, p.last_name
                FROM person_education_subjects pes
                JOIN popular_education_subjects s ON pes.subject_id = s.id
                JOIN person_education pe ON pes.person_education_id = pe.id
                JOIN persons p ON pe.person_id = p.id
                WHERE pes.marks BETWEEN ? AND ?
                AND pes.deleted_at IS NULL";

        $params = [$minMarks, $maxMarks];

        if ($marksType) {
            $sql .= " AND pes.marks_type = ?";
            $params[] = $marksType;
        }

        $sql .= " ORDER BY pes.marks DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get subject count per person education
     */
    public function getSubjectCount($personEducationId) {
        $sql = "SELECT COUNT(*) as count
                FROM person_education_subjects
                WHERE person_education_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$personEducationId]);
        return $result['count'] ?? 0;
    }

    /**
     * Check if subject exists in education record
     */
    public function subjectExists($personEducationId, $subjectId, $exceptId = null) {
        $sql = "SELECT id FROM person_education_subjects
                WHERE person_education_id = ? AND subject_id = ?
                AND deleted_at IS NULL";
        $params = [$personEducationId, $subjectId];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return !empty($result);
    }

    /**
     * Get statistics by marks type
     */
    public function getStatisticsByMarksType() {
        $sql = "SELECT marks_type,
                COUNT(*) as count,
                AVG(marks) as average_marks,
                MIN(marks) as min_marks,
                MAX(marks) as max_marks
                FROM person_education_subjects
                WHERE marks IS NOT NULL AND deleted_at IS NULL
                GROUP BY marks_type";
        return $this->query($sql);
    }

    /**
     * Get all with pagination and filtering
     */
    public function getAllWithDetails($limit = null, $offset = null, $filters = []) {
        $sql = "SELECT pes.*, s.name as subject_name,
                pe.institution, pe.education_level,
                p.first_name, p.last_name
                FROM person_education_subjects pes
                JOIN popular_education_subjects s ON pes.subject_id = s.id
                JOIN person_education pe ON pes.person_education_id = pe.id
                JOIN persons p ON pe.person_id = p.id
                WHERE pes.deleted_at IS NULL";

        $params = [];

        if (!empty($filters['subject_id'])) {
            $sql .= " AND pes.subject_id = ?";
            $params[] = $filters['subject_id'];
        }

        if (!empty($filters['marks_type'])) {
            $sql .= " AND pes.marks_type = ?";
            $params[] = $filters['marks_type'];
        }

        $sql .= " ORDER BY s.name ASC, pes.marks DESC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset ?? 0;
        }

        return $this->query($sql, $params);
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_records,
                    COUNT(CASE WHEN marks IS NOT NULL THEN 1 END) as records_with_marks,
                    COUNT(DISTINCT subject_id) as unique_subjects,
                    AVG(marks) as overall_average_marks
                FROM person_education_subjects
                WHERE deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'person_education_id' => 'required|integer',
            'subject_id' => 'required|integer',
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
        return $record['subject_name'] . ' - ' . $record['institution'];
    }
}
