<?php

require_once __DIR__ . '/BaseEntity.php';

class PopularOrganizationDepartment extends BaseEntity {
    protected $table = 'popular_organization_departments';
    protected $fillable = [
        'id',
        'parent_department_id',
        'name',
        'code',
        'slug',
        'description',
        'department_type',
        'function_category',
        'responsibility_area',
        'cost_center',
        'budget_code',
        'annual_budget',
        'budget_currency',
        'head_employee_id',
        'manager_employee_id',
        'location_building_id',
        'location_floor',
        'location_area',
        'employee_count',
        'max_capacity',
        'establishment_date',
        'dissolution_date',
        'operational_status',
        'priority_level',
        'performance_metrics',
        'objectives',
        'kpis',
        'responsibilities',
        'services_offered',
        'internal_clients',
        'external_clients',
        'suppliers',
        'equipment_inventory',
        'software_tools',
        'certification_requirements',
        'compliance_standards',
        'risk_factors',
        'security_clearance_level',
        'working_hours',
        'shift_patterns',
        'remote_work_policy',
        'collaboration_tools',
        'communication_channels',
        'escalation_procedures',
        'emergency_contacts',
        'disaster_recovery_plan',
        'training_requirements',
        'skill_matrix',
        'competency_framework',
        'career_progression_paths',
        'succession_planning',
        'recruitment_guidelines',
        'onboarding_process',
        'performance_review_cycle',
        'recognition_programs',
        'team_building_activities',
        'meeting_schedule',
        'reporting_frequency',
        'dashboard_metrics',
        'automation_level',
        'digitalization_status',
        'innovation_initiatives',
        'improvement_projects',
        'benchmark_standards',
        'vendor_relationships',
        'partnership_agreements',
        'service_level_agreements',
        'quality_standards',
        'audit_frequency',
        'regulatory_requirements',
        'environmental_impact',
        'sustainability_goals',
        'diversity_metrics',
        'inclusion_programs',
        'wellness_initiatives',
        'safety_protocols',
        'incident_reporting',
        'lessons_learned',
        'best_practices',
        'knowledge_management',
        'documentation_standards',
        'version_control',
        'access_permissions',
        'data_classification',
        'confidentiality_level',
        'intellectual_property',
        'contract_management',
        'invoice_processing',
        'expense_tracking',
        'asset_management',
        'maintenance_schedule',
        'upgrade_planning',
        'technology_roadmap',
        'integration_points',
        'api_endpoints',
        'data_feeds',
        'reporting_tools',
        'analytics_platforms',
        'monitoring_systems',
        'alerting_mechanisms',
        'backup_procedures',
        'recovery_processes',
        'business_continuity',
        'change_management',
        'approval_workflows',
        'governance_model',
        'policy_framework',
        'procedure_manuals',
        'work_instructions',
        'forms_templates',
        'checklists',
        'standard_operating_procedures',
        'quality_assurance',
        'continuous_improvement',
        'feedback_mechanisms',
        'customer_satisfaction',
        'stakeholder_engagement',
        'communication_strategy',
        'brand_guidelines',
        'marketing_materials',
        'public_relations',
        'social_responsibility',
        'community_involvement',
        'charitable_activities',
        'volunteer_programs',
        'employee_engagement',
        'culture_initiatives',
        'values_alignment',
        'ethics_guidelines',
        'code_of_conduct',
        'conflict_resolution',
        'grievance_procedures',
        'disciplinary_actions',
        'reward_systems',
        'bonus_schemes',
        'incentive_programs',
        'profit_sharing',
        'stock_options',
        'benefits_package',
        'leave_policies',
        'flexible_working',
        'work_life_balance',
        'mental_health_support',
        'counseling_services',
        'stress_management',
        'burnout_prevention',
        'ergonomic_assessments',
        'health_screenings',
        'fitness_programs',
        'nutrition_guidance',
        'vaccination_programs',
        'pandemic_response',
        'crisis_management',
        'media_relations',
        'stakeholder_communications',
        'investor_relations',
        'regulatory_reporting',
        'tax_compliance',
        'legal_requirements',
        'contract_negotiations',
        'dispute_resolution',
        'litigation_management',
        'insurance_coverage',
        'claims_processing',
        'risk_mitigation',
        'security_measures',
        'access_controls',
        'surveillance_systems',
        'visitor_management',
        'badge_requirements',
        'clearance_levels',
        'background_checks',
        'reference_verification',
        'drug_testing',
        'medical_examinations',
        'fitness_assessments',
        'psychological_evaluations',
        'personality_tests',
        'aptitude_assessments',
        'technical_certifications',
        'professional_memberships',
        'continuing_education',
        'conference_attendance',
        'workshop_participation',
        'seminar_enrollment',
        'online_learning',
        'mentorship_programs',
        'coaching_services',
        'leadership_development',
        'management_training',
        'supervisory_skills',
        'team_leadership',
        'project_management',
        'resource_planning',
        'timeline_management',
        'milestone_tracking',
        'deliverable_monitoring',
        'quality_control',
        'testing_procedures',
        'validation_processes',
        'verification_methods',
        'acceptance_criteria',
        'sign_off_procedures',
        'deployment_guidelines',
        'rollback_plans',
        'maintenance_windows',
        'service_availability',
        'uptime_requirements',
        'performance_targets',
        'response_times',
        'throughput_metrics',
        'capacity_planning',
        'scalability_factors',
        'load_balancing',
        'failover_mechanisms',
        'redundancy_systems',
        'backup_strategies',
        'archive_policies',
        'retention_schedules',
        'disposal_procedures',
        'recycling_programs',
        'waste_management',
        'energy_consumption',
        'carbon_footprint',
        'green_initiatives',
        'environmental_certifications',
        'sustainability_reporting',
        'corporate_social_responsibility',
        'ethical_sourcing',
        'fair_trade_practices',
        'supply_chain_transparency',
        'vendor_audits',
        'supplier_assessments',
        'contract_reviews',
        'performance_evaluations',
        'relationship_management',
        'partnership_development',
        'collaboration_agreements',
        'joint_ventures',
        'strategic_alliances',
        'merger_integrations',
        'acquisition_planning',
        'divestiture_strategies',
        'restructuring_initiatives',
        'transformation_programs',
        'digital_transformation',
        'process_optimization',
        'workflow_automation',
        'artificial_intelligence',
        'machine_learning',
        'data_analytics',
        'business_intelligence',
        'predictive_modeling',
        'forecasting_methods',
        'trend_analysis',
        'market_research',
        'competitive_intelligence',
        'industry_benchmarking',
        'peer_comparisons',
        'best_practice_sharing',
        'knowledge_transfer',
        'lessons_learned_capture',
        'innovation_labs',
        'research_development',
        'prototype_development',
        'pilot_programs',
        'proof_of_concepts',
        'feasibility_studies',
        'business_case_development',
        'return_on_investment',
        'cost_benefit_analysis',
        'financial_modeling',
        'budget_planning',
        'forecasting_accuracy',
        'variance_analysis',
        'financial_reporting',
        'management_accounting',
        'cost_accounting',
        'activity_based_costing',
        'profitability_analysis',
        'pricing_strategies',
        'revenue_optimization',
        'margin_improvement',
        'efficiency_gains',
        'productivity_enhancements',
        'output_optimization',
        'resource_utilization',
        'capacity_optimization',
        'scheduling_efficiency',
        'workflow_streamlining',
        'process_standardization',
        'procedure_harmonization',
        'system_integration',
        'data_consolidation',
        'reporting_standardization',
        'dashboard_unification',
        'metrics_alignment',
        'kpi_synchronization',
        'goal_cascading',
        'objective_alignment',
        'strategy_execution',
        'tactical_planning',
        'operational_excellence',
        'service_excellence',
        'customer_focus',
        'quality_orientation',
        'continuous_learning',
        'adaptability',
        'resilience',
        'agility',
        'flexibility',
        'innovation',
        'creativity',
        'collaboration',
        'teamwork',
        'communication',
        'transparency',
        'accountability',
        'integrity',
        'respect',
        'diversity',
        'inclusion',
        'equity',
        'fairness',
        'justice',
        'empathy',
        'compassion',
        'trust',
        'reliability',
        'dependability',
        'consistency',
        'predictability',
        'stability',
        'security',
        'safety',
        'compliance',
        'governance',
        'oversight',
        'control',
        'monitoring',
        'evaluation',
        'assessment',
        'review',
        'audit',
        'inspection',
        'verification',
        'validation',
        'certification',
        'accreditation',
        'authorization',
        'approval',
        'endorsement',
        'recognition',
        'achievement',
        'success',
        'excellence',
        'distinction',
        'merit',
        'honor',
        'prestige',
        'reputation',
        'credibility',
        'trustworthiness',
        'reliability_score',
        'performance_rating',
        'maturity_level',
        'capability_assessment',
        'readiness_evaluation',
        'preparedness_status',
        'implementation_stage',
        'deployment_phase',
        'rollout_progress',
        'adoption_rate',
        'utilization_percentage',
        'satisfaction_score',
        'engagement_level',
        'retention_rate',
        'turnover_percentage',
        'absenteeism_rate',
        'productivity_index',
        'efficiency_ratio',
        'effectiveness_measure',
        'impact_assessment',
        'outcome_evaluation',
        'result_analysis',
        'benefit_realization',
        'value_creation',
        'competitive_advantage',
        'market_position',
        'brand_strength',
        'customer_loyalty',
        'stakeholder_confidence',
        'investor_trust',
        'regulatory_standing',
        'industry_leadership',
        'thought_leadership',
        'innovation_index',
        'digital_maturity',
        'technology_adoption',
        'process_maturity',
        'organizational_capability',
        'strategic_alignment',
        'cultural_fit',
        'values_integration',
        'mission_alignment',
        'vision_realization',
        'purpose_fulfillment',
        'impact_measurement',
        'social_value',
        'economic_contribution',
        'environmental_benefit',
        'sustainability_index',
        'esg_rating',
        'csr_score',
        'stakeholder_value',
        'shareholder_returns',
        'total_value_creation',
        'remarks',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected function getSchema() {
        return "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            parent_department_id INTEGER NULL,
            name VARCHAR(255) NOT NULL,
            code VARCHAR(50),
            slug VARCHAR(255) UNIQUE,
            description TEXT,
            department_type VARCHAR(50) DEFAULT 'Functional',
            function_category VARCHAR(100),
            responsibility_area TEXT,
            cost_center VARCHAR(50),
            budget_code VARCHAR(50),
            annual_budget DECIMAL(15,2),
            budget_currency VARCHAR(3) DEFAULT 'USD',
            head_employee_id INTEGER,
            manager_employee_id INTEGER,
            location_building_id INTEGER,
            location_floor INTEGER,
            location_area VARCHAR(100),
            employee_count INTEGER DEFAULT 0,
            max_capacity INTEGER,
            establishment_date DATE,
            dissolution_date DATE,
            operational_status VARCHAR(20) DEFAULT 'Active',
            priority_level VARCHAR(20) DEFAULT 'Medium',
            performance_metrics TEXT,
            objectives TEXT,
            kpis TEXT,
            responsibilities TEXT,
            services_offered TEXT,
            internal_clients TEXT,
            external_clients TEXT,
            suppliers TEXT,
            equipment_inventory TEXT,
            software_tools TEXT,
            certification_requirements TEXT,
            compliance_standards TEXT,
            risk_factors TEXT,
            security_clearance_level VARCHAR(50),
            working_hours VARCHAR(100),
            shift_patterns TEXT,
            remote_work_policy TEXT,
            collaboration_tools TEXT,
            communication_channels TEXT,
            escalation_procedures TEXT,
            emergency_contacts TEXT,
            disaster_recovery_plan TEXT,
            training_requirements TEXT,
            skill_matrix TEXT,
            competency_framework TEXT,
            career_progression_paths TEXT,
            succession_planning TEXT,
            recruitment_guidelines TEXT,
            onboarding_process TEXT,
            performance_review_cycle VARCHAR(50),
            recognition_programs TEXT,
            team_building_activities TEXT,
            meeting_schedule TEXT,
            reporting_frequency VARCHAR(50),
            dashboard_metrics TEXT,
            automation_level VARCHAR(20),
            digitalization_status VARCHAR(20),
            innovation_initiatives TEXT,
            improvement_projects TEXT,
            benchmark_standards TEXT,
            vendor_relationships TEXT,
            partnership_agreements TEXT,
            service_level_agreements TEXT,
            quality_standards TEXT,
            audit_frequency VARCHAR(50),
            regulatory_requirements TEXT,
            environmental_impact TEXT,
            sustainability_goals TEXT,
            diversity_metrics TEXT,
            inclusion_programs TEXT,
            wellness_initiatives TEXT,
            safety_protocols TEXT,
            incident_reporting TEXT,
            lessons_learned TEXT,
            best_practices TEXT,
            knowledge_management TEXT,
            documentation_standards TEXT,
            version_control TEXT,
            access_permissions TEXT,
            data_classification VARCHAR(50),
            confidentiality_level VARCHAR(20),
            intellectual_property TEXT,
            contract_management TEXT,
            invoice_processing TEXT,
            expense_tracking TEXT,
            asset_management TEXT,
            maintenance_schedule TEXT,
            upgrade_planning TEXT,
            technology_roadmap TEXT,
            integration_points TEXT,
            api_endpoints TEXT,
            data_feeds TEXT,
            reporting_tools TEXT,
            analytics_platforms TEXT,
            monitoring_systems TEXT,
            alerting_mechanisms TEXT,
            backup_procedures TEXT,
            recovery_processes TEXT,
            business_continuity TEXT,
            change_management TEXT,
            approval_workflows TEXT,
            governance_model TEXT,
            policy_framework TEXT,
            procedure_manuals TEXT,
            work_instructions TEXT,
            forms_templates TEXT,
            checklists TEXT,
            standard_operating_procedures TEXT,
            quality_assurance TEXT,
            continuous_improvement TEXT,
            feedback_mechanisms TEXT,
            customer_satisfaction DECIMAL(3,2),
            stakeholder_engagement TEXT,
            communication_strategy TEXT,
            brand_guidelines TEXT,
            marketing_materials TEXT,
            public_relations TEXT,
            social_responsibility TEXT,
            community_involvement TEXT,
            charitable_activities TEXT,
            volunteer_programs TEXT,
            employee_engagement DECIMAL(3,2),
            culture_initiatives TEXT,
            values_alignment TEXT,
            ethics_guidelines TEXT,
            code_of_conduct TEXT,
            conflict_resolution TEXT,
            grievance_procedures TEXT,
            disciplinary_actions TEXT,
            reward_systems TEXT,
            bonus_schemes TEXT,
            incentive_programs TEXT,
            profit_sharing TEXT,
            stock_options TEXT,
            benefits_package TEXT,
            leave_policies TEXT,
            flexible_working TEXT,
            work_life_balance TEXT,
            mental_health_support TEXT,
            counseling_services TEXT,
            stress_management TEXT,
            burnout_prevention TEXT,
            ergonomic_assessments TEXT,
            health_screenings TEXT,
            fitness_programs TEXT,
            nutrition_guidance TEXT,
            vaccination_programs TEXT,
            pandemic_response TEXT,
            crisis_management TEXT,
            media_relations TEXT,
            stakeholder_communications TEXT,
            investor_relations TEXT,
            regulatory_reporting TEXT,
            tax_compliance TEXT,
            legal_requirements TEXT,
            contract_negotiations TEXT,
            dispute_resolution TEXT,
            litigation_management TEXT,
            insurance_coverage TEXT,
            claims_processing TEXT,
            risk_mitigation TEXT,
            security_measures TEXT,
            access_controls TEXT,
            surveillance_systems TEXT,
            visitor_management TEXT,
            badge_requirements TEXT,
            clearance_levels TEXT,
            background_checks TEXT,
            reference_verification TEXT,
            drug_testing TEXT,
            medical_examinations TEXT,
            fitness_assessments TEXT,
            psychological_evaluations TEXT,
            personality_tests TEXT,
            aptitude_assessments TEXT,
            technical_certifications TEXT,
            professional_memberships TEXT,
            continuing_education TEXT,
            conference_attendance TEXT,
            workshop_participation TEXT,
            seminar_enrollment TEXT,
            online_learning TEXT,
            mentorship_programs TEXT,
            coaching_services TEXT,
            leadership_development TEXT,
            management_training TEXT,
            supervisory_skills TEXT,
            team_leadership TEXT,
            project_management TEXT,
            resource_planning TEXT,
            timeline_management TEXT,
            milestone_tracking TEXT,
            deliverable_monitoring TEXT,
            quality_control TEXT,
            testing_procedures TEXT,
            validation_processes TEXT,
            verification_methods TEXT,
            acceptance_criteria TEXT,
            sign_off_procedures TEXT,
            deployment_guidelines TEXT,
            rollback_plans TEXT,
            maintenance_windows TEXT,
            service_availability DECIMAL(5,2),
            uptime_requirements DECIMAL(5,2),
            performance_targets TEXT,
            response_times TEXT,
            throughput_metrics TEXT,
            capacity_planning TEXT,
            scalability_factors TEXT,
            load_balancing TEXT,
            failover_mechanisms TEXT,
            redundancy_systems TEXT,
            backup_strategies TEXT,
            archive_policies TEXT,
            retention_schedules TEXT,
            disposal_procedures TEXT,
            recycling_programs TEXT,
            waste_management TEXT,
            energy_consumption DECIMAL(10,2),
            carbon_footprint DECIMAL(10,2),
            green_initiatives TEXT,
            environmental_certifications TEXT,
            sustainability_reporting TEXT,
            corporate_social_responsibility TEXT,
            ethical_sourcing TEXT,
            fair_trade_practices TEXT,
            supply_chain_transparency TEXT,
            vendor_audits TEXT,
            supplier_assessments TEXT,
            contract_reviews TEXT,
            performance_evaluations TEXT,
            relationship_management TEXT,
            partnership_development TEXT,
            collaboration_agreements TEXT,
            joint_ventures TEXT,
            strategic_alliances TEXT,
            merger_integrations TEXT,
            acquisition_planning TEXT,
            divestiture_strategies TEXT,
            restructuring_initiatives TEXT,
            transformation_programs TEXT,
            digital_transformation TEXT,
            process_optimization TEXT,
            workflow_automation TEXT,
            artificial_intelligence TEXT,
            machine_learning TEXT,
            data_analytics TEXT,
            business_intelligence TEXT,
            predictive_modeling TEXT,
            forecasting_methods TEXT,
            trend_analysis TEXT,
            market_research TEXT,
            competitive_intelligence TEXT,
            industry_benchmarking TEXT,
            peer_comparisons TEXT,
            best_practice_sharing TEXT,
            knowledge_transfer TEXT,
            lessons_learned_capture TEXT,
            innovation_labs TEXT,
            research_development TEXT,
            prototype_development TEXT,
            pilot_programs TEXT,
            proof_of_concepts TEXT,
            feasibility_studies TEXT,
            business_case_development TEXT,
            return_on_investment DECIMAL(5,2),
            cost_benefit_analysis TEXT,
            financial_modeling TEXT,
            budget_planning TEXT,
            forecasting_accuracy DECIMAL(5,2),
            variance_analysis TEXT,
            financial_reporting TEXT,
            management_accounting TEXT,
            cost_accounting TEXT,
            activity_based_costing TEXT,
            profitability_analysis TEXT,
            pricing_strategies TEXT,
            revenue_optimization TEXT,
            margin_improvement TEXT,
            efficiency_gains DECIMAL(5,2),
            productivity_enhancements TEXT,
            output_optimization TEXT,
            resource_utilization DECIMAL(5,2),
            capacity_optimization TEXT,
            scheduling_efficiency DECIMAL(5,2),
            workflow_streamlining TEXT,
            process_standardization TEXT,
            procedure_harmonization TEXT,
            system_integration TEXT,
            data_consolidation TEXT,
            reporting_standardization TEXT,
            dashboard_unification TEXT,
            metrics_alignment TEXT,
            kpi_synchronization TEXT,
            goal_cascading TEXT,
            objective_alignment TEXT,
            strategy_execution TEXT,
            tactical_planning TEXT,
            operational_excellence TEXT,
            service_excellence TEXT,
            customer_focus TEXT,
            quality_orientation TEXT,
            continuous_learning TEXT,
            adaptability DECIMAL(3,2),
            resilience DECIMAL(3,2),
            agility DECIMAL(3,2),
            flexibility DECIMAL(3,2),
            innovation DECIMAL(3,2),
            creativity DECIMAL(3,2),
            collaboration DECIMAL(3,2),
            teamwork DECIMAL(3,2),
            communication DECIMAL(3,2),
            transparency DECIMAL(3,2),
            accountability DECIMAL(3,2),
            integrity DECIMAL(3,2),
            respect DECIMAL(3,2),
            diversity DECIMAL(3,2),
            inclusion DECIMAL(3,2),
            equity DECIMAL(3,2),
            fairness DECIMAL(3,2),
            justice DECIMAL(3,2),
            empathy DECIMAL(3,2),
            compassion DECIMAL(3,2),
            trust DECIMAL(3,2),
            reliability DECIMAL(3,2),
            dependability DECIMAL(3,2),
            consistency DECIMAL(3,2),
            predictability DECIMAL(3,2),
            stability DECIMAL(3,2),
            security DECIMAL(3,2),
            safety DECIMAL(3,2),
            compliance DECIMAL(3,2),
            governance DECIMAL(3,2),
            oversight DECIMAL(3,2),
            control DECIMAL(3,2),
            monitoring DECIMAL(3,2),
            evaluation DECIMAL(3,2),
            assessment DECIMAL(3,2),
            review DECIMAL(3,2),
            audit DECIMAL(3,2),
            inspection DECIMAL(3,2),
            verification DECIMAL(3,2),
            validation DECIMAL(3,2),
            certification DECIMAL(3,2),
            accreditation DECIMAL(3,2),
            authorization DECIMAL(3,2),
            approval DECIMAL(3,2),
            endorsement DECIMAL(3,2),
            recognition DECIMAL(3,2),
            achievement DECIMAL(3,2),
            success DECIMAL(3,2),
            excellence DECIMAL(3,2),
            distinction DECIMAL(3,2),
            merit DECIMAL(3,2),
            honor DECIMAL(3,2),
            prestige DECIMAL(3,2),
            reputation DECIMAL(3,2),
            credibility DECIMAL(3,2),
            trustworthiness DECIMAL(3,2),
            reliability_score DECIMAL(3,2),
            performance_rating DECIMAL(3,2),
            maturity_level VARCHAR(20),
            capability_assessment TEXT,
            readiness_evaluation TEXT,
            preparedness_status VARCHAR(20),
            implementation_stage VARCHAR(50),
            deployment_phase VARCHAR(50),
            rollout_progress DECIMAL(5,2),
            adoption_rate DECIMAL(5,2),
            utilization_percentage DECIMAL(5,2),
            satisfaction_score DECIMAL(3,2),
            engagement_level DECIMAL(3,2),
            retention_rate DECIMAL(5,2),
            turnover_percentage DECIMAL(5,2),
            absenteeism_rate DECIMAL(5,2),
            productivity_index DECIMAL(5,2),
            efficiency_ratio DECIMAL(5,2),
            effectiveness_measure DECIMAL(5,2),
            impact_assessment TEXT,
            outcome_evaluation TEXT,
            result_analysis TEXT,
            benefit_realization TEXT,
            value_creation TEXT,
            competitive_advantage TEXT,
            market_position TEXT,
            brand_strength DECIMAL(3,2),
            customer_loyalty DECIMAL(3,2),
            stakeholder_confidence DECIMAL(3,2),
            investor_trust DECIMAL(3,2),
            regulatory_standing DECIMAL(3,2),
            industry_leadership DECIMAL(3,2),
            thought_leadership DECIMAL(3,2),
            innovation_index DECIMAL(3,2),
            digital_maturity DECIMAL(3,2),
            technology_adoption DECIMAL(3,2),
            process_maturity DECIMAL(3,2),
            organizational_capability DECIMAL(3,2),
            strategic_alignment DECIMAL(3,2),
            cultural_fit DECIMAL(3,2),
            values_integration DECIMAL(3,2),
            mission_alignment DECIMAL(3,2),
            vision_realization DECIMAL(3,2),
            purpose_fulfillment DECIMAL(3,2),
            impact_measurement TEXT,
            social_value DECIMAL(15,2),
            economic_contribution DECIMAL(15,2),
            environmental_benefit DECIMAL(15,2),
            sustainability_index DECIMAL(3,2),
            esg_rating DECIMAL(3,2),
            csr_score DECIMAL(3,2),
            stakeholder_value DECIMAL(15,2),
            shareholder_returns DECIMAL(5,2),
            total_value_creation DECIMAL(15,2),
            status VARCHAR(20) DEFAULT 'Active',
            is_active BOOLEAN DEFAULT 1,
            remarks TEXT,
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_by INTEGER
        )";
    }

