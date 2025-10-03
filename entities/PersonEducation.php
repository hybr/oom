<?php

require_once __DIR__ . '/BaseEntity.php';

class PersonEducation extends BaseEntity {
    protected $table = 'person_education';
    protected $fillable = [
        'id',
        'person_id',
        'institution_id',
        'institution_name',
        'level',
        'major_subjects',
        'score_type',
        'score_value',
        'year_of_completion',
        'year_of_enrollment',
        'duration',
        'remarks',
        'degree_name',
        'specialization',
        'board_university',
        'roll_number',
        'certificate_number',
        'certificate_url',
        'transcript_url',
        'is_highest_qualification',
        'attendance_percentage',
        'achievements',
        'extracurricular',
        'final_project_title',
        'thesis_title',
        'supervisor_name',
        'distinction',
        'scholarship',
        'rank',
        'status',
        'is_active',
        'is_verified',
        'verified_by',
        'verified_at',
        'sort_order',
        'created_at',
        'updated_at'
    ];

    // Education levels
    const LEVEL_PRE_PRIMARY = 'Pre Primary';
    const LEVEL_PRIMARY = 'Primary';
    const LEVEL_SECONDARY = 'Secondary';
    const LEVEL_HIGHER_SECONDARY = 'Higher Secondary';
    const LEVEL_DIPLOMA = 'Diploma';
    const LEVEL_BACHELOR = 'Bachelor';
    const LEVEL_MASTER = 'Master';
    const LEVEL_DOCTOR = 'Doctor';
    const LEVEL_POST_DOCTOR = 'Post Doctor';
    const LEVEL_CERTIFICATE = 'Certificate';

    // Level hierarchy for comparison
    private static $levelHierarchy = [
        self::LEVEL_PRE_PRIMARY => 1,
        self::LEVEL_PRIMARY => 2,
        self::LEVEL_SECONDARY => 3,
        self::LEVEL_HIGHER_SECONDARY => 4,
        self::LEVEL_DIPLOMA => 5,
        self::LEVEL_CERTIFICATE => 5,
        self::LEVEL_BACHELOR => 6,
        self::LEVEL_MASTER => 7,
        self::LEVEL_DOCTOR => 8,
        self::LEVEL_POST_DOCTOR => 9
    ];

    // Score types
    const SCORE_TYPE_GRADE = 'Grade';
    const SCORE_TYPE_PERCENTAGE = '%';
    const SCORE_TYPE_CGPA = 'CGPA';
    const SCORE_TYPE_GPA = 'GPA';
    const SCORE_TYPE_OTHER = 'Other';

    // Status
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DROPOUT = 'dropout';

