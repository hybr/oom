<?php
/**
 * Script to Update Foreign Key Labels in Forms
 *
 * This script helps identify forms that need FK label updates
 * Run: php scripts/update-fk-labels.php
 */

require_once __DIR__ . '/../bootstrap.php';

// Common FK field patterns and their entity mappings
$fkMappings = [
    'person_id' => ['entity' => 'persons', 'label' => 'Person', 'icon' => 'bi-person'],
    'admin_id' => ['entity' => 'persons', 'label' => 'Administrator', 'icon' => 'bi-person-badge'],
    'organization_id' => ['entity' => 'organizations', 'label' => 'Organization', 'icon' => 'bi-building'],
    'country_id' => ['entity' => 'countries', 'label' => 'Country', 'icon' => 'bi-flag'],
    'continent_id' => ['entity' => 'continents', 'label' => 'Continent', 'icon' => 'bi-globe-americas'],
    'language_id' => ['entity' => 'languages', 'label' => 'Language', 'icon' => 'bi-translate'],
    'skill_id' => ['entity' => 'popular_skills', 'label' => 'Skill', 'icon' => 'bi-lightbulb'],
    'subject_id' => ['entity' => 'popular_skills', 'label' => 'Skill', 'icon' => 'bi-lightbulb'],
    'department_id' => ['entity' => 'popular_organization_departments', 'label' => 'Department', 'icon' => 'bi-diagram-2'],
    'branch_id' => ['entity' => 'organization_branches', 'label' => 'Branch', 'icon' => 'bi-diagram-3'],
    'building_id' => ['entity' => 'organization_buildings', 'label' => 'Building', 'icon' => 'bi-house'],
    'workstation_id' => ['entity' => 'workstations', 'label' => 'Workstation', 'icon' => 'bi-pc-display'],
    'vacancy_id' => ['entity' => 'organization_vacancies', 'label' => 'Vacancy', 'icon' => 'bi-megaphone'],
    'category_id' => ['entity' => 'catalog_categories', 'label' => 'Category', 'icon' => 'bi-folder'],
    'industry_id' => ['entity' => 'industry_categories', 'label' => 'Industry', 'icon' => 'bi-diagram-3'],
    'legal_category_id' => ['entity' => 'organization_legal_categories', 'label' => 'Legal Category', 'icon' => 'bi-file-earmark-ruled'],
];

echo "=== Foreign Key Label Update Report ===\n\n";

// Scan create and edit forms
$forms = array_merge(
    glob(__DIR__ . '/../public/pages/entities/*/create.php'),
    glob(__DIR__ . '/../public/pages/entities/*/edit.php')
);

$totalForms = 0;
$formsWithFK = 0;
$totalFKFields = 0;

foreach ($forms as $formPath) {
    $totalForms++;
    $content = file_get_contents($formPath);
    $hasFK = false;
    $fkFields = [];

    // Find FK field labels
    foreach ($fkMappings as $fieldName => $mapping) {
        // Pattern: <label for="person_id"
        if (preg_match('/<label[^>]+for=["\']' . $fieldName . '["\']/', $content)) {
            $hasFK = true;
            $fkFields[] = $fieldName;
            $totalFKFields++;
        }
    }

    if ($hasFK) {
        $formsWithFK++;
        $relativePath = str_replace(__DIR__ . '/../', '', $formPath);
        echo "📄 $relativePath\n";
        echo "   FK Fields: " . implode(', ', $fkFields) . "\n";

        foreach ($fkFields as $field) {
            $mapping = $fkMappings[$field];
            echo "   ✏️  Replace label for '$field' with FK component:\n";
            echo "      <?php\n";
            echo "      \$fk_label = '{$mapping['label']}';\n";
            echo "      \$fk_for = '$field';\n";
            echo "      \$fk_entity = '{$mapping['entity']}';\n";
            echo "      \$fk_required = true; // Adjust as needed\n";
            echo "      \$fk_icon = '{$mapping['icon']}';\n";
            echo "      include __DIR__ . '/../../../../views/components/fk-label.php';\n";
            echo "      ?>\n\n";
        }
        echo "\n";
    }
}

echo "\n=== Summary ===\n";
echo "Total forms scanned: $totalForms\n";
echo "Forms with FK fields: $formsWithFK\n";
echo "Total FK fields found: $totalFKFields\n";
echo "\n✅ Review the suggestions above and update forms manually.\n";
echo "📖 See docs/FK_LABEL_COMPONENT.md for detailed usage guide.\n";
