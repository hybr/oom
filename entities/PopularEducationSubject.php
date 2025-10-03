<?php

require_once __DIR__ . '/BaseEntity.php';

class PopularEducationSubject extends BaseEntity {
    protected $table = 'popular_education_subjects';
    protected $fillable = [
        'id',
        'name',
        'code',
        'slug',
        'description',
        'category',
        'level',
        'prerequisites',
        'credits',
        'duration_hours',
        'difficulty_level',
        'language',
        'textbooks',
        'syllabus_url',
        'learning_outcomes',
        'assessment_methods',
        'practical_component',
        'theory_component',
        'lab_required',
        'is_core',
        'is_elective',
        'is_mandatory',
        'popularity_score',
        'pass_percentage',
        'average_grade',
        'enrollment_count',
        'completion_rate',
        'related_subjects',
        'career_paths',
        'industry_relevance',
        'certification_available',
        'status',
        'is_active',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_at',
        'updated_at'
    ];

    // Subject categories
    const CATEGORY_SCIENCE = 'Science';
    const CATEGORY_MATHEMATICS = 'Mathematics';
    const CATEGORY_ARTS = 'Arts';
    const CATEGORY_COMMERCE = 'Commerce';
    const CATEGORY_HUMANITIES = 'Humanities';
    const CATEGORY_SOCIAL_SCIENCES = 'Social Sciences';
    const CATEGORY_LANGUAGE = 'Language';
    const CATEGORY_ENGINEERING = 'Engineering';
    const CATEGORY_MEDICAL = 'Medical';
    const CATEGORY_LAW = 'Law';
    const CATEGORY_MANAGEMENT = 'Management';
    const CATEGORY_COMPUTER_SCIENCE = 'Computer Science';
    const CATEGORY_FINE_ARTS = 'Fine Arts';
    const CATEGORY_PHYSICAL_EDUCATION = 'Physical Education';
    const CATEGORY_VOCATIONAL = 'Vocational';

    // Education levels
    const LEVEL_PRIMARY = 'Primary';
    const LEVEL_SECONDARY = 'Secondary';
    const LEVEL_HIGHER_SECONDARY = 'Higher Secondary';
    const LEVEL_DIPLOMA = 'Diploma';
    const LEVEL_BACHELOR = 'Bachelor';
    const LEVEL_MASTER = 'Master';
    const LEVEL_DOCTOR = 'Doctor';
    const LEVEL_CERTIFICATE = 'Certificate';
    const LEVEL_ALL = 'All Levels';

    // Difficulty levels
    const DIFFICULTY_BEGINNER = 'Beginner';
    const DIFFICULTY_INTERMEDIATE = 'Intermediate';
    const DIFFICULTY_ADVANCED = 'Advanced';
    const DIFFICULTY_EXPERT = 'Expert';

    // Status
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_DRAFT = 'draft';
    const STATUS_ARCHIVED = 'archived';

