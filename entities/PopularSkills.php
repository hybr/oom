<?php

require_once __DIR__ . '/BaseEntity.php';

class PopularSkills extends BaseEntity {
    protected $table = 'popular_skills';
    protected $fillable = [
        'id',
        'name',
        'code',
        'slug',
        'description',
        'skill_category',
        'skill_type',
        'skill_domain',
        'skill_family',
        'skill_subfamily',
        'skill_level',
        'proficiency_scale',
        'competency_framework',
        'taxonomy_classification',
        'industry_standard',
        'certification_available',
        'certification_providers',
        'certification_cost_range',
        'certification_validity_years',
        'learning_path',
        'prerequisites',
        'recommended_experience',
        'time_to_learn_hours',
        'difficulty_level',
        'market_demand',
        'demand_trend',
        'salary_impact',
        'job_postings_count',
        'popularity_score',
        'relevance_score',
        'future_proof_score',
        'obsolescence_risk',
        'replacement_technologies',
        'complementary_skills',
        'related_skills',
        'alternative_skills',
        'foundational_skills',
        'advanced_skills',
        'expert_skills',
        'application_areas',
        'use_cases',
        'tools_technologies',
        'platforms_supported',
        'programming_languages',
        'frameworks_libraries',
        'methodologies',
        'best_practices',
        'design_patterns',
        'standards_compliance',
        'vendor_specific',
        'vendor_names',
        'open_source_alternative',
        'proprietary_technology',
        'license_requirements',
        'cost_of_ownership',
        'training_resources',
        'online_courses',
        'books_recommended',
        'practice_platforms',
        'community_support',
        'forums_groups',
        'conferences_events',
        'thought_leaders',
        'benchmark_metrics',
        'assessment_methods',
        'skill_tests_available',
        'practical_exercises',
        'project_examples',
        'portfolio_requirements',
        'interview_questions',
        'job_roles',
        'typical_designations',
        'experience_levels',
        'seniority_mapping',
        'career_progression',
        'growth_opportunities',
        'freelance_opportunities',
        'remote_work_suitability',
        'geographic_demand',
        'industry_sectors',
        'company_sizes',
        'startup_relevance',
        'enterprise_relevance',
        'government_demand',
        'academic_requirement',
        'research_relevance',
        'innovation_potential',
        'collaboration_requirement',
        'team_vs_individual',
        'communication_importance',
        'leadership_component',
        'analytical_component',
        'creative_component',
        'technical_component',
        'business_component',
        'soft_skill_mix',
        'hard_skill_mix',
        'cognitive_load',
        'physical_requirement',
        'emotional_intelligence',
        'cultural_awareness',
        'language_requirement',
        'certification_body',
        'accreditation_status',
        'industry_recognition',
        'employer_preference',
        'university_programs',
        'bootcamp_availability',
        'self_study_feasible',
        'mentorship_recommended',
        'hands_on_percentage',
        'theoretical_percentage',
        'continuous_learning',
        'update_frequency',
        'version_control',
        'backward_compatibility',
        'migration_complexity',
        'integration_capability',
        'interoperability',
        'scalability_aspect',
        'security_relevance',
        'compliance_importance',
        'audit_requirement',
        'documentation_quality',
        'code_quality_impact',
        'performance_optimization',
        'debugging_capability',
        'troubleshooting_aspect',
        'problem_solving_type',
        'analytical_thinking',
        'critical_thinking',
        'systems_thinking',
        'design_thinking',
        'strategic_thinking',
        'operational_excellence',
        'process_improvement',
        'quality_assurance',
        'risk_management',
        'decision_making',
        'prioritization',
        'time_management',
        'resource_management',
        'stakeholder_management',
        'vendor_management',
        'client_facing',
        'presentation_skills',
        'negotiation_skills',
        'conflict_resolution',
        'change_management',
        'agile_alignment',
        'devops_culture',
        'cloud_native',
        'ai_ml_integration',
        'automation_potential',
        'no_code_low_code',
        'citizen_developer',
        'business_user_friendly',
        'technical_depth',
        'breadth_vs_depth',
        't_shaped_skill',
        'full_stack_component',
        'specialization_level',
        'generalist_value',
        'niche_expertise',
        'transferable_skill',
        'industry_agnostic',
        'domain_specific',
        'cross_functional',
        'interdisciplinary',
        'emerging_technology',
        'mature_technology',
        'legacy_system',
        'modernization_skill',
        'transformation_enabler',
        'digital_literacy',
        'data_literacy',
        'cyber_literacy',
        'ai_literacy',
        'sustainability_aspect',
        'ethics_consideration',
        'privacy_awareness',
        'accessibility_knowledge',
        'inclusive_design',
        'user_centric',
        'customer_empathy',
        'business_acumen',
        'financial_literacy',
        'commercial_awareness',
        'entrepreneurial_mindset',
        'innovation_thinking',
        'creative_problem_solving',
        'adaptability',
        'resilience',
        'growth_mindset',
        'continuous_improvement',
        'learning_agility',
        'feedback_receptiveness',
        'self_awareness',
        'emotional_regulation',
        'stress_management',
        'work_life_balance',
        'burnout_prevention',
        'wellness_integration',
        'remote_collaboration',
        'virtual_communication',
        'asynchronous_work',
        'global_mindset',
        'cultural_intelligence',
        'diversity_inclusion',
        'social_responsibility',
        'environmental_awareness',
        'governance_understanding',
        'regulatory_knowledge',
        'legal_awareness',
        'ip_rights_knowledge',
        'contract_understanding',
        'procurement_basics',
        'supply_chain_awareness',
        'operations_knowledge',
        'logistics_understanding',
        'manufacturing_basics',
        'service_delivery',
        'customer_service',
        'support_excellence',
        'quality_mindset',
        'continuous_monitoring',
        'metrics_driven',
        'data_informed',
        'evidence_based',
        'scientific_method',
        'experimentation',
        'hypothesis_testing',
        'statistical_literacy',
        'quantitative_analysis',
        'qualitative_analysis',
        'research_methodology',
        'literature_review',
        'academic_writing',
        'technical_writing',
        'documentation_skills',
        'reporting_capability',
        'visualization_skills',
        'storytelling_ability',
        'persuasion_skills',
        'influence_capability',
        'networking_ability',
        'relationship_building',
        'trust_establishment',
        'credibility_building',
        'personal_branding',
        'professional_presence',
        'executive_presence',
        'public_speaking',
        'facilitation_skills',
        'training_delivery',
        'coaching_capability',
        'mentoring_ability',
        'teaching_aptitude',
        'knowledge_transfer',
        'community_building',
        'team_collaboration',
        'cross_team_coordination',
        'matrix_management',
        'virtual_leadership',
        'servant_leadership',
        'transformational_leadership',
        'situational_leadership',
        'inclusive_leadership',
        'ethical_leadership',
        'is_active',
        'is_featured',
        'is_trending',
        'is_emerging',
        'is_deprecated',
        'deprecation_date',
        'replacement_skill',
        'version',
        'last_updated',
        'review_date',
        'next_review_date',
        'data_source',
        'verification_status',
        'quality_score',
        'completeness_score',
        'accuracy_rating',
        'contributor_count',
        'endorsement_count',
        'usage_count',
        'search_count',
        'view_count',
        'share_count',
        'bookmark_count',
        'rating_average',
        'rating_count',
        'feedback_score',
        'recommendation_score',
        'ai_confidence_score',
        'human_verified',
        'expert_reviewed',
        'peer_validated',
        'industry_endorsed',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'notes',
        'tags',
        'keywords',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'meta_data'
    ];