    // Business Logic Methods

    public function addSubDepartment($subDepartment) {
        if ($subDepartment instanceof PopularOrganizationDepartment) {
            $subDepartment->parent_department_id = $this->id;
            return $subDepartment->save();
        }
        return false;
    }

    public function getSubDepartments() {
        return static::where('parent_department_id', '=', $this->id);
    }

    public function getParentDepartment() {
        if ($this->parent_department_id) {
            return static::find($this->parent_department_id);
        }
        return null;
    }

    public function getDepartmentHierarchy() {
        $hierarchy = [];
        $current = $this;

        while ($current) {
            array_unshift($hierarchy, $current);
            $current = $current->getParentDepartment();
        }

        return $hierarchy;
    }

    // Relationship with PopularOrganizationTeam
    public function getTeams() {
        require_once __DIR__ . '/PopularOrganizationTeam.php';
        return PopularOrganizationTeam::getTeamsByDepartment($this->id);
    }

    public function assignHead($employeeId) {
        $this->head_employee_id = $employeeId;
        return $this->save();
    }

    public function assignManager($employeeId) {
        $this->manager_employee_id = $employeeId;
        return $this->save();
    }

    public function activate() {
        $this->operational_status = 'Active';
        $this->is_active = 1;
        return $this->save();
    }

