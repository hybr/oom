<?php
/**
 * Add all attributes for ORGANIZATION_VACANCY entity
 * This script generates unique UUIDs to avoid conflicts
 */

require_once __DIR__ . '/../../bootstrap.php';

// Entity ID for ORGANIZATION_VACANCY
$entityId = '4c07f6b1-7208-4b72-826a-a9c65195bf0f';

// Check if entity exists
$entity = Database::fetchOne("SELECT * FROM entity_definition WHERE id = ?", [$entityId]);
if (!$entity) {
    die("ERROR: ORGANIZATION_VACANCY entity not found!\n");
}

echo "Adding attributes for ORGANIZATION_VACANCY entity...\n";

// Define all attributes
$attributes = [
    ['code' => 'organization_id', 'name' => 'Organization ID', 'data_type' => 'text', 'is_required' => 1, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => null, 'description' => 'Reference to ORGANIZATION entity', 'display_order' => 1],
    ['code' => 'popular_position_id', 'name' => 'Position ID', 'data_type' => 'text', 'is_required' => 1, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => null, 'description' => 'Reference to POPULAR_ORGANIZATION_POSITION entity', 'display_order' => 2],
    ['code' => 'created_by', 'name' => 'Created By', 'data_type' => 'text', 'is_required' => 1, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => null, 'description' => 'Reference to PERSON who created this vacancy', 'display_order' => 3],
    ['code' => 'title', 'name' => 'Vacancy Title', 'data_type' => 'text', 'is_required' => 1, 'is_label' => 1, 'is_unique' => 0, 'enum_values' => null, 'description' => 'Title of the vacancy', 'display_order' => 4],
    ['code' => 'description', 'name' => 'Description', 'data_type' => 'text', 'is_required' => 0, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => null, 'description' => 'Detailed description of the vacancy', 'display_order' => 5],
    ['code' => 'requirements', 'name' => 'Requirements', 'data_type' => 'text', 'is_required' => 0, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => null, 'description' => 'Required qualifications and skills', 'display_order' => 6],
    ['code' => 'responsibilities', 'name' => 'Responsibilities', 'data_type' => 'text', 'is_required' => 0, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => null, 'description' => 'Key responsibilities of the position', 'display_order' => 7],
    ['code' => 'number_of_openings', 'name' => 'Number of Openings', 'data_type' => 'integer', 'is_required' => 1, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => null, 'description' => 'Number of positions available', 'display_order' => 8],
    ['code' => 'opening_date', 'name' => 'Opening Date', 'data_type' => 'date', 'is_required' => 1, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => null, 'description' => 'Date when vacancy was opened', 'display_order' => 9],
    ['code' => 'closing_date', 'name' => 'Closing Date', 'data_type' => 'date', 'is_required' => 0, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => null, 'description' => 'Date when vacancy will close', 'display_order' => 10],
    ['code' => 'min_salary', 'name' => 'Minimum Salary', 'data_type' => 'number', 'is_required' => 0, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => null, 'description' => 'Minimum salary offered', 'display_order' => 11],
    ['code' => 'max_salary', 'name' => 'Maximum Salary', 'data_type' => 'number', 'is_required' => 0, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => null, 'description' => 'Maximum salary offered', 'display_order' => 12],
    ['code' => 'employment_type', 'name' => 'Employment Type', 'data_type' => 'enum_strings', 'is_required' => 0, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => '["Full-time","Part-time","Contract","Temporary","Internship"]', 'description' => 'Type of employment', 'display_order' => 13],
    ['code' => 'status', 'name' => 'Status', 'data_type' => 'enum_objects', 'is_required' => 1, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => '{"render_as":"select","options":[{"value":"draft","label":"Draft"},{"value":"open","label":"Open"},{"value":"closed","label":"Closed"},{"value":"cancelled","label":"Cancelled"},{"value":"filled","label":"Filled"}]}', 'description' => 'Current status of the vacancy', 'display_order' => 14],
    ['code' => 'is_urgent', 'name' => 'Is Urgent', 'data_type' => 'boolean', 'is_required' => 0, 'is_label' => 0, 'is_unique' => 0, 'enum_values' => null, 'description' => 'Whether this is an urgent vacancy', 'display_order' => 15],
];

$count = 0;
foreach ($attributes as $attr) {
    // Check if attribute already exists
    $existing = Database::fetchOne(
        "SELECT id FROM entity_attribute WHERE entity_id = ? AND code = ?",
        [$entityId, $attr['code']]
    );

    if ($existing) {
        echo "  - {$attr['code']}: Already exists, skipping\n";
        continue;
    }

    // Generate unique UUID
    $attrId = Auth::generateUuid();

    // Insert attribute
    $sql = "INSERT INTO entity_attribute (
                id, entity_id, code, name, data_type, is_required, is_label, is_unique,
                enum_values, description, display_order
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    Database::execute($sql, [
        $attrId,
        $entityId,
        $attr['code'],
        $attr['name'],
        $attr['data_type'],
        $attr['is_required'],
        $attr['is_label'],
        $attr['is_unique'],
        $attr['enum_values'],
        $attr['description'],
        $attr['display_order']
    ]);

    echo "  + {$attr['code']}: Created with ID {$attrId}\n";
    $count++;
}

echo "\nDone! Added {$count} new attributes.\n";
echo "Total attributes for ORGANIZATION_VACANCY: ";
$total = Database::fetchOne("SELECT COUNT(*) as cnt FROM entity_attribute WHERE entity_id = ?", [$entityId]);
echo $total['cnt'] . "\n";