    public function __construct() {
        parent::__construct();
        $this->attributes['status'] = self::STATUS_DRAFT;
        $this->attributes['is_active'] = 1;
        $this->attributes['is_featured'] = 0;
        $this->attributes['is_core'] = 0;
        $this->attributes['is_elective'] = 0;
        $this->attributes['is_mandatory'] = 0;
        $this->attributes['lab_required'] = 0;
        $this->attributes['certification_available'] = 0;
        $this->attributes['popularity_score'] = 0;
        $this->attributes['enrollment_count'] = 0;
        $this->attributes['completion_rate'] = 0.0;
        $this->attributes['sort_order'] = 0;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    // ==================== CRUD Methods ====================

    /**
     * Add a new subject
     */
    public function addSubject($name, $code, $description, $category, $level, $credits = null, $prerequisites = []) {
        $this->name = $name;
        $this->code = $code;
        $this->description = $description;
        $this->category = $category;
        $this->level = $level;
        $this->credits = $credits;

        if (!empty($prerequisites)) {
            $this->setPrerequisites($prerequisites);
        }

        $this->slug = $this->generateSlug($name);
        $this->status = self::STATUS_ACTIVE;
        $this->is_active = 1;
        $this->updated_at = date('Y-m-d H:i:s');

        return $this->save();
    }

    /**
     * Update subject details
     */
    public function updateSubject($field, $new_value) {
        if (in_array($field, $this->fillable)) {
            $this->$field = $new_value;
            $this->updated_at = date('Y-m-d H:i:s');
            return $this->save();
        }
        return false;
    }

    /**
     * Remove/delete subject
     */
    public function removeSubject() {
        return $this->delete();
    }

    /**
     * Get subject by ID
     */
    public static function getSubject($subject_id) {
        return self::find($subject_id);
    }

    /**
     * List subjects with optional filtering
     */
    public static function listSubjects($filter_by_category = null, $filter_by_level = null) {
        $subjects = self::all();

        if ($filter_by_category) {
            $subjects = array_filter($subjects, function($subject) use ($filter_by_category) {
                return $subject->category === $filter_by_category;
            });
        }

        if ($filter_by_level) {
            $subjects = array_filter($subjects, function($subject) use ($filter_by_level) {
                return $subject->level === $filter_by_level;
            });
        }

        return array_values($subjects);
    }

    /**
     * Search subjects by keyword
     */
    public static function searchSubject($keyword) {
        $subjects = self::all();
        $keyword = strtolower($keyword);

        return array_filter($subjects, function($subject) use ($keyword) {
            return stripos($subject->name, $keyword) !== false ||
                   stripos($subject->code, $keyword) !== false ||
                   stripos($subject->description ?? '', $keyword) !== false;
        });
    }

    /**
     * Set subject status
     */
    public function setStatus($status) {
        if (in_array($status, [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DRAFT, self::STATUS_ARCHIVED])) {
            $this->status = $status;
            $this->is_active = ($status === self::STATUS_ACTIVE) ? 1 : 0;
            $this->updated_at = date('Y-m-d H:i:s');
            return $this->save();
        }
        return false;
    }

    // ==================== Prerequisites Management ====================

    /**
     * Get prerequisites array
     */
    public function getPrerequisites() {
        if (!$this->prerequisites) return [];
        return json_decode($this->prerequisites, true) ?: [];
    }

    /**
     * Set prerequisites
     */
    public function setPrerequisites($prerequisite_ids) {
        if (is_array($prerequisite_ids)) {
            $this->prerequisites = json_encode($prerequisite_ids);
        } else {
            $this->prerequisites = $prerequisite_ids;
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    /**
     * Add a prerequisite
     */
    public function addPrerequisite($subject_id) {
        $prerequisites = $this->getPrerequisites();
        if (!in_array($subject_id, $prerequisites)) {
            $prerequisites[] = $subject_id;
            $this->setPrerequisites($prerequisites);
            return $this->save();
        }
        return true;
    }

    /**
     * Remove a prerequisite
     */
    public function removePrerequisite($subject_id) {
        $prerequisites = $this->getPrerequisites();
        $prerequisites = array_filter($prerequisites, function($id) use ($subject_id) {
            return $id != $subject_id;
        });
        $this->setPrerequisites(array_values($prerequisites));
        return $this->save();
    }

    /**
     * Get prerequisite subjects (full objects)
     */
    public function getPrerequisiteSubjects() {
        $prerequisite_ids = $this->getPrerequisites();
        $subjects = [];

        foreach ($prerequisite_ids as $id) {
            $subject = self::find($id);
            if ($subject) {
                $subjects[] = $subject;
            }
        }

        return $subjects;
    }

    /**
     * Check if subject has prerequisites
     */
    public function hasPrerequisites() {
        return count($this->getPrerequisites()) > 0;
    }

    /**
     * Check if a specific subject is a prerequisite
     */
    public function isPrerequisite($subject_id) {
        return in_array($subject_id, $this->getPrerequisites());
    }

    /**
     * Get subjects that require this subject as prerequisite
     */
    public function getDependentSubjects() {
        $all_subjects = self::all();
        $dependents = [];

        foreach ($all_subjects as $subject) {
            if ($subject->isPrerequisite($this->id)) {
                $dependents[] = $subject;
            }
        }

        return $dependents;
    }

    // ==================== Related Subjects Management ====================

    /**
     * Get related subjects array
     */
    public function getRelatedSubjects() {
        if (!$this->related_subjects) return [];
        return json_decode($this->related_subjects, true) ?: [];
    }

    /**
     * Set related subjects
     */
    public function setRelatedSubjects($subject_ids) {
        if (is_array($subject_ids)) {
            $this->related_subjects = json_encode($subject_ids);
        } else {
            $this->related_subjects = $subject_ids;
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    /**
     * Add a related subject
     */
    public function addRelatedSubject($subject_id) {
        $related = $this->getRelatedSubjects();
        if (!in_array($subject_id, $related)) {
            $related[] = $subject_id;
            $this->setRelatedSubjects($related);
            return $this->save();
        }
        return true;
    }

    // ==================== Status & Flags Management ====================

    public function activate() {
        return $this->setStatus(self::STATUS_ACTIVE);
    }

    public function deactivate() {
        return $this->setStatus(self::STATUS_INACTIVE);
    }

    public function archive() {
        return $this->setStatus(self::STATUS_ARCHIVED);
    }

    public function isActive() {
        return $this->is_active == 1 && $this->status === self::STATUS_ACTIVE;
    }

    public function isFeatured() {
        return $this->is_featured == 1;
    }

    public function isCore() {
        return $this->is_core == 1;
    }

    public function isElective() {
        return $this->is_elective == 1;
    }

    public function isMandatory() {
        return $this->is_mandatory == 1;
    }

    public function requiresLab() {
        return $this->lab_required == 1;
    }

    public function hasCertification() {
        return $this->certification_available == 1;
    }

    public function markAsFeatured() {
        $this->is_featured = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function markAsCore() {
        $this->is_core = 1;
        $this->is_elective = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function markAsElective() {
        $this->is_elective = 1;
        $this->is_core = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function markAsMandatory() {
        $this->is_mandatory = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // ==================== Additional Data Management ====================

    /**
     * Get learning outcomes array
     */
    public function getLearningOutcomes() {
        if (!$this->learning_outcomes) return [];
        return json_decode($this->learning_outcomes, true) ?: [];
    }

    /**
     * Set learning outcomes
     */
    public function setLearningOutcomes($outcomes) {
        $this->learning_outcomes = is_array($outcomes) ? json_encode($outcomes) : $outcomes;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    /**
     * Get assessment methods array
     */
    public function getAssessmentMethods() {
        if (!$this->assessment_methods) return [];
        return json_decode($this->assessment_methods, true) ?: [];
    }

    /**
     * Set assessment methods
     */
    public function setAssessmentMethods($methods) {
        $this->assessment_methods = is_array($methods) ? json_encode($methods) : $methods;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    /**
     * Get textbooks array
     */
    public function getTextbooks() {
        if (!$this->textbooks) return [];
        return json_decode($this->textbooks, true) ?: [];
    }

    /**
     * Set textbooks
     */
    public function setTextbooks($books) {
        $this->textbooks = is_array($books) ? json_encode($books) : $books;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    /**
     * Get career paths array
     */
    public function getCareerPaths() {
        if (!$this->career_paths) return [];
        return json_decode($this->career_paths, true) ?: [];
    }

    /**
     * Set career paths
     */
    public function setCareerPaths($paths) {
        $this->career_paths = is_array($paths) ? json_encode($paths) : $paths;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    // ==================== Statistics & Analytics ====================

    /**
     * Update enrollment count
     */
    public function incrementEnrollment($count = 1) {
        $this->enrollment_count = ($this->enrollment_count ?? 0) + $count;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    /**
     * Update popularity score
     */
    public function updatePopularityScore($score) {
        $this->popularity_score = max(0, min(100, $score));
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    /**
     * Update completion rate
     */
    public function updateCompletionRate($rate) {
        $this->completion_rate = max(0, min(100, $rate));
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    /**
     * Get subject statistics
     */
    public function getStatistics() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'category' => $this->category,
            'level' => $this->level,
            'credits' => $this->credits,
            'enrollment_count' => $this->enrollment_count ?? 0,
            'popularity_score' => $this->popularity_score ?? 0,
            'completion_rate' => $this->completion_rate ?? 0,
            'pass_percentage' => $this->pass_percentage ?? 0,
            'prerequisite_count' => count($this->getPrerequisites()),
            'is_core' => $this->isCore(),
            'is_elective' => $this->isElective(),
            'is_mandatory' => $this->isMandatory(),
            'requires_lab' => $this->requiresLab()
        ];
    }

    // ==================== Slug Management ====================

    public function generateSlug($name = null) {
        $name = $name ?: $this->name;
        if (!$name) return '';

        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while ($this->isSlugTaken($slug)) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    private function isSlugTaken($slug) {
        $existing = static::where('slug', '=', $slug);

        if ($this->id) {
            $existing = array_filter($existing, function($subject) {
                return $subject->id !== $this->id;
            });
        }

        return !empty($existing);
    }

    // ==================== Query Methods ====================

    public static function findByCode($code) {
        $results = static::where('code', '=', $code);
        return !empty($results) ? $results[0] : null;
    }

    public static function findByCategory($category) {
        return static::where('category', '=', $category);
    }

    public static function findByLevel($level) {
        return static::where('level', '=', $level);
    }

    public static function findActive() {
        return static::where('is_active', '=', 1);
    }

    public static function findFeatured() {
        return static::where('is_featured', '=', 1);
    }

    public static function findCore() {
        return static::where('is_core', '=', 1);
    }

    public static function findElective() {
        return static::where('is_elective', '=', 1);
    }

    public static function findMandatory() {
        return static::where('is_mandatory', '=', 1);
    }

    public static function findByDifficulty($difficulty) {
        return static::where('difficulty_level', '=', $difficulty);
    }

    public static function getAllCategories() {
        return [
            self::CATEGORY_SCIENCE,
            self::CATEGORY_MATHEMATICS,
            self::CATEGORY_ARTS,
            self::CATEGORY_COMMERCE,
            self::CATEGORY_HUMANITIES,
            self::CATEGORY_SOCIAL_SCIENCES,
            self::CATEGORY_LANGUAGE,
            self::CATEGORY_ENGINEERING,
            self::CATEGORY_MEDICAL,
            self::CATEGORY_LAW,
            self::CATEGORY_MANAGEMENT,
            self::CATEGORY_COMPUTER_SCIENCE,
            self::CATEGORY_FINE_ARTS,
            self::CATEGORY_PHYSICAL_EDUCATION,
            self::CATEGORY_VOCATIONAL
        ];
    }

    public static function getAllLevels() {
        return [
            self::LEVEL_PRIMARY,
            self::LEVEL_SECONDARY,
            self::LEVEL_HIGHER_SECONDARY,
            self::LEVEL_DIPLOMA,
            self::LEVEL_BACHELOR,
            self::LEVEL_MASTER,
            self::LEVEL_DOCTOR,
            self::LEVEL_CERTIFICATE,
            self::LEVEL_ALL
        ];
    }

    // ==================== Utility Methods ====================

    public function toArray() {
        $data = parent::toArray();

        // Add computed/decoded fields
        $data['prerequisites_array'] = $this->getPrerequisites();
        $data['prerequisite_subjects'] = array_map(function($s) {
            return ['id' => $s->id, 'name' => $s->name, 'code' => $s->code];
        }, $this->getPrerequisiteSubjects());
        $data['related_subjects_array'] = $this->getRelatedSubjects();
        $data['learning_outcomes_array'] = $this->getLearningOutcomes();
        $data['assessment_methods_array'] = $this->getAssessmentMethods();
        $data['textbooks_array'] = $this->getTextbooks();
        $data['career_paths_array'] = $this->getCareerPaths();
        $data['has_prerequisites'] = $this->hasPrerequisites();
        $data['dependent_count'] = count($this->getDependentSubjects());

        return $data;
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS popular_education_subjects (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                code TEXT UNIQUE NOT NULL,
                slug TEXT UNIQUE,
                description TEXT,
                category TEXT NOT NULL,
                level TEXT NOT NULL,
                prerequisites TEXT,
                credits INTEGER,
                duration_hours INTEGER,
                difficulty_level TEXT,
                language TEXT DEFAULT 'English',
                textbooks TEXT,
                syllabus_url TEXT,
                learning_outcomes TEXT,
                assessment_methods TEXT,
                practical_component INTEGER DEFAULT 0,
                theory_component INTEGER DEFAULT 0,
                lab_required INTEGER DEFAULT 0,
                is_core INTEGER DEFAULT 0,
                is_elective INTEGER DEFAULT 0,
                is_mandatory INTEGER DEFAULT 0,
                popularity_score INTEGER DEFAULT 0,
                pass_percentage REAL DEFAULT 0,
                average_grade TEXT,
                enrollment_count INTEGER DEFAULT 0,
                completion_rate REAL DEFAULT 0,
                related_subjects TEXT,
                career_paths TEXT,
                industry_relevance TEXT,
                certification_available INTEGER DEFAULT 0,
                status TEXT DEFAULT 'draft',
                is_active INTEGER DEFAULT 1,
                is_featured INTEGER DEFAULT 0,
                sort_order INTEGER DEFAULT 0,
                meta_title TEXT,
                meta_description TEXT,
                meta_keywords TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE(code),
                UNIQUE(slug)
            )
        ";
    }
}