    public function deactivate() {
        $this->operational_status = 'Inactive';
        $this->is_active = 0;
        return $this->save();
    }

    public function dissolve($dissolutionDate = null) {
        $this->operational_status = 'Dissolved';
        $this->dissolution_date = $dissolutionDate ?: date('Y-m-d');
        $this->is_active = 0;
        return $this->save();
    }

    public function updateBudget($amount, $currency = 'USD') {
        $this->annual_budget = $amount;
        $this->budget_currency = $currency;
        return $this->save();
    }

    public function setEmployeeCount($count) {
        $this->employee_count = max(0, intval($count));
        return $this->save();
    }

    public function incrementEmployeeCount($increment = 1) {
        $this->employee_count = ($this->employee_count ?? 0) + $increment;
        return $this->save();
    }

    public function decrementEmployeeCount($decrement = 1) {
        $this->employee_count = max(0, ($this->employee_count ?? 0) - $decrement);
        return $this->save();
    }

    // Static query methods

    public static function getDepartmentsByType($type) {
        return static::where('department_type', '=', $type);
    }

    public static function getDepartmentsByFunction($functionCategory) {
        return static::where('function_category', '=', $functionCategory);
    }

    public static function getActiveDepartments() {
        return static::where('operational_status', '=', 'Active');
    }

