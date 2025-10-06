<?php
/**
 * Generate Organization Entity Pages
 */

require_once __DIR__ . '/../bootstrap.php';

use App\PageGenerator;

// Organization Domain
PageGenerator::generatePages('Entities\\IndustryCategory', 'industry_categories', [
    'displayName' => 'Industry Categories',
    'pluralName' => 'industry_categories',
    'icon' => 'bi-diagram-3',
]);

PageGenerator::generatePages('Entities\\PopularOrganizationDepartment', 'popular_organization_departments', [
    'displayName' => 'Departments',
    'pluralName' => 'popular_organization_departments',
    'icon' => 'bi-building',
]);

PageGenerator::generatePages('Entities\\PopularOrganizationTeam', 'popular_organization_teams', [
    'displayName' => 'Teams',
    'pluralName' => 'popular_organization_teams',
    'icon' => 'bi-people',
]);

PageGenerator::generatePages('Entities\\PopularOrganizationDesignation', 'popular_organization_designations', [
    'displayName' => 'Designations',
    'pluralName' => 'popular_organization_designations',
    'icon' => 'bi-award',
]);

PageGenerator::generatePages('Entities\\OrganizationLegalCategory', 'organization_legal_categories', [
    'displayName' => 'Legal Categories',
    'pluralName' => 'organization_legal_categories',
    'icon' => 'bi-file-text',
]);

PageGenerator::generatePages('Entities\\Organization', 'organizations', [
    'displayName' => 'Organizations',
    'pluralName' => 'organizations',
    'icon' => 'bi-building',
]);

PageGenerator::generatePages('Entities\\OrganizationBranch', 'organization_branches', [
    'displayName' => 'Branches',
    'pluralName' => 'organization_branches',
    'icon' => 'bi-diagram-2',
]);

PageGenerator::generatePages('Entities\\OrganizationBuilding', 'organization_buildings', [
    'displayName' => 'Buildings',
    'pluralName' => 'organization_buildings',
    'icon' => 'bi-house',
]);

PageGenerator::generatePages('Entities\\Workstation', 'workstations', [
    'displayName' => 'Workstations',
    'pluralName' => 'workstations',
    'icon' => 'bi-pc-display',
]);

echo "\n✅ All organization pages generated successfully!\n";
