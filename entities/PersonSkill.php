<?php

require_once __DIR__ . '/BaseEntity.php';

class PersonSkill extends BaseEntity {
    protected $table = 'person_skill';
    protected $fillable = [
        'id',
        'person_id',
        'skill_id',
        'skill_name',
        'skill_category',
        'skill_type',
        'proficiency_level',
        'proficiency_percentage',
        'years_of_experience',
        'months_of_experience',
        'total_experience_months',
        'last_used_date',
        'frequency_of_use',
        'context_of_use',
        'self_rating',
        'verified_rating',
        'expert_rating',
        'peer_rating',
        'manager_rating',
        'client_rating',
        'average_rating',
        'rating_count',
        'endorsement_count',
        'certification_name',
        'certification_provider',
        'certification_number',
        'certification_date',
        'certification_expiry',
        'certification_url',
        'certification_status',
        'is_certified',
        'is_primary_skill',
        'is_core_skill',
        'is_verified',
        'verified_by',
        'verified_at',
        'verification_method',
        'verification_notes',
        'projects_count',
        'project_names',
        'project_descriptions',
        'achievements',
        'awards',
        'recognitions',
        'publications',
        'presentations',
        'training_completed',
        'training_providers',
        'training_hours',
        'practice_hours',
        'learning_resources',
        'mentors',
        'skill_level_progression',
        'improvement_plan',
        'target_proficiency',
        'target_date',
        'learning_status',
        'willing_to_mentor',
        'willing_to_teach',
        'interest_level',
        'passion_score',
        'market_value',
        'relevance_score',
        'strength_area',
        'weakness_area',
        'improvement_needed',
        'notes',
        'remarks',
        'tags',
        'keywords',
        'source',
        'acquired_from',
        'acquired_date',
        'skill_proof_url',
        'portfolio_url',
        'github_repos',
        'demo_links',
        'testimonials',
        'skill_references',
        'status',
        'is_active',
        'is_public',
        'is_featured',
        'display_order',
        'sort_order',
        'created_at',
        'updated_at'
    ];

    // Proficiency levels
    const PROFICIENCY_BEGINNER = 'Beginner';
    const PROFICIENCY_ELEMENTARY = 'Elementary';
    const PROFICIENCY_INTERMEDIATE = 'Intermediate';
    const PROFICIENCY_ADVANCED = 'Advanced';
    const PROFICIENCY_EXPERT = 'Expert';
    const PROFICIENCY_MASTER = 'Master';

    // Proficiency percentages
    private static $proficiencyPercentages = [
        self::PROFICIENCY_BEGINNER => 20,
        self::PROFICIENCY_ELEMENTARY => 40,
        self::PROFICIENCY_INTERMEDIATE => 60,
        self::PROFICIENCY_ADVANCED => 80,
        self::PROFICIENCY_EXPERT => 95,
        self::PROFICIENCY_MASTER => 100
    ];

    // Frequency of use
    const FREQUENCY_DAILY = 'Daily';
    const FREQUENCY_WEEKLY = 'Weekly';
    const FREQUENCY_MONTHLY = 'Monthly';
    const FREQUENCY_OCCASIONALLY = 'Occasionally';
    const FREQUENCY_RARELY = 'Rarely';

    // Status
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_LEARNING = 'learning';
    const STATUS_PROFICIENT = 'proficient';
    const STATUS_EXPERT = 'expert';
    const STATUS_OUTDATED = 'outdated';

