<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entities/PopularEducationSubject.php';

class EducationSubjectsPopulator {
    private $subjects = [];
    private $subjectIdMap = []; // To track subject IDs for prerequisites

    public function __construct() {
        $this->initializeSubjects();
    }

    private function initializeSubjects() {
        // ==================== PRIMARY LEVEL SUBJECTS ====================

        // Mathematics - Primary
        $this->subjects[] = [
            'name' => 'Primary Mathematics',
            'code' => 'PMATH01',
            'category' => 'Mathematics',
            'level' => 'Primary',
            'description' => 'Basic arithmetic, number systems, simple geometry, and problem-solving',
            'credits' => 4,
            'duration_hours' => 180,
            'difficulty_level' => 'Beginner',
            'is_core' => 1,
            'is_mandatory' => 1
        ];

        // Language - Primary
        $this->subjects[] = [
            'name' => 'Primary English Language',
            'code' => 'PENG01',
            'category' => 'Language',
            'level' => 'Primary',
            'description' => 'Basic reading, writing, grammar, and communication skills',
            'credits' => 4,
            'duration_hours' => 180,
            'difficulty_level' => 'Beginner',
            'is_core' => 1,
            'is_mandatory' => 1
        ];

        // Science - Primary
        $this->subjects[] = [
            'name' => 'Primary Science',
            'code' => 'PSCI01',
            'category' => 'Science',
            'level' => 'Primary',
            'description' => 'Introduction to plants, animals, weather, and basic scientific concepts',
            'credits' => 3,
            'duration_hours' => 150,
            'difficulty_level' => 'Beginner',
            'is_core' => 1,
            'is_mandatory' => 1,
            'lab_required' => 1
        ];

        // Social Studies - Primary
        $this->subjects[] = [
            'name' => 'Primary Social Studies',
            'code' => 'PSOC01',
            'category' => 'Social Sciences',
            'level' => 'Primary',
            'description' => 'Basic geography, history, and social awareness',
            'credits' => 3,
            'duration_hours' => 120,
            'difficulty_level' => 'Beginner',
            'is_core' => 1
        ];

        // Arts - Primary
        $this->subjects[] = [
            'name' => 'Primary Arts and Crafts',
            'code' => 'PART01',
            'category' => 'Fine Arts',
            'level' => 'Primary',
            'description' => 'Drawing, coloring, basic craft work, and creative expression',
            'credits' => 2,
            'duration_hours' => 90,
            'difficulty_level' => 'Beginner'
        ];

        // Physical Education - Primary
        $this->subjects[] = [
            'name' => 'Primary Physical Education',
            'code' => 'PPE01',
            'category' => 'Physical Education',
            'level' => 'Primary',
            'description' => 'Basic physical activities, sports, and health awareness',
            'credits' => 2,
            'duration_hours' => 90,
            'difficulty_level' => 'Beginner',
            'is_mandatory' => 1
        ];

        // ==================== SECONDARY LEVEL SUBJECTS ====================

        // Mathematics - Secondary
        $this->subjects[] = [
            'name' => 'Secondary Mathematics',
            'code' => 'SMATH01',
            'category' => 'Mathematics',
            'level' => 'Secondary',
            'description' => 'Algebra, geometry, trigonometry, and advanced problem-solving',
            'credits' => 5,
            'duration_hours' => 240,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1,
            'is_mandatory' => 1,
            'prerequisites_codes' => ['PMATH01']
        ];

        // Physics - Secondary
        $this->subjects[] = [
            'name' => 'Secondary Physics',
            'code' => 'SPHY01',
            'category' => 'Science',
            'level' => 'Secondary',
            'description' => 'Mechanics, heat, light, sound, electricity, and magnetism',
            'credits' => 4,
            'duration_hours' => 200,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1,
            'is_mandatory' => 1,
            'lab_required' => 1,
            'practical_component' => 30,
            'theory_component' => 70,
            'prerequisites_codes' => ['PSCI01', 'SMATH01']
        ];

        // Chemistry - Secondary
        $this->subjects[] = [
            'name' => 'Secondary Chemistry',
            'code' => 'SCHEM01',
            'category' => 'Science',
            'level' => 'Secondary',
            'description' => 'Elements, compounds, reactions, acids, bases, and organic chemistry basics',
            'credits' => 4,
            'duration_hours' => 200,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1,
            'is_mandatory' => 1,
            'lab_required' => 1,
            'practical_component' => 30,
            'theory_component' => 70,
            'prerequisites_codes' => ['PSCI01']
        ];

        // Biology - Secondary
        $this->subjects[] = [
            'name' => 'Secondary Biology',
            'code' => 'SBIO01',
            'category' => 'Science',
            'level' => 'Secondary',
            'description' => 'Cell biology, human body systems, plants, ecology, and evolution',
            'credits' => 4,
            'duration_hours' => 200,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1,
            'is_mandatory' => 1,
            'lab_required' => 1,
            'practical_component' => 30,
            'theory_component' => 70,
            'prerequisites_codes' => ['PSCI01']
        ];

        // English - Secondary
        $this->subjects[] = [
            'name' => 'Secondary English Language & Literature',
            'code' => 'SENG01',
            'category' => 'Language',
            'level' => 'Secondary',
            'description' => 'Advanced grammar, literature analysis, essay writing, and communication',
            'credits' => 5,
            'duration_hours' => 220,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1,
            'is_mandatory' => 1,
            'prerequisites_codes' => ['PENG01']
        ];

        // History - Secondary
        $this->subjects[] = [
            'name' => 'Secondary History',
            'code' => 'SHIST01',
            'category' => 'Humanities',
            'level' => 'Secondary',
            'description' => 'World history, national history, historical events, and civilizations',
            'credits' => 3,
            'duration_hours' => 150,
            'difficulty_level' => 'Intermediate',
            'is_elective' => 1
        ];

        // Geography - Secondary
        $this->subjects[] = [
            'name' => 'Secondary Geography',
            'code' => 'SGEO01',
            'category' => 'Social Sciences',
            'level' => 'Secondary',
            'description' => 'Physical geography, human geography, maps, and environmental studies',
            'credits' => 3,
            'duration_hours' => 150,
            'difficulty_level' => 'Intermediate',
            'is_elective' => 1
        ];

        // Computer Science - Secondary
        $this->subjects[] = [
            'name' => 'Secondary Computer Science',
            'code' => 'SCS01',
            'category' => 'Computer Science',
            'level' => 'Secondary',
            'description' => 'Basic programming, computer fundamentals, and digital literacy',
            'credits' => 3,
            'duration_hours' => 150,
            'difficulty_level' => 'Intermediate',
            'lab_required' => 1,
            'practical_component' => 50,
            'theory_component' => 50
        ];

        // ==================== HIGHER SECONDARY LEVEL SUBJECTS ====================

        // Advanced Mathematics
        $this->subjects[] = [
            'name' => 'Calculus and Analytical Geometry',
            'code' => 'HSMATH01',
            'category' => 'Mathematics',
            'level' => 'Higher Secondary',
            'description' => 'Differential and integral calculus, limits, continuity, and 3D geometry',
            'credits' => 6,
            'duration_hours' => 300,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'is_mandatory' => 1,
            'prerequisites_codes' => ['SMATH01']
        ];

        // Advanced Physics
        $this->subjects[] = [
            'name' => 'Advanced Physics',
            'code' => 'HSPHY01',
            'category' => 'Science',
            'level' => 'Higher Secondary',
            'description' => 'Modern physics, thermodynamics, optics, and electromagnetic theory',
            'credits' => 6,
            'duration_hours' => 300,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'lab_required' => 1,
            'practical_component' => 30,
            'theory_component' => 70,
            'prerequisites_codes' => ['SPHY01', 'SMATH01']
        ];

        // Advanced Chemistry
        $this->subjects[] = [
            'name' => 'Advanced Chemistry',
            'code' => 'HSCHEM01',
            'category' => 'Science',
            'level' => 'Higher Secondary',
            'description' => 'Organic chemistry, physical chemistry, and inorganic chemistry',
            'credits' => 6,
            'duration_hours' => 300,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'lab_required' => 1,
            'practical_component' => 30,
            'theory_component' => 70,
            'prerequisites_codes' => ['SCHEM01']
        ];

        // Advanced Biology
        $this->subjects[] = [
            'name' => 'Advanced Biology',
            'code' => 'HSBIO01',
            'category' => 'Science',
            'level' => 'Higher Secondary',
            'description' => 'Genetics, molecular biology, biotechnology, and human physiology',
            'credits' => 6,
            'duration_hours' => 300,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'lab_required' => 1,
            'practical_component' => 30,
            'theory_component' => 70,
            'prerequisites_codes' => ['SBIO01']
        ];

        // Accountancy
        $this->subjects[] = [
            'name' => 'Accountancy',
            'code' => 'HSACC01',
            'category' => 'Commerce',
            'level' => 'Higher Secondary',
            'description' => 'Financial accounting, partnership accounts, and company accounts',
            'credits' => 6,
            'duration_hours' => 280,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1
        ];

        // Business Studies
        $this->subjects[] = [
            'name' => 'Business Studies',
            'code' => 'HSBUS01',
            'category' => 'Commerce',
            'level' => 'Higher Secondary',
            'description' => 'Business organization, management principles, and marketing',
            'credits' => 5,
            'duration_hours' => 250,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1
        ];

        // Economics
        $this->subjects[] = [
            'name' => 'Economics',
            'code' => 'HSECON01',
            'category' => 'Commerce',
            'level' => 'Higher Secondary',
            'description' => 'Microeconomics, macroeconomics, and Indian economy',
            'credits' => 5,
            'duration_hours' => 250,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1
        ];

        // Political Science
        $this->subjects[] = [
            'name' => 'Political Science',
            'code' => 'HSPOL01',
            'category' => 'Social Sciences',
            'level' => 'Higher Secondary',
            'description' => 'Political theory, Indian constitution, and international relations',
            'credits' => 5,
            'duration_hours' => 240,
            'difficulty_level' => 'Intermediate',
            'is_elective' => 1
        ];

        // Psychology
        $this->subjects[] = [
            'name' => 'Psychology',
            'code' => 'HSPSY01',
            'category' => 'Social Sciences',
            'level' => 'Higher Secondary',
            'description' => 'Introduction to psychology, human behavior, and mental processes',
            'credits' => 4,
            'duration_hours' => 200,
            'difficulty_level' => 'Intermediate',
            'is_elective' => 1
        ];

        // ==================== BACHELOR LEVEL SUBJECTS ====================

        // Computer Science - Bachelor
        $this->subjects[] = [
            'name' => 'Data Structures and Algorithms',
            'code' => 'BCSDS01',
            'category' => 'Computer Science',
            'level' => 'Bachelor',
            'description' => 'Arrays, linked lists, trees, graphs, sorting, searching algorithms',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'is_mandatory' => 1,
            'lab_required' => 1,
            'practical_component' => 40,
            'theory_component' => 60,
            'certification_available' => 1,
            'prerequisites_codes' => ['SCS01']
        ];

        $this->subjects[] = [
            'name' => 'Database Management Systems',
            'code' => 'BCSDB01',
            'category' => 'Computer Science',
            'level' => 'Bachelor',
            'description' => 'Relational databases, SQL, normalization, transactions, and database design',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1,
            'is_mandatory' => 1,
            'lab_required' => 1,
            'practical_component' => 50,
            'theory_component' => 50,
            'certification_available' => 1
        ];

        $this->subjects[] = [
            'name' => 'Operating Systems',
            'code' => 'BCSOS01',
            'category' => 'Computer Science',
            'level' => 'Bachelor',
            'description' => 'Process management, memory management, file systems, and scheduling',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'is_mandatory' => 1,
            'lab_required' => 1,
            'practical_component' => 30,
            'theory_component' => 70
        ];

        $this->subjects[] = [
            'name' => 'Computer Networks',
            'code' => 'BCSNET01',
            'category' => 'Computer Science',
            'level' => 'Bachelor',
            'description' => 'OSI model, TCP/IP, routing, network protocols, and security',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'lab_required' => 1,
            'practical_component' => 40,
            'theory_component' => 60,
            'certification_available' => 1
        ];

        $this->subjects[] = [
            'name' => 'Software Engineering',
            'code' => 'BCSSE01',
            'category' => 'Computer Science',
            'level' => 'Bachelor',
            'description' => 'SDLC, design patterns, testing, project management, and agile methodologies',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'is_mandatory' => 1
        ];

        $this->subjects[] = [
            'name' => 'Artificial Intelligence',
            'code' => 'BCSAI01',
            'category' => 'Computer Science',
            'level' => 'Bachelor',
            'description' => 'Search algorithms, knowledge representation, machine learning basics, expert systems',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_elective' => 1,
            'lab_required' => 1,
            'practical_component' => 50,
            'theory_component' => 50,
            'certification_available' => 1,
            'prerequisites_codes' => ['BCSDS01']
        ];

        $this->subjects[] = [
            'name' => 'Web Technologies',
            'code' => 'BCSWEB01',
            'category' => 'Computer Science',
            'level' => 'Bachelor',
            'description' => 'HTML, CSS, JavaScript, React, Node.js, and web application development',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Intermediate',
            'is_elective' => 1,
            'lab_required' => 1,
            'practical_component' => 70,
            'theory_component' => 30,
            'certification_available' => 1
        ];

        // Engineering - Bachelor
        $this->subjects[] = [
            'name' => 'Engineering Mechanics',
            'code' => 'BENGMECH01',
            'category' => 'Engineering',
            'level' => 'Bachelor',
            'description' => 'Statics, dynamics, force analysis, and equilibrium',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'is_mandatory' => 1,
            'prerequisites_codes' => ['HSPHY01', 'HSMATH01']
        ];

        $this->subjects[] = [
            'name' => 'Thermodynamics',
            'code' => 'BENGTHERMO01',
            'category' => 'Engineering',
            'level' => 'Bachelor',
            'description' => 'Laws of thermodynamics, heat engines, refrigeration, and entropy',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'is_mandatory' => 1,
            'prerequisites_codes' => ['HSPHY01']
        ];

        $this->subjects[] = [
            'name' => 'Electrical Circuits',
            'code' => 'BENGELEC01',
            'category' => 'Engineering',
            'level' => 'Bachelor',
            'description' => 'Circuit analysis, AC/DC circuits, network theorems, and circuit design',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'lab_required' => 1,
            'practical_component' => 40,
            'theory_component' => 60,
            'prerequisites_codes' => ['HSPHY01', 'HSMATH01']
        ];

        // Management - Bachelor
        $this->subjects[] = [
            'name' => 'Principles of Management',
            'code' => 'BMGTMGT01',
            'category' => 'Management',
            'level' => 'Bachelor',
            'description' => 'Management functions, organizational behavior, and leadership',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1,
            'is_mandatory' => 1
        ];

        $this->subjects[] = [
            'name' => 'Marketing Management',
            'code' => 'BMGTMKT01',
            'category' => 'Management',
            'level' => 'Bachelor',
            'description' => 'Marketing concepts, consumer behavior, branding, and digital marketing',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1,
            'certification_available' => 1,
            'prerequisites_codes' => ['BMGTMGT01']
        ];

        $this->subjects[] = [
            'name' => 'Financial Management',
            'code' => 'BMGTFIN01',
            'category' => 'Management',
            'level' => 'Bachelor',
            'description' => 'Financial planning, capital budgeting, and investment analysis',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'certification_available' => 1,
            'prerequisites_codes' => ['HSACC01']
        ];

        $this->subjects[] = [
            'name' => 'Human Resource Management',
            'code' => 'BMGTHR01',
            'category' => 'Management',
            'level' => 'Bachelor',
            'description' => 'Recruitment, training, performance management, and employee relations',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Intermediate',
            'is_elective' => 1,
            'prerequisites_codes' => ['BMGTMGT01']
        ];

        // Medical - Bachelor
        $this->subjects[] = [
            'name' => 'Anatomy',
            'code' => 'BMEDANAT01',
            'category' => 'Medical',
            'level' => 'Bachelor',
            'description' => 'Human body structure, organs, and systems',
            'credits' => 6,
            'duration_hours' => 120,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'is_mandatory' => 1,
            'lab_required' => 1,
            'practical_component' => 50,
            'theory_component' => 50,
            'prerequisites_codes' => ['HSBIO01']
        ];

        $this->subjects[] = [
            'name' => 'Physiology',
            'code' => 'BMEDPHYS01',
            'category' => 'Medical',
            'level' => 'Bachelor',
            'description' => 'Body functions, homeostasis, and organ systems physiology',
            'credits' => 6,
            'duration_hours' => 120,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'is_mandatory' => 1,
            'lab_required' => 1,
            'practical_component' => 40,
            'theory_component' => 60,
            'prerequisites_codes' => ['HSBIO01', 'HSCHEM01']
        ];

        $this->subjects[] = [
            'name' => 'Biochemistry',
            'code' => 'BMEDBIOCHEM01',
            'category' => 'Medical',
            'level' => 'Bachelor',
            'description' => 'Biomolecules, metabolism, enzymes, and clinical biochemistry',
            'credits' => 5,
            'duration_hours' => 100,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'is_mandatory' => 1,
            'lab_required' => 1,
            'practical_component' => 40,
            'theory_component' => 60,
            'prerequisites_codes' => ['HSCHEM01', 'HSBIO01']
        ];

        $this->subjects[] = [
            'name' => 'Pharmacology',
            'code' => 'BMEDPHARM01',
            'category' => 'Medical',
            'level' => 'Bachelor',
            'description' => 'Drug actions, classifications, therapeutics, and toxicology',
            'credits' => 5,
            'duration_hours' => 100,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'is_mandatory' => 1,
            'lab_required' => 1,
            'prerequisites_codes' => ['BMEDPHYS01', 'BMEDBIOCHEM01']
        ];

        // Law - Bachelor
        $this->subjects[] = [
            'name' => 'Constitutional Law',
            'code' => 'BLAWCONST01',
            'category' => 'Law',
            'level' => 'Bachelor',
            'description' => 'Constitution, fundamental rights, directive principles, and amendments',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'is_mandatory' => 1
        ];

        $this->subjects[] = [
            'name' => 'Contract Law',
            'code' => 'BLAWCONT01',
            'category' => 'Law',
            'level' => 'Bachelor',
            'description' => 'Contract formation, performance, breach, and remedies',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1,
            'is_mandatory' => 1
        ];

        $this->subjects[] = [
            'name' => 'Criminal Law',
            'code' => 'BLAWCRIM01',
            'category' => 'Law',
            'level' => 'Bachelor',
            'description' => 'Criminal offenses, penalties, and criminal procedure',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'is_mandatory' => 1
        ];

        // ==================== MASTER LEVEL SUBJECTS ====================

        $this->subjects[] = [
            'name' => 'Advanced Machine Learning',
            'code' => 'MCSML01',
            'category' => 'Computer Science',
            'level' => 'Master',
            'description' => 'Deep learning, neural networks, reinforcement learning, and advanced ML algorithms',
            'credits' => 4,
            'duration_hours' => 48,
            'difficulty_level' => 'Expert',
            'is_core' => 1,
            'lab_required' => 1,
            'practical_component' => 60,
            'theory_component' => 40,
            'certification_available' => 1,
            'prerequisites_codes' => ['BCSAI01']
        ];

        $this->subjects[] = [
            'name' => 'Big Data Analytics',
            'code' => 'MCSBDA01',
            'category' => 'Computer Science',
            'level' => 'Master',
            'description' => 'Hadoop, Spark, data mining, and large-scale data processing',
            'credits' => 4,
            'duration_hours' => 48,
            'difficulty_level' => 'Expert',
            'is_core' => 1,
            'lab_required' => 1,
            'practical_component' => 50,
            'theory_component' => 50,
            'certification_available' => 1,
            'prerequisites_codes' => ['BCSDB01']
        ];

        $this->subjects[] = [
            'name' => 'Cloud Computing',
            'code' => 'MCSCLOUD01',
            'category' => 'Computer Science',
            'level' => 'Master',
            'description' => 'AWS, Azure, containerization, microservices, and cloud architecture',
            'credits' => 4,
            'duration_hours' => 48,
            'difficulty_level' => 'Expert',
            'is_elective' => 1,
            'lab_required' => 1,
            'practical_component' => 60,
            'theory_component' => 40,
            'certification_available' => 1
        ];

        $this->subjects[] = [
            'name' => 'Cyber Security',
            'code' => 'MCSSEC01',
            'category' => 'Computer Science',
            'level' => 'Master',
            'description' => 'Network security, cryptography, ethical hacking, and security protocols',
            'credits' => 4,
            'duration_hours' => 48,
            'difficulty_level' => 'Expert',
            'is_elective' => 1,
            'lab_required' => 1,
            'practical_component' => 50,
            'theory_component' => 50,
            'certification_available' => 1,
            'prerequisites_codes' => ['BCSNET01']
        ];

        $this->subjects[] = [
            'name' => 'Strategic Management',
            'code' => 'MMGTSTRAT01',
            'category' => 'Management',
            'level' => 'Master',
            'description' => 'Corporate strategy, competitive analysis, and strategic planning',
            'credits' => 4,
            'duration_hours' => 48,
            'difficulty_level' => 'Expert',
            'is_core' => 1,
            'is_mandatory' => 1,
            'prerequisites_codes' => ['BMGTMGT01']
        ];

        $this->subjects[] = [
            'name' => 'Advanced Financial Management',
            'code' => 'MMGTFIN01',
            'category' => 'Management',
            'level' => 'Master',
            'description' => 'Corporate finance, mergers & acquisitions, and financial derivatives',
            'credits' => 4,
            'duration_hours' => 48,
            'difficulty_level' => 'Expert',
            'is_core' => 1,
            'certification_available' => 1,
            'prerequisites_codes' => ['BMGTFIN01']
        ];

        $this->subjects[] = [
            'name' => 'Business Analytics',
            'code' => 'MMGTBA01',
            'category' => 'Management',
            'level' => 'Master',
            'description' => 'Data analytics, predictive modeling, and business intelligence',
            'credits' => 4,
            'duration_hours' => 48,
            'difficulty_level' => 'Expert',
            'is_elective' => 1,
            'lab_required' => 1,
            'practical_component' => 60,
            'theory_component' => 40,
            'certification_available' => 1
        ];

        $this->subjects[] = [
            'name' => 'Advanced Surgery',
            'code' => 'MMEDSURG01',
            'category' => 'Medical',
            'level' => 'Master',
            'description' => 'Specialized surgical techniques and procedures',
            'credits' => 6,
            'duration_hours' => 200,
            'difficulty_level' => 'Expert',
            'is_core' => 1,
            'is_mandatory' => 1,
            'lab_required' => 1,
            'practical_component' => 70,
            'theory_component' => 30,
            'prerequisites_codes' => ['BMEDANAT01', 'BMEDPHYS01']
        ];

        // ==================== VOCATIONAL & CERTIFICATE SUBJECTS ====================

        $this->subjects[] = [
            'name' => 'Digital Marketing',
            'code' => 'CERTDM01',
            'category' => 'Vocational',
            'level' => 'Certificate',
            'description' => 'SEO, social media marketing, content marketing, and analytics',
            'credits' => 3,
            'duration_hours' => 40,
            'difficulty_level' => 'Beginner',
            'certification_available' => 1,
            'practical_component' => 70,
            'theory_component' => 30
        ];

        $this->subjects[] = [
            'name' => 'Graphic Design',
            'code' => 'CERTGD01',
            'category' => 'Vocational',
            'level' => 'Certificate',
            'description' => 'Photoshop, Illustrator, design principles, and visual communication',
            'credits' => 3,
            'duration_hours' => 40,
            'difficulty_level' => 'Beginner',
            'certification_available' => 1,
            'lab_required' => 1,
            'practical_component' => 80,
            'theory_component' => 20
        ];

        $this->subjects[] = [
            'name' => 'Python Programming',
            'code' => 'CERTPY01',
            'category' => 'Vocational',
            'level' => 'Certificate',
            'description' => 'Python basics, data structures, and application development',
            'credits' => 3,
            'duration_hours' => 40,
            'difficulty_level' => 'Beginner',
            'certification_available' => 1,
            'lab_required' => 1,
            'practical_component' => 80,
            'theory_component' => 20
        ];

        $this->subjects[] = [
            'name' => 'Data Science Fundamentals',
            'code' => 'CERTDS01',
            'category' => 'Vocational',
            'level' => 'Certificate',
            'description' => 'Statistics, data visualization, pandas, and basic machine learning',
            'credits' => 4,
            'duration_hours' => 48,
            'difficulty_level' => 'Intermediate',
            'certification_available' => 1,
            'lab_required' => 1,
            'practical_component' => 70,
            'theory_component' => 30,
            'prerequisites_codes' => ['CERTPY01']
        ];

        // ==================== ADDITIONAL BACHELOR SUBJECTS ====================

        $this->subjects[] = [
            'name' => 'Microeconomics',
            'code' => 'BECONMICRO01',
            'category' => 'Commerce',
            'level' => 'Bachelor',
            'description' => 'Consumer theory, producer theory, market structures, and welfare economics',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1,
            'prerequisites_codes' => ['HSECON01']
        ];

        $this->subjects[] = [
            'name' => 'Macroeconomics',
            'code' => 'BECONMACRO01',
            'category' => 'Commerce',
            'level' => 'Bachelor',
            'description' => 'National income, inflation, unemployment, monetary and fiscal policy',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1,
            'prerequisites_codes' => ['HSECON01']
        ];

        $this->subjects[] = [
            'name' => 'Statistics for Business',
            'code' => 'BCOMSTAT01',
            'category' => 'Commerce',
            'level' => 'Bachelor',
            'description' => 'Probability, distributions, hypothesis testing, and regression analysis',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1,
            'lab_required' => 1,
            'practical_component' => 40,
            'theory_component' => 60,
            'prerequisites_codes' => ['SMATH01']
        ];

        $this->subjects[] = [
            'name' => 'Linear Algebra',
            'code' => 'BMATHLA01',
            'category' => 'Mathematics',
            'level' => 'Bachelor',
            'description' => 'Matrices, vector spaces, eigenvalues, and linear transformations',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'prerequisites_codes' => ['HSMATH01']
        ];

        $this->subjects[] = [
            'name' => 'Differential Equations',
            'code' => 'BMATHDE01',
            'category' => 'Mathematics',
            'level' => 'Bachelor',
            'description' => 'Ordinary and partial differential equations and their applications',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'prerequisites_codes' => ['HSMATH01']
        ];

        $this->subjects[] = [
            'name' => 'Probability and Statistics',
            'code' => 'BMATHPROB01',
            'category' => 'Mathematics',
            'level' => 'Bachelor',
            'description' => 'Probability theory, random variables, and statistical inference',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_core' => 1,
            'prerequisites_codes' => ['HSMATH01']
        ];

        $this->subjects[] = [
            'name' => 'English Literature',
            'code' => 'BALIT01',
            'category' => 'Arts',
            'level' => 'Bachelor',
            'description' => 'Literary analysis, poetry, drama, fiction, and critical theory',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Intermediate',
            'is_core' => 1,
            'prerequisites_codes' => ['SENG01']
        ];

        $this->subjects[] = [
            'name' => 'Sociology',
            'code' => 'BASOC01',
            'category' => 'Social Sciences',
            'level' => 'Bachelor',
            'description' => 'Social structure, culture, socialization, and social institutions',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Intermediate',
            'is_elective' => 1
        ];

        $this->subjects[] = [
            'name' => 'Philosophy',
            'code' => 'BAPHIL01',
            'category' => 'Humanities',
            'level' => 'Bachelor',
            'description' => 'Logic, ethics, metaphysics, and philosophy of mind',
            'credits' => 4,
            'duration_hours' => 60,
            'difficulty_level' => 'Advanced',
            'is_elective' => 1
        ];
    }