    public function __construct() {
        parent::__construct();
        $this->attributes['status'] = self::STATUS_ACTIVE;
        $this->attributes['is_active'] = 1;
        $this->attributes['is_verified'] = 0;
        $this->attributes['is_highest_qualification'] = 0;
        $this->attributes['distinction'] = 0;
        $this->attributes['scholarship'] = 0;
        $this->attributes['sort_order'] = 0;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    // ==================== CRUD Methods ====================

    /**
     * Add a new education record for a person
     */
    public function addEducation(
        $person_id,
        $institution_id,
        $level,
        $major_subjects = [],
        $score_type = null,
        $score_value = null,
        $year_of_completion = null,
        $duration = null,
        $remarks = null
    ) {
        $this->person_id = $person_id;
        $this->institution_id = $institution_id;
        $this->level = $level;

        if (!empty($major_subjects)) {
            $this->setMajorSubjects($major_subjects);
        }

        $this->score_type = $score_type;
        $this->score_value = $score_value;
        $this->year_of_completion = $year_of_completion;
        $this->duration = $duration;
        $this->remarks = $remarks;
        $this->status = self::STATUS_ACTIVE;
        $this->is_active = 1;
        $this->updated_at = date('Y-m-d H:i:s');

        return $this->save();
    }

    /**
     * Update education record field
     */
    public function updateEducation($field, $new_value) {
        if (in_array($field, $this->fillable)) {
            $this->$field = $new_value;
            $this->updated_at = date('Y-m-d H:i:s');
            return $this->save();
        }
        return false;
    }

    /**
     * Remove/deactivate education record
     */
    public function removeEducation() {
        $this->is_active = 0;
        $this->status = self::STATUS_INACTIVE;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    /**
     * Permanently delete education record
     */
    public function hardDelete() {
        return $this->delete();
    }

    /**
     * Get education by ID
     */
    public static function getEducation($education_id) {
        return self::find($education_id);
    }

    /**
     * List all education records for a person
     */
    public static function listEducationByPerson($person_id) {
        $records = self::where('person_id', '=', $person_id);

        // Sort by level hierarchy descending (highest first)
        usort($records, function($a, $b) {
            $levelA = self::$levelHierarchy[$a->level] ?? 0;
            $levelB = self::$levelHierarchy[$b->level] ?? 0;

            if ($levelA === $levelB) {
                // If same level, sort by year_of_completion descending
                return ($b->year_of_completion ?? 0) - ($a->year_of_completion ?? 0);
            }

            return $levelB - $levelA;
        });

        return $records;
    }

    /**
     * Get education at a specific level
     */
    public static function listEducationByLevel($person_id, $level) {
        return self::where('person_id', '=', $person_id, 'level', '=', $level);
    }

    /**
     * Get highest education qualification
     */
    public static function getHighestEducation($person_id) {
        $educations = self::listEducationByPerson($person_id);

        if (empty($educations)) {
            return null;
        }

        // Already sorted by level hierarchy, return first
        return $educations[0];
    }

    // ==================== Major Subjects Management ====================

    /**
     * Get major subjects array
     */
    public function getMajorSubjects() {
        if (!$this->major_subjects) return [];
        return json_decode($this->major_subjects, true) ?: [];
    }

    /**
     * Set major subjects (max 6)
     */
    public function setMajorSubjects($subject_ids) {
        if (is_array($subject_ids)) {
            // Limit to 6 subjects
            $subject_ids = array_slice($subject_ids, 0, 6);
            $this->major_subjects = json_encode(array_values($subject_ids));
        } else {
            $this->major_subjects = $subject_ids;
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    /**
     * Add a subject to education (if less than 6)
     */
    public function addSubjectToEducation($subject_id) {
        $subjects = $this->getMajorSubjects();

        // Check if already exists
        if (in_array($subject_id, $subjects)) {
            return true;
        }

        // Check if already at max (6 subjects)
        if (count($subjects) >= 6) {
            return false;
        }

        $subjects[] = $subject_id;
        $this->setMajorSubjects($subjects);
        return $this->save();
    }

    /**
     * Remove a subject from education
     */
    public function removeSubjectFromEducation($subject_id) {
        $subjects = $this->getMajorSubjects();
        $subjects = array_filter($subjects, function($id) use ($subject_id) {
            return $id != $subject_id;
        });
        $this->setMajorSubjects(array_values($subjects));
        return $this->save();
    }

    /**
     * Get major subject objects (with details)
     */
    public function getMajorSubjectObjects() {
        $subject_ids = $this->getMajorSubjects();
        $subjects = [];

        if (empty($subject_ids)) {
            return $subjects;
        }

        require_once __DIR__ . '/PopularEducationSubject.php';

        foreach ($subject_ids as $id) {
            $subject = PopularEducationSubject::find($id);
            if ($subject) {
                $subjects[] = $subject;
            }
        }

        return $subjects;
    }

    /**
     * Get subject count
     */
    public function getSubjectCount() {
        return count($this->getMajorSubjects());
    }

    /**
     * Can add more subjects?
     */
    public function canAddMoreSubjects() {
        return $this->getSubjectCount() < 6;
    }

    // ==================== Person Relationship ====================

    /**
     * Get person object
     */
    public function getPerson() {
        if (!$this->person_id) return null;

        require_once __DIR__ . '/Person.php';
        return Person::find($this->person_id);
    }

    /**
     * Get person name
     */
    public function getPersonName() {
        $person = $this->getPerson();
        return $person ? $person->getFullName() : 'Unknown';
    }

    // ==================== Institution Relationship ====================

    /**
     * Get institution name
     */
    public function getInstitutionName() {
        return $this->institution_name ?: 'Unknown Institution';
    }

    // ==================== GPA/Score Calculations ====================

    /**
     * Calculate GPA for a person (average of all numeric scores)
     */
    public static function calculateGPA($person_id) {
        $educations = self::listEducationByPerson($person_id);
        $total = 0;
        $count = 0;

        foreach ($educations as $edu) {
            $numericScore = $edu->getNumericScore();
            if ($numericScore !== null) {
                $total += $numericScore;
                $count++;
            }
        }

        return $count > 0 ? round($total / $count, 2) : null;
    }

    /**
     * Get numeric score value (normalized to 0-100 or GPA scale)
     */
    public function getNumericScore() {
        if (!$this->score_value) return null;

        switch ($this->score_type) {
            case self::SCORE_TYPE_PERCENTAGE:
                return floatval($this->score_value);

            case self::SCORE_TYPE_CGPA:
            case self::SCORE_TYPE_GPA:
                // Assuming 4.0 scale, convert to percentage
                $gpa = floatval($this->score_value);
                return ($gpa / 4.0) * 100;

            case self::SCORE_TYPE_GRADE:
                // Convert letter grades to percentage
                return $this->gradeToPercentage($this->score_value);

            default:
                // Try to extract numeric value
                if (is_numeric($this->score_value)) {
                    return floatval($this->score_value);
                }
                return null;
        }
    }

    /**
     * Convert letter grade to percentage
     */
    private function gradeToPercentage($grade) {
        $gradeMap = [
            'A+' => 95, 'A' => 90, 'A-' => 85,
            'B+' => 80, 'B' => 75, 'B-' => 70,
            'C+' => 65, 'C' => 60, 'C-' => 55,
            'D+' => 50, 'D' => 45, 'D-' => 40,
            'F' => 35,
            'O' => 95, 'E' => 90, // Some grading systems
            'DISTINCTION' => 95,
            'FIRST CLASS' => 85,
            'SECOND CLASS' => 70,
            'THIRD CLASS' => 55,
            'PASS' => 50
        ];

        $grade = strtoupper(trim($grade));
        return $gradeMap[$grade] ?? null;
    }

    /**
     * Get formatted score
     */
    public function getFormattedScore() {
        if (!$this->score_value) return '-';

        switch ($this->score_type) {
            case self::SCORE_TYPE_PERCENTAGE:
                return $this->score_value . '%';
            case self::SCORE_TYPE_CGPA:
                return $this->score_value . ' CGPA';
            case self::SCORE_TYPE_GPA:
                return $this->score_value . ' GPA';
            default:
                return $this->score_value;
        }
    }

    // ==================== Achievements & Additional Data ====================

    /**
     * Get achievements array
     */
    public function getAchievements() {
        if (!$this->achievements) return [];
        return json_decode($this->achievements, true) ?: [];
    }

    /**
     * Set achievements
     */
    public function setAchievements($achievements) {
        $this->achievements = is_array($achievements) ? json_encode($achievements) : $achievements;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    /**
     * Add achievement
     */
    public function addAchievement($achievement) {
        $achievements = $this->getAchievements();
        $achievements[] = $achievement;
        $this->setAchievements($achievements);
        return $this->save();
    }

    /**
     * Get extracurricular activities
     */
    public function getExtracurricular() {
        if (!$this->extracurricular) return [];
        return json_decode($this->extracurricular, true) ?: [];
    }

    /**
     * Set extracurricular activities
     */
    public function setExtracurricular($activities) {
        $this->extracurricular = is_array($activities) ? json_encode($activities) : $activities;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    // ==================== Status & Flags Management ====================

    /**
     * Mark as highest qualification
     */
    public function markAsHighestQualification() {
        // First, unmark all other education records for this person
        if ($this->person_id) {
            $allEducations = self::listEducationByPerson($this->person_id);
            foreach ($allEducations as $edu) {
                if ($edu->id != $this->id && $edu->is_highest_qualification) {
                    $edu->is_highest_qualification = 0;
                    $edu->save();
                }
            }
        }

        $this->is_highest_qualification = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    /**
     * Mark as having distinction
     */
    public function markDistinction() {
        $this->distinction = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    /**
     * Mark as having scholarship
     */
    public function markScholarship() {
        $this->scholarship = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    /**
     * Verify education record
     */
    public function verify($verified_by = null) {
        $this->is_verified = 1;
        $this->verified_by = $verified_by;
        $this->verified_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    /**
     * Mark as completed
     */
    public function markCompleted() {
        $this->status = self::STATUS_COMPLETED;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    /**
     * Mark as in progress
     */
    public function markInProgress() {
        $this->status = self::STATUS_IN_PROGRESS;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    /**
     * Mark as dropout
     */
    public function markDropout() {
        $this->status = self::STATUS_DROPOUT;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // ==================== Helper Methods ====================

    /**
     * Get level rank (for sorting)
     */
    public function getLevelRank() {
        return self::$levelHierarchy[$this->level] ?? 0;
    }

    /**
     * Check if this is higher than another level
     */
    public function isHigherThan($other_level) {
        $myRank = self::$levelHierarchy[$this->level] ?? 0;
        $otherRank = self::$levelHierarchy[$other_level] ?? 0;
        return $myRank > $otherRank;
    }

    /**
     * Get education summary
     */
    public function getSummary() {
        $parts = [$this->level];

        if ($this->degree_name) {
            $parts[] = $this->degree_name;
        }

        if ($this->specialization) {
            $parts[] = "({$this->specialization})";
        }

        if ($this->institution_name) {
            $parts[] = "from {$this->institution_name}";
        }

        if ($this->year_of_completion) {
            $parts[] = "({$this->year_of_completion})";
        }

        if ($this->score_value) {
            $parts[] = "- {$this->getFormattedScore()}";
        }

        return implode(' ', $parts);
    }

    /**
     * Get duration text
     */
    public function getDurationText() {
        if (!$this->duration) return null;

        if ($this->duration == 1) {
            return '1 year';
        }

        return $this->duration . ' years';
    }

    // ==================== Query Methods ====================

    public static function findByPerson($person_id) {
        return self::where('person_id', '=', $person_id);
    }

    public static function findByLevel($level) {
        return self::where('level', '=', $level);
    }

    public static function findActive() {
        return self::where('is_active', '=', 1);
    }

    public static function findCompleted() {
        return self::where('status', '=', self::STATUS_COMPLETED);
    }

    public static function findInProgress() {
        return self::where('status', '=', self::STATUS_IN_PROGRESS);
    }

    public static function findVerified() {
        return self::where('is_verified', '=', 1);
    }

    public static function findWithDistinction() {
        return self::where('distinction', '=', 1);
    }

    public static function findWithScholarship() {
        return self::where('scholarship', '=', 1);
    }

    public static function getAllLevels() {
        return array_keys(self::$levelHierarchy);
    }

    public static function getAllScoreTypes() {
        return [
            self::SCORE_TYPE_GRADE,
            self::SCORE_TYPE_PERCENTAGE,
            self::SCORE_TYPE_CGPA,
            self::SCORE_TYPE_GPA,
            self::SCORE_TYPE_OTHER
        ];
    }

    // ==================== Statistics ====================

    /**
     * Get education statistics for a person
     */
    public static function getPersonEducationStats($person_id) {
        $educations = self::listEducationByPerson($person_id);

        return [
            'total_count' => count($educations),
            'highest_level' => $educations[0]->level ?? null,
            'completed_count' => count(array_filter($educations, function($e) {
                return $e->status === self::STATUS_COMPLETED;
            })),
            'in_progress_count' => count(array_filter($educations, function($e) {
                return $e->status === self::STATUS_IN_PROGRESS;
            })),
            'verified_count' => count(array_filter($educations, function($e) {
                return $e->is_verified == 1;
            })),
            'distinction_count' => count(array_filter($educations, function($e) {
                return $e->distinction == 1;
            })),
            'scholarship_count' => count(array_filter($educations, function($e) {
                return $e->scholarship == 1;
            })),
            'average_gpa' => self::calculateGPA($person_id)
        ];
    }

    // ==================== Utility Methods ====================

    public function toArray() {
        $data = parent::toArray();

        // Add computed fields
        $data['person_name'] = $this->getPersonName();
        $data['institution_display_name'] = $this->getInstitutionName();
        $data['major_subjects_array'] = $this->getMajorSubjects();
        $data['major_subject_objects'] = array_map(function($s) {
            return ['id' => $s->id, 'name' => $s->name, 'code' => $s->code];
        }, $this->getMajorSubjectObjects());
        $data['subject_count'] = $this->getSubjectCount();
        $data['can_add_more_subjects'] = $this->canAddMoreSubjects();
        $data['formatted_score'] = $this->getFormattedScore();
        $data['numeric_score'] = $this->getNumericScore();
        $data['level_rank'] = $this->getLevelRank();
        $data['summary'] = $this->getSummary();
        $data['duration_text'] = $this->getDurationText();
        $data['achievements_array'] = $this->getAchievements();
        $data['extracurricular_array'] = $this->getExtracurricular();

        return $data;
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS person_education (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                person_id INTEGER NOT NULL,
                institution_id INTEGER,
                institution_name TEXT,
                level TEXT NOT NULL,
                major_subjects TEXT,
                score_type TEXT,
                score_value TEXT,
                year_of_completion INTEGER,
                year_of_enrollment INTEGER,
                duration INTEGER,
                remarks TEXT,
                degree_name TEXT,
                specialization TEXT,
                board_university TEXT,
                roll_number TEXT,
                certificate_number TEXT,
                certificate_url TEXT,
                transcript_url TEXT,
                is_highest_qualification INTEGER DEFAULT 0,
                attendance_percentage REAL,
                achievements TEXT,
                extracurricular TEXT,
                final_project_title TEXT,
                thesis_title TEXT,
                supervisor_name TEXT,
                distinction INTEGER DEFAULT 0,
                scholarship INTEGER DEFAULT 0,
                rank INTEGER,
                status TEXT DEFAULT 'active',
                is_active INTEGER DEFAULT 1,
                is_verified INTEGER DEFAULT 0,
                verified_by INTEGER,
                verified_at DATETIME,
                sort_order INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (person_id) REFERENCES persons (id) ON DELETE CASCADE
            )
        ";
    }
}
