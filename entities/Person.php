<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * Person Entity
 */
class Person extends BaseEntity {
    protected $table = 'persons';
    protected $fillable = ['first_name', 'middle_name', 'last_name', 'date_of_birth'];

    /**
     * Get full name
     */
    public function getFullName($personId) {
        $person = $this->find($personId);
        if (!$person) {
            return 'Unknown';
        }

        $name = $person['first_name'];
        if (!empty($person['middle_name'])) {
            $name .= ' ' . $person['middle_name'];
        }
        $name .= ' ' . $person['last_name'];

        return $name;
    }

    /**
     * Get with full details
     */
    public function getWithDetails($personId) {
        $sql = "SELECT p.*,
                c.username,
                (SELECT COUNT(*) FROM person_education WHERE person_id = p.id AND deleted_at IS NULL) as education_count,
                (SELECT COUNT(*) FROM person_skills WHERE person_id = p.id AND deleted_at IS NULL) as skill_count,
                (SELECT COUNT(*) FROM organizations WHERE admin_id = p.id AND deleted_at IS NULL) as organization_count
                FROM persons p
                LEFT JOIN credentials c ON c.person_id = p.id AND c.deleted_at IS NULL
                WHERE p.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$personId]);
    }

    /**
     * Get credentials for this person
     */
    public function getCredential($personId) {
        $sql = "SELECT * FROM credentials WHERE person_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$personId]);
    }

    /**
     * Check if person has credentials
     */
    public function hasCredential($personId) {
        $credential = $this->getCredential($personId);
        return !empty($credential);
    }

    /**
     * Get education records
     */
    public function getEducation($personId) {
        $sql = "SELECT * FROM person_education WHERE person_id = ? AND deleted_at IS NULL ORDER BY start_date DESC";
        return $this->query($sql, [$personId]);
    }

    /**
     * Get education count
     */
    public function getEducationCount($personId) {
        $sql = "SELECT COUNT(*) as count FROM person_education WHERE person_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$personId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get highest education
     */
    public function getHighestEducation($personId) {
        $sql = "SELECT * FROM person_education
                WHERE person_id = ? AND deleted_at IS NULL
                ORDER BY end_date DESC, start_date DESC
                LIMIT 1";
        return $this->queryOne($sql, [$personId]);
    }

    /**
     * Get skills
     */
    public function getSkills($personId) {
        $sql = "SELECT ps.*, s.name as skill_name
                FROM person_skills ps
                JOIN popular_skills s ON ps.skill_id = s.id
                WHERE ps.person_id = ? AND ps.deleted_at IS NULL
                ORDER BY s.name";
        return $this->query($sql, [$personId]);
    }

    /**
     * Get skill count
     */
    public function getSkillCount($personId) {
        $sql = "SELECT COUNT(*) as count FROM person_skills WHERE person_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$personId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get skills by proficiency
     */
    public function getSkillsByProficiency($personId, $minProficiency = null) {
        $sql = "SELECT ps.*, s.name as skill_name
                FROM person_skills ps
                JOIN popular_skills s ON ps.skill_id = s.id
                WHERE ps.person_id = ? AND ps.deleted_at IS NULL";

        $params = [$personId];

        if ($minProficiency) {
            $sql .= " AND ps.proficiency_level >= ?";
            $params[] = $minProficiency;
        }

        $sql .= " ORDER BY ps.proficiency_level DESC, s.name ASC";
        return $this->query($sql, $params);
    }

    /**
     * Get organizations owned by this person
     */
    public function getOrganizations($personId) {
        $sql = "SELECT * FROM organizations WHERE admin_id = ? AND deleted_at IS NULL ORDER BY short_name";
        return $this->query($sql, [$personId]);
    }

    /**
     * Get organization count
     */
    public function getOrganizationCount($personId) {
        $sql = "SELECT COUNT(*) as count FROM organizations WHERE admin_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$personId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get job applications
     */
    public function getApplications($personId, $status = null) {
        $sql = "SELECT va.*, ov.opening_date, ov.closing_date,
                       pop.name as position_name, o.short_name as organization_name
                FROM vacancy_applications va
                JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                JOIN organizations o ON ov.organization_id = o.id
                WHERE va.applicant_id = ? AND va.deleted_at IS NULL";

        $params = [$personId];

        if ($status) {
            $sql .= " AND va.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY va.application_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get application count
     */
    public function getApplicationCount($personId, $status = null) {
        $sql = "SELECT COUNT(*) as count FROM vacancy_applications
                WHERE applicant_id = ? AND deleted_at IS NULL";

        $params = [$personId];

        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        $result = $this->queryOne($sql, $params);
        return $result['count'] ?? 0;
    }

    /**
     * Get job offers
     */
    public function getJobOffers($personId, $status = null) {
        $sql = "SELECT jo.*,
                pop.name as position_name, o.short_name as organization_name
                FROM job_offers jo
                JOIN organization_vacancies ov ON jo.vacancy_id = ov.id
                JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                JOIN organizations o ON ov.organization_id = o.id
                WHERE jo.applicant_id = ? AND jo.deleted_at IS NULL";

        $params = [$personId];

        if ($status) {
            $sql .= " AND jo.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY jo.offer_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get employment contracts
     */
    public function getEmploymentContracts($personId, $status = null) {
        $sql = "SELECT ec.*,
                pop.name as position_name, o.short_name as organization_name
                FROM employment_contracts ec
                JOIN organization_vacancies ov ON ec.vacancy_id = ov.id
                JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                JOIN organizations o ON ov.organization_id = o.id
                WHERE ec.applicant_id = ? AND ec.deleted_at IS NULL";

        $params = [$personId];

        if ($status) {
            $sql .= " AND ec.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY ec.start_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Calculate age
     */
    public function getAge($personId) {
        $person = $this->find($personId);
        if (!$person || empty($person['date_of_birth'])) {
            return null;
        }

        $dob = new DateTime($person['date_of_birth']);
        $now = new DateTime();
        $age = $dob->diff($now);

        return $age->y;
    }

    /**
     * Search persons by name
     */
    public function searchByName($term, $limit = 50) {
        $sql = "SELECT * FROM persons
                WHERE (first_name LIKE ? OR middle_name LIKE ? OR last_name LIKE ?)
                AND deleted_at IS NULL
                ORDER BY last_name ASC, first_name ASC
                LIMIT ?";
        return $this->query($sql, ["%$term%", "%$term%", "%$term%", $limit]);
    }

    /**
     * Get persons by birth date range
     */
    public function getByBirthDateRange($startDate, $endDate) {
        $sql = "SELECT * FROM persons
                WHERE date_of_birth BETWEEN ? AND ?
                AND deleted_at IS NULL
                ORDER BY date_of_birth DESC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    /**
     * Get persons by age range
     */
    public function getByAgeRange($minAge, $maxAge) {
        $maxDate = date('Y-m-d', strtotime("-$minAge years"));
        $minDate = date('Y-m-d', strtotime("-$maxAge years"));

        $sql = "SELECT * FROM persons
                WHERE date_of_birth BETWEEN ? AND ?
                AND deleted_at IS NULL
                ORDER BY date_of_birth DESC";
        return $this->query($sql, [$minDate, $maxDate]);
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_persons,
                    COUNT(CASE WHEN date_of_birth IS NOT NULL THEN 1 END) as with_dob,
                    AVG(CAST((julianday('now') - julianday(date_of_birth)) / 365.25 AS INTEGER)) as average_age,
                    COUNT(DISTINCT c.id) as with_credentials
                FROM persons p
                LEFT JOIN credentials c ON c.person_id = p.id AND c.deleted_at IS NULL
                WHERE p.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate person data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'middle_name' => 'max:100',
            'date_of_birth' => 'date',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel to return full name
     */
    public function getLabel($id) {
        return $this->getFullName($id);
    }
}
