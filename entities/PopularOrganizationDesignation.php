<?php

require_once __DIR__ . '/BaseEntity.php';

class PopularOrganizationDesignation extends BaseEntity {
    protected $table = 'popular_organization_designations';
    protected $fillable = [
        'id',
        'name',
        'code',
        'slug',
        'description',
        'designation_level',
        'hierarchy_level',
        'category',
        'functional_area',
        'job_family',
        'job_subfamily',
        'career_track',
        'seniority_level',
        'grade',
        'band',
        'role_type',
        'employment_type',
        'reports_to_designation_id',
        'min_experience_years',
        'max_experience_years',
        'min_education_level',
        'required_qualifications',
        'preferred_qualifications',
        'certifications_required',
        'certifications_preferred',
        'technical_skills',
        'soft_skills',
        'leadership_skills',
        'domain_knowledge',
        'languages_required',
        'responsibilities',
        'key_accountabilities',
        'performance_metrics',
        'success_criteria',
        'decision_authority',
        'budget_authority',
        'signing_authority',
        'team_size_min',
        'team_size_max',
        'span_of_control',
        'direct_reports',
        'indirect_reports',
        'dotted_line_reports',
        'collaboration_scope',
        'stakeholder_interaction',
        'internal_contacts',
        'external_contacts',
        'travel_requirement',
        'work_location_type',
        'remote_work_eligible',
        'relocation_required',
        'overtime_expected',
        'on_call_required',
        'shift_work_required',
        'physical_requirements',
        'working_conditions',
        'safety_requirements',
        'security_clearance',
        'background_check_level',
        'salary_grade',
        'salary_range_min',
        'salary_range_max',
        'salary_currency',
        'bonus_eligible',
        'commission_eligible',
        'equity_eligible',
        'benefits_tier',
        'compensation_structure',
        'pay_frequency',
        'overtime_policy',
        'leave_entitlement',
        'probation_period',
        'notice_period',
        'contract_type',
        'employment_duration',
        'renewal_terms',
        'promotion_criteria',
        'next_level_designation',
        'career_progression_path',
        'lateral_move_options',
        'skill_development_areas',
        'training_requirements',
        'onboarding_duration',
        'mentoring_available',
        'coaching_support',
        'performance_review_frequency',
        'goal_setting_process',
        'feedback_mechanisms',
        'recognition_programs',
        'succession_planning',
        'talent_pool',
        'high_potential_track',
        'leadership_pipeline',
        'competency_framework',
        'behavioral_competencies',
        'technical_competencies',
        'functional_competencies',
        'leadership_competencies',
        'core_values_alignment',
        'cultural_fit_criteria',
        'diversity_considerations',
        'inclusion_requirements',
        'accessibility_accommodations',
        'work_life_balance',
        'flexibility_options',
        'wellness_support',
        'mental_health_resources',
        'stress_management',
        'ergonomic_support',
        'health_safety_training',
        'emergency_response_role',
        'business_continuity_role',
        'crisis_management_role',
        'risk_management_responsibilities',
        'compliance_obligations',
        'ethical_standards',
        'code_of_conduct',
        'conflict_of_interest_policy',
        'confidentiality_requirements',
        'data_privacy_obligations',
        'intellectual_property_rights',
        'non_compete_clause',
        'non_disclosure_agreement',
        'social_media_policy',
        'personal_conduct_expectations',
        'professional_development_budget',
        'conference_attendance_budget',
        'certification_reimbursement',
        'education_assistance',
        'tuition_reimbursement',
        'book_allowance',
        'technology_allowance',
        'home_office_setup',
        'mobile_phone_allowance',
        'internet_allowance',
        'transportation_allowance',
        'parking_allowance',
        'meal_allowance',
        'uniform_allowance',
        'tool_allowance',
        'equipment_provided',
        'software_licenses',
        'access_rights',
        'system_permissions',
        'application_access',
        'database_access',
        'network_access',
        'building_access',
        'facility_access',
        'lab_access',
        'warehouse_access',
        'restricted_area_access',
        'key_card_level',
        'parking_privileges',
        'office_space_type',
        'workspace_requirements',
        'meeting_room_access',
        'executive_privileges',
        'administrative_support',
        'assistant_allocation',
        'secretary_support',
        'travel_arrangements_support',
        'expense_processing_support',
        'it_support_priority',
        'help_desk_sla',
        'communication_channels',
        'email_signature_format',
        'business_card_design',
        'name_plate_eligibility',
        'directory_listing',
        'org_chart_visibility',
        'title_display_format',
        'linkedin_title',
        'external_representation',
        'media_spokesperson',
        'public_speaking_role',
        'conference_presenter',
        'webinar_host',
        'blog_contributor',
        'whitepaper_author',
        'thought_leadership_role',
        'industry_association_membership',
        'professional_body_membership',
        'community_involvement',
        'volunteer_opportunities',
        'corporate_social_responsibility',
        'mentorship_role',
        'committee_participation',
        'task_force_involvement',
        'project_team_roles',
        'cross_functional_collaboration',
        'matrix_reporting',
        'dual_reporting',
        'shared_services_interaction',
        'center_of_excellence_role',
        'innovation_contribution',
        'continuous_improvement_role',
        'process_ownership',
        'quality_assurance_role',
        'audit_responsibilities',
        'governance_role',
        'policy_development',
        'procedure_documentation',
        'standard_operating_procedures',
        'best_practice_definition',
        'knowledge_management',
        'documentation_requirements',
        'reporting_obligations',
        'dashboard_maintenance',
        'metrics_tracking',
        'kpi_ownership',
        'scorecard_responsibility',
        'analytics_requirements',
        'data_interpretation',
        'insights_generation',
        'recommendation_authority',
        'decision_making_framework',
        'escalation_authority',
        'conflict_resolution_role',
        'negotiation_authority',
        'contract_approval_limit',
        'procurement_authority',
        'vendor_selection_role',
        'supplier_management',
        'partnership_development',
        'client_relationship_management',
        'customer_interaction_level',
        'service_delivery_responsibility',
        'quality_standards_ownership',
        'customer_satisfaction_accountability',
        'complaint_resolution_authority',
        'issue_escalation_protocol',
        'problem_solving_expectations',
        'root_cause_analysis_role',
        'corrective_action_authority',
        'preventive_action_responsibility',
        'continuous_monitoring_role',
        'performance_optimization',
        'efficiency_improvement',
        'productivity_enhancement',
        'cost_reduction_targets',
        'revenue_generation_responsibility',
        'profit_accountability',
        'margin_improvement_role',
        'growth_contribution',
        'market_expansion_role',
        'product_development_involvement',
        'service_innovation_role',
        'technology_adoption_responsibility',
        'digital_transformation_role',
        'automation_implementation',
        'ai_ml_utilization',
        'data_driven_decision_making',
        'agile_methodology_adoption',
        'lean_principles_application',
        'six_sigma_involvement',
        'project_management_methodology',
        'change_management_role',
        'stakeholder_management',
        'communication_responsibility',
        'presentation_requirements',
        'report_writing_expectations',
        'documentation_standards',
        'writing_proficiency_level',
        'verbal_communication_level',
        'interpersonal_skills_level',
        'teamwork_expectations',
        'collaboration_requirements',
        'networking_expectations',
        'relationship_building',
        'influence_without_authority',
        'persuasion_skills',
        'negotiation_skills',
        'conflict_management_skills',
        'emotional_intelligence',
        'adaptability',
        'resilience',
        'stress_tolerance',
        'time_management',
        'prioritization_skills',
        'multitasking_ability',
        'attention_to_detail',
        'analytical_thinking',
        'critical_thinking',
        'creative_thinking',
        'problem_solving_approach',
        'innovation_mindset',
        'entrepreneurial_spirit',
        'ownership_mentality',
        'accountability_culture',
        'results_orientation',
        'achievement_drive',
        'quality_focus',
        'customer_centricity',
        'business_acumen',
        'financial_literacy',
        'commercial_awareness',
        'strategic_thinking',
        'tactical_execution',
        'operational_excellence',
        'service_excellence',
        'technical_excellence',
        'professional_excellence',
        'ethical_conduct',
        'integrity_standards',
        'transparency_expectations',
        'honesty_requirements',
        'trustworthiness',
        'reliability',
        'dependability',
        'consistency',
        'punctuality',
        'attendance_expectations',
        'availability_requirements',
        'responsiveness_standards',
        'turnaround_time_expectations',
        'service_level_commitments',
        'deadline_adherence',
        'milestone_achievement',
        'goal_attainment',
        'target_completion',
        'success_metrics',
        'performance_indicators',
        'evaluation_criteria',
        'rating_scale',
        'ranking_methodology',
        'comparison_benchmarks',
        'industry_standards',
        'market_practices',
        'competitive_positioning',
        'value_proposition',
        'unique_selling_points',
        'differentiation_factors',
        'status',
        'is_active',
        'effective_date',
        'expiry_date',
        'version',
        'last_review_date',
        'next_review_date',
        'approval_status',
        'approved_by',
        'approval_date',
        'remarks',
        'notes',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    // Designation levels
    const LEVEL_EXECUTIVE = 'Executive';
    const LEVEL_SENIOR_MANAGEMENT = 'Senior Management';
    const LEVEL_MIDDLE_MANAGEMENT = 'Middle Management';
    const LEVEL_JUNIOR_MANAGEMENT = 'Junior Management';
    const LEVEL_SENIOR_PROFESSIONAL = 'Senior Professional';
    const LEVEL_PROFESSIONAL = 'Professional';
    const LEVEL_JUNIOR_PROFESSIONAL = 'Junior Professional';
    const LEVEL_ENTRY_LEVEL = 'Entry Level';

    // Categories
    const CATEGORY_LEADERSHIP = 'Leadership';
    const CATEGORY_MANAGEMENT = 'Management';
    const CATEGORY_TECHNICAL = 'Technical';
    const CATEGORY_SPECIALIST = 'Specialist';
    const CATEGORY_SUPPORT = 'Support';
    const CATEGORY_OPERATIONAL = 'Operational';

    // Employment types
    const EMPLOYMENT_FULL_TIME = 'Full-time';
    const EMPLOYMENT_PART_TIME = 'Part-time';
    const EMPLOYMENT_CONTRACT = 'Contract';
    const EMPLOYMENT_TEMPORARY = 'Temporary';
    const EMPLOYMENT_INTERN = 'Intern';
    const EMPLOYMENT_CONSULTANT = 'Consultant';

    protected function getSchema() {
        return "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            code VARCHAR(50) UNIQUE,
            slug VARCHAR(255) UNIQUE,
            description TEXT,
            designation_level VARCHAR(50),
            hierarchy_level INTEGER,
            category VARCHAR(50),
            functional_area VARCHAR(100),
            job_family VARCHAR(100),
            job_subfamily VARCHAR(100),
            career_track VARCHAR(50),
            seniority_level VARCHAR(50),
            grade VARCHAR(20),
            band VARCHAR(20),
            role_type VARCHAR(50),
            employment_type VARCHAR(50) DEFAULT 'Full-time',
            reports_to_designation_id INTEGER,
            min_experience_years INTEGER,
            max_experience_years INTEGER,
            min_education_level VARCHAR(100),
            required_qualifications TEXT,
            preferred_qualifications TEXT,
            certifications_required TEXT,
            certifications_preferred TEXT,
            technical_skills TEXT,
            soft_skills TEXT,
            leadership_skills TEXT,
            domain_knowledge TEXT,
            languages_required TEXT,
            responsibilities TEXT,
            key_accountabilities TEXT,
            performance_metrics TEXT,
            success_criteria TEXT,
            decision_authority TEXT,
            budget_authority DECIMAL(15,2),
            signing_authority DECIMAL(15,2),
            team_size_min INTEGER,
            team_size_max INTEGER,
            span_of_control INTEGER,
            direct_reports INTEGER,
            indirect_reports INTEGER,
            dotted_line_reports INTEGER,
            collaboration_scope TEXT,
            stakeholder_interaction TEXT,
            internal_contacts TEXT,
            external_contacts TEXT,
            travel_requirement VARCHAR(50),
            work_location_type VARCHAR(50),
            remote_work_eligible BOOLEAN DEFAULT 0,
            relocation_required BOOLEAN DEFAULT 0,
            overtime_expected BOOLEAN DEFAULT 0,
            on_call_required BOOLEAN DEFAULT 0,
            shift_work_required BOOLEAN DEFAULT 0,
            physical_requirements TEXT,
            working_conditions TEXT,
            safety_requirements TEXT,
            security_clearance VARCHAR(50),
            background_check_level VARCHAR(50),
            salary_grade VARCHAR(20),
            salary_range_min DECIMAL(15,2),
            salary_range_max DECIMAL(15,2),
            salary_currency VARCHAR(3) DEFAULT 'USD',
            bonus_eligible BOOLEAN DEFAULT 0,
            commission_eligible BOOLEAN DEFAULT 0,
            equity_eligible BOOLEAN DEFAULT 0,
            benefits_tier VARCHAR(20),
            compensation_structure TEXT,
            pay_frequency VARCHAR(20),
            overtime_policy TEXT,
            leave_entitlement TEXT,
            probation_period INTEGER,
            notice_period INTEGER,
            contract_type VARCHAR(50),
            employment_duration VARCHAR(50),
            renewal_terms TEXT,
            promotion_criteria TEXT,
            next_level_designation TEXT,
            career_progression_path TEXT,
            lateral_move_options TEXT,
            skill_development_areas TEXT,
            training_requirements TEXT,
            onboarding_duration INTEGER,
            mentoring_available BOOLEAN DEFAULT 0,
            coaching_support BOOLEAN DEFAULT 0,
            performance_review_frequency VARCHAR(50),
            goal_setting_process TEXT,
            feedback_mechanisms TEXT,
            recognition_programs TEXT,
            succession_planning TEXT,
            talent_pool VARCHAR(50),
            high_potential_track BOOLEAN DEFAULT 0,
            leadership_pipeline TEXT,
            competency_framework TEXT,
            behavioral_competencies TEXT,
            technical_competencies TEXT,
            functional_competencies TEXT,
            leadership_competencies TEXT,
            core_values_alignment TEXT,
            cultural_fit_criteria TEXT,
            diversity_considerations TEXT,
            inclusion_requirements TEXT,
            accessibility_accommodations TEXT,
            work_life_balance TEXT,
            flexibility_options TEXT,
            wellness_support TEXT,
            mental_health_resources TEXT,
            stress_management TEXT,
            ergonomic_support TEXT,
            health_safety_training TEXT,
            emergency_response_role TEXT,
            business_continuity_role TEXT,
            crisis_management_role TEXT,
            risk_management_responsibilities TEXT,
            compliance_obligations TEXT,
            ethical_standards TEXT,
            code_of_conduct TEXT,
            conflict_of_interest_policy TEXT,
            confidentiality_requirements TEXT,
            data_privacy_obligations TEXT,
            intellectual_property_rights TEXT,
            non_compete_clause TEXT,
            non_disclosure_agreement TEXT,
            social_media_policy TEXT,
            personal_conduct_expectations TEXT,
            professional_development_budget DECIMAL(10,2),
            conference_attendance_budget DECIMAL(10,2),
            certification_reimbursement DECIMAL(10,2),
            education_assistance DECIMAL(10,2),
            tuition_reimbursement DECIMAL(10,2),
            book_allowance DECIMAL(8,2),
            technology_allowance DECIMAL(8,2),
            home_office_setup DECIMAL(10,2),
            mobile_phone_allowance DECIMAL(8,2),
            internet_allowance DECIMAL(8,2),
            transportation_allowance DECIMAL(8,2),
            parking_allowance DECIMAL(8,2),
            meal_allowance DECIMAL(8,2),
            uniform_allowance DECIMAL(8,2),
            tool_allowance DECIMAL(8,2),
            equipment_provided TEXT,
            software_licenses TEXT,
            access_rights TEXT,
            system_permissions TEXT,
            application_access TEXT,
            database_access TEXT,
            network_access TEXT,
            building_access TEXT,
            facility_access TEXT,
            lab_access TEXT,
            warehouse_access TEXT,
            restricted_area_access TEXT,
            key_card_level VARCHAR(20),
            parking_privileges TEXT,
            office_space_type VARCHAR(50),
            workspace_requirements TEXT,
            meeting_room_access TEXT,
            executive_privileges TEXT,
            administrative_support TEXT,
            assistant_allocation BOOLEAN DEFAULT 0,
            secretary_support BOOLEAN DEFAULT 0,
            travel_arrangements_support BOOLEAN DEFAULT 0,
            expense_processing_support BOOLEAN DEFAULT 0,
            it_support_priority VARCHAR(20),
            help_desk_sla VARCHAR(50),
            communication_channels TEXT,
            email_signature_format TEXT,
            business_card_design TEXT,
            name_plate_eligibility BOOLEAN DEFAULT 0,
            directory_listing TEXT,
            org_chart_visibility VARCHAR(20),
            title_display_format VARCHAR(100),
            linkedin_title VARCHAR(100),
            external_representation TEXT,
            media_spokesperson BOOLEAN DEFAULT 0,
            public_speaking_role TEXT,
            conference_presenter BOOLEAN DEFAULT 0,
            webinar_host BOOLEAN DEFAULT 0,
            blog_contributor BOOLEAN DEFAULT 0,
            whitepaper_author BOOLEAN DEFAULT 0,
            thought_leadership_role TEXT,
            industry_association_membership TEXT,
            professional_body_membership TEXT,
            community_involvement TEXT,
            volunteer_opportunities TEXT,
            corporate_social_responsibility TEXT,
            mentorship_role TEXT,
            committee_participation TEXT,
            task_force_involvement TEXT,
            project_team_roles TEXT,
            cross_functional_collaboration TEXT,
            matrix_reporting TEXT,
            dual_reporting TEXT,
            shared_services_interaction TEXT,
            center_of_excellence_role TEXT,
            innovation_contribution TEXT,
            continuous_improvement_role TEXT,
            process_ownership TEXT,
            quality_assurance_role TEXT,
            audit_responsibilities TEXT,
            governance_role TEXT,
            policy_development TEXT,
            procedure_documentation TEXT,
            standard_operating_procedures TEXT,
            best_practice_definition TEXT,
            knowledge_management TEXT,
            documentation_requirements TEXT,
            reporting_obligations TEXT,
            dashboard_maintenance TEXT,
            metrics_tracking TEXT,
            kpi_ownership TEXT,
            scorecard_responsibility TEXT,
            analytics_requirements TEXT,
            data_interpretation TEXT,
            insights_generation TEXT,
            recommendation_authority TEXT,
            decision_making_framework TEXT,
            escalation_authority TEXT,
            conflict_resolution_role TEXT,
            negotiation_authority TEXT,
            contract_approval_limit DECIMAL(15,2),
            procurement_authority DECIMAL(15,2),
            vendor_selection_role TEXT,
            supplier_management TEXT,
            partnership_development TEXT,
            client_relationship_management TEXT,
            customer_interaction_level VARCHAR(50),
            service_delivery_responsibility TEXT,
            quality_standards_ownership TEXT,
            customer_satisfaction_accountability TEXT,
            complaint_resolution_authority TEXT,
            issue_escalation_protocol TEXT,
            problem_solving_expectations TEXT,
            root_cause_analysis_role TEXT,
            corrective_action_authority TEXT,
            preventive_action_responsibility TEXT,
            continuous_monitoring_role TEXT,
            performance_optimization TEXT,
            efficiency_improvement TEXT,
            productivity_enhancement TEXT,
            cost_reduction_targets TEXT,
            revenue_generation_responsibility TEXT,
            profit_accountability TEXT,
            margin_improvement_role TEXT,
            growth_contribution TEXT,
            market_expansion_role TEXT,
            product_development_involvement TEXT,
            service_innovation_role TEXT,
            technology_adoption_responsibility TEXT,
            digital_transformation_role TEXT,
            automation_implementation TEXT,
            ai_ml_utilization TEXT,
            data_driven_decision_making TEXT,
            agile_methodology_adoption TEXT,
            lean_principles_application TEXT,
            six_sigma_involvement TEXT,
            project_management_methodology TEXT,
            change_management_role TEXT,
            stakeholder_management TEXT,
            communication_responsibility TEXT,
            presentation_requirements TEXT,
            report_writing_expectations TEXT,
            documentation_standards TEXT,
            writing_proficiency_level VARCHAR(50),
            verbal_communication_level VARCHAR(50),
            interpersonal_skills_level VARCHAR(50),
            teamwork_expectations TEXT,
            collaboration_requirements TEXT,
            networking_expectations TEXT,
            relationship_building TEXT,
            influence_without_authority TEXT,
            persuasion_skills TEXT,
            negotiation_skills TEXT,
            conflict_management_skills TEXT,
            emotional_intelligence DECIMAL(3,2),
            adaptability DECIMAL(3,2),
            resilience DECIMAL(3,2),
            stress_tolerance DECIMAL(3,2),
            time_management DECIMAL(3,2),
            prioritization_skills DECIMAL(3,2),
            multitasking_ability DECIMAL(3,2),
            attention_to_detail DECIMAL(3,2),
            analytical_thinking DECIMAL(3,2),
            critical_thinking DECIMAL(3,2),
            creative_thinking DECIMAL(3,2),
            problem_solving_approach TEXT,
            innovation_mindset DECIMAL(3,2),
            entrepreneurial_spirit DECIMAL(3,2),
            ownership_mentality DECIMAL(3,2),
            accountability_culture DECIMAL(3,2),
            results_orientation DECIMAL(3,2),
            achievement_drive DECIMAL(3,2),
            quality_focus DECIMAL(3,2),
            customer_centricity DECIMAL(3,2),
            business_acumen DECIMAL(3,2),
            financial_literacy DECIMAL(3,2),
            commercial_awareness DECIMAL(3,2),
            strategic_thinking DECIMAL(3,2),
            tactical_execution DECIMAL(3,2),
            operational_excellence DECIMAL(3,2),
            service_excellence DECIMAL(3,2),
            technical_excellence DECIMAL(3,2),
            professional_excellence DECIMAL(3,2),
            ethical_conduct DECIMAL(3,2),
            integrity_standards DECIMAL(3,2),
            transparency_expectations DECIMAL(3,2),
            honesty_requirements DECIMAL(3,2),
            trustworthiness DECIMAL(3,2),
            reliability DECIMAL(3,2),
            dependability DECIMAL(3,2),
            consistency DECIMAL(3,2),
            punctuality DECIMAL(3,2),
            attendance_expectations TEXT,
            availability_requirements TEXT,
            responsiveness_standards TEXT,
            turnaround_time_expectations TEXT,
            service_level_commitments TEXT,
            deadline_adherence DECIMAL(3,2),
            milestone_achievement DECIMAL(3,2),
            goal_attainment DECIMAL(3,2),
            target_completion DECIMAL(3,2),
            success_metrics TEXT,
            performance_indicators TEXT,
            evaluation_criteria TEXT,
            rating_scale VARCHAR(50),
            ranking_methodology TEXT,
            comparison_benchmarks TEXT,
            industry_standards TEXT,
            market_practices TEXT,
            competitive_positioning TEXT,
            value_proposition TEXT,
            unique_selling_points TEXT,
            differentiation_factors TEXT,
            status VARCHAR(20) DEFAULT 'Active',
            is_active BOOLEAN DEFAULT 1,
            effective_date DATE,
            expiry_date DATE,
            version VARCHAR(20),
            last_review_date DATE,
            next_review_date DATE,
            approval_status VARCHAR(20),
            approved_by INTEGER,
            approval_date DATE,
            remarks TEXT,
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_by INTEGER
        )";
    }

