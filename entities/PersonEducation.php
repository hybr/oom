<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * PersonEducation Entity
 * Represents a person's education record
 */
class PersonEducation extends BaseEntity {
    protected $table = 'person_education';
    protected $fillable = [
        'person_id', 'institution', 'start_date', 'complete_date', 'education_level'
    ];

    /**
     * Get person for this education record
     */
    public function getPerson($educationId) {
        $sql = "SELECT p.* FROM persons p
                JOIN person_education pe ON pe.person_id = p.id
                WHERE pe.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$educationId]);
    }

    /**
     * Get all subjects for this education record
     */
    public function getSubjects($educationId) {
        $sql = "SELECT pes.*, s.name as subject_name
                FROM person_education_subjects pes
                JOIN popular_education_subjects s ON pes.subject_id = s.id
                WHERE pes.person_education_id = ? AND pes.deleted_at IS NULL
                ORDER BY s.name ASC";
        return $this->query($sql, [$educationId]);
    }

    /**
     * Get education records by person
     */
    public function getByPerson($personId) {
        return $this->all(['person_id' => $personId], 'start_date DESC');
    }

    /**
     * Get education records by level
     */
    public function getByLevel($educationLevel) {
        return $this->all(['education_level' => $educationLevel], 'start_date DESC');
    }

    /**
     * Get education records by institution
     */
    public function getByInstitution($institution) {
        $sql = "SELECT pe.*, p.first_name, p.last_name
                FROM person_education pe
                JOIN persons p ON pe.person_id = p.id
                WHERE pe.institution LIKE ? AND pe.deleted_at IS NULL
                ORDER BY pe.complete_date DESC";
        return $this->query($sql, ["%$institution%"]);
    }

    /**
     * Get completed education records
     */
    public function getCompleted($personId = null) {
        $sql = "SELECT pe.*, p.first_name, p.last_name
                FROM person_education pe
                JOIN persons p ON pe.person_id = p.id
                WHERE pe.complete_date IS NOT NULL AND pe.deleted_at IS NULL";

        $params = [];
        if ($personId) {
            $sql .= " AND pe.person_id = ?";
            $params[] = $personId;
        }

        $sql .= " ORDER BY pe.complete_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get ongoing education records
     */
    public function getOngoing($personId = null) {
        $sql = "SELECT pe.*, p.first_name, p.last_name
                FROM person_education pe
                JOIN persons p ON pe.person_id = p.id
                WHERE pe.complete_date IS NULL AND pe.deleted_at IS NULL";

        $params = [];
        if ($personId) {
            $sql .= " AND pe.person_id = ?";
            $params[] = $personId;
        }

        $sql .= " ORDER BY pe.start_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get duration of education in months
     */
    public function getDuration($educationId) {
        $education = $this->find($educationId);
        if (!$education || !$education['start_date']) {
            return 0;
        }

        $startDate = new DateTime($education['start_date']);
        $endDate = $education['complete_date']
            ? new DateTime($education['complete_date'])
            : new DateTime();

        $interval = $startDate->diff($endDate);
        return ($interval->y * 12) + $interval->m;
    }

    /**
     * Get highest education level for person
     */
    public function getHighestLevel($personId) {
        $sql = "SELECT * FROM person_education
                WHERE person_id = ? AND deleted_at IS NULL
                ORDER BY
                    CASE education_level
                        WHEN 'EDUCATION_DOCTORATE' THEN 8
                        WHEN 'EDUCATION_MASTER' THEN 7
                        WHEN 'EDUCATION_BACHELOR' THEN 6
                        WHEN 'EDUCATION_SECONDARY' THEN 5
                        WHEN 'EDUCATION_PRIMARY' THEN 4
                        WHEN 'EDUCATION_VOCATIONAL' THEN 3
                        WHEN 'EDUCATION_CERTIFICATION' THEN 2
                        ELSE 1
                    END DESC
                LIMIT 1";
        return $this->queryOne($sql, [$personId]);
    }

    /**
     * Get education records in date range
     */
    public function getInDateRange($startDate, $endDate) {
        $sql = "SELECT pe.*, p.first_name, p.last_name
                FROM person_education pe
                JOIN persons p ON pe.person_id = p.id
                WHERE pe.start_date BETWEEN ? AND ?
                AND pe.deleted_at IS NULL
                ORDER BY pe.start_date DESC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    /**
     * Get unique institutions
     */
    public function getUniqueInstitutions() {
        $sql = "SELECT DISTINCT institution
                FROM person_education
                WHERE institution IS NOT NULL AND deleted_at IS NULL
                ORDER BY institution ASC";
        return $this->query($sql);
    }

    /**
     * Get statistics by education level
     */
    public function getStatisticsByLevel() {
        $sql = "SELECT education_level, COUNT(*) as count
                FROM person_education
                WHERE deleted_at IS NULL
                GROUP BY education_level
                ORDER BY count DESC";
        return $this->query($sql);
    }

    /**
     * Check if person has education at level
     */
    public function hasEducationAtLevel($personId, $educationLevel) {
        $sql = "SELECT COUNT(*) as count
                FROM person_education
                WHERE person_id = ? AND education_level = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$personId, $educationLevel]);
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Get education with full details
     */
    public function getWithDetails($educationId) {
        $education = $this->find($educationId);
        if (!$education) {
            return null;
        }

        $education['person'] = $this->getPerson($educationId);
        $education['subjects'] = $this->getSubjects($educationId);
        $education['duration_months'] = $this->getDuration($educationId);

        return $education;
    }

    /**
     * Get education statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_records,
                    COUNT(CASE WHEN complete_date IS NULL THEN 1 END) as ongoing,
                    COUNT(CASE WHEN complete_date IS NOT NULL THEN 1 END) as completed,
                    COUNT(DISTINCT person_id) as unique_persons,
                    COUNT(DISTINCT institution) as unique_institutions
                FROM person_education
                WHERE deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate education data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'person_id' => 'required|integer',
            'institution' => 'required|min:2|max:200',
            'start_date' => 'required|date',
            'complete_date' => 'date',
            'education_level' => 'required',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $education = $this->find($id);
        if (!$education) {
            return 'N/A';
        }
        return $education['institution'] . ' (' . $education['education_level'] . ')';
    }
}
