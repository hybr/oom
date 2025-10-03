<?php

require_once __DIR__ . '/BaseEntity.php';

class OrganizationPosition extends BaseEntity {
    protected $table = 'organization_positions';
    protected $fillable = [
        'id',
        'organization_id',
        'popular_organization_department_id',
        'popular_organization_team_id',
        'popular_organization_designation_id',
        'position_code',
        'position_title',
        'slug',
        'description',
        'summary',
        'position_type',
        'employment_type',
        'work_mode',
        'location_type',
        'location_city',
        'location_state',
        'location_country',
        'location_building_id',
        'location_floor',
        'location_area',
        'reports_to_position_id',
        'dotted_line_reports_to_position_id',
        'headcount',
        'current_headcount',
        'vacancy_count',
        'is_vacant',
        'is_critical',
        'is_key_position',
        'is_leadership_position',
        'is_customer_facing',
        'is_billable',
        'min_education_level',
        'min_education_subject_id',
        'preferred_education_level',
        'preferred_education_subject_id',
        'required_qualifications',
        'preferred_qualifications',
        'required_certifications',
        'preferred_certifications',
        'required_skills',
        'preferred_skills',
        'nice_to_have_skills',
        'min_experience_years',
        'max_experience_years',
        'preferred_experience_years',
        'industry_experience',
        'domain_experience',
        'technical_experience',
        'management_experience',
        'responsibilities',
        'key_deliverables',
        'success_metrics',
        'performance_indicators',
        'kpis',
        'objectives',
        'goals',
        'decision_authority',
        'budget_authority',
        'approval_authority',
        'signing_authority',
        'procurement_authority',
        'hiring_authority',
        'termination_authority',
        'compensation_authority',
        'direct_reports_count',
        'indirect_reports_count',
        'dotted_line_reports_count',
        'team_size_min',
        'team_size_max',
        'span_of_control',
        'collaboration_requirements',
        'stakeholder_interaction',
        'internal_contacts',
        'external_contacts',
        'vendor_interaction',
        'client_interaction',
        'travel_requirement',
        'travel_percentage',
        'relocation_required',
        'relocation_assistance',
        'remote_work_eligible',
        'hybrid_work_eligible',
        'flexible_hours',
        'shift_work_required',
        'on_call_required',
        'weekend_work_required',
        'overtime_expected',
        'working_hours',
        'work_schedule',
        'time_zone',
        'physical_requirements',
        'working_conditions',
        'safety_requirements',
        'health_requirements',
        'background_check_required',
        'security_clearance_required',
        'security_clearance_level',
        'drug_test_required',
        'medical_examination_required',
        'salary_grade',
        'salary_band',
        'salary_range_min',
        'salary_range_max',
        'salary_currency',
        'salary_frequency',
        'bonus_eligible',
        'bonus_percentage',
        'commission_eligible',
        'commission_structure',
        'equity_eligible',
        'equity_percentage',
        'stock_options',
        'benefits_tier',
        'benefits_package',
        'perks',
        'allowances',
        'relocation_package',
        'signing_bonus',
        'retention_bonus',
        'performance_bonus',
        'annual_bonus_target',
        'variable_pay_percentage',
        'total_compensation_range',
        'probation_period_days',
        'notice_period_days',
        'contract_duration',
        'contract_type',
        'renewal_terms',
        'extension_possible',
        'conversion_to_permanent',
        'promotion_eligible',
        'promotion_criteria',
        'next_level_position',
        'career_path',
        'growth_opportunities',
        'skill_development_areas',
        'training_requirements',
        'training_budget',
        'certification_reimbursement',
        'education_assistance',
        'conference_attendance',
        'mentoring_available',
        'coaching_available',
        'onboarding_duration_days',
        'onboarding_plan',
        'ramp_up_time_days',
        'performance_review_frequency',
        'feedback_frequency',
        'goal_setting_process',
        'competency_requirements',
        'behavioral_competencies',
        'technical_competencies',
        'functional_competencies',
        'leadership_competencies',
        'soft_skills_required',
        'hard_skills_required',
        'language_requirements',
        'communication_skills',
        'presentation_skills',
        'writing_skills',
        'analytical_skills',
        'problem_solving_skills',
        'decision_making_skills',
        'innovation_requirements',
        'creativity_requirements',
        'strategic_thinking',
        'tactical_execution',
        'operational_knowledge',
        'business_acumen',
        'financial_acumen',
        'technical_depth',
        'breadth_of_knowledge',
        'specialization_required',
        'generalist_approach',
        'cross_functional_skills',
        'tools_technologies',
        'software_proficiency',
        'systems_knowledge',
        'platforms_experience',
        'methodologies',
        'frameworks',
        'best_practices',
        'compliance_requirements',
        'regulatory_knowledge',
        'policy_adherence',
        'governance_requirements',
        'ethics_standards',
        'confidentiality_requirements',
        'data_privacy_requirements',
        'intellectual_property_handling',
        'conflict_of_interest_policy',
        'code_of_conduct',
        'work_environment',
        'team_culture',
        'work_style',
        'pace_of_work',
        'autonomy_level',
        'supervision_level',
        'collaboration_style',
        'communication_style',
        'diversity_inclusion',
        'accessibility_accommodations',
        'reasonable_accommodations',
        'equal_opportunity',
        'affirmative_action',
        'veteran_preference',
        'disability_preference',
        'internal_only',
        'external_candidates',
        'referral_eligible',
        'referral_bonus',
        'recruitment_sources',
        'advertising_channels',
        'headhunter_engaged',
        'recruitment_agency',
        'university_recruiting',
        'campus_hiring',
        'lateral_hiring',
        'rehire_eligible',
        'boomerang_employee',
        'application_deadline',
        'target_start_date',
        'urgency_level',
        'priority_level',
        'requisition_number',
        'job_posting_id',
        'ats_id',
        'created_date',
        'approved_date',
        'posted_date',
        'filled_date',
        'closed_date',
        'position_status',
        'approval_status',
        'posting_status',
        'filling_status',
        'approved_by',
        'created_by_user_id',
        'hiring_manager_id',
        'recruiter_id',
        'hr_business_partner_id',
        'position_justification',
        'business_case',
        'budget_impact',
        'revenue_impact',
        'replacement_position',
        'backfill_position',
        'new_position',
        'expansion_position',
        'project_based',
        'project_duration',
        'seasonal_position',
        'seasonal_dates',
        'temporary_to_permanent',
        'job_family',
        'job_function',
        'job_category',
        'job_sub_category',
        'occupational_code',
        'soc_code',
        'naics_code',
        'flsa_classification',
        'exempt_status',
        'union_position',
        'bargaining_unit',
        'eeoc_category',
        'affirmative_action_plan',
        'succession_planning',
        'succession_candidates',
        'talent_pool',
        'high_potential_track',
        'critical_role',
        'retention_risk',
        'flight_risk_mitigation',
        'knowledge_transfer_plan',
        'cross_training_plan',
        'backup_coverage',
        'business_continuity_role',
        'disaster_recovery_role',
        'emergency_response_role',
        'on_call_rotation',
        'support_coverage',
        'escalation_point',
        'subject_matter_expert',
        'go_to_person',
        'key_relationships',
        'networking_requirements',
        'industry_connections',
        'professional_associations',
        'community_involvement',
        'thought_leadership',
        'public_speaking',
        'media_interaction',
        'social_media_presence',
        'personal_branding',
        'executive_visibility',
        'board_interaction',
        'investor_relations',
        'analyst_relations',
        'partner_relations',
        'alliance_management',
        'ecosystem_participation',
        'market_intelligence',
        'competitive_analysis',
        'trend_monitoring',
        'innovation_contribution',
        'research_participation',
        'patent_contribution',
        'publication_expectation',
        'conference_presentation',
        'webinar_hosting',
        'training_delivery',
        'knowledge_sharing',
        'mentorship_role',
        'coaching_role',
        'committee_participation',
        'task_force_involvement',
        'special_projects',
        'additional_duties',
        'other_responsibilities',
        'position_challenges',
        'growth_areas',
        'development_opportunities',
        'visibility_opportunities',
        'impact_areas',
        'value_creation',
        'strategic_importance',
        'operational_importance',
        'customer_impact',
        'employee_impact',
        'financial_impact',
        'brand_impact',
        'market_impact',
        'competitive_advantage',
        'differentiation',
        'unique_aspects',
        'position_highlights',
        'selling_points',
        'why_join',
        'ideal_candidate',
        'success_profile',
        'cultural_fit',
        'values_alignment',
        'mission_alignment',
        'vision_alignment',
        'status',
        'is_active',
        'is_published',
        'is_featured',
        'is_confidential',
        'effective_date',
        'expiry_date',
        'last_review_date',
        'next_review_date',
        'version',
        'revision_history',
        'change_log',
        'notes',
        'remarks',
        'internal_notes',
        'recruiter_notes',
        'interview_notes',
        'tags',
        'keywords',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'seo_optimized',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    // Education Levels
    const EDUCATION_NONE = 'None';
    const EDUCATION_PRE_PRIMARY = 'Pre Primary';
    const EDUCATION_PRIMARY = 'Primary';
    const EDUCATION_SECONDARY = 'Secondary';
    const EDUCATION_HIGHER_SECONDARY = 'Higher Secondary';
    const EDUCATION_DIPLOMA = 'Diploma';
    const EDUCATION_ASSOCIATE = 'Associate Degree';
    const EDUCATION_BACHELOR = 'Bachelor Degree';
    const EDUCATION_MASTER = 'Master Degree';
    const EDUCATION_DOCTORATE = 'Doctorate';
    const EDUCATION_POST_DOCTORATE = 'Post Doctorate';
    const EDUCATION_PROFESSIONAL = 'Professional Degree';
    const EDUCATION_VOCATIONAL = 'Vocational';
    const EDUCATION_CERTIFICATION = 'Certification';

    // Position Types
    const TYPE_PERMANENT = 'Permanent';
    const TYPE_TEMPORARY = 'Temporary';
    const TYPE_CONTRACT = 'Contract';
    const TYPE_CONSULTANT = 'Consultant';
    const TYPE_INTERN = 'Intern';
    const TYPE_APPRENTICE = 'Apprentice';
    const TYPE_SEASONAL = 'Seasonal';
    const TYPE_PROJECT_BASED = 'Project Based';

    // Employment Types
    const EMPLOYMENT_FULL_TIME = 'Full-time';
    const EMPLOYMENT_PART_TIME = 'Part-time';
    const EMPLOYMENT_CONTRACT = 'Contract';
    const EMPLOYMENT_TEMPORARY = 'Temporary';
    const EMPLOYMENT_INTERN = 'Intern';
    const EMPLOYMENT_CONSULTANT = 'Consultant';
    const EMPLOYMENT_FREELANCE = 'Freelance';

    // Work Modes
    const WORK_MODE_ONSITE = 'Onsite';
    const WORK_MODE_REMOTE = 'Remote';
    const WORK_MODE_HYBRID = 'Hybrid';
    const WORK_MODE_FLEXIBLE = 'Flexible';

    // Position Status
    const STATUS_DRAFT = 'Draft';
    const STATUS_PENDING_APPROVAL = 'Pending Approval';
    const STATUS_APPROVED = 'Approved';
    const STATUS_POSTED = 'Posted';
    const STATUS_ACTIVE = 'Active';
    const STATUS_ON_HOLD = 'On Hold';
    const STATUS_FILLED = 'Filled';
    const STATUS_CLOSED = 'Closed';
    const STATUS_CANCELLED = 'Cancelled';

    public function __construct() {
        parent::__construct();
        $this->attributes['position_status'] = self::STATUS_DRAFT;
        $this->attributes['is_active'] = 1;
        $this->attributes['is_published'] = 0;
        $this->attributes['is_featured'] = 0;
        $this->attributes['is_confidential'] = 0;
        $this->attributes['is_vacant'] = 1;
        $this->attributes['headcount'] = 1;
        $this->attributes['current_headcount'] = 0;
        $this->attributes['vacancy_count'] = 1;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    // ==================== Relationship Methods ====================

    /**
     * Get the organization this position belongs to
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
     * Get the department this position belongs to
     */
    public function getDepartment() {
        if (!$this->popular_organization_department_id) return null;

        require_once __DIR__ . '/PopularOrganizationDepartment.php';
        return PopularOrganizationDepartment::find($this->popular_organization_department_id);
    }

    public function getDepartmentName() {
        $dept = $this->getDepartment();
        return $dept ? $dept->name : 'No Department';
    }

    /**
     * Get the team this position belongs to
     */
    public function getTeam() {
        if (!$this->popular_organization_team_id) return null;

        require_once __DIR__ . '/PopularOrganizationTeam.php';
        return PopularOrganizationTeam::find($this->popular_organization_team_id);
    }

    public function getTeamName() {
        $team = $this->getTeam();
        return $team ? $team->name : 'No Team';
    }

    /**
     * Get the designation for this position
     */
    public function getDesignation() {
        if (!$this->popular_organization_designation_id) return null;

        require_once __DIR__ . '/PopularOrganizationDesignation.php';
        return PopularOrganizationDesignation::find($this->popular_organization_designation_id);
    }

    public function getDesignationName() {
        $designation = $this->getDesignation();
        return $designation ? $designation->name : 'No Designation';
    }

    /**
     * Get minimum education subject
     */
    public function getMinEducationSubject() {
        if (!$this->min_education_subject_id) return null;

        require_once __DIR__ . '/PopularEducationSubject.php';
        return PopularEducationSubject::find($this->min_education_subject_id);
    }

    public function getMinEducationSubjectName() {
        $subject = $this->getMinEducationSubject();
        return $subject ? $subject->name : 'Any';
    }

    /**
     * Get preferred education subject
     */
    public function getPreferredEducationSubject() {
        if (!$this->preferred_education_subject_id) return null;

        require_once __DIR__ . '/PopularEducationSubject.php';
        return PopularEducationSubject::find($this->preferred_education_subject_id);
    }

    /**
     * Get required skills
     */
    public function getRequiredSkills() {
        if (!$this->required_skills) return [];
        $skillIds = json_decode($this->required_skills, true);
        if (!is_array($skillIds)) return [];

        require_once __DIR__ . '/PopularSkills.php';
        $skills = [];
        foreach ($skillIds as $id) {
            $skill = PopularSkills::find($id);
            if ($skill) {
                $skills[] = $skill;
            }
        }
        return $skills;
    }

    /**
     * Get preferred skills
     */
    public function getPreferredSkills() {
        if (!$this->preferred_skills) return [];
        $skillIds = json_decode($this->preferred_skills, true);
        if (!is_array($skillIds)) return [];

        require_once __DIR__ . '/PopularSkills.php';
        $skills = [];
        foreach ($skillIds as $id) {
            $skill = PopularSkills::find($id);
            if ($skill) {
                $skills[] = $skill;
            }
        }
        return $skills;
    }

    /**
     * Get nice-to-have skills
     */
    public function getNiceToHaveSkills() {
        if (!$this->nice_to_have_skills) return [];
        $skillIds = json_decode($this->nice_to_have_skills, true);
        if (!is_array($skillIds)) return [];

        require_once __DIR__ . '/PopularSkills.php';
        $skills = [];
        foreach ($skillIds as $id) {
            $skill = PopularSkills::find($id);
            if ($skill) {
                $skills[] = $skill;
            }
        }
        return $skills;
    }

    /**
     * Get reporting position (manager)
     */
    public function getReportsToPosition() {
        if (!$this->reports_to_position_id) return null;
        return static::find($this->reports_to_position_id);
    }

    /**
     * Get dotted line reporting position
     */
    public function getDottedLineReportsToPosition() {
        if (!$this->dotted_line_reports_to_position_id) return null;
        return static::find($this->dotted_line_reports_to_position_id);
    }

    /**
     * Get subordinate positions (direct reports)
     */
    public function getSubordinatePositions() {
        return static::where('reports_to_position_id', '=', $this->id);
    }

    /**
     * Get dotted line subordinates
     */
    public function getDottedLineSubordinates() {
        return static::where('dotted_line_reports_to_position_id', '=', $this->id);
    }

    // ==================== Skill Management ====================

    /**
     * Set required skills
     */
    public function setRequiredSkills($skillIds) {
        if (is_array($skillIds)) {
            $this->required_skills = json_encode(array_values($skillIds));
        } else {
            $this->required_skills = $skillIds;
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    /**
     * Add a required skill
     */
    public function addRequiredSkill($skillId) {
        $skills = $this->getRequiredSkillIds();
        if (!in_array($skillId, $skills)) {
            $skills[] = $skillId;
            $this->setRequiredSkills($skills);
            return $this->save();
        }
        return true;
    }

    /**
     * Remove a required skill
     */
    public function removeRequiredSkill($skillId) {
        $skills = $this->getRequiredSkillIds();
        $skills = array_filter($skills, function($id) use ($skillId) {
            return $id != $skillId;
        });
        $this->setRequiredSkills(array_values($skills));
        return $this->save();
    }

    /**
     * Get required skill IDs
     */
    public function getRequiredSkillIds() {
        if (!$this->required_skills) return [];
        $skillIds = json_decode($this->required_skills, true);
        return is_array($skillIds) ? $skillIds : [];
    }

    /**
     * Set preferred skills
     */
    public function setPreferredSkills($skillIds) {
        if (is_array($skillIds)) {
            $this->preferred_skills = json_encode(array_values($skillIds));
        } else {
            $this->preferred_skills = $skillIds;
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    /**
     * Set nice-to-have skills
     */
    public function setNiceToHaveSkills($skillIds) {
        if (is_array($skillIds)) {
            $this->nice_to_have_skills = json_encode(array_values($skillIds));
        } else {
            $this->nice_to_have_skills = $skillIds;
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    // ==================== Status Management ====================

    public function activate() {
        $this->status = 'Active';
        $this->is_active = 1;
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
        $this->position_status = self::STATUS_APPROVED;
        $this->approval_status = 'Approved';
        $this->approved_date = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function post() {
        $this->position_status = self::STATUS_POSTED;
        $this->posting_status = 'Posted';
        $this->posted_date = date('Y-m-d H:i:s');
        $this->is_published = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function markAsFilled() {
        $this->position_status = self::STATUS_FILLED;
        $this->filling_status = 'Filled';
        $this->filled_date = date('Y-m-d H:i:s');
        $this->is_vacant = 0;
        $this->vacancy_count = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function close() {
        $this->position_status = self::STATUS_CLOSED;
        $this->closed_date = date('Y-m-d H:i:s');
        $this->is_active = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function markAsVacant() {
        $this->is_vacant = 1;
        $this->vacancy_count = max(1, $this->headcount - $this->current_headcount);
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function markAsCritical() {
        $this->is_critical = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function markAsFeatured() {
        $this->is_featured = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // ==================== Business Logic Methods ====================

    /**
     * Generate position code
     */
    public function generatePositionCode() {
        if ($this->position_code) return $this->position_code;

        $orgCode = 'ORG';
        if ($this->organization_id) {
            $org = $this->getOrganization();
            if ($org && $org->code) {
                $orgCode = substr($org->code, 0, 3);
            }
        }

        $deptCode = 'DEP';
        if ($this->popular_organization_department_id) {
            $dept = $this->getDepartment();
            if ($dept && $dept->code) {
                $deptCode = substr($dept->code, 0, 3);
            }
        }

        $sequence = str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
        return strtoupper($orgCode . '-' . $deptCode . '-' . $sequence);
    }

    /**
     * Generate slug
     */
    public function generateSlug($title = null) {
        $title = $title ?: $this->position_title;
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
            $existing = array_filter($existing, function($p) {
                return $p->id !== $this->id;
            });
        }

        return !empty($existing);
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

        return "{$currency} {$min} - {$max}";
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
     * Check if position is leadership role
     */
    public function isLeadershipPosition() {
        return $this->is_leadership_position == 1 || $this->direct_reports_count > 0;
    }

    /**
     * Check if position is customer facing
     */
    public function isCustomerFacing() {
        return $this->is_customer_facing == 1;
    }

    /**
     * Check if position is remote eligible
     */
    public function isRemoteEligible() {
        return $this->remote_work_eligible == 1;
    }

    /**
     * Update headcount
     */
    public function updateHeadcount($current) {
        $this->current_headcount = max(0, intval($current));
        $this->vacancy_count = max(0, $this->headcount - $this->current_headcount);
        $this->is_vacant = ($this->vacancy_count > 0) ? 1 : 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // ==================== Static Query Methods ====================

    public static function findByOrganization($organizationId) {
        return static::where('organization_id', '=', $organizationId);
    }

    public static function findByDepartment($departmentId) {
        return static::where('popular_organization_department_id', '=', $departmentId);
    }

    public static function findByTeam($teamId) {
        return static::where('popular_organization_team_id', '=', $teamId);
    }

    public static function findByDesignation($designationId) {
        return static::where('popular_organization_designation_id', '=', $designationId);
    }

    public static function findVacant() {
        return static::where('is_vacant', '=', 1);
    }

    public static function findCritical() {
        return static::where('is_critical', '=', 1);
    }

    public static function findActive() {
        return static::where('is_active', '=', 1);
    }

    public static function findByStatus($status) {
        return static::where('position_status', '=', $status);
    }

    public static function findByWorkMode($workMode) {
        return static::where('work_mode', '=', $workMode);
    }

    public static function findRemoteEligible() {
        return static::where('remote_work_eligible', '=', 1);
    }

    public static function searchPositions($query) {
        $positions = static::all();
        $query = strtolower($query);

        return array_filter($positions, function($p) use ($query) {
            return strpos(strtolower($p->position_title ?: ''), $query) !== false ||
                   strpos(strtolower($p->position_code ?: ''), $query) !== false ||
                   strpos(strtolower($p->description ?: ''), $query) !== false;
        });
    }

    // ==================== Validation ====================

    public function validate() {
        $errors = [];

        if (empty($this->attributes['position_title'])) {
            $errors[] = "Position title is required";
        }

        if (empty($this->attributes['organization_id'])) {
            $errors[] = "Organization is required";
        }

        if (empty($this->attributes['popular_organization_designation_id'])) {
            $errors[] = "Designation is required";
        }

        if (!empty($this->attributes['salary_range_min']) && !empty($this->attributes['salary_range_max'])) {
            if ($this->attributes['salary_range_min'] > $this->attributes['salary_range_max']) {
                $errors[] = "Minimum salary cannot be greater than maximum salary";
            }
        }

        if (!empty($this->attributes['min_experience_years']) && !empty($this->attributes['max_experience_years'])) {
            if ($this->attributes['min_experience_years'] > $this->attributes['max_experience_years']) {
                $errors[] = "Minimum experience cannot be greater than maximum experience";
            }
        }

        return $errors;
    }

    // ==================== Lifecycle Hooks ====================

    public function beforeSave() {
        // Generate position code if not provided
        if (empty($this->attributes['position_code'])) {
            $this->attributes['position_code'] = $this->generatePositionCode();
        }

        // Generate slug if not provided
        if (empty($this->attributes['slug']) && !empty($this->attributes['position_title'])) {
            $this->attributes['slug'] = $this->generateSlug();
        }

        // Calculate vacancy count
        if (isset($this->attributes['headcount']) && isset($this->attributes['current_headcount'])) {
            $this->attributes['vacancy_count'] = max(0, $this->attributes['headcount'] - $this->attributes['current_headcount']);
            $this->attributes['is_vacant'] = ($this->attributes['vacancy_count'] > 0) ? 1 : 0;
        }

        // Set timestamps
        if (empty($this->attributes['created_at'])) {
            $this->attributes['created_at'] = date('Y-m-d H:i:s');
        }
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');

        return true;
    }

    public function afterSave() {
        error_log("OrganizationPosition {$this->id} ({$this->position_title}) saved successfully");
        return true;
    }

    // ==================== Utility Methods ====================

    public static function getAllEducationLevels() {
        return [
            self::EDUCATION_NONE,
            self::EDUCATION_PRE_PRIMARY,
            self::EDUCATION_PRIMARY,
            self::EDUCATION_SECONDARY,
            self::EDUCATION_HIGHER_SECONDARY,
            self::EDUCATION_DIPLOMA,
            self::EDUCATION_ASSOCIATE,
            self::EDUCATION_BACHELOR,
            self::EDUCATION_MASTER,
            self::EDUCATION_DOCTORATE,
            self::EDUCATION_POST_DOCTORATE,
            self::EDUCATION_PROFESSIONAL,
            self::EDUCATION_VOCATIONAL,
            self::EDUCATION_CERTIFICATION
        ];
    }

    public function toArray() {
        $data = parent::toArray();

        // Add computed fields
        $data['organization_name'] = $this->getOrganizationName();
        $data['department_name'] = $this->getDepartmentName();
        $data['team_name'] = $this->getTeamName();
        $data['designation_name'] = $this->getDesignationName();
        $data['min_education_subject_name'] = $this->getMinEducationSubjectName();
        $data['salary_range_formatted'] = $this->getSalaryRange();
        $data['experience_range_formatted'] = $this->getExperienceRange();
        $data['required_skills_array'] = array_map(function($s) {
            return ['id' => $s->id, 'name' => $s->name];
        }, $this->getRequiredSkills());
        $data['preferred_skills_array'] = array_map(function($s) {
            return ['id' => $s->id, 'name' => $s->name];
        }, $this->getPreferredSkills());
        $data['is_leadership'] = $this->isLeadershipPosition();
        $data['is_remote_eligible'] = $this->isRemoteEligible();

        return $data;
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS organization_positions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                organization_id INTEGER NOT NULL,
                popular_organization_department_id INTEGER,
                popular_organization_team_id INTEGER,
                popular_organization_designation_id INTEGER NOT NULL,
                position_code TEXT UNIQUE,
                position_title TEXT NOT NULL,
                slug TEXT UNIQUE,
                description TEXT,
                summary TEXT,
                position_type TEXT DEFAULT 'Permanent',
                employment_type TEXT DEFAULT 'Full-time',
                work_mode TEXT DEFAULT 'Onsite',
                location_type TEXT,
                location_city TEXT,
                location_state TEXT,
                location_country TEXT,
                location_building_id INTEGER,
                location_floor INTEGER,
                location_area TEXT,
                reports_to_position_id INTEGER,
                dotted_line_reports_to_position_id INTEGER,
                headcount INTEGER DEFAULT 1,
                current_headcount INTEGER DEFAULT 0,
                vacancy_count INTEGER DEFAULT 1,
                is_vacant INTEGER DEFAULT 1,
                is_critical INTEGER DEFAULT 0,
                is_key_position INTEGER DEFAULT 0,
                is_leadership_position INTEGER DEFAULT 0,
                is_customer_facing INTEGER DEFAULT 0,
                is_billable INTEGER DEFAULT 0,
                min_education_level TEXT,
                min_education_subject_id INTEGER,
                preferred_education_level TEXT,
                preferred_education_subject_id INTEGER,
                required_qualifications TEXT,
                preferred_qualifications TEXT,
                required_certifications TEXT,
                preferred_certifications TEXT,
                required_skills TEXT,
                preferred_skills TEXT,
                nice_to_have_skills TEXT,
                min_experience_years INTEGER,
                max_experience_years INTEGER,
                preferred_experience_years INTEGER,
                industry_experience TEXT,
                domain_experience TEXT,
                technical_experience TEXT,
                management_experience TEXT,
                responsibilities TEXT,
                key_deliverables TEXT,
                success_metrics TEXT,
                performance_indicators TEXT,
                kpis TEXT,
                objectives TEXT,
                goals TEXT,
                decision_authority TEXT,
                budget_authority DECIMAL(15,2),
                approval_authority DECIMAL(15,2),
                signing_authority DECIMAL(15,2),
                procurement_authority DECIMAL(15,2),
                hiring_authority INTEGER DEFAULT 0,
                termination_authority INTEGER DEFAULT 0,
                compensation_authority INTEGER DEFAULT 0,
                direct_reports_count INTEGER DEFAULT 0,
                indirect_reports_count INTEGER DEFAULT 0,
                dotted_line_reports_count INTEGER DEFAULT 0,
                team_size_min INTEGER,
                team_size_max INTEGER,
                span_of_control INTEGER,
                collaboration_requirements TEXT,
                stakeholder_interaction TEXT,
                internal_contacts TEXT,
                external_contacts TEXT,
                vendor_interaction TEXT,
                client_interaction TEXT,
                travel_requirement TEXT,
                travel_percentage INTEGER,
                relocation_required INTEGER DEFAULT 0,
                relocation_assistance INTEGER DEFAULT 0,
                remote_work_eligible INTEGER DEFAULT 0,
                hybrid_work_eligible INTEGER DEFAULT 0,
                flexible_hours INTEGER DEFAULT 0,
                shift_work_required INTEGER DEFAULT 0,
                on_call_required INTEGER DEFAULT 0,
                weekend_work_required INTEGER DEFAULT 0,
                overtime_expected INTEGER DEFAULT 0,
                working_hours TEXT,
                work_schedule TEXT,
                time_zone TEXT,
                physical_requirements TEXT,
                working_conditions TEXT,
                safety_requirements TEXT,
                health_requirements TEXT,
                background_check_required INTEGER DEFAULT 0,
                security_clearance_required INTEGER DEFAULT 0,
                security_clearance_level TEXT,
                drug_test_required INTEGER DEFAULT 0,
                medical_examination_required INTEGER DEFAULT 0,
                salary_grade TEXT,
                salary_band TEXT,
                salary_range_min DECIMAL(15,2),
                salary_range_max DECIMAL(15,2),
                salary_currency TEXT DEFAULT 'USD',
                salary_frequency TEXT,
                bonus_eligible INTEGER DEFAULT 0,
                bonus_percentage DECIMAL(5,2),
                commission_eligible INTEGER DEFAULT 0,
                commission_structure TEXT,
                equity_eligible INTEGER DEFAULT 0,
                equity_percentage DECIMAL(5,2),
                stock_options TEXT,
                benefits_tier TEXT,
                benefits_package TEXT,
                perks TEXT,
                allowances TEXT,
                relocation_package TEXT,
                signing_bonus DECIMAL(15,2),
                retention_bonus DECIMAL(15,2),
                performance_bonus DECIMAL(15,2),
                annual_bonus_target DECIMAL(15,2),
                variable_pay_percentage DECIMAL(5,2),
                total_compensation_range TEXT,
                probation_period_days INTEGER,
                notice_period_days INTEGER,
                contract_duration TEXT,
                contract_type TEXT,
                renewal_terms TEXT,
                extension_possible INTEGER DEFAULT 0,
                conversion_to_permanent INTEGER DEFAULT 0,
                promotion_eligible INTEGER DEFAULT 1,
                promotion_criteria TEXT,
                next_level_position TEXT,
                career_path TEXT,
                growth_opportunities TEXT,
                skill_development_areas TEXT,
                training_requirements TEXT,
                training_budget DECIMAL(10,2),
                certification_reimbursement DECIMAL(10,2),
                education_assistance DECIMAL(10,2),
                conference_attendance INTEGER DEFAULT 0,
                mentoring_available INTEGER DEFAULT 0,
                coaching_available INTEGER DEFAULT 0,
                onboarding_duration_days INTEGER,
                onboarding_plan TEXT,
                ramp_up_time_days INTEGER,
                performance_review_frequency TEXT,
                feedback_frequency TEXT,
                goal_setting_process TEXT,
                competency_requirements TEXT,
                behavioral_competencies TEXT,
                technical_competencies TEXT,
                functional_competencies TEXT,
                leadership_competencies TEXT,
                soft_skills_required TEXT,
                hard_skills_required TEXT,
                language_requirements TEXT,
                communication_skills TEXT,
                presentation_skills TEXT,
                writing_skills TEXT,
                analytical_skills TEXT,
                problem_solving_skills TEXT,
                decision_making_skills TEXT,
                innovation_requirements TEXT,
                creativity_requirements TEXT,
                strategic_thinking TEXT,
                tactical_execution TEXT,
                operational_knowledge TEXT,
                business_acumen TEXT,
                financial_acumen TEXT,
                technical_depth TEXT,
                breadth_of_knowledge TEXT,
                specialization_required TEXT,
                generalist_approach TEXT,
                cross_functional_skills TEXT,
                tools_technologies TEXT,
                software_proficiency TEXT,
                systems_knowledge TEXT,
                platforms_experience TEXT,
                methodologies TEXT,
                frameworks TEXT,
                best_practices TEXT,
                compliance_requirements TEXT,
                regulatory_knowledge TEXT,
                policy_adherence TEXT,
                governance_requirements TEXT,
                ethics_standards TEXT,
                confidentiality_requirements TEXT,
                data_privacy_requirements TEXT,
                intellectual_property_handling TEXT,
                conflict_of_interest_policy TEXT,
                code_of_conduct TEXT,
                work_environment TEXT,
                team_culture TEXT,
                work_style TEXT,
                pace_of_work TEXT,
                autonomy_level TEXT,
                supervision_level TEXT,
                collaboration_style TEXT,
                communication_style TEXT,
                diversity_inclusion TEXT,
                accessibility_accommodations TEXT,
                reasonable_accommodations TEXT,
                equal_opportunity TEXT,
                affirmative_action TEXT,
                veteran_preference INTEGER DEFAULT 0,
                disability_preference INTEGER DEFAULT 0,
                internal_only INTEGER DEFAULT 0,
                external_candidates INTEGER DEFAULT 1,
                referral_eligible INTEGER DEFAULT 1,
                referral_bonus DECIMAL(10,2),
                recruitment_sources TEXT,
                advertising_channels TEXT,
                headhunter_engaged INTEGER DEFAULT 0,
                recruitment_agency TEXT,
                university_recruiting INTEGER DEFAULT 0,
                campus_hiring INTEGER DEFAULT 0,
                lateral_hiring INTEGER DEFAULT 1,
                rehire_eligible INTEGER DEFAULT 1,
                boomerang_employee INTEGER DEFAULT 0,
                application_deadline DATE,
                target_start_date DATE,
                urgency_level TEXT,
                priority_level TEXT,
                requisition_number TEXT,
                job_posting_id TEXT,
                ats_id TEXT,
                created_date DATE,
                approved_date DATE,
                posted_date DATE,
                filled_date DATE,
                closed_date DATE,
                position_status TEXT DEFAULT 'Draft',
                approval_status TEXT,
                posting_status TEXT,
                filling_status TEXT,
                approved_by INTEGER,
                created_by_user_id INTEGER,
                hiring_manager_id INTEGER,
                recruiter_id INTEGER,
                hr_business_partner_id INTEGER,
                position_justification TEXT,
                business_case TEXT,
                budget_impact TEXT,
                revenue_impact TEXT,
                replacement_position INTEGER DEFAULT 0,
                backfill_position INTEGER DEFAULT 0,
                new_position INTEGER DEFAULT 1,
                expansion_position INTEGER DEFAULT 0,
                project_based INTEGER DEFAULT 0,
                project_duration TEXT,
                seasonal_position INTEGER DEFAULT 0,
                seasonal_dates TEXT,
                temporary_to_permanent INTEGER DEFAULT 0,
                job_family TEXT,
                job_function TEXT,
                job_category TEXT,
                job_sub_category TEXT,
                occupational_code TEXT,
                soc_code TEXT,
                naics_code TEXT,
                flsa_classification TEXT,
                exempt_status INTEGER,
                union_position INTEGER DEFAULT 0,
                bargaining_unit TEXT,
                eeoc_category TEXT,
                affirmative_action_plan TEXT,
                succession_planning TEXT,
                succession_candidates TEXT,
                talent_pool TEXT,
                high_potential_track INTEGER DEFAULT 0,
                critical_role INTEGER DEFAULT 0,
                retention_risk TEXT,
                flight_risk_mitigation TEXT,
                knowledge_transfer_plan TEXT,
                cross_training_plan TEXT,
                backup_coverage TEXT,
                business_continuity_role TEXT,
                disaster_recovery_role TEXT,
                emergency_response_role TEXT,
                on_call_rotation TEXT,
                support_coverage TEXT,
                escalation_point INTEGER DEFAULT 0,
                subject_matter_expert INTEGER DEFAULT 0,
                go_to_person INTEGER DEFAULT 0,
                key_relationships TEXT,
                networking_requirements TEXT,
                industry_connections TEXT,
                professional_associations TEXT,
                community_involvement TEXT,
                thought_leadership TEXT,
                public_speaking TEXT,
                media_interaction TEXT,
                social_media_presence TEXT,
                personal_branding TEXT,
                executive_visibility TEXT,
                board_interaction TEXT,
                investor_relations TEXT,
                analyst_relations TEXT,
                partner_relations TEXT,
                alliance_management TEXT,
                ecosystem_participation TEXT,
                market_intelligence TEXT,
                competitive_analysis TEXT,
                trend_monitoring TEXT,
                innovation_contribution TEXT,
                research_participation TEXT,
                patent_contribution TEXT,
                publication_expectation TEXT,
                conference_presentation TEXT,
                webinar_hosting TEXT,
                training_delivery TEXT,
                knowledge_sharing TEXT,
                mentorship_role TEXT,
                coaching_role TEXT,
                committee_participation TEXT,
                task_force_involvement TEXT,
                special_projects TEXT,
                additional_duties TEXT,
                other_responsibilities TEXT,
                position_challenges TEXT,
                growth_areas TEXT,
                development_opportunities TEXT,
                visibility_opportunities TEXT,
                impact_areas TEXT,
                value_creation TEXT,
                strategic_importance TEXT,
                operational_importance TEXT,
                customer_impact TEXT,
                employee_impact TEXT,
                financial_impact TEXT,
                brand_impact TEXT,
                market_impact TEXT,
                competitive_advantage TEXT,
                differentiation TEXT,
                unique_aspects TEXT,
                position_highlights TEXT,
                selling_points TEXT,
                why_join TEXT,
                ideal_candidate TEXT,
                success_profile TEXT,
                cultural_fit TEXT,
                values_alignment TEXT,
                mission_alignment TEXT,
                vision_alignment TEXT,
                status TEXT DEFAULT 'Active',
                is_active INTEGER DEFAULT 1,
                is_published INTEGER DEFAULT 0,
                is_featured INTEGER DEFAULT 0,
                is_confidential INTEGER DEFAULT 0,
                effective_date DATE,
                expiry_date DATE,
                last_review_date DATE,
                next_review_date DATE,
                version TEXT,
                revision_history TEXT,
                change_log TEXT,
                notes TEXT,
                remarks TEXT,
                internal_notes TEXT,
                recruiter_notes TEXT,
                interview_notes TEXT,
                tags TEXT,
                keywords TEXT,
                meta_title TEXT,
                meta_description TEXT,
                meta_keywords TEXT,
                seo_optimized INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                created_by INTEGER,
                updated_by INTEGER,
                FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE,
                FOREIGN KEY (popular_organization_department_id) REFERENCES popular_organization_departments (id) ON DELETE SET NULL,
                FOREIGN KEY (popular_organization_team_id) REFERENCES popular_organization_teams (id) ON DELETE SET NULL,
                FOREIGN KEY (popular_organization_designation_id) REFERENCES popular_organization_designations (id) ON DELETE RESTRICT,
                FOREIGN KEY (min_education_subject_id) REFERENCES popular_education_subjects (id) ON DELETE SET NULL,
                FOREIGN KEY (preferred_education_subject_id) REFERENCES popular_education_subjects (id) ON DELETE SET NULL,
                FOREIGN KEY (reports_to_position_id) REFERENCES organization_positions (id) ON DELETE SET NULL,
                FOREIGN KEY (dotted_line_reports_to_position_id) REFERENCES organization_positions (id) ON DELETE SET NULL
            )
        ";
    }
}
?>