    public function __construct() {
        parent::__construct();
        $this->attributes['proficiency_level'] = self::PROFICIENCY_BEGINNER;
        $this->attributes['proficiency_percentage'] = 0;
        $this->attributes['years_of_experience'] = 0;
        $this->attributes['months_of_experience'] = 0;
        $this->attributes['total_experience_months'] = 0;
        $this->attributes['self_rating'] = 0;
        $this->attributes['average_rating'] = 0;
        $this->attributes['rating_count'] = 0;
        $this->attributes['endorsement_count'] = 0;
        $this->attributes['is_certified'] = 0;
        $this->attributes['is_primary_skill'] = 0;
        $this->attributes['is_core_skill'] = 0;
        $this->attributes['is_verified'] = 0;
        $this->attributes['projects_count'] = 0;
        $this->attributes['willing_to_mentor'] = 0;
        $this->attributes['willing_to_teach'] = 0;
        $this->attributes['interest_level'] = 5;
        $this->attributes['passion_score'] = 5;
        $this->attributes['status'] = self::STATUS_ACTIVE;
        $this->attributes['is_active'] = 1;
        $this->attributes['is_public'] = 1;
        $this->attributes['is_featured'] = 0;
        $this->attributes['display_order'] = 0;
        $this->attributes['sort_order'] = 0;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    // ==================== CRUD Methods ====================

    /**
     * Add a skill to a person
     */
    public function addSkill($person_id, $skill_id, $proficiency_level = null,
        $years_of_experience = 0, $months_of_experience = 0, $notes = null) {

        // Get skill details from PopularSkills
        require_once __DIR__ . '/PopularSkills.php';
        $popularSkill = new PopularSkills();
        $popularSkill->id = $skill_id;
        $skillData = $popularSkill->find($skill_id);

        if (!$skillData) {
            throw new Exception("Skill with ID {$skill_id} not found");
        }

        $this->attributes['person_id'] = $person_id;
        $this->attributes['skill_id'] = $skill_id;
        $this->attributes['skill_name'] = $skillData['name'];
        $this->attributes['skill_category'] = $skillData['skill_category'] ?? null;
        $this->attributes['skill_type'] = $skillData['skill_type'] ?? null;
        $this->attributes['proficiency_level'] = $proficiency_level ?? self::PROFICIENCY_BEGINNER;
        $this->attributes['proficiency_percentage'] = self::$proficiencyPercentages[$this->attributes['proficiency_level']] ?? 0;
        $this->attributes['years_of_experience'] = $years_of_experience;
        $this->attributes['months_of_experience'] = $months_of_experience;
        $this->attributes['total_experience_months'] = ($years_of_experience * 12) + $months_of_experience;
        $this->attributes['notes'] = $notes;

        return $this->save();
    }

    /**
     * Update skill details
     */
    public function updateSkill($skill_id, $updates) {
        $this->skill_id = $skill_id;

        if (isset($updates['years_of_experience']) || isset($updates['months_of_experience'])) {
            $years = $updates['years_of_experience'] ?? $this->attributes['years_of_experience'] ?? 0;
            $months = $updates['months_of_experience'] ?? $this->attributes['months_of_experience'] ?? 0;
            $updates['total_experience_months'] = ($years * 12) + $months;
        }

        if (isset($updates['proficiency_level'])) {
            $updates['proficiency_percentage'] = self::$proficiencyPercentages[$updates['proficiency_level']] ?? 0;
        }

        $updates['updated_at'] = date('Y-m-d H:i:s');

        return $this->update($updates);
    }

    /**
     * Remove a skill
     */
    public function removeSkill($skill_id) {
        $this->skill_id = $skill_id;
        return $this->delete();
    }

    /**
     * Get skill details
     */
    public function getSkill($skill_id) {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$skill_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==================== Query Methods ====================

    /**
     * List all skills for a person
     */
    public static function listSkillsByPerson($person_id) {
        $instance = new self();
        $db = $instance->db->getConnection();

        $stmt = $db->prepare("
            SELECT * FROM {$instance->table}
            WHERE person_id = ? AND is_active = 1
            ORDER BY is_primary_skill DESC, proficiency_percentage DESC, total_experience_months DESC
        ");
        $stmt->execute([$person_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * List skills by proficiency level
     */
    public static function listSkillsByProficiency($person_id, $proficiency_level) {
        $instance = new self();
        $db = $instance->db->getConnection();

        $stmt = $db->prepare("
            SELECT * FROM {$instance->table}
            WHERE person_id = ? AND proficiency_level = ? AND is_active = 1
            ORDER BY total_experience_months DESC
        ");
        $stmt->execute([$person_id, $proficiency_level]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * List certified skills for a person
     */
    public static function listCertifiedSkills($person_id) {
        $instance = new self();
        $db = $instance->db->getConnection();

        $stmt = $db->prepare("
            SELECT * FROM {$instance->table}
            WHERE person_id = ? AND is_certified = 1 AND is_active = 1
            ORDER BY certification_date DESC
        ");
        $stmt->execute([$person_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * List primary/core skills
     */
    public static function listPrimarySkills($person_id) {
        $instance = new self();
        $db = $instance->db->getConnection();

        $stmt = $db->prepare("
            SELECT * FROM {$instance->table}
            WHERE person_id = ? AND (is_primary_skill = 1 OR is_core_skill = 1) AND is_active = 1
            ORDER BY proficiency_percentage DESC
        ");
        $stmt->execute([$person_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * List skills by category
     */
    public static function listSkillsByCategory($person_id, $category) {
        $instance = new self();
        $db = $instance->db->getConnection();

        $stmt = $db->prepare("
            SELECT * FROM {$instance->table}
            WHERE person_id = ? AND skill_category = ? AND is_active = 1
            ORDER BY proficiency_percentage DESC
        ");
        $stmt->execute([$person_id, $category]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==================== Rating & Endorsement Methods ====================

    /**
     * Add or update rating
     */
    public function addRating($rating_type, $rating_value, $rated_by = null) {
        $field_name = $rating_type . '_rating';

        if (!in_array($field_name, $this->fillable)) {
            return false;
        }

        $this->attributes[$field_name] = $rating_value;

        // Recalculate average rating
        $ratings = [];
        foreach (['self', 'verified', 'expert', 'peer', 'manager', 'client'] as $type) {
            $field = $type . '_rating';
            if (!empty($this->attributes[$field]) && $this->attributes[$field] > 0) {
                $ratings[] = $this->attributes[$field];
            }
        }

        if (count($ratings) > 0) {
            $this->attributes['average_rating'] = array_sum($ratings) / count($ratings);
            $this->attributes['rating_count'] = count($ratings);
        }

        if ($rated_by) {
            $this->attributes['verified_by'] = $rated_by;
            $this->attributes['verified_at'] = date('Y-m-d H:i:s');
        }

        return $this->save();
    }

    /**
     * Add endorsement
     */
    public function addEndorsement() {
        $this->attributes['endorsement_count'] = ($this->attributes['endorsement_count'] ?? 0) + 1;
        return $this->save();
    }

    // ==================== Certification Methods ====================

    /**
     * Add certification to skill
     */
    public function addCertification($cert_name, $cert_provider, $cert_number = null,
        $cert_date = null, $cert_expiry = null, $cert_url = null) {

        $this->attributes['certification_name'] = $cert_name;
        $this->attributes['certification_provider'] = $cert_provider;
        $this->attributes['certification_number'] = $cert_number;
        $this->attributes['certification_date'] = $cert_date ?? date('Y-m-d');
        $this->attributes['certification_expiry'] = $cert_expiry;
        $this->attributes['certification_url'] = $cert_url;
        $this->attributes['is_certified'] = 1;
        $this->attributes['certification_status'] = 'active';

        return $this->save();
    }

    /**
     * Check certification validity
     */
    public function isCertificationValid() {
        if (!$this->attributes['is_certified'] || empty($this->attributes['certification_expiry'])) {
            return $this->attributes['is_certified'];
        }

        return strtotime($this->attributes['certification_expiry']) > time();
    }

    // ==================== Analytics Methods ====================

    /**
     * Calculate skill proficiency score
     */
    public function calculateProficiencyScore() {
        $score = 0;

        // Base proficiency (40%)
        $score += ($this->attributes['proficiency_percentage'] ?? 0) * 0.4;

        // Experience (30%)
        $exp_months = $this->attributes['total_experience_months'] ?? 0;
        $exp_score = min(100, ($exp_months / 60) * 100); // 5 years = 100%
        $score += $exp_score * 0.3;

        // Ratings (20%)
        $score += ($this->attributes['average_rating'] ?? 0) * 10 * 0.2;

        // Certification (10%)
        $cert_score = $this->attributes['is_certified'] ? 100 : 0;
        $score += $cert_score * 0.1;

        return round($score, 2);
    }

    /**
     * Get skill strength level
     */
    public function getSkillStrength() {
        $score = $this->calculateProficiencyScore();

        if ($score >= 90) return 'Exceptional';
        if ($score >= 75) return 'Strong';
        if ($score >= 60) return 'Competent';
        if ($score >= 40) return 'Developing';
        return 'Beginner';
    }

    /**
     * Get skills requiring improvement for a person
     */
    public static function getSkillsNeedingImprovement($person_id, $threshold = 60) {
        $instance = new self();
        $db = $instance->db->getConnection();

        $stmt = $db->prepare("
            SELECT * FROM {$instance->table}
            WHERE person_id = ? AND proficiency_percentage < ? AND is_active = 1
            ORDER BY proficiency_percentage ASC
        ");
        $stmt->execute([$person_id, $threshold]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get expiring certifications
     */
    public static function getExpiringCertifications($person_id, $days = 90) {
        $instance = new self();
        $db = $instance->db->getConnection();

        $expiry_date = date('Y-m-d', strtotime("+{$days} days"));

        $stmt = $db->prepare("
            SELECT * FROM {$instance->table}
            WHERE person_id = ? AND is_certified = 1
            AND certification_expiry IS NOT NULL
            AND certification_expiry <= ?
            AND is_active = 1
            ORDER BY certification_expiry ASC
        ");
        $stmt->execute([$person_id, $expiry_date]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get skill statistics for a person
     */
    public static function getSkillStatistics($person_id) {
        $instance = new self();
        $db = $instance->db->getConnection();

        $stmt = $db->prepare("
            SELECT
                COUNT(*) as total_skills,
                SUM(CASE WHEN is_certified = 1 THEN 1 ELSE 0 END) as certified_skills,
                SUM(CASE WHEN is_primary_skill = 1 THEN 1 ELSE 0 END) as primary_skills,
                SUM(CASE WHEN proficiency_level = ? THEN 1 ELSE 0 END) as expert_skills,
                AVG(proficiency_percentage) as avg_proficiency,
                AVG(total_experience_months) as avg_experience_months,
                SUM(endorsement_count) as total_endorsements
            FROM {$instance->table}
            WHERE person_id = ? AND is_active = 1
        ");
        $stmt->execute([self::PROFICIENCY_EXPERT, $person_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==================== Database Schema ====================

    /**
     * Get schema SQL
     */
    protected function getSchema() {
        return ""; // Schema is defined in createTable method
    }

    /**
     * Create table schema
     */
    public static function createTable() {
        $instance = new self();
        $db = $instance->db->getConnection();

        $sql = "CREATE TABLE IF NOT EXISTS {$instance->table} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            person_id INTEGER NOT NULL,
            skill_id INTEGER NOT NULL,
            skill_name TEXT,
            skill_category TEXT,
            skill_type TEXT,
            proficiency_level TEXT DEFAULT 'Beginner',
            proficiency_percentage INTEGER DEFAULT 0,
            years_of_experience INTEGER DEFAULT 0,
            months_of_experience INTEGER DEFAULT 0,
            total_experience_months INTEGER DEFAULT 0,
            last_used_date DATE,
            frequency_of_use TEXT,
            context_of_use TEXT,
            self_rating REAL DEFAULT 0,
            verified_rating REAL DEFAULT 0,
            expert_rating REAL DEFAULT 0,
            peer_rating REAL DEFAULT 0,
            manager_rating REAL DEFAULT 0,
            client_rating REAL DEFAULT 0,
            average_rating REAL DEFAULT 0,
            rating_count INTEGER DEFAULT 0,
            endorsement_count INTEGER DEFAULT 0,
            certification_name TEXT,
            certification_provider TEXT,
            certification_number TEXT,
            certification_date DATE,
            certification_expiry DATE,
            certification_url TEXT,
            certification_status TEXT,
            is_certified INTEGER DEFAULT 0,
            is_primary_skill INTEGER DEFAULT 0,
            is_core_skill INTEGER DEFAULT 0,
            is_verified INTEGER DEFAULT 0,
            verified_by INTEGER,
            verified_at DATETIME,
            verification_method TEXT,
            verification_notes TEXT,
            projects_count INTEGER DEFAULT 0,
            project_names TEXT,
            project_descriptions TEXT,
            achievements TEXT,
            awards TEXT,
            recognitions TEXT,
            publications TEXT,
            presentations TEXT,
            training_completed TEXT,
            training_providers TEXT,
            training_hours INTEGER,
            practice_hours INTEGER,
            learning_resources TEXT,
            mentors TEXT,
            skill_level_progression TEXT,
            improvement_plan TEXT,
            target_proficiency TEXT,
            target_date DATE,
            learning_status TEXT,
            willing_to_mentor INTEGER DEFAULT 0,
            willing_to_teach INTEGER DEFAULT 0,
            interest_level INTEGER DEFAULT 5,
            passion_score INTEGER DEFAULT 5,
            market_value TEXT,
            relevance_score INTEGER,
            strength_area TEXT,
            weakness_area TEXT,
            improvement_needed TEXT,
            notes TEXT,
            remarks TEXT,
            tags TEXT,
            keywords TEXT,
            source TEXT,
            acquired_from TEXT,
            acquired_date DATE,
            skill_proof_url TEXT,
            portfolio_url TEXT,
            github_repos TEXT,
            demo_links TEXT,
            testimonials TEXT,
            skill_references TEXT,
            status TEXT DEFAULT 'active',
            is_active INTEGER DEFAULT 1,
            is_public INTEGER DEFAULT 1,
            is_featured INTEGER DEFAULT 0,
            display_order INTEGER DEFAULT 0,
            sort_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE CASCADE,
            FOREIGN KEY (skill_id) REFERENCES popular_skills(id) ON DELETE CASCADE
        )";

        $db->exec($sql);

        // Create indexes
        $db->exec("CREATE INDEX IF NOT EXISTS idx_person_skill_person ON {$instance->table}(person_id)");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_person_skill_skill ON {$instance->table}(skill_id)");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_person_skill_proficiency ON {$instance->table}(proficiency_level)");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_person_skill_category ON {$instance->table}(skill_category)");

        return true;
    }
}