    public static function getTopLevelDepartments() {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE parent_department_id IS NULL AND is_active = 1";
        $results = $instance->db->fetchAll($sql);

        $departments = [];
        foreach ($results as $data) {
            $department = new static();
            $department->fill($data);
            $departments[] = $department;
        }
        return $departments;
    }

    public static function getDepartmentTree() {
        $allDepartments = static::all();
        $tree = [];
        $departmentMap = [];

        // Create a map for quick lookup
        foreach ($allDepartments as $dept) {
            $departmentMap[$dept->id] = $dept;
            $dept->children = [];
        }

        // Build the tree structure
        foreach ($allDepartments as $dept) {
            if ($dept->parent_department_id && isset($departmentMap[$dept->parent_department_id])) {
                $departmentMap[$dept->parent_department_id]->children[] = $dept;
            } else {
                $tree[] = $dept;
            }
        }

        return $tree;
    }

    public static function searchDepartments($criteria) {
        $instance = new static();
        $conditions = [];
        $params = [];

        if (!empty($criteria['department_type'])) {
            $conditions[] = "department_type = :department_type";
            $params['department_type'] = $criteria['department_type'];
        }

        if (!empty($criteria['function_category'])) {
            $conditions[] = "function_category = :function_category";
            $params['function_category'] = $criteria['function_category'];
        }

        if (!empty($criteria['operational_status'])) {
            $conditions[] = "operational_status = :operational_status";
            $params['operational_status'] = $criteria['operational_status'];
        }

        if (!empty($criteria['search'])) {
            $conditions[] = "(name LIKE :search OR description LIKE :search OR code LIKE :search)";
            $params['search'] = '%' . $criteria['search'] . '%';
        }

        $sql = "SELECT * FROM {$instance->table} WHERE is_active = 1";
        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }
        $sql .= " ORDER BY name";

