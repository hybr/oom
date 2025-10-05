<?php
/**
 * Generate Entity Pages Script
 * Run: php scripts/generate-entity-pages.php
 */

require_once __DIR__ . '/../bootstrap.php';

use App\PageGenerator;

// Geography Domain
PageGenerator::generatePages('Entities\\Country', 'countries', [
    'displayName' => 'Countries',
    'pluralName' => 'countries',
    'icon' => 'bi-flag',
]);

PageGenerator::generatePages('Entities\\Language', 'languages', [
    'displayName' => 'Languages',
    'pluralName' => 'languages',
    'icon' => 'bi-translate',
]);

PageGenerator::generatePages('Entities\\PostalAddress', 'postal_addresses', [
    'displayName' => 'Postal Addresses',
    'pluralName' => 'postal_addresses',
    'icon' => 'bi-geo-alt',
]);

// Person Domain
PageGenerator::generatePages('Entities\\Person', 'persons', [
    'displayName' => 'Persons',
    'pluralName' => 'persons',
    'icon' => 'bi-person',
]);

PageGenerator::generatePages('Entities\\Credential', 'credentials', [
    'displayName' => 'Credentials',
    'pluralName' => 'credentials',
    'icon' => 'bi-key',
]);

// Education & Skill Domain
PageGenerator::generatePages('Entities\\PopularEducationSubject', 'popular_education_subjects', [
    'displayName' => 'Education Subjects',
    'pluralName' => 'popular_education_subjects',
    'icon' => 'bi-book',
]);

PageGenerator::generatePages('Entities\\PopularSkill', 'popular_skills', [
    'displayName' => 'Popular Skills',
    'pluralName' => 'popular_skills',
    'icon' => 'bi-star',
]);

PageGenerator::generatePages('Entities\\PersonEducation', 'person_education', [
    'displayName' => 'Person Education',
    'pluralName' => 'person_education',
    'icon' => 'bi-mortarboard',
]);

PageGenerator::generatePages('Entities\\PersonEducationSubject', 'person_education_subjects', [
    'displayName' => 'Education Subjects',
    'pluralName' => 'person_education_subjects',
    'icon' => 'bi-journal',
]);

PageGenerator::generatePages('Entities\\PersonSkill', 'person_skills', [
    'displayName' => 'Person Skills',
    'pluralName' => 'person_skills',
    'icon' => 'bi-award',
]);

echo "\n✅ All entity pages generated successfully!\n";