    // Business Logic Methods

    public function activate() {
        $this->status = 'Active';
        $this->is_active = 1;
        return $this->save();
    }

    public function deactivate() {
        $this->status = 'Inactive';
        $this->is_active = 0;
        return $this->save();
    }

    public function archive() {
        $this->status = 'Archived';
        $this->is_active = 0;
        return $this->save();
    }

    public function getReportsToDesignation() {
        if (!$this->reports_to_designation_id) return null;

        return static::find($this->reports_to_designation_id);
    }

    public function getSubordinateDesignations() {
        return static::where('reports_to_designation_id', '=', $this->id);
    }

    public function getHierarchy() {
        $hierarchy = [];
        $current = $this;

        while ($current) {
            array_unshift($hierarchy, $current);
            $current = $current->getReportsToDesignation();
        }

        return $hierarchy;
    }

    public function getHierarchyPath() {
        $hierarchy = $this->getHierarchy();
        return implode(' > ', array_map(function($d) {
            return $d->name;
        }, $hierarchy));
    }

    public function generateCode() {
        if ($this->code) return $this->code;

        $prefix = strtoupper(substr($this->category ?? 'DES', 0, 3));
        $sequence = str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . '-' . $sequence;
    }

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

        // If editing, exclude current designation
        if ($this->id) {
            $existing = array_filter($existing, function($d) {
                return $d->id !== $this->id;
            });
        }

