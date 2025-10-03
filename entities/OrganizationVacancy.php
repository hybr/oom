<?php

require_once __DIR__ . '/BaseEntity.php';

class OrganizationVacancy extends BaseEntity {
    protected $table = 'organization_vacancies';
    protected $fillable = [
        'id',
        'organization_id',
        'organization_position_id',
        'vacancy_code',
        'vacancy_title',
        'slug',
        'description',
        'summary',
        'requirements',
        'responsibilities',
        'number_of_openings',
        'filled_count',
        'remaining_openings',
        'vacancy_type',
        'employment_type',
        'work_mode',
        'priority_level',
        'urgency_level',
        'is_urgent',
        'is_featured',
        'is_confidential',
        'is_internal_only',
        'is_external',
        'is_published',
        'posting_date',
        'application_deadline',
        'target_start_date',
        'expected_closure_date',
        'actual_closure_date',
        'location_city',
        'location_state',
        'location_country',
        'available_workstations',
        'preferred_workstation_type',
        'workstation_facilities_required',
        'min_experience_years',
        'max_experience_years',
        'min_education_level',
        'preferred_education_level',
        'required_skills',
        'preferred_skills',
        'required_certifications',
        'preferred_certifications',
        'salary_range_min',
        'salary_range_max',
        'salary_currency',
        'salary_negotiable',
        'benefits_offered',
        'perks',
        'bonus_eligible',
        'equity_offered',
        'relocation_assistance',
        'contact_person_name',
        'contact_person_title',
        'contact_person_email',
        'contact_person_phone',
        'contact_person_mobile',
        'contact_department',
        'alternate_contact_name',
        'alternate_contact_email',
        'alternate_contact_phone',
        'hr_contact_name',
        'hr_contact_email',
        'hr_contact_phone',
        'application_email',
        'application_url',
        'application_method',
        'application_instructions',
        'required_documents',
        'screening_process',
        'interview_rounds',
        'selection_timeline',
        'assessment_required',
        'assessment_type',
        'background_check_required',
        'reference_check_required',
        'portfolio_required',
        'work_samples_required',
        'total_applications',
        'shortlisted_count',
        'interviewed_count',
        'offered_count',
        'accepted_count',
        'rejected_count',
        'withdrawn_count',
        'last_application_date',
        'vacancy_status',
        'approval_status',
        'posting_status',
        'approved_by',
        'approved_date',
        'created_by_user_id',
        'hiring_manager_id',
        'recruiter_id',
        'hr_partner_id',
        'posted_on_website',
        'posted_on_job_boards',
        'job_board_urls',
        'posted_on_social_media',
        'social_media_urls',
        'internal_posting',
        'external_posting',
        'recruitment_source',
        'advertising_budget',
        'recruitment_cost',
        'cost_per_hire',
        'time_to_fill',
        'quality_of_hire',
        'offer_acceptance_rate',
        'applicant_satisfaction_score',
        'hiring_manager_satisfaction',
        'recruitment_notes',
        'internal_notes',
        'tags',
        'keywords',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'seo_optimized',
        'view_count',
        'click_count',
        'conversion_rate',
        'status',
        'is_active',
        'effective_date',
        'expiry_date',
        'auto_close_on_deadline',
        'auto_renew',
        'renewal_interval_days',
        'last_renewed_date',
        'remarks',
        'notes',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    // Vacancy Status
    const STATUS_DRAFT = 'Draft';
    const STATUS_PENDING_APPROVAL = 'Pending Approval';
    const STATUS_APPROVED = 'Approved';
    const STATUS_POSTED = 'Posted';
    const STATUS_ACTIVE = 'Active';
    const STATUS_ON_HOLD = 'On Hold';
    const STATUS_FILLED = 'Filled';
    const STATUS_CLOSED = 'Closed';
    const STATUS_CANCELLED = 'Cancelled';
    const STATUS_EXPIRED = 'Expired';

    // Vacancy Types
    const TYPE_PERMANENT = 'Permanent';
    const TYPE_TEMPORARY = 'Temporary';
    const TYPE_CONTRACT = 'Contract';
    const TYPE_INTERN = 'Intern';
    const TYPE_CONSULTANT = 'Consultant';
    const TYPE_PART_TIME = 'Part-time';
    const TYPE_FULL_TIME = 'Full-time';

    // Priority Levels
    const PRIORITY_LOW = 'Low';
    const PRIORITY_MEDIUM = 'Medium';
    const PRIORITY_HIGH = 'High';
    const PRIORITY_CRITICAL = 'Critical';

    // Application Methods
    const METHOD_EMAIL = 'Email';
    const METHOD_ONLINE_FORM = 'Online Form';
    const METHOD_CAREER_PORTAL = 'Career Portal';
    const METHOD_IN_PERSON = 'In Person';
    const METHOD_THIRD_PARTY = 'Third Party System';

    public function __construct() {
        parent::__construct();
        $this->attributes['vacancy_status'] = self::STATUS_DRAFT;
        $this->attributes['is_active'] = 1;
        $this->attributes['is_published'] = 0;
        $this->attributes['is_featured'] = 0;
        $this->attributes['is_confidential'] = 0;
        $this->attributes['is_urgent'] = 0;
        $this->attributes['number_of_openings'] = 1;
        $this->attributes['filled_count'] = 0;
        $this->attributes['remaining_openings'] = 1;
        $this->attributes['total_applications'] = 0;
        $this->attributes['shortlisted_count'] = 0;
        $this->attributes['interviewed_count'] = 0;
        $this->attributes['offered_count'] = 0;
        $this->attributes['accepted_count'] = 0;
        $this->attributes['rejected_count'] = 0;
        $this->attributes['withdrawn_count'] = 0;
        $this->attributes['view_count'] = 0;
        $this->attributes['click_count'] = 0;
        $this->attributes['salary_negotiable'] = 0;
        $this->attributes['auto_close_on_deadline'] = 1;
        $this->attributes['auto_renew'] = 0;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    // ==================== Relationship Methods ====================

    /**
     * Get the organization this vacancy belongs to
     */
    public function getOrganization() {
        if (!$this->organization_id) return null;

        require_once __DIR__ . '/Organization.php';
        return Organization::find($this->organization_id);
    }

    public function getOrganizationName() {
        $org = $this->getOrganization();
        return $org ? $org->getDisplayName() : 'No Organization';
    }

    /**
     * Get the position this vacancy is for
     */
    public function getPosition() {
        if (!$this->organization_position_id) return null;

        require_once __DIR__ . '/OrganizationPosition.php';
        return OrganizationPosition::find($this->organization_position_id);
    }

    public function getPositionTitle() {
        $position = $this->getPosition();
        return $position ? $position->position_title : 'No Position';
    }

    public function getPositionDetails() {
        return $this->getPosition();
    }

    /**
     * Get available workstations for this vacancy
     */
    public function getAvailableWorkstations() {
        if (!$this->available_workstations) return [];

        $workstationIds = json_decode($this->available_workstations, true);
        if (!is_array($workstationIds)) return [];

        require_once __DIR__ . '/OrganizationWorkstation.php';
        $workstations = [];
        foreach ($workstationIds as $id) {
            $workstation = OrganizationWorkstation::find($id);
            if ($workstation) {
                $workstations[] = $workstation;
            }
        }
        return $workstations;
    }

    /**
     * Get workstation IDs
     */
    public function getWorkstationIds() {
        if (!$this->available_workstations) return [];
        $ids = json_decode($this->available_workstations, true);
        return is_array($ids) ? $ids : [];
    }

    /**
     * Set available workstations
     */
    public function setAvailableWorkstations($workstationIds) {
        if (is_array($workstationIds)) {
            $this->available_workstations = json_encode(array_values($workstationIds));
        } else {
            $this->available_workstations = $workstationIds;
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    /**
     * Add a workstation
     */
    public function addWorkstation($workstationId) {
        $workstations = $this->getWorkstationIds();
        if (!in_array($workstationId, $workstations)) {
            $workstations[] = $workstationId;
            $this->setAvailableWorkstations($workstations);
            return $this->save();
        }
        return true;
    }

    /**
     * Remove a workstation
     */
    public function removeWorkstation($workstationId) {
        $workstations = $this->getWorkstationIds();
        $workstations = array_filter($workstations, function($id) use ($workstationId) {
            return $id != $workstationId;
        });
        $this->setAvailableWorkstations(array_values($workstations));
        return $this->save();
    }

    /**
     * Get hiring manager
     */
    public function getHiringManager() {
        if (!$this->hiring_manager_id) return null;

        require_once __DIR__ . '/User.php';
        return User::find($this->hiring_manager_id);
    }

    /**
     * Get recruiter
     */
    public function getRecruiter() {
        if (!$this->recruiter_id) return null;

        require_once __DIR__ . '/User.php';
        return User::find($this->recruiter_id);
    }

    // ==================== Status Management ====================

    public function activate() {
        $this->status = 'Active';
        $this->is_active = 1;
        $this->vacancy_status = self::STATUS_ACTIVE;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function deactivate() {
        $this->status = 'Inactive';
        $this->is_active = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function approve() {
        $this->vacancy_status = self::STATUS_APPROVED;
        $this->approval_status = 'Approved';
        $this->approved_date = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function post() {
        $this->vacancy_status = self::STATUS_POSTED;
        $this->posting_status = 'Posted';
        $this->posting_date = date('Y-m-d H:i:s');
        $this->is_published = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function hold() {
        $this->vacancy_status = self::STATUS_ON_HOLD;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function markAsFilled() {
        $this->vacancy_status = self::STATUS_FILLED;
        $this->actual_closure_date = date('Y-m-d H:i:s');
        $this->is_active = 0;
        $this->is_published = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function close() {
        $this->vacancy_status = self::STATUS_CLOSED;
        $this->actual_closure_date = date('Y-m-d H:i:s');
        $this->is_active = 0;
        $this->is_published = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function cancel() {
        $this->vacancy_status = self::STATUS_CANCELLED;
        $this->actual_closure_date = date('Y-m-d H:i:s');
        $this->is_active = 0;
        $this->is_published = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function markAsExpired() {
        $this->vacancy_status = self::STATUS_EXPIRED;
        $this->is_active = 0;
        $this->is_published = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function publish() {
        $this->is_published = 1;
        $this->posting_date = $this->posting_date ?: date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function unpublish() {
        $this->is_published = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function markAsFeatured() {
        $this->is_featured = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function markAsUrgent() {
        $this->is_urgent = 1;
        $this->priority_level = self::PRIORITY_CRITICAL;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // ==================== Application Tracking ====================

    public function incrementApplicationCount() {
        $this->total_applications = ($this->total_applications ?? 0) + 1;
        $this->last_application_date = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function incrementShortlistedCount() {
        $this->shortlisted_count = ($this->shortlisted_count ?? 0) + 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function incrementInterviewedCount() {
        $this->interviewed_count = ($this->interviewed_count ?? 0) + 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function incrementOfferedCount() {
        $this->offered_count = ($this->offered_count ?? 0) + 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function incrementAcceptedCount() {
        $this->accepted_count = ($this->accepted_count ?? 0) + 1;
        $this->filled_count = ($this->filled_count ?? 0) + 1;
        $this->remaining_openings = max(0, $this->number_of_openings - $this->filled_count);

        // Auto-close if all positions filled
        if ($this->remaining_openings == 0 && $this->auto_close_on_deadline) {
            $this->markAsFilled();
        }

        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function incrementRejectedCount() {
        $this->rejected_count = ($this->rejected_count ?? 0) + 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function incrementWithdrawnCount() {
        $this->withdrawn_count = ($this->withdrawn_count ?? 0) + 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function incrementViewCount() {
        $this->view_count = ($this->view_count ?? 0) + 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function incrementClickCount() {
        $this->click_count = ($this->click_count ?? 0) + 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // ==================== Business Logic Methods ====================

    /**
     * Generate vacancy code
     */
    public function generateVacancyCode() {
        if ($this->vacancy_code) return $this->vacancy_code;

        $orgCode = 'ORG';
        if ($this->organization_id) {
            $org = $this->getOrganization();
            if ($org && $org->code) {
                $orgCode = substr($org->code, 0, 3);
            }
        }

        $year = date('Y');
        $sequence = str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
        return strtoupper($orgCode . '-VAC-' . $year . '-' . $sequence);
    }

    /**
     * Generate slug
     */
    public function generateSlug($title = null) {
        $title = $title ?: $this->vacancy_title;
        if (!$title) return '';

        $slug = strtolower(trim($title));
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
            $existing = array_filter($existing, function($v) {
                return $v->id !== $this->id;
            });
        }

        return !empty($existing);
    }

    /**
     * Check if vacancy is expired
     */
    public function isExpired() {
        if ($this->application_deadline) {
            $deadline = new DateTime($this->application_deadline);
            $now = new DateTime();
            return $now > $deadline;
        }
        return false;
    }

    /**
     * Get days until deadline
     */
    public function getDaysUntilDeadline() {
        if ($this->application_deadline) {
            $deadline = new DateTime($this->application_deadline);
            $now = new DateTime();
            $diff = $now->diff($deadline);
            return $diff->invert ? 0 : $diff->days;
        }
        return null;
    }

    /**
     * Check if vacancy is open
     */
    public function isOpen() {
        return $this->is_active &&
               $this->is_published &&
               in_array($this->vacancy_status, [self::STATUS_ACTIVE, self::STATUS_POSTED]) &&
               !$this->isExpired() &&
               $this->remaining_openings > 0;
    }

    /**
     * Check if accepting applications
     */
    public function isAcceptingApplications() {
        return $this->isOpen();
    }

    /**
     * Get salary range formatted
     */
    public function getSalaryRange() {
        if (!$this->salary_range_min && !$this->salary_range_max) {
            return 'Not specified';
        }

        $currency = $this->salary_currency ?: 'USD';
        $min = $this->salary_range_min ? number_format($this->salary_range_min) : '?';
        $max = $this->salary_range_max ? number_format($this->salary_range_max) : '?';

        $range = "{$currency} {$min} - {$max}";

        if ($this->salary_negotiable) {
            $range .= ' (Negotiable)';
        }

        return $range;
    }

    /**
     * Get experience range formatted
     */
    public function getExperienceRange() {
        if (!$this->min_experience_years && !$this->max_experience_years) {
            return 'Not specified';
        }

        $min = $this->min_experience_years ?? 0;
        $max = $this->max_experience_years ?? '?';

        return "{$min}-{$max} years";
    }

    /**
     * Calculate conversion rate
     */
    public function calculateConversionRate() {
        if ($this->view_count > 0) {
            $this->conversion_rate = round(($this->total_applications / $this->view_count) * 100, 2);
            return $this->conversion_rate;
        }
        return 0;
    }

    /**
     * Calculate time to fill
     */
    public function calculateTimeToFill() {
        if ($this->posting_date && $this->actual_closure_date) {
            $postDate = new DateTime($this->posting_date);
            $closeDate = new DateTime($this->actual_closure_date);
            $diff = $postDate->diff($closeDate);
            $this->time_to_fill = $diff->days;
            return $this->time_to_fill;
        }
        return null;
    }

    /**
     * Get contact information
     */
    public function getContactInfo() {
        return [
            'primary' => [
                'name' => $this->contact_person_name,
                'title' => $this->contact_person_title,
                'email' => $this->contact_person_email,
                'phone' => $this->contact_person_phone,
                'mobile' => $this->contact_person_mobile,
                'department' => $this->contact_department
            ],
            'alternate' => [
                'name' => $this->alternate_contact_name,
                'email' => $this->alternate_contact_email,
                'phone' => $this->alternate_contact_phone
            ],
            'hr' => [
                'name' => $this->hr_contact_name,
                'email' => $this->hr_contact_email,
                'phone' => $this->hr_contact_phone
            ],
            'application' => [
                'email' => $this->application_email,
                'url' => $this->application_url,
                'method' => $this->application_method
            ]
        ];
    }

    /**
     * Renew vacancy
     */
    public function renew($days = null) {
        $days = $days ?: $this->renewal_interval_days ?: 30;

        $this->application_deadline = date('Y-m-d', strtotime("+{$days} days"));
        $this->last_renewed_date = date('Y-m-d H:i:s');
        $this->vacancy_status = self::STATUS_ACTIVE;
        $this->is_active = 1;
        $this->is_published = 1;
        $this->updated_at = date('Y-m-d H:i:s');

        return $this->save();
    }

    // ==================== Static Query Methods ====================

    public static function findByOrganization($organizationId) {
        return static::where('organization_id', '=', $organizationId);
    }

    public static function findByPosition($positionId) {
        return static::where('organization_position_id', '=', $positionId);
    }

    public static function findActive() {
        $vacancies = static::where('is_active', '=', 1);
        return array_filter($vacancies, function($v) {
            return !$v->isExpired();
        });
    }

    public static function findOpen() {
        $vacancies = static::all();
        return array_filter($vacancies, function($v) {
            return $v->isOpen();
        });
    }

    public static function findPublished() {
        return static::where('is_published', '=', 1);
    }

    public static function findFeatured() {
        return static::where('is_featured', '=', 1);
    }

    public static function findUrgent() {
        return static::where('is_urgent', '=', 1);
    }

    public static function findByStatus($status) {
        return static::where('vacancy_status', '=', $status);
    }

    public static function findExpiring($days = 7) {
        $vacancies = static::findActive();
        return array_filter($vacancies, function($v) use ($days) {
            $remaining = $v->getDaysUntilDeadline();
            return $remaining !== null && $remaining <= $days && $remaining > 0;
        });
    }

    public static function searchVacancies($query) {
        $vacancies = static::all();
        $query = strtolower($query);

        return array_filter($vacancies, function($v) use ($query) {
            return strpos(strtolower($v->vacancy_title ?: ''), $query) !== false ||
                   strpos(strtolower($v->vacancy_code ?: ''), $query) !== false ||
                   strpos(strtolower($v->description ?: ''), $query) !== false;
        });
    }

    // ==================== Validation ====================

    public function validate() {
        $errors = [];

        if (empty($this->attributes['vacancy_title'])) {
            $errors[] = "Vacancy title is required";
        }

        if (empty($this->attributes['organization_id'])) {
            $errors[] = "Organization is required";
        }

        if (empty($this->attributes['organization_position_id'])) {
            $errors[] = "Position is required";
        }

        if (empty($this->attributes['number_of_openings']) || $this->attributes['number_of_openings'] < 1) {
            $errors[] = "Number of openings must be at least 1";
        }

        if (!empty($this->attributes['application_deadline'])) {
            $deadline = new DateTime($this->attributes['application_deadline']);
            $now = new DateTime();
            if ($deadline <= $now) {
                $errors[] = "Application deadline must be in the future";
            }
        }

        if (!empty($this->attributes['salary_range_min']) && !empty($this->attributes['salary_range_max'])) {
            if ($this->attributes['salary_range_min'] > $this->attributes['salary_range_max']) {
                $errors[] = "Minimum salary cannot be greater than maximum salary";
            }
        }

        return $errors;
    }

    // ==================== Lifecycle Hooks ====================

    public function beforeSave() {
        // Generate vacancy code if not provided
        if (empty($this->attributes['vacancy_code'])) {
            $this->attributes['vacancy_code'] = $this->generateVacancyCode();
        }

        // Generate slug if not provided
        if (empty($this->attributes['slug']) && !empty($this->attributes['vacancy_title'])) {
            $this->attributes['slug'] = $this->generateSlug();
        }

        // Calculate remaining openings
        if (isset($this->attributes['number_of_openings']) && isset($this->attributes['filled_count'])) {
            $this->attributes['remaining_openings'] = max(0, $this->attributes['number_of_openings'] - $this->attributes['filled_count']);
        }

        // Auto-expire if past deadline
        if ($this->isExpired() && $this->auto_close_on_deadline && $this->is_active) {
            $this->attributes['vacancy_status'] = self::STATUS_EXPIRED;
            $this->attributes['is_active'] = 0;
            $this->attributes['is_published'] = 0;
        }

        // Set timestamps
        if (empty($this->attributes['created_at'])) {
            $this->attributes['created_at'] = date('Y-m-d H:i:s');
        }
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');

        return true;
    }

    public function afterSave() {
        error_log("OrganizationVacancy {$this->id} ({$this->vacancy_title}) saved successfully");
        return true;
    }

    // ==================== Schema ====================

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS organization_vacancies (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                organization_id INTEGER NOT NULL,
                organization_position_id INTEGER NOT NULL,
                vacancy_code TEXT UNIQUE,
                vacancy_title TEXT NOT NULL,
                slug TEXT UNIQUE,
                description TEXT,
                summary TEXT,
                requirements TEXT,
                responsibilities TEXT,
                number_of_openings INTEGER DEFAULT 1,
                filled_count INTEGER DEFAULT 0,
                remaining_openings INTEGER DEFAULT 1,
                vacancy_type TEXT DEFAULT 'Permanent',
                employment_type TEXT DEFAULT 'Full-time',
                work_mode TEXT DEFAULT 'Onsite',
                priority_level TEXT DEFAULT 'Medium',
                urgency_level TEXT,
                is_urgent INTEGER DEFAULT 0,
                is_featured INTEGER DEFAULT 0,
                is_confidential INTEGER DEFAULT 0,
                is_internal_only INTEGER DEFAULT 0,
                is_external INTEGER DEFAULT 1,
                is_published INTEGER DEFAULT 0,
                posting_date DATETIME,
                application_deadline DATE,
                target_start_date DATE,
                expected_closure_date DATE,
                actual_closure_date DATE,
                location_city TEXT,
                location_state TEXT,
                location_country TEXT,
                available_workstations TEXT,
                preferred_workstation_type TEXT,
                workstation_facilities_required TEXT,
                min_experience_years INTEGER,
                max_experience_years INTEGER,
                min_education_level TEXT,
                preferred_education_level TEXT,
                required_skills TEXT,
                preferred_skills TEXT,
                required_certifications TEXT,
                preferred_certifications TEXT,
                salary_range_min DECIMAL(15,2),
                salary_range_max DECIMAL(15,2),
                salary_currency TEXT DEFAULT 'USD',
                salary_negotiable INTEGER DEFAULT 0,
                benefits_offered TEXT,
                perks TEXT,
                bonus_eligible INTEGER DEFAULT 0,
                equity_offered INTEGER DEFAULT 0,
                relocation_assistance INTEGER DEFAULT 0,
                contact_person_name TEXT,
                contact_person_title TEXT,
                contact_person_email TEXT,
                contact_person_phone TEXT,
                contact_person_mobile TEXT,
                contact_department TEXT,
                alternate_contact_name TEXT,
                alternate_contact_email TEXT,
                alternate_contact_phone TEXT,
                hr_contact_name TEXT,
                hr_contact_email TEXT,
                hr_contact_phone TEXT,
                application_email TEXT,
                application_url TEXT,
                application_method TEXT DEFAULT 'Email',
                application_instructions TEXT,
                required_documents TEXT,
                screening_process TEXT,
                interview_rounds INTEGER,
                selection_timeline TEXT,
                assessment_required INTEGER DEFAULT 0,
                assessment_type TEXT,
                background_check_required INTEGER DEFAULT 0,
                reference_check_required INTEGER DEFAULT 0,
                portfolio_required INTEGER DEFAULT 0,
                work_samples_required INTEGER DEFAULT 0,
                total_applications INTEGER DEFAULT 0,
                shortlisted_count INTEGER DEFAULT 0,
                interviewed_count INTEGER DEFAULT 0,
                offered_count INTEGER DEFAULT 0,
                accepted_count INTEGER DEFAULT 0,
                rejected_count INTEGER DEFAULT 0,
                withdrawn_count INTEGER DEFAULT 0,
                last_application_date DATETIME,
                vacancy_status TEXT DEFAULT 'Draft',
                approval_status TEXT,
                posting_status TEXT,
                approved_by INTEGER,
                approved_date DATETIME,
                created_by_user_id INTEGER,
                hiring_manager_id INTEGER,
                recruiter_id INTEGER,
                hr_partner_id INTEGER,
                posted_on_website INTEGER DEFAULT 0,
                posted_on_job_boards INTEGER DEFAULT 0,
                job_board_urls TEXT,
                posted_on_social_media INTEGER DEFAULT 0,
                social_media_urls TEXT,
                internal_posting INTEGER DEFAULT 0,
                external_posting INTEGER DEFAULT 1,
                recruitment_source TEXT,
                advertising_budget DECIMAL(10,2),
                recruitment_cost DECIMAL(10,2),
                cost_per_hire DECIMAL(10,2),
                time_to_fill INTEGER,
                quality_of_hire DECIMAL(5,2),
                offer_acceptance_rate DECIMAL(5,2),
                applicant_satisfaction_score DECIMAL(5,2),
                hiring_manager_satisfaction DECIMAL(5,2),
                recruitment_notes TEXT,
                internal_notes TEXT,
                tags TEXT,
                keywords TEXT,
                meta_title TEXT,
                meta_description TEXT,
                meta_keywords TEXT,
                seo_optimized INTEGER DEFAULT 0,
                view_count INTEGER DEFAULT 0,
                click_count INTEGER DEFAULT 0,
                conversion_rate DECIMAL(5,2),
                status TEXT DEFAULT 'Active',
                is_active INTEGER DEFAULT 1,
                effective_date DATE,
                expiry_date DATE,
                auto_close_on_deadline INTEGER DEFAULT 1,
                auto_renew INTEGER DEFAULT 0,
                renewal_interval_days INTEGER DEFAULT 30,
                last_renewed_date DATE,
                remarks TEXT,
                notes TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                created_by INTEGER,
                updated_by INTEGER,
                FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE,
                FOREIGN KEY (organization_position_id) REFERENCES organization_positions (id) ON DELETE CASCADE,
                FOREIGN KEY (hiring_manager_id) REFERENCES users (id) ON DELETE SET NULL,
                FOREIGN KEY (recruiter_id) REFERENCES users (id) ON DELETE SET NULL,
                FOREIGN KEY (hr_partner_id) REFERENCES users (id) ON DELETE SET NULL,
                FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL,
                FOREIGN KEY (updated_by) REFERENCES users (id) ON DELETE SET NULL
            )
        ";
    }

    public function toArray() {
        $data = parent::toArray();

        // Add computed fields
        $data['organization_name'] = $this->getOrganizationName();
        $data['position_title'] = $this->getPositionTitle();
        $data['salary_range_formatted'] = $this->getSalaryRange();
        $data['experience_range_formatted'] = $this->getExperienceRange();
        $data['is_open'] = $this->isOpen();
        $data['is_expired'] = $this->isExpired();
        $data['days_until_deadline'] = $this->getDaysUntilDeadline();
        $data['contact_info'] = $this->getContactInfo();
        $data['workstation_ids'] = $this->getWorkstationIds();

        return $data;
    }
}
?>
