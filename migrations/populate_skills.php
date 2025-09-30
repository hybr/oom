<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entities/PopularSkills.php';

class SkillsPopulator {
    private $skills = [];

    public function __construct() {
        $this->initializeSkills();
    }

    private function initializeSkills() {
        // Technical Skills - Programming Languages
        $this->skills[] = [
            'name' => 'Python',
            'code' => 'PYTHON',
            'slug' => 'python',
            'description' => 'High-level programming language for web development, data science, AI/ML, and automation',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Programming',
            'skill_family' => 'Programming Languages',
            'difficulty_level' => PopularSkills::DIFFICULTY_EASY,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 200,
            'popularity_score' => 95,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'JavaScript',
            'code' => 'JAVASCRIPT',
            'slug' => 'javascript',
            'description' => 'Essential programming language for web development, both frontend and backend',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Programming',
            'skill_family' => 'Programming Languages',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 180,
            'popularity_score' => 98,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Java',
            'code' => 'JAVA',
            'slug' => 'java',
            'description' => 'Enterprise-grade programming language for backend systems, Android development, and large-scale applications',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Programming',
            'skill_family' => 'Programming Languages',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 250,
            'popularity_score' => 90,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'C#',
            'code' => 'CSHARP',
            'slug' => 'csharp',
            'description' => 'Microsoft programming language for .NET applications, game development, and enterprise software',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Programming',
            'skill_family' => 'Programming Languages',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 240,
            'popularity_score' => 85,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'TypeScript',
            'code' => 'TYPESCRIPT',
            'slug' => 'typescript',
            'description' => 'Typed superset of JavaScript for building robust, scalable web applications',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Programming',
            'skill_family' => 'Programming Languages',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 100,
            'popularity_score' => 92,
            'is_trending' => 1,
            'certification_available' => 0,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Go',
            'code' => 'GOLANG',
            'slug' => 'golang',
            'description' => 'Google programming language for cloud services, microservices, and high-performance systems',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Programming',
            'skill_family' => 'Programming Languages',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 150,
            'popularity_score' => 88,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Rust',
            'code' => 'RUST',
            'slug' => 'rust',
            'description' => 'Systems programming language focused on safety, speed, and concurrency',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Programming',
            'skill_family' => 'Programming Languages',
            'difficulty_level' => PopularSkills::DIFFICULTY_HARD,
            'market_demand' => PopularSkills::DEMAND_MODERATE,
            'demand_trend' => PopularSkills::TREND_EMERGING,
            'time_to_learn_hours' => 300,
            'popularity_score' => 75,
            'is_emerging' => 1,
            'certification_available' => 0,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'PHP',
            'code' => 'PHP',
            'slug' => 'php',
            'description' => 'Server-side scripting language for web development and content management systems',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Programming',
            'skill_family' => 'Programming Languages',
            'difficulty_level' => PopularSkills::DIFFICULTY_EASY,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 120,
            'popularity_score' => 80,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        // Frontend Technologies
        $this->skills[] = [
            'name' => 'React',
            'code' => 'REACT',
            'slug' => 'react',
            'description' => 'Popular JavaScript library for building user interfaces and single-page applications',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Frontend',
            'skill_family' => 'JavaScript Frameworks',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 120,
            'popularity_score' => 96,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Angular',
            'code' => 'ANGULAR',
            'slug' => 'angular',
            'description' => 'Comprehensive TypeScript-based framework for building enterprise web applications',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Frontend',
            'skill_family' => 'JavaScript Frameworks',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 150,
            'popularity_score' => 85,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Vue.js',
            'code' => 'VUEJS',
            'slug' => 'vuejs',
            'description' => 'Progressive JavaScript framework for building interactive web interfaces',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Frontend',
            'skill_family' => 'JavaScript Frameworks',
            'difficulty_level' => PopularSkills::DIFFICULTY_EASY,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 100,
            'popularity_score' => 88,
            'is_trending' => 1,
            'certification_available' => 0,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'HTML/CSS',
            'code' => 'HTML_CSS',
            'slug' => 'html-css',
            'description' => 'Fundamental web technologies for structuring and styling web content',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_FOUNDATIONAL,
            'skill_domain' => 'Frontend',
            'skill_family' => 'Web Technologies',
            'difficulty_level' => PopularSkills::DIFFICULTY_BEGINNER,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 80,
            'popularity_score' => 100,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        // Backend Technologies
        $this->skills[] = [
            'name' => 'Node.js',
            'code' => 'NODEJS',
            'slug' => 'nodejs',
            'description' => 'JavaScript runtime for building scalable server-side applications',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Backend',
            'skill_family' => 'Runtime Environments',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 120,
            'popularity_score' => 94,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Django',
            'code' => 'DJANGO',
            'slug' => 'django',
            'description' => 'High-level Python web framework for rapid development and clean design',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Backend',
            'skill_family' => 'Web Frameworks',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 100,
            'popularity_score' => 86,
            'certification_available' => 0,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Spring Boot',
            'code' => 'SPRING_BOOT',
            'slug' => 'spring-boot',
            'description' => 'Java framework for building production-ready enterprise applications',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Backend',
            'skill_family' => 'Web Frameworks',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 180,
            'popularity_score' => 90,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        // Database Skills
        $this->skills[] = [
            'name' => 'SQL',
            'code' => 'SQL',
            'slug' => 'sql',
            'description' => 'Standard language for managing and querying relational databases',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_FOUNDATIONAL,
            'skill_domain' => 'Database',
            'skill_family' => 'Database Languages',
            'difficulty_level' => PopularSkills::DIFFICULTY_EASY,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 100,
            'popularity_score' => 95,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'PostgreSQL',
            'code' => 'POSTGRESQL',
            'slug' => 'postgresql',
            'description' => 'Advanced open-source relational database with powerful features',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Database',
            'skill_family' => 'Database Systems',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 80,
            'popularity_score' => 88,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'MongoDB',
            'code' => 'MONGODB',
            'slug' => 'mongodb',
            'description' => 'Popular NoSQL database for flexible, scalable document storage',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Database',
            'skill_family' => 'NoSQL Databases',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 60,
            'popularity_score' => 90,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Redis',
            'code' => 'REDIS',
            'slug' => 'redis',
            'description' => 'In-memory data structure store for caching and real-time applications',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Database',
            'skill_family' => 'Cache Systems',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 40,
            'popularity_score' => 85,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        // Cloud & DevOps
        $this->skills[] = [
            'name' => 'AWS',
            'code' => 'AWS',
            'slug' => 'aws',
            'description' => 'Amazon Web Services cloud platform for scalable computing and storage',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Cloud',
            'skill_family' => 'Cloud Platforms',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 200,
            'popularity_score' => 95,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Azure',
            'code' => 'AZURE',
            'slug' => 'azure',
            'description' => 'Microsoft cloud platform for building and managing applications',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Cloud',
            'skill_family' => 'Cloud Platforms',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 200,
            'popularity_score' => 92,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Docker',
            'code' => 'DOCKER',
            'slug' => 'docker',
            'description' => 'Containerization platform for packaging and deploying applications',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'DevOps',
            'skill_family' => 'Containerization',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 60,
            'popularity_score' => 93,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Kubernetes',
            'code' => 'KUBERNETES',
            'slug' => 'kubernetes',
            'description' => 'Container orchestration platform for automating deployment and scaling',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'DevOps',
            'skill_family' => 'Orchestration',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 150,
            'popularity_score' => 91,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'CI/CD',
            'code' => 'CICD',
            'slug' => 'cicd',
            'description' => 'Continuous Integration and Deployment practices for automated software delivery',
            'skill_category' => PopularSkills::CATEGORY_METHODOLOGY,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'DevOps',
            'skill_family' => 'Development Practices',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 80,
            'popularity_score' => 90,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Terraform',
            'code' => 'TERRAFORM',
            'slug' => 'terraform',
            'description' => 'Infrastructure as Code tool for building and managing cloud resources',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'DevOps',
            'skill_family' => 'Infrastructure as Code',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 100,
            'popularity_score' => 87,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        // AI/ML Skills
        $this->skills[] = [
            'name' => 'Machine Learning',
            'code' => 'ML',
            'slug' => 'machine-learning',
            'description' => 'Building systems that learn from data and improve performance automatically',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_SPECIALIZED,
            'skill_domain' => 'AI/ML',
            'skill_family' => 'Machine Learning',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 300,
            'popularity_score' => 97,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Deep Learning',
            'code' => 'DL',
            'slug' => 'deep-learning',
            'description' => 'Neural network-based machine learning for complex pattern recognition',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_SPECIALIZED,
            'skill_domain' => 'AI/ML',
            'skill_family' => 'Machine Learning',
            'difficulty_level' => PopularSkills::DIFFICULTY_EXPERT,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 400,
            'popularity_score' => 94,
            'is_trending' => 1,
            'is_emerging' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Natural Language Processing',
            'code' => 'NLP',
            'slug' => 'nlp',
            'description' => 'Processing and analyzing human language using computational methods',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_SPECIALIZED,
            'skill_domain' => 'AI/ML',
            'skill_family' => 'Machine Learning',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 250,
            'popularity_score' => 92,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Computer Vision',
            'code' => 'CV',
            'slug' => 'computer-vision',
            'description' => 'Enabling computers to understand and process visual information',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_SPECIALIZED,
            'skill_domain' => 'AI/ML',
            'skill_family' => 'Machine Learning',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 280,
            'popularity_score' => 89,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'TensorFlow',
            'code' => 'TENSORFLOW',
            'slug' => 'tensorflow',
            'description' => 'Open-source platform for machine learning and deep learning applications',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'AI/ML',
            'skill_family' => 'ML Frameworks',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 150,
            'popularity_score' => 88,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'PyTorch',
            'code' => 'PYTORCH',
            'slug' => 'pytorch',
            'description' => 'Deep learning framework popular in research and production',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'AI/ML',
            'skill_family' => 'ML Frameworks',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 150,
            'popularity_score' => 90,
            'is_trending' => 1,
            'certification_available' => 0,
            'self_study_feasible' => 1
        ];

        // Data Skills
        $this->skills[] = [
            'name' => 'Data Analysis',
            'code' => 'DATA_ANALYSIS',
            'slug' => 'data-analysis',
            'description' => 'Extracting insights and patterns from data to support decision-making',
            'skill_category' => PopularSkills::CATEGORY_ANALYTICAL,
            'skill_type' => PopularSkills::TYPE_HYBRID,
            'skill_domain' => 'Data Science',
            'skill_family' => 'Data Analysis',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 200,
            'popularity_score' => 93,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Data Visualization',
            'code' => 'DATA_VIZ',
            'slug' => 'data-visualization',
            'description' => 'Creating visual representations of data to communicate insights effectively',
            'skill_category' => PopularSkills::CATEGORY_ANALYTICAL,
            'skill_type' => PopularSkills::TYPE_HYBRID,
            'skill_domain' => 'Data Science',
            'skill_family' => 'Data Analysis',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 100,
            'popularity_score' => 87,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Statistical Analysis',
            'code' => 'STATISTICS',
            'slug' => 'statistical-analysis',
            'description' => 'Applying statistical methods to analyze and interpret data',
            'skill_category' => PopularSkills::CATEGORY_ANALYTICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Data Science',
            'skill_family' => 'Data Analysis',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 180,
            'popularity_score' => 85,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Big Data',
            'code' => 'BIG_DATA',
            'slug' => 'big-data',
            'description' => 'Processing and analyzing large-scale datasets using distributed systems',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_SPECIALIZED,
            'skill_domain' => 'Data Engineering',
            'skill_family' => 'Data Processing',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 250,
            'popularity_score' => 88,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Apache Spark',
            'code' => 'SPARK',
            'slug' => 'apache-spark',
            'description' => 'Unified analytics engine for large-scale data processing',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Data Engineering',
            'skill_family' => 'Data Processing',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 120,
            'popularity_score' => 82,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        // Soft Skills
        $this->skills[] = [
            'name' => 'Communication',
            'code' => 'COMMUNICATION',
            'slug' => 'communication',
            'description' => 'Effectively conveying information and ideas to others verbally and in writing',
            'skill_category' => PopularSkills::CATEGORY_COMMUNICATION,
            'skill_type' => PopularSkills::TYPE_SOFT_SKILL,
            'skill_domain' => 'Interpersonal',
            'skill_family' => 'Communication',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 500,
            'popularity_score' => 100,
            'is_featured' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Leadership',
            'code' => 'LEADERSHIP',
            'slug' => 'leadership',
            'description' => 'Guiding and inspiring teams to achieve goals and objectives',
            'skill_category' => PopularSkills::CATEGORY_LEADERSHIP,
            'skill_type' => PopularSkills::TYPE_SOFT_SKILL,
            'skill_domain' => 'Management',
            'skill_family' => 'Leadership',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 1000,
            'popularity_score' => 98,
            'is_featured' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Problem Solving',
            'code' => 'PROBLEM_SOLVING',
            'slug' => 'problem-solving',
            'description' => 'Identifying issues and developing effective solutions',
            'skill_category' => PopularSkills::CATEGORY_ANALYTICAL,
            'skill_type' => PopularSkills::TYPE_SOFT_SKILL,
            'skill_domain' => 'Cognitive',
            'skill_family' => 'Critical Thinking',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 800,
            'popularity_score' => 99,
            'is_featured' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Critical Thinking',
            'code' => 'CRITICAL_THINKING',
            'slug' => 'critical-thinking',
            'description' => 'Analyzing information objectively to make reasoned judgments',
            'skill_category' => PopularSkills::CATEGORY_ANALYTICAL,
            'skill_type' => PopularSkills::TYPE_SOFT_SKILL,
            'skill_domain' => 'Cognitive',
            'skill_family' => 'Critical Thinking',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 600,
            'popularity_score' => 96,
            'is_featured' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Teamwork',
            'code' => 'TEAMWORK',
            'slug' => 'teamwork',
            'description' => 'Collaborating effectively with others to achieve common goals',
            'skill_category' => PopularSkills::CATEGORY_SOFT_SKILLS,
            'skill_type' => PopularSkills::TYPE_SOFT_SKILL,
            'skill_domain' => 'Interpersonal',
            'skill_family' => 'Collaboration',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 500,
            'popularity_score' => 97,
            'is_featured' => 1,
            'certification_available' => 0,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Adaptability',
            'code' => 'ADAPTABILITY',
            'slug' => 'adaptability',
            'description' => 'Adjusting effectively to changing circumstances and requirements',
            'skill_category' => PopularSkills::CATEGORY_SOFT_SKILLS,
            'skill_type' => PopularSkills::TYPE_SOFT_SKILL,
            'skill_domain' => 'Personal',
            'skill_family' => 'Flexibility',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 400,
            'popularity_score' => 94,
            'is_trending' => 1,
            'certification_available' => 0,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Time Management',
            'code' => 'TIME_MGMT',
            'slug' => 'time-management',
            'description' => 'Organizing and prioritizing tasks to maximize productivity',
            'skill_category' => PopularSkills::CATEGORY_MANAGEMENT,
            'skill_type' => PopularSkills::TYPE_SOFT_SKILL,
            'skill_domain' => 'Personal',
            'skill_family' => 'Productivity',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 300,
            'popularity_score' => 95,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Emotional Intelligence',
            'code' => 'EQ',
            'slug' => 'emotional-intelligence',
            'description' => 'Understanding and managing emotions in oneself and others',
            'skill_category' => PopularSkills::CATEGORY_SOFT_SKILLS,
            'skill_type' => PopularSkills::TYPE_SOFT_SKILL,
            'skill_domain' => 'Interpersonal',
            'skill_family' => 'Emotional Skills',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 700,
            'popularity_score' => 91,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        // Business Skills
        $this->skills[] = [
            'name' => 'Project Management',
            'code' => 'PM',
            'slug' => 'project-management',
            'description' => 'Planning, executing, and delivering projects on time and within budget',
            'skill_category' => PopularSkills::CATEGORY_MANAGEMENT,
            'skill_type' => PopularSkills::TYPE_HYBRID,
            'skill_domain' => 'Project Management',
            'skill_family' => 'Management',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 300,
            'popularity_score' => 96,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Agile Methodologies',
            'code' => 'AGILE',
            'slug' => 'agile',
            'description' => 'Iterative approach to project management and software development',
            'skill_category' => PopularSkills::CATEGORY_METHODOLOGY,
            'skill_type' => PopularSkills::TYPE_HYBRID,
            'skill_domain' => 'Project Management',
            'skill_family' => 'Methodologies',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 150,
            'popularity_score' => 94,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Scrum',
            'code' => 'SCRUM',
            'slug' => 'scrum',
            'description' => 'Framework for managing and completing complex projects with sprints',
            'skill_category' => PopularSkills::CATEGORY_METHODOLOGY,
            'skill_type' => PopularSkills::TYPE_HYBRID,
            'skill_domain' => 'Project Management',
            'skill_family' => 'Methodologies',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 80,
            'popularity_score' => 92,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Business Analysis',
            'code' => 'BA',
            'slug' => 'business-analysis',
            'description' => 'Identifying business needs and determining solutions to problems',
            'skill_category' => PopularSkills::CATEGORY_BUSINESS,
            'skill_type' => PopularSkills::TYPE_HYBRID,
            'skill_domain' => 'Business',
            'skill_family' => 'Analysis',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 250,
            'popularity_score' => 88,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Product Management',
            'code' => 'PROD_MGMT',
            'slug' => 'product-management',
            'description' => 'Defining product vision, strategy, and roadmap for success',
            'skill_category' => PopularSkills::CATEGORY_MANAGEMENT,
            'skill_type' => PopularSkills::TYPE_HYBRID,
            'skill_domain' => 'Product',
            'skill_family' => 'Management',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 400,
            'popularity_score' => 93,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Strategic Planning',
            'code' => 'STRATEGY',
            'slug' => 'strategic-planning',
            'description' => 'Developing long-term goals and plans to achieve organizational objectives',
            'skill_category' => PopularSkills::CATEGORY_BUSINESS,
            'skill_type' => PopularSkills::TYPE_SOFT_SKILL,
            'skill_domain' => 'Business',
            'skill_family' => 'Strategy',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 500,
            'popularity_score' => 90,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        // Security Skills
        $this->skills[] = [
            'name' => 'Cybersecurity',
            'code' => 'CYBERSEC',
            'slug' => 'cybersecurity',
            'description' => 'Protecting systems, networks, and data from cyber threats',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_SPECIALIZED,
            'skill_domain' => 'Security',
            'skill_family' => 'Information Security',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 350,
            'popularity_score' => 95,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Penetration Testing',
            'code' => 'PENTEST',
            'slug' => 'penetration-testing',
            'description' => 'Ethical hacking to identify and fix security vulnerabilities',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_SPECIALIZED,
            'skill_domain' => 'Security',
            'skill_family' => 'Information Security',
            'difficulty_level' => PopularSkills::DIFFICULTY_EXPERT,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 400,
            'popularity_score' => 86,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Security Architecture',
            'code' => 'SEC_ARCH',
            'slug' => 'security-architecture',
            'description' => 'Designing secure systems and infrastructure to protect against threats',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_SPECIALIZED,
            'skill_domain' => 'Security',
            'skill_family' => 'Information Security',
            'difficulty_level' => PopularSkills::DIFFICULTY_EXPERT,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 450,
            'popularity_score' => 88,
            'certification_available' => 1,
            'self_study_feasible' => 0
        ];

        // Design Skills
        $this->skills[] = [
            'name' => 'UI/UX Design',
            'code' => 'UIUX',
            'slug' => 'ui-ux-design',
            'description' => 'Creating intuitive and engaging user interfaces and experiences',
            'skill_category' => PopularSkills::CATEGORY_CREATIVE,
            'skill_type' => PopularSkills::TYPE_HYBRID,
            'skill_domain' => 'Design',
            'skill_family' => 'User Experience',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 300,
            'popularity_score' => 94,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Figma',
            'code' => 'FIGMA',
            'slug' => 'figma',
            'description' => 'Collaborative interface design tool for creating prototypes and designs',
            'skill_category' => PopularSkills::CATEGORY_TOOLS,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Design',
            'skill_family' => 'Design Tools',
            'difficulty_level' => PopularSkills::DIFFICULTY_EASY,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 60,
            'popularity_score' => 92,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Adobe Creative Suite',
            'code' => 'ADOBE',
            'slug' => 'adobe-creative-suite',
            'description' => 'Professional design and multimedia creation software suite',
            'skill_category' => PopularSkills::CATEGORY_TOOLS,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Design',
            'skill_family' => 'Design Tools',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 200,
            'popularity_score' => 87,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        // Mobile Development
        $this->skills[] = [
            'name' => 'iOS Development',
            'code' => 'IOS_DEV',
            'slug' => 'ios-development',
            'description' => 'Building applications for Apple iOS devices using Swift and Xcode',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Mobile',
            'skill_family' => 'Mobile Development',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 250,
            'popularity_score' => 88,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Android Development',
            'code' => 'ANDROID_DEV',
            'slug' => 'android-development',
            'description' => 'Creating applications for Android devices using Kotlin and Android Studio',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Mobile',
            'skill_family' => 'Mobile Development',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 250,
            'popularity_score' => 86,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'React Native',
            'code' => 'REACT_NATIVE',
            'slug' => 'react-native',
            'description' => 'Cross-platform mobile development framework using React',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Mobile',
            'skill_family' => 'Mobile Development',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 150,
            'popularity_score' => 89,
            'is_trending' => 1,
            'certification_available' => 0,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Flutter',
            'code' => 'FLUTTER',
            'slug' => 'flutter',
            'description' => 'Google UI toolkit for building natively compiled multiplatform applications',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Mobile',
            'skill_family' => 'Mobile Development',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 130,
            'popularity_score' => 90,
            'is_trending' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        // Testing Skills
        $this->skills[] = [
            'name' => 'Test Automation',
            'code' => 'TEST_AUTO',
            'slug' => 'test-automation',
            'description' => 'Automating software testing to improve efficiency and coverage',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Quality Assurance',
            'skill_family' => 'Testing',
            'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 150,
            'popularity_score' => 87,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Selenium',
            'code' => 'SELENIUM',
            'slug' => 'selenium',
            'description' => 'Popular framework for automating web browser testing',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Quality Assurance',
            'skill_family' => 'Testing Tools',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 100,
            'popularity_score' => 84,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'API Testing',
            'code' => 'API_TEST',
            'slug' => 'api-testing',
            'description' => 'Testing application programming interfaces for functionality and reliability',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_HARD_SKILL,
            'skill_domain' => 'Quality Assurance',
            'skill_family' => 'Testing',
            'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 120,
            'popularity_score' => 86,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        // Version Control
        $this->skills[] = [
            'name' => 'Git',
            'code' => 'GIT',
            'slug' => 'git',
            'description' => 'Distributed version control system for tracking code changes',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_FOUNDATIONAL,
            'skill_domain' => 'Development Tools',
            'skill_family' => 'Version Control',
            'difficulty_level' => PopularSkills::DIFFICULTY_EASY,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 60,
            'popularity_score' => 99,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'GitHub',
            'code' => 'GITHUB',
            'slug' => 'github',
            'description' => 'Platform for version control and collaboration using Git',
            'skill_category' => PopularSkills::CATEGORY_TOOLS,
            'skill_type' => PopularSkills::TYPE_FOUNDATIONAL,
            'skill_domain' => 'Development Tools',
            'skill_family' => 'Version Control',
            'difficulty_level' => PopularSkills::DIFFICULTY_EASY,
            'market_demand' => PopularSkills::DEMAND_VERY_HIGH,
            'demand_trend' => PopularSkills::TREND_STABLE,
            'time_to_learn_hours' => 40,
            'popularity_score' => 97,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        // Emerging Technologies
        $this->skills[] = [
            'name' => 'Blockchain',
            'code' => 'BLOCKCHAIN',
            'slug' => 'blockchain',
            'description' => 'Distributed ledger technology for secure, decentralized transactions',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_SPECIALIZED,
            'skill_domain' => 'Emerging Tech',
            'skill_family' => 'Blockchain',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_MODERATE,
            'demand_trend' => PopularSkills::TREND_EMERGING,
            'time_to_learn_hours' => 250,
            'popularity_score' => 78,
            'is_emerging' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Web3',
            'code' => 'WEB3',
            'slug' => 'web3',
            'description' => 'Decentralized internet built on blockchain technology',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_SPECIALIZED,
            'skill_domain' => 'Emerging Tech',
            'skill_family' => 'Blockchain',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_MODERATE,
            'demand_trend' => PopularSkills::TREND_EMERGING,
            'time_to_learn_hours' => 200,
            'popularity_score' => 75,
            'is_emerging' => 1,
            'certification_available' => 0,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'IoT',
            'code' => 'IOT',
            'slug' => 'iot',
            'description' => 'Internet of Things - connecting physical devices to the internet',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_SPECIALIZED,
            'skill_domain' => 'Emerging Tech',
            'skill_family' => 'IoT',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_HIGH,
            'demand_trend' => PopularSkills::TREND_RISING,
            'time_to_learn_hours' => 280,
            'popularity_score' => 82,
            'is_emerging' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'AR/VR Development',
            'code' => 'ARVR',
            'slug' => 'ar-vr-development',
            'description' => 'Creating augmented and virtual reality experiences',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_SPECIALIZED,
            'skill_domain' => 'Emerging Tech',
            'skill_family' => 'Extended Reality',
            'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED,
            'market_demand' => PopularSkills::DEMAND_MODERATE,
            'demand_trend' => PopularSkills::TREND_EMERGING,
            'time_to_learn_hours' => 300,
            'popularity_score' => 77,
            'is_emerging' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 1
        ];

        $this->skills[] = [
            'name' => 'Quantum Computing',
            'code' => 'QUANTUM',
            'slug' => 'quantum-computing',
            'description' => 'Computing using quantum mechanical phenomena for complex calculations',
            'skill_category' => PopularSkills::CATEGORY_TECHNICAL,
            'skill_type' => PopularSkills::TYPE_SPECIALIZED,
            'skill_domain' => 'Emerging Tech',
            'skill_family' => 'Quantum',
            'difficulty_level' => PopularSkills::DIFFICULTY_EXPERT,
            'market_demand' => PopularSkills::DEMAND_LOW,
            'demand_trend' => PopularSkills::TREND_EMERGING,
            'time_to_learn_hours' => 500,
            'popularity_score' => 65,
            'is_emerging' => 1,
            'certification_available' => 1,
            'self_study_feasible' => 0
        ];

        // Add more comprehensive skills to reach 200+
        $additionalSkills = [
            // More Programming Languages
            ['name' => 'Ruby', 'code' => 'RUBY', 'slug' => 'ruby', 'skill_category' => PopularSkills::CATEGORY_TECHNICAL, 'difficulty_level' => PopularSkills::DIFFICULTY_EASY, 'market_demand' => PopularSkills::DEMAND_MODERATE],
            ['name' => 'Swift', 'code' => 'SWIFT', 'slug' => 'swift', 'skill_category' => PopularSkills::CATEGORY_TECHNICAL, 'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE, 'market_demand' => PopularSkills::DEMAND_HIGH],
            ['name' => 'Kotlin', 'code' => 'KOTLIN', 'slug' => 'kotlin', 'skill_category' => PopularSkills::CATEGORY_TECHNICAL, 'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE, 'market_demand' => PopularSkills::DEMAND_HIGH],
            ['name' => 'Scala', 'code' => 'SCALA', 'slug' => 'scala', 'skill_category' => PopularSkills::CATEGORY_TECHNICAL, 'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED, 'market_demand' => PopularSkills::DEMAND_MODERATE],
            ['name' => 'R', 'code' => 'R_LANG', 'slug' => 'r-language', 'skill_category' => PopularSkills::CATEGORY_TECHNICAL, 'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE, 'market_demand' => PopularSkills::DEMAND_HIGH],

            // More Soft Skills
            ['name' => 'Active Listening', 'code' => 'LISTENING', 'slug' => 'active-listening', 'skill_category' => PopularSkills::CATEGORY_COMMUNICATION, 'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE, 'market_demand' => PopularSkills::DEMAND_HIGH],
            ['name' => 'Conflict Management', 'code' => 'CONFLICT_MGMT', 'slug' => 'conflict-management', 'skill_category' => PopularSkills::CATEGORY_SOFT_SKILLS, 'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE, 'market_demand' => PopularSkills::DEMAND_HIGH],
            ['name' => 'Negotiation', 'code' => 'NEGOTIATION', 'slug' => 'negotiation', 'skill_category' => PopularSkills::CATEGORY_BUSINESS, 'difficulty_level' => PopularSkills::DIFFICULTY_ADVANCED, 'market_demand' => PopularSkills::DEMAND_HIGH],
            ['name' => 'Decision Making', 'code' => 'DECISION_MAKING', 'slug' => 'decision-making', 'skill_category' => PopularSkills::CATEGORY_MANAGEMENT, 'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE, 'market_demand' => PopularSkills::DEMAND_VERY_HIGH],
            ['name' => 'Public Speaking', 'code' => 'PUBLIC_SPEAK', 'slug' => 'public-speaking', 'skill_category' => PopularSkills::CATEGORY_COMMUNICATION, 'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE, 'market_demand' => PopularSkills::DEMAND_HIGH],

            // Business & Sales
            ['name' => 'Sales', 'code' => 'SALES', 'slug' => 'sales', 'skill_category' => PopularSkills::CATEGORY_BUSINESS, 'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE, 'market_demand' => PopularSkills::DEMAND_VERY_HIGH],
            ['name' => 'Marketing', 'code' => 'MARKETING', 'slug' => 'marketing', 'skill_category' => PopularSkills::CATEGORY_BUSINESS, 'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE, 'market_demand' => PopularSkills::DEMAND_VERY_HIGH],
            ['name' => 'Digital Marketing', 'code' => 'DIGITAL_MKT', 'slug' => 'digital-marketing', 'skill_category' => PopularSkills::CATEGORY_BUSINESS, 'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE, 'market_demand' => PopularSkills::DEMAND_VERY_HIGH],
            ['name' => 'SEO', 'code' => 'SEO', 'slug' => 'seo', 'skill_category' => PopularSkills::CATEGORY_BUSINESS, 'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE, 'market_demand' => PopularSkills::DEMAND_HIGH],
            ['name' => 'Content Marketing', 'code' => 'CONTENT_MKT', 'slug' => 'content-marketing', 'skill_category' => PopularSkills::CATEGORY_BUSINESS, 'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE, 'market_demand' => PopularSkills::DEMAND_HIGH],
            ['name' => 'Social Media Marketing', 'code' => 'SOCIAL_MKT', 'slug' => 'social-media-marketing', 'skill_category' => PopularSkills::CATEGORY_BUSINESS, 'difficulty_level' => PopularSkills::DIFFICULTY_EASY, 'market_demand' => PopularSkills::DEMAND_HIGH],
            ['name' => 'Email Marketing', 'code' => 'EMAIL_MKT', 'slug' => 'email-marketing', 'skill_category' => PopularSkills::CATEGORY_BUSINESS, 'difficulty_level' => PopularSkills::DIFFICULTY_EASY, 'market_demand' => PopularSkills::DEMAND_MODERATE],
            ['name' => 'Customer Relationship Management', 'code' => 'CRM', 'slug' => 'crm', 'skill_category' => PopularSkills::CATEGORY_BUSINESS, 'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE, 'market_demand' => PopularSkills::DEMAND_HIGH],
            ['name' => 'Account Management', 'code' => 'ACCOUNT_MGMT', 'slug' => 'account-management', 'skill_category' => PopularSkills::CATEGORY_BUSINESS, 'difficulty_level' => PopularSkills::DIFFICULTY_MODERATE, 'market_demand' => PopularSkills::DEMAND_HIGH],
            ['name' => 'Business Development', 'code' => 'BIZ_DEV', 'slug' => 'business-development', 'skill_category' => PopularSkills::CATEGORY_BUSINESS, 'difficulty_level' => PopularSkills::DIFFICULTY_INTERMEDIATE, 'market_demand' => PopularSkills::DEMAND_HIGH]
        ];

        foreach ($additionalSkills as $skill) {
            $skill['description'] = $skill['description'] ?? 'Important professional skill';
            $skill['skill_type'] = $skill['skill_type'] ?? PopularSkills::TYPE_HARD_SKILL;
            $skill['skill_domain'] = $skill['skill_domain'] ?? 'Professional';
            $skill['skill_family'] = $skill['skill_family'] ?? 'General';
            $skill['demand_trend'] = $skill['demand_trend'] ?? PopularSkills::TREND_STABLE;
            $skill['time_to_learn_hours'] = $skill['time_to_learn_hours'] ?? 100;
            $skill['popularity_score'] = $skill['popularity_score'] ?? 80;
            $skill['is_trending'] = $skill['is_trending'] ?? 0;
            $skill['certification_available'] = $skill['certification_available'] ?? 1;
            $skill['self_study_feasible'] = $skill['self_study_feasible'] ?? 1;
            $this->skills[] = $skill;
        }
    }

    public function populate() {
        echo "\n==============================================\n";
        echo "       POPULAR SKILLS POPULATOR\n";
        echo "==============================================\n\n";

        echo "Starting skills population...\n\n";

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($this->skills as $skillData) {
            try {
                $skill = new PopularSkills();

                foreach ($skillData as $key => $value) {
                    $skill->$key = $value;
                }

                if ($skill->save()) {
                    echo "  ✓ Created: {$skillData['name']}\n";
                    $createdCount++;
                } else {
                    echo "  ✗ Error creating skill '{$skillData['name']}'\n";
                    $skippedCount++;
                }
            } catch (Exception $e) {
                echo "  ✗ Error creating skill '{$skillData['name']}': " . $e->getMessage() . "\n";
                $skippedCount++;
            }
        }

        echo "\n==============================================\n";
        echo "Skills population completed successfully!\n";
        echo "Total skills created: {$createdCount}\n";
        echo "Total skills skipped: {$skippedCount}\n";
        echo "==============================================\n\n";

        echo "Done!\n";
    }
}

// Run the populator
$populator = new SkillsPopulator();
$populator->populate();