<?php

// Script to find and fix duplicate fields in PopularOrganizationTeam.php

$file = __DIR__ . '/../entities/PopularOrganizationTeam.php';
$content = file_get_contents($file);

echo "Analyzing PopularOrganizationTeam.php for duplicates...\n\n";

// Find all field names in the fillable array
preg_match('/protected \$fillable = \[(.*?)\];/s', $content, $fillableMatch);
if ($fillableMatch) {
    $fillableContent = $fillableMatch[1];
    preg_match_all("/'([^']+)'/", $fillableContent, $fields);
    $allFields = $fields[1];

    echo "Total fields in fillable array: " . count($allFields) . "\n";

    // Find duplicates
    $fieldCounts = array_count_values($allFields);
    $duplicates = array_filter($fieldCounts, function($count) {
        return $count > 1;
    });

    if (!empty($duplicates)) {
        echo "\nDuplicate fields found in fillable array:\n";
        foreach ($duplicates as $field => $count) {
            echo "  - $field (appears $count times)\n";
        }
    } else {
        echo "\nNo duplicates found in fillable array.\n";
    }
}

// Find duplicates in schema
preg_match('/protected function getSchema\(\) \{.*?return "CREATE TABLE.*?\(.*?\)";/s', $content, $schemaMatch);
if ($schemaMatch) {
    $schemaContent = $schemaMatch[0];
    preg_match_all('/\s+(\w+)\s+(TEXT|VARCHAR|INTEGER|DECIMAL|DATE|DATETIME|BOOLEAN)/i', $schemaContent, $schemaFields);
    $allSchemaFields = $schemaFields[1];

    echo "\nTotal fields in schema: " . count($allSchemaFields) . "\n";

    // Find duplicates
    $schemaCounts = array_count_values($allSchemaFields);
    $schemaDuplicates = array_filter($schemaCounts, function($count) {
        return $count > 1;
    });

    if (!empty($schemaDuplicates)) {
        echo "\nDuplicate fields found in schema:\n";
        foreach ($schemaDuplicates as $field => $count) {
            echo "  - $field (appears $count times)\n";
        }
    } else {
        echo "\nNo duplicates found in schema.\n";
    }
}

echo "\nDone!\n";