    // Skill Categories
    const CATEGORY_TECHNICAL = 'Technical';
    const CATEGORY_SOFT_SKILLS = 'Soft Skills';
    const CATEGORY_BUSINESS = 'Business';
    const CATEGORY_LEADERSHIP = 'Leadership';
    const CATEGORY_CREATIVE = 'Creative';
    const CATEGORY_ANALYTICAL = 'Analytical';
    const CATEGORY_COMMUNICATION = 'Communication';
    const CATEGORY_MANAGEMENT = 'Management';
    const CATEGORY_DOMAIN = 'Domain Specific';
    const CATEGORY_TOOLS = 'Tools & Technologies';
    const CATEGORY_METHODOLOGY = 'Methodology';
    const CATEGORY_COMPLIANCE = 'Compliance & Governance';

    // Skill Types
    const TYPE_HARD_SKILL = 'Hard Skill';
    const TYPE_SOFT_SKILL = 'Soft Skill';
    const TYPE_HYBRID = 'Hybrid';
    const TYPE_FOUNDATIONAL = 'Foundational';
    const TYPE_SPECIALIZED = 'Specialized';
    const TYPE_TRANSFERABLE = 'Transferable';
    const TYPE_TECHNICAL = 'Technical';
    const TYPE_BEHAVIORAL = 'Behavioral';
    const TYPE_COGNITIVE = 'Cognitive';

    // Skill Levels
    const LEVEL_FOUNDATIONAL = 'Foundational';
    const LEVEL_INTERMEDIATE = 'Intermediate';
    const LEVEL_ADVANCED = 'Advanced';
    const LEVEL_EXPERT = 'Expert';
    const LEVEL_MASTER = 'Master';
    const LEVEL_BEGINNER = 'Beginner';
    const LEVEL_PROFICIENT = 'Proficient';

    // Difficulty Levels
    const DIFFICULTY_BEGINNER = 'Beginner';
    const DIFFICULTY_EASY = 'Easy';
    const DIFFICULTY_MODERATE = 'Moderate';
    const DIFFICULTY_INTERMEDIATE = 'Intermediate';
    const DIFFICULTY_ADVANCED = 'Advanced';
    const DIFFICULTY_HARD = 'Hard';
    const DIFFICULTY_EXPERT = 'Expert';

    // Market Demand
    const DEMAND_VERY_HIGH = 'Very High';
    const DEMAND_HIGH = 'High';
    const DEMAND_MODERATE = 'Moderate';
    const DEMAND_LOW = 'Low';
    const DEMAND_VERY_LOW = 'Very Low';
    const DEMAND_EMERGING = 'Emerging';
    const DEMAND_DECLINING = 'Declining';