        $results = $instance->db->fetchAll($sql, $params);
        $departments = [];
        foreach ($results as $data) {
            $department = new static();
            $department->fill($data);
            $departments[] = $department;
        }
        return $departments;
    }

    public static function getDepartmentStatistics() {
        $instance = new static();
        $sql = "SELECT
                    department_type,
                    operational_status,
                    COUNT(*) as count,
                    AVG(employee_count) as avg_employees,
                    SUM(annual_budget) as total_budget
                FROM {$instance->table}
                WHERE is_active = 1
                GROUP BY department_type, operational_status";

        return $instance->db->fetchAll($sql);
    }

    public function validate() {
        $errors = [];

        if (empty($this->attributes['name'])) {
            $errors[] = "Department name is required";
        }

        if (empty($this->attributes['code'])) {
            $errors[] = "Department code is required";
        }

        if (empty($this->attributes['department_type'])) {
            $errors[] = "Department type is required";
        }

        if (empty($this->attributes['function_category'])) {
            $errors[] = "Function category is required";
        }

        if (empty($this->attributes['operational_status'])) {
            $errors[] = "Operational status is required";
        }

        // Validate department type
        $validTypes = ['Functional', 'Divisional', 'Matrix', 'Network', 'Virtual'];
        if (!in_array($this->attributes['department_type'], $validTypes)) {
            $errors[] = "Invalid department type";
        }

        // Validate operational status
        $validStatuses = ['Active', 'Inactive', 'Suspended', 'Dissolved', 'Pending'];
        if (!in_array($this->attributes['operational_status'], $validStatuses)) {
            $errors[] = "Invalid operational status";
        }

        return $errors;
    }

    public function beforeSave() {
        // Set default values
        if (empty($this->attributes['operational_status'])) {
            $this->attributes['operational_status'] = 'Active';
        }

        if (empty($this->attributes['is_active'])) {
            $this->attributes['is_active'] = 1;
        }

        if (empty($this->attributes['status'])) {
            $this->attributes['status'] = $this->attributes['operational_status'] ?? 'Active';
        }

        if (empty($this->attributes['budget_currency'])) {
            $this->attributes['budget_currency'] = 'USD';
        }

        if (empty($this->attributes['priority_level'])) {
            $this->attributes['priority_level'] = 'Medium';
        }

        // Generate slug from name if not provided
        if (empty($this->attributes['slug']) && !empty($this->attributes['name'])) {
            $this->attributes['slug'] = strtolower(str_replace(' ', '-', $this->attributes['name']));
        }

        // Set timestamps
        if (empty($this->attributes['created_at'])) {
            $this->attributes['created_at'] = date('Y-m-d H:i:s');
        }
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');

        return true;
    }

    public function afterSave() {
        // Log department creation/update
        error_log("PopularOrganizationDepartment {$this->id} ({$this->name}) saved successfully");
        return true;
    }

    public function beforeDelete() {
        // Check if department has sub-departments
        $subDepartments = $this->getSubDepartments();
        if (!empty($subDepartments)) {
            throw new Exception("Cannot delete department with sub-departments. Please reassign or delete sub-departments first.");
        }
        return true;
    }

    public function afterDelete() {
        error_log("PopularOrganizationDepartment {$this->id} ({$this->name}) deleted successfully");
        return true;
    }
}
?>