        return !empty($existing);
    }

    public function getSalaryRange() {
        if (!$this->salary_range_min && !$this->salary_range_max) {
            return 'Not specified';
        }

        $currency = $this->salary_currency ?: 'USD';
        $min = $this->salary_range_min ? number_format($this->salary_range_min) : '?';
        $max = $this->salary_range_max ? number_format($this->salary_range_max) : '?';

        return "{$currency} {$min} - {$max}";
    }

    public function getExperienceRange() {
        if (!$this->min_experience_years && !$this->max_experience_years) {
            return 'Not specified';
        }

        $min = $this->min_experience_years ?? 0;
        $max = $this->max_experience_years ?? '?';

        return "{$min}-{$max} years";
    }

    public function isManagementRole() {
        return in_array($this->category, [
            self::CATEGORY_LEADERSHIP,
            self::CATEGORY_MANAGEMENT
        ]) || $this->direct_reports > 0;
    }

    public function isTechnicalRole() {
        return $this->category === self::CATEGORY_TECHNICAL;
    }

    public function isExecutiveRole() {
        return $this->designation_level === self::LEVEL_EXECUTIVE;
    }

    public function requiresCertification() {
        return !empty($this->certifications_required);
    }

    public function isRemoteEligible() {
        return $this->remote_work_eligible == 1;
    }

    // Static query methods

    public static function findByLevel($level) {
        return static::where('designation_level', '=', $level);
    }

    public static function findByCategory($category) {
        return static::where('category', '=', $category);
    }

    public static function findByFunctionalArea($area) {
        return static::where('functional_area', '=', $area);
    }

    public static function findManagementRoles() {
        $all = static::all();
        return array_filter($all, function($d) {
            return $d->isManagementRole();
        });
    }

    public static function findTechnicalRoles() {
        return static::findByCategory(self::CATEGORY_TECHNICAL);
    }

    public static function findExecutiveRoles() {
        return static::findByLevel(self::LEVEL_EXECUTIVE);
    }

    public static function findRemoteEligible() {
        return static::where('remote_work_eligible', '=', 1);
    }

    public static function findBySalaryRange($min, $max) {
        $all = static::all();
        return array_filter($all, function($d) use ($min, $max) {
            return ($d->salary_range_min >= $min && $d->salary_range_max <= $max);
        });
    }

    public static function searchDesignations($query) {
        $designations = static::all();
        $query = strtolower($query);

        return array_filter($designations, function($d) use ($query) {
            return strpos(strtolower($d->name ?: ''), $query) !== false ||
                   strpos(strtolower($d->code ?: ''), $query) !== false ||
                   strpos(strtolower($d->description ?: ''), $query) !== false ||
                   strpos(strtolower($d->category ?: ''), $query) !== false ||
                   strpos(strtolower($d->functional_area ?: ''), $query) !== false;
        });
    }

    public function validate() {
        $errors = [];

        if (empty($this->attributes['name'])) {
            $errors[] = "Designation name is required";
        }

        if (empty($this->attributes['designation_level'])) {
            $errors[] = "Designation level is required";
        }

        if (empty($this->attributes['category'])) {
            $errors[] = "Category is required";
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

    public function beforeSave() {
        // Generate code if not provided
        if (empty($this->attributes['code'])) {
            $this->attributes['code'] = $this->generateCode();
        }

        // Generate slug if not provided
        if (empty($this->attributes['slug']) && !empty($this->attributes['name'])) {
            $this->attributes['slug'] = $this->generateSlug();
        }

        // Set default values
        if (empty($this->attributes['status'])) {
            $this->attributes['status'] = 'Active';
        }

        if (empty($this->attributes['is_active'])) {
            $this->attributes['is_active'] = 1;
        }

        if (empty($this->attributes['employment_type'])) {
            $this->attributes['employment_type'] = self::EMPLOYMENT_FULL_TIME;
        }

        // Set timestamps
        if (empty($this->attributes['created_at'])) {
            $this->attributes['created_at'] = date('Y-m-d H:i:s');
        }
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');

        return true;
    }

    public function afterSave() {
        error_log("PopularOrganizationDesignation {$this->id} ({$this->name}) saved successfully");
        return true;
    }
}
?>