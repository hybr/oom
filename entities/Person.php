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
     * Get credentials for this person
     */
    public function getCredential($personId) {
        $sql = "SELECT * FROM credentials WHERE person_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$personId]);
    }

    /**
     * Get education records
     */
    public function getEducation($personId) {
        $sql = "SELECT * FROM person_education WHERE person_id = ? AND deleted_at IS NULL ORDER BY start_date DESC";
        return $this->query($sql, [$personId]);
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
     * Get organizations owned by this person
     */
    public function getOrganizations($personId) {
        $sql = "SELECT * FROM organizations WHERE admin_id = ? AND deleted_at IS NULL ORDER BY short_name";
        return $this->query($sql, [$personId]);
    }

    /**
     * Get job applications
     */
    public function getApplications($personId) {
        $sql = "SELECT va.*, ov.opening_date, ov.closing_date,
                       pop.name as position_name, o.short_name as organization_name
                FROM vacancy_applications va
                JOIN organization_vacancies ov ON va.vacancy_id = ov.id
                JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                JOIN organizations o ON ov.organization_id = o.id
                WHERE va.applicant_id = ? AND va.deleted_at IS NULL
                ORDER BY va.application_date DESC";
        return $this->query($sql, [$personId]);
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
