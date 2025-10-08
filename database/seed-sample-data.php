<?php

/**
 * Sample Data Seeder
 * Run this to populate the database with sample data for testing
 */

require_once __DIR__ . '/../bootstrap.php';

echo "=================================================\n";
echo "  V4L - Seeding Sample Data\n";
echo "=================================================\n\n";

$db = db();

try {
    $db->beginTransaction();

    // 1. Add Continents
    echo "Adding continents...\n";
    $continents = [
        'Africa',
        'Antarctica',
        'Asia',
        'Europe',
        'North America',
        'Oceania',
        'South America'
    ];

    $continentIds = [];
    foreach ($continents as $continent) {
        $id = $db->insert(
            "INSERT INTO continents (name, created_at, updated_at) VALUES (?, datetime('now'), datetime('now'))",
            [$continent]
        );
        $continentIds[$continent] = $id;
        echo "   ✅ Added: $continent (ID: $id)\n";
    }

    // 2. Add Countries
    echo "\nAdding countries...\n";
    $countries = [
        ['United States', 'North America'],
        ['Canada', 'North America'],
        ['Mexico', 'North America'],
        ['United Kingdom', 'Europe'],
        ['Germany', 'Europe'],
        ['France', 'Europe'],
        ['China', 'Asia'],
        ['India', 'Asia'],
        ['Japan', 'Asia'],
        ['Australia', 'Oceania'],
        ['Brazil', 'South America'],
        ['South Africa', 'Africa'],
    ];

    $countryIds = [];
    foreach ($countries as list($country, $continent)) {
        $continentId = $continentIds[$continent];
        $id = $db->insert(
            "INSERT INTO countries (name, continent_id, created_at, updated_at) VALUES (?, ?, datetime('now'), datetime('now'))",
            [$country, $continentId]
        );
        $countryIds[$country] = $id;
        echo "   ✅ Added: $country in $continent (ID: $id)\n";
    }

    // 3. Add Languages
    echo "\nAdding languages...\n";
    $languages = [
        ['English', 'United States'],
        ['Spanish', 'United States'],
        ['English', 'United Kingdom'],
        ['German', 'Germany'],
        ['French', 'France'],
        ['Mandarin', 'China'],
        ['Hindi', 'India'],
        ['Japanese', 'Japan'],
        ['Portuguese', 'Brazil'],
    ];

    foreach ($languages as list($language, $country)) {
        $countryId = $countryIds[$country];
        $id = $db->insert(
            "INSERT INTO languages (name, country_id, created_at, updated_at) VALUES (?, ?, datetime('now'), datetime('now'))",
            [$language, $countryId]
        );
        echo "   ✅ Added: $language in $country (ID: $id)\n";
    }

    // 4. Add Popular Skills
    echo "\nAdding popular skills...\n";
    $skills = [
        'PHP Programming',
        'JavaScript',
        'Python',
        'Java',
        'Project Management',
        'Marketing',
        'Sales',
        'Customer Service',
        'Accounting',
        'Graphic Design',
        'Content Writing',
        'Data Analysis',
        'Machine Learning',
        'Cloud Computing',
        'DevOps'
    ];

    foreach ($skills as $skill) {
        $id = $db->insert(
            "INSERT INTO popular_skills (name, created_at, updated_at) VALUES (?, datetime('now'), datetime('now'))",
            [$skill]
        );
        echo "   ✅ Added: $skill (ID: $id)\n";
    }

    // 5. Add Popular Education Subjects
    echo "\nAdding education subjects...\n";
    $subjects = [
        'Computer Science',
        'Business Administration',
        'Engineering',
        'Marketing',
        'Finance',
        'Accounting',
        'Mathematics',
        'Physics',
        'Chemistry',
        'Economics',
        'Psychology',
        'English Literature',
        'Communications',
        'Information Technology',
        'Data Science'
    ];

    foreach ($subjects as $subject) {
        $id = $db->insert(
            "INSERT INTO popular_education_subjects (name, created_at, updated_at) VALUES (?, datetime('now'), datetime('now'))",
            [$subject]
        );
        echo "   ✅ Added: $subject (ID: $id)\n";
    }

    // 6. Add Industry Categories
    echo "\nAdding industry categories...\n";
    $industries = [
        'Technology',
        'Healthcare',
        'Retail',
        'Finance',
        'Education',
        'Manufacturing',
        'Hospitality',
        'Real Estate',
        'Transportation',
        'Entertainment',
        'Food & Beverage',
        'Professional Services',
        'Construction',
        'Agriculture'
    ];

    foreach ($industries as $industry) {
        $id = $db->insert(
            "INSERT INTO industry_categories (name, created_at, updated_at) VALUES (?, datetime('now'), datetime('now'))",
            [$industry]
        );
        echo "   ✅ Added: $industry (ID: $id)\n";
    }

    // 7. Add Organization Legal Categories
    echo "\nAdding organization legal categories...\n";
    $legalCategories = [
        'LLC',
        'Corporation',
        'Partnership',
        'Sole Proprietorship',
        'Non-Profit',
        'Cooperative',
        'Limited Partnership'
    ];

    foreach ($legalCategories as $category) {
        $id = $db->insert(
            "INSERT INTO organization_legal_categories (name, created_at, updated_at) VALUES (?, datetime('now'), datetime('now'))",
            [$category]
        );
        echo "   ✅ Added: $category (ID: $id)\n";
    }

    // 8. Add Popular Organization Departments
    echo "\nAdding organization departments...\n";
    $departments = [
        'Sales',
        'Marketing',
        'Human Resources',
        'Finance',
        'Operations',
        'IT',
        'Customer Service',
        'Product Development',
        'Research & Development',
        'Legal'
    ];

    $deptIds = [];
    foreach ($departments as $dept) {
        $id = $db->insert(
            "INSERT INTO popular_organization_departments (name, created_at, updated_at) VALUES (?, datetime('now'), datetime('now'))",
            [$dept]
        );
        $deptIds[$dept] = $id;
        echo "   ✅ Added: $dept (ID: $id)\n";
    }

    // 9. Add Popular Organization Designations
    echo "\nAdding organization designations...\n";
    $designations = [
        'Manager',
        'Senior Manager',
        'Director',
        'Vice President',
        'Executive',
        'Specialist',
        'Coordinator',
        'Associate',
        'Analyst',
        'Consultant'
    ];

    $desigIds = [];
    foreach ($designations as $desig) {
        $id = $db->insert(
            "INSERT INTO popular_organization_designations (name, created_at, updated_at) VALUES (?, datetime('now'), datetime('now'))",
            [$desig]
        );
        $desigIds[$desig] = $id;
        echo "   ✅ Added: $desig (ID: $id)\n";
    }

    // 10. Add Some Popular Organization Positions
    echo "\nAdding organization positions...\n";
    $positions = [
        ['Sales Manager', 'Sales', 'Manager'],
        ['Marketing Director', 'Marketing', 'Director'],
        ['HR Specialist', 'Human Resources', 'Specialist'],
        ['IT Manager', 'IT', 'Manager'],
        ['Financial Analyst', 'Finance', 'Analyst'],
        ['Customer Service Representative', 'Customer Service', 'Associate'],
    ];

    foreach ($positions as list($name, $dept, $desig)) {
        $deptId = $deptIds[$dept];
        $desigId = $desigIds[$desig];
        $id = $db->insert(
            "INSERT INTO popular_organization_positions (name, department_id, designation_id, created_at, updated_at)
             VALUES (?, ?, ?, datetime('now'), datetime('now'))",
            [$name, $deptId, $desigId]
        );
        echo "   ✅ Added: $name (ID: $id)\n";
    }

    // 11. Add Catalog Categories
    echo "\nAdding catalog categories...\n";
    $catalogCategories = [
        'Electronics',
        'Clothing & Apparel',
        'Food & Groceries',
        'Home & Garden',
        'Sports & Outdoors',
        'Health & Beauty',
        'Automotive',
        'Books & Media',
        'Toys & Games',
        'Professional Services',
        'Home Services',
        'Business Services'
    ];

    foreach ($catalogCategories as $category) {
        $id = $db->insert(
            "INSERT INTO catalog_categories (name, is_active, managed_by_system, created_at, updated_at)
             VALUES (?, 1, 1, datetime('now'), datetime('now'))",
            [$category]
        );
        echo "   ✅ Added: $category (ID: $id)\n";
    }

    $db->commit();

    echo "\n=================================================\n";
    echo "  ✅ Sample data seeded successfully!\n";
    echo "=================================================\n\n";

    echo "Summary:\n";
    echo "- " . count($continents) . " continents\n";
    echo "- " . count($countries) . " countries\n";
    echo "- " . count($languages) . " languages\n";
    echo "- " . count($skills) . " skills\n";
    echo "- " . count($subjects) . " education subjects\n";
    echo "- " . count($industries) . " industries\n";
    echo "- " . count($legalCategories) . " legal categories\n";
    echo "- " . count($departments) . " departments\n";
    echo "- " . count($designations) . " designations\n";
    echo "- " . count($positions) . " positions\n";
    echo "- " . count($catalogCategories) . " catalog categories\n\n";

    echo "You can now:\n";
    echo "1. Sign up and create your account\n";
    echo "2. Create organizations with the seeded data\n";
    echo "3. Post vacancies using the positions\n";
    echo "4. Add catalog items in the categories\n\n";

    echo "Start your server:\n";
    echo "  cd public && php -S localhost:8000\n\n";

} catch (Exception $e) {
    $db->rollBack();
    echo "\n❌ Error seeding data: " . $e->getMessage() . "\n";
    exit(1);
}