    public function populate() {
        echo "Starting education subjects population...\n\n";

        $created = 0;
        $skipped = 0;
        $errors = 0;

        // First pass: Create all subjects without prerequisites
        foreach ($this->subjects as $subjectData) {
            try {
                // Check if subject already exists
                $existing = PopularEducationSubject::findByCode($subjectData['code']);

                if ($existing) {
                    echo "  Skipping {$subjectData['code']} - {$subjectData['name']} (already exists)\n";
                    $this->subjectIdMap[$subjectData['code']] = $existing->id;
                    $skipped++;
                    continue;
                }

                // Create new subject
                $subject = new PopularEducationSubject();
                $success = $subject->addSubject(
                    $subjectData['name'],
                    $subjectData['code'],
                    $subjectData['description'] ?? '',
                    $subjectData['category'],
                    $subjectData['level'],
                    $subjectData['credits'] ?? null,
                    [] // Prerequisites will be added in second pass
                );

                // Set additional fields
                if (isset($subjectData['duration_hours'])) {
                    $subject->duration_hours = $subjectData['duration_hours'];
                }
                if (isset($subjectData['difficulty_level'])) {
                    $subject->difficulty_level = $subjectData['difficulty_level'];
                }
                if (isset($subjectData['is_core'])) {
                    $subject->is_core = $subjectData['is_core'];
                }
                if (isset($subjectData['is_elective'])) {
                    $subject->is_elective = $subjectData['is_elective'];
                }
                if (isset($subjectData['is_mandatory'])) {
                    $subject->is_mandatory = $subjectData['is_mandatory'];
                }
                if (isset($subjectData['lab_required'])) {
                    $subject->lab_required = $subjectData['lab_required'];
                }
                if (isset($subjectData['practical_component'])) {
                    $subject->practical_component = $subjectData['practical_component'];
                }
                if (isset($subjectData['theory_component'])) {
                    $subject->theory_component = $subjectData['theory_component'];
                }
                if (isset($subjectData['certification_available'])) {
                    $subject->certification_available = $subjectData['certification_available'];
                }

                $subject->status = PopularEducationSubject::STATUS_ACTIVE;
                $subject->is_active = 1;

                if ($success && $subject->save()) {
                    $this->subjectIdMap[$subjectData['code']] = $subject->id;
                    echo "  ✓ Created {$subjectData['code']} - {$subjectData['name']}\n";
                    $created++;
                } else {
                    echo "  ✗ Failed to save {$subjectData['code']}\n";
                    $errors++;
                }

            } catch (Exception $e) {
                echo "  ✗ Error creating {$subjectData['code']}: " . $e->getMessage() . "\n";
                $errors++;
            }
        }

        echo "\nFirst pass complete. Now adding prerequisites...\n\n";

        // Second pass: Add prerequisites
        $prerequisitesAdded = 0;
        foreach ($this->subjects as $subjectData) {
            if (isset($subjectData['prerequisites_codes']) && !empty($subjectData['prerequisites_codes'])) {
                try {
                    $subjectId = $this->subjectIdMap[$subjectData['code']] ?? null;
                    if (!$subjectId) {
                        continue;
                    }

                    $subject = PopularEducationSubject::find($subjectId);
                    if (!$subject) {
                        continue;
                    }

                    $prerequisiteIds = [];
                    foreach ($subjectData['prerequisites_codes'] as $prereqCode) {
                        if (isset($this->subjectIdMap[$prereqCode])) {
                            $prerequisiteIds[] = $this->subjectIdMap[$prereqCode];
                        }
                    }

                    if (!empty($prerequisiteIds)) {
                        $subject->setPrerequisites($prerequisiteIds);
                        $subject->save();
                        echo "  ✓ Added prerequisites for {$subjectData['code']}\n";
                        $prerequisitesAdded++;
                    }

                } catch (Exception $e) {
                    echo "  ✗ Error adding prerequisites for {$subjectData['code']}: " . $e->getMessage() . "\n";
                }
            }
        }

        echo "\nPopulation complete!\n";
        echo "Created: $created\n";
        echo "Skipped: $skipped\n";
        echo "Errors: $errors\n";
        echo "Prerequisites Added: $prerequisitesAdded\n";
        echo "Total: " . count($this->subjects) . "\n";
    }
}

// Run the populator
echo "=== Popular Education Subjects Populator ===\n\n";

try {
    $populator = new EducationSubjectsPopulator();
    $populator->populate();
    echo "\n✓ Education subjects population completed successfully!\n";
} catch (Exception $e) {
    echo "\n✗ Population failed: " . $e->getMessage() . "\n";
    exit(1);
}