    // Demand Trends
    const TREND_RISING = 'Rising';
    const TREND_STABLE = 'Stable';
    const TREND_DECLINING = 'Declining';
    const TREND_EMERGING = 'Emerging';
    const TREND_PEAK = 'Peak';
    const TREND_OBSOLETE = 'Obsolete';

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS {$this->table} (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE,
                code TEXT UNIQUE,
                slug TEXT UNIQUE,
                description TEXT,
                skill_category TEXT,
                skill_type TEXT,
                skill_domain TEXT,
                skill_family TEXT,
                skill_subfamily TEXT,
                skill_level TEXT,
                proficiency_scale TEXT,
                competency_framework TEXT,
                taxonomy_classification TEXT,
                industry_standard TEXT,
                certification_available INTEGER DEFAULT 0,
                certification_providers TEXT,
                certification_cost_range TEXT,
                certification_validity_years INTEGER,
                learning_path TEXT,
                prerequisites TEXT,
                recommended_experience TEXT,
                time_to_learn_hours INTEGER,
                difficulty_level TEXT,
                market_demand TEXT,
                demand_trend TEXT,
                salary_impact TEXT,
                job_postings_count INTEGER DEFAULT 0,
                popularity_score INTEGER DEFAULT 0,
                relevance_score INTEGER DEFAULT 0,
                future_proof_score INTEGER DEFAULT 0,
                obsolescence_risk TEXT,
                replacement_technologies TEXT,
                complementary_skills TEXT,
                related_skills TEXT,
                alternative_skills TEXT,
                foundational_skills TEXT,
                advanced_skills TEXT,
                expert_skills TEXT,
                application_areas TEXT,
                use_cases TEXT,
                tools_technologies TEXT,
                platforms_supported TEXT,
                programming_languages TEXT,
                frameworks_libraries TEXT,
                methodologies TEXT,
                best_practices TEXT,
                design_patterns TEXT,
                standards_compliance TEXT,
                vendor_specific INTEGER DEFAULT 0,
                vendor_names TEXT,
                open_source_alternative TEXT,
                proprietary_technology INTEGER DEFAULT 0,
                license_requirements TEXT,
                cost_of_ownership TEXT,
                training_resources TEXT,
                online_courses TEXT,
                books_recommended TEXT,
                practice_platforms TEXT,
                community_support TEXT,
                forums_groups TEXT,
                conferences_events TEXT,
                thought_leaders TEXT,
                benchmark_metrics TEXT,
                assessment_methods TEXT,
                skill_tests_available TEXT,
                practical_exercises TEXT,
                project_examples TEXT,
                portfolio_requirements TEXT,
                interview_questions TEXT,
                job_roles TEXT,
                typical_designations TEXT,
                experience_levels TEXT,
                seniority_mapping TEXT,
                career_progression TEXT,
                growth_opportunities TEXT,
                freelance_opportunities TEXT,
                remote_work_suitability TEXT,
                geographic_demand TEXT,
                industry_sectors TEXT,
                company_sizes TEXT,
                startup_relevance TEXT,
                enterprise_relevance TEXT,
                government_demand TEXT,
                academic_requirement TEXT,
                research_relevance TEXT,
                innovation_potential TEXT,
                collaboration_requirement TEXT,
                team_vs_individual TEXT,
                communication_importance TEXT,
                leadership_component TEXT,
                analytical_component TEXT,
                creative_component TEXT,
                technical_component TEXT,
                business_component TEXT,
                soft_skill_mix TEXT,
                hard_skill_mix TEXT,
                cognitive_load TEXT,
                physical_requirement TEXT,
                emotional_intelligence TEXT,
                cultural_awareness TEXT,
                language_requirement TEXT,
                certification_body TEXT,
                accreditation_status TEXT,
                industry_recognition TEXT,
                employer_preference TEXT,
                university_programs TEXT,
                bootcamp_availability TEXT,
                self_study_feasible INTEGER DEFAULT 1,
                mentorship_recommended INTEGER DEFAULT 0,
                hands_on_percentage INTEGER DEFAULT 50,
                theoretical_percentage INTEGER DEFAULT 50,
                continuous_learning INTEGER DEFAULT 1,
                update_frequency TEXT,
                version_control TEXT,
                backward_compatibility TEXT,
                migration_complexity TEXT,
                integration_capability TEXT,
                interoperability TEXT,
                scalability_aspect TEXT,
                security_relevance TEXT,
                compliance_importance TEXT,
                audit_requirement TEXT,
                documentation_quality TEXT,
                code_quality_impact TEXT,
                performance_optimization TEXT,
                debugging_capability TEXT,
                troubleshooting_aspect TEXT,
                problem_solving_type TEXT,
                analytical_thinking TEXT,
                critical_thinking TEXT,
                systems_thinking TEXT,
                design_thinking TEXT,
                strategic_thinking TEXT,
                operational_excellence TEXT,
                process_improvement TEXT,
                quality_assurance TEXT,
                risk_management TEXT,
                decision_making TEXT,
                prioritization TEXT,
                time_management TEXT,
                resource_management TEXT,
                stakeholder_management TEXT,
                vendor_management TEXT,
                client_facing TEXT,
                presentation_skills TEXT,
                negotiation_skills TEXT,
                conflict_resolution TEXT,
                change_management TEXT,
                agile_alignment TEXT,
                devops_culture TEXT,
                cloud_native TEXT,
                ai_ml_integration TEXT,
                automation_potential TEXT,
                no_code_low_code TEXT,
                citizen_developer TEXT,
                business_user_friendly TEXT,
                technical_depth TEXT,
                breadth_vs_depth TEXT,
                t_shaped_skill TEXT,
                full_stack_component TEXT,
                specialization_level TEXT,
                generalist_value TEXT,
                niche_expertise TEXT,
                transferable_skill TEXT,
                industry_agnostic TEXT,
                domain_specific TEXT,
                cross_functional TEXT,
                interdisciplinary TEXT,
                emerging_technology TEXT,
                mature_technology TEXT,
                legacy_system TEXT,
                modernization_skill TEXT,
                transformation_enabler TEXT,
                digital_literacy TEXT,
                data_literacy TEXT,
                cyber_literacy TEXT,
                ai_literacy TEXT,
                sustainability_aspect TEXT,
                ethics_consideration TEXT,
                privacy_awareness TEXT,
                accessibility_knowledge TEXT,
                inclusive_design TEXT,
                user_centric TEXT,
                customer_empathy TEXT,
                business_acumen TEXT,
                financial_literacy TEXT,
                commercial_awareness TEXT,
                entrepreneurial_mindset TEXT,
                innovation_thinking TEXT,
                creative_problem_solving TEXT,
                adaptability TEXT,
                resilience TEXT,
                growth_mindset TEXT,
                continuous_improvement TEXT,
                learning_agility TEXT,
                feedback_receptiveness TEXT,
                self_awareness TEXT,
                emotional_regulation TEXT,
                stress_management TEXT,
                work_life_balance TEXT,
                burnout_prevention TEXT,
                wellness_integration TEXT,
                remote_collaboration TEXT,
                virtual_communication TEXT,
                asynchronous_work TEXT,
                global_mindset TEXT,
                cultural_intelligence TEXT,
                diversity_inclusion TEXT,
                social_responsibility TEXT,
                environmental_awareness TEXT,
                governance_understanding TEXT,
                regulatory_knowledge TEXT,
                legal_awareness TEXT,
                ip_rights_knowledge TEXT,
                contract_understanding TEXT,
                procurement_basics TEXT,
                supply_chain_awareness TEXT,
                operations_knowledge TEXT,
                logistics_understanding TEXT,
                manufacturing_basics TEXT,
                service_delivery TEXT,
                customer_service TEXT,
                support_excellence TEXT,
                quality_mindset TEXT,
                continuous_monitoring TEXT,
                metrics_driven TEXT,
                data_informed TEXT,
                evidence_based TEXT,
                scientific_method TEXT,
                experimentation TEXT,
                hypothesis_testing TEXT,
                statistical_literacy TEXT,
                quantitative_analysis TEXT,
                qualitative_analysis TEXT,
                research_methodology TEXT,
                literature_review TEXT,
                academic_writing TEXT,
                technical_writing TEXT,
                documentation_skills TEXT,
                reporting_capability TEXT,
                visualization_skills TEXT,
                storytelling_ability TEXT,
                persuasion_skills TEXT,
                influence_capability TEXT,
                networking_ability TEXT,
                relationship_building TEXT,
                trust_establishment TEXT,
                credibility_building TEXT,
                personal_branding TEXT,
                professional_presence TEXT,
                executive_presence TEXT,
                public_speaking TEXT,
                facilitation_skills TEXT,
                training_delivery TEXT,
                coaching_capability TEXT,
                mentoring_ability TEXT,
                teaching_aptitude TEXT,
                knowledge_transfer TEXT,
                community_building TEXT,
                team_collaboration TEXT,
                cross_team_coordination TEXT,
                matrix_management TEXT,
                virtual_leadership TEXT,
                servant_leadership TEXT,
                transformational_leadership TEXT,
                situational_leadership TEXT,
                inclusive_leadership TEXT,
                ethical_leadership TEXT,
                is_active INTEGER DEFAULT 1,
                is_featured INTEGER DEFAULT 0,
                is_trending INTEGER DEFAULT 0,
                is_emerging INTEGER DEFAULT 0,
                is_deprecated INTEGER DEFAULT 0,
                deprecation_date TEXT,
                replacement_skill TEXT,
                version TEXT,
                last_updated TEXT,
                review_date TEXT,
                next_review_date TEXT,
                data_source TEXT,
                verification_status TEXT,
                quality_score INTEGER DEFAULT 0,
                completeness_score INTEGER DEFAULT 0,
                accuracy_rating INTEGER DEFAULT 0,
                contributor_count INTEGER DEFAULT 0,
                endorsement_count INTEGER DEFAULT 0,
                usage_count INTEGER DEFAULT 0,
                search_count INTEGER DEFAULT 0,
                view_count INTEGER DEFAULT 0,
                share_count INTEGER DEFAULT 0,
                bookmark_count INTEGER DEFAULT 0,
                rating_average REAL DEFAULT 0.0,
                rating_count INTEGER DEFAULT 0,
                feedback_score INTEGER DEFAULT 0,
                recommendation_score INTEGER DEFAULT 0,
                ai_confidence_score REAL DEFAULT 0.0,
                human_verified INTEGER DEFAULT 0,
                expert_reviewed INTEGER DEFAULT 0,
                peer_validated INTEGER DEFAULT 0,
                industry_endorsed INTEGER DEFAULT 0,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
                created_by TEXT,
                updated_by TEXT,
                notes TEXT,
                tags TEXT,
                keywords TEXT,
                seo_title TEXT,
                seo_description TEXT,
                seo_keywords TEXT,
                meta_data TEXT
            )
        ";
    }

    // Get all skills
    public static function getAllSkills() {
        $instance = new self();
        return $instance->all();
    }

    // Get skills by category
    public static function getSkillsByCategory($category) {
        $instance = new self();
        $db = $instance->getDb();
        $stmt = $db->prepare("SELECT * FROM {$instance->table} WHERE skill_category = ? AND is_active = 1 ORDER BY name");
        $stmt->execute([$category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get skills by type
    public static function getSkillsByType($type) {
        $instance = new self();
        $db = $instance->getDb();
        $stmt = $db->prepare("SELECT * FROM {$instance->table} WHERE skill_type = ? AND is_active = 1 ORDER BY name");
        $stmt->execute([$type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get skills by domain
    public static function getSkillsByDomain($domain) {
        $instance = new self();
        $db = $instance->getDb();
        $stmt = $db->prepare("SELECT * FROM {$instance->table} WHERE skill_domain = ? AND is_active = 1 ORDER BY name");
        $stmt->execute([$domain]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get skills by difficulty level
    public static function getSkillsByDifficulty($difficulty) {
        $instance = new self();
        $db = $instance->getDb();
        $stmt = $db->prepare("SELECT * FROM {$instance->table} WHERE difficulty_level = ? AND is_active = 1 ORDER BY name");
        $stmt->execute([$difficulty]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get skills by market demand
    public static function getSkillsByDemand($demand) {
        $instance = new self();
        $db = $instance->getDb();
        $stmt = $db->prepare("SELECT * FROM {$instance->table} WHERE market_demand = ? AND is_active = 1 ORDER BY popularity_score DESC");
        $stmt->execute([$demand]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get trending skills
    public static function getTrendingSkills($limit = 20) {
        $instance = new self();
        $db = $instance->getDb();
        $stmt = $db->prepare("SELECT * FROM {$instance->table} WHERE is_trending = 1 AND is_active = 1 ORDER BY popularity_score DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get emerging skills
    public static function getEmergingSkills($limit = 20) {
        $instance = new self();
        $db = $instance->getDb();
        $stmt = $db->prepare("SELECT * FROM {$instance->table} WHERE is_emerging = 1 AND is_active = 1 ORDER BY future_proof_score DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get featured skills
    public static function getFeaturedSkills($limit = 10) {
        $instance = new self();
        $db = $instance->getDb();
        $stmt = $db->prepare("SELECT * FROM {$instance->table} WHERE is_featured = 1 AND is_active = 1 ORDER BY popularity_score DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search skills
    public static function searchSkills($query) {
        $instance = new self();
        $db = $instance->getDb();
        $stmt = $db->prepare("SELECT * FROM {$instance->table} WHERE (name LIKE ? OR description LIKE ? OR keywords LIKE ? OR tags LIKE ?) AND is_active = 1 ORDER BY popularity_score DESC");
        $searchTerm = "%{$query}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get related skills
    public function getRelatedSkills() {
        if (!$this->related_skills) return [];
        $skillIds = json_decode($this->related_skills, true);
        if (!is_array($skillIds)) return [];

        $db = $this->getDb();
        $placeholders = implode(',', array_fill(0, count($skillIds), '?'));
        $stmt = $db->prepare("SELECT * FROM {$this->table} WHERE id IN ($placeholders) AND is_active = 1");
        $stmt->execute($skillIds);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get complementary skills
    public function getComplementarySkills() {
        if (!$this->complementary_skills) return [];
        $skillIds = json_decode($this->complementary_skills, true);
        if (!is_array($skillIds)) return [];

        $db = $this->getDb();
        $placeholders = implode(',', array_fill(0, count($skillIds), '?'));
        $stmt = $db->prepare("SELECT * FROM {$this->table} WHERE id IN ($placeholders) AND is_active = 1");
        $stmt->execute($skillIds);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get skill statistics
    public static function getStatistics() {
        $instance = new self();
        $db = $instance->getDb();

        $stats = [
            'total' => $db->query("SELECT COUNT(*) FROM {$instance->table} WHERE is_active = 1")->fetchColumn(),
            'trending' => $db->query("SELECT COUNT(*) FROM {$instance->table} WHERE is_trending = 1 AND is_active = 1")->fetchColumn(),
            'emerging' => $db->query("SELECT COUNT(*) FROM {$instance->table} WHERE is_emerging = 1 AND is_active = 1")->fetchColumn(),
            'featured' => $db->query("SELECT COUNT(*) FROM {$instance->table} WHERE is_featured = 1 AND is_active = 1")->fetchColumn(),
            'technical' => $db->query("SELECT COUNT(*) FROM {$instance->table} WHERE skill_category = 'Technical' AND is_active = 1")->fetchColumn(),
            'soft_skills' => $db->query("SELECT COUNT(*) FROM {$instance->table} WHERE skill_category = 'Soft Skills' AND is_active = 1")->fetchColumn(),
            'business' => $db->query("SELECT COUNT(*) FROM {$instance->table} WHERE skill_category = 'Business' AND is_active = 1")->fetchColumn(),
            'leadership' => $db->query("SELECT COUNT(*) FROM {$instance->table} WHERE skill_category = 'Leadership' AND is_active = 1")->fetchColumn(),
            'high_demand' => $db->query("SELECT COUNT(*) FROM {$instance->table} WHERE market_demand IN ('High', 'Very High') AND is_active = 1")->fetchColumn(),
            'with_certification' => $db->query("SELECT COUNT(*) FROM {$instance->table} WHERE certification_available = 1 AND is_active = 1")->fetchColumn()
        ];

        return $stats;
    }

    // Mark skill as trending
    public function markAsTrending() {
        $this->is_trending = 1;
        return $this->save();
    }

    // Mark skill as emerging
    public function markAsEmerging() {
        $this->is_emerging = 1;
        return $this->save();
    }

    // Mark skill as featured
    public function markAsFeatured() {
        $this->is_featured = 1;
        return $this->save();
    }

    // Deprecate skill
    public function deprecate($replacementSkill = null) {
        $this->is_deprecated = 1;
        $this->deprecation_date = date('Y-m-d');
        if ($replacementSkill) {
            $this->replacement_skill = $replacementSkill;
        }
        return $this->save();
    }

    // Activate skill
    public function activate() {
        $this->is_active = 1;
        return $this->save();
    }

    // Deactivate skill
    public function deactivate() {
        $this->is_active = 0;
        return $this->save();
    }

    // Increment view count
    public function incrementViewCount() {
        $this->view_count = ($this->view_count ?? 0) + 1;
        return $this->save();
    }

    // Increment usage count
    public function incrementUsageCount() {
        $this->usage_count = ($this->usage_count ?? 0) + 1;
        return $this->save();
    }

    // Update popularity score
    public function updatePopularityScore() {
        $score = ($this->view_count ?? 0) * 0.3 +
                 ($this->usage_count ?? 0) * 0.4 +
                 ($this->endorsement_count ?? 0) * 0.2 +
                 ($this->bookmark_count ?? 0) * 0.1;
        $this->popularity_score = round($score);
        return $this->save();
    }
}