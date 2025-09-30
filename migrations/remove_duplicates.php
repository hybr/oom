<?php

// Script to remove duplicate fields from PopularOrganizationTeam.php

$file = __DIR__ . '/../entities/PopularOrganizationTeam.php';
$content = file_get_contents($file);

echo "Removing duplicates from PopularOrganizationTeam.php...\n\n";

// Step 1: Fix fillable array
preg_match('/protected \$fillable = \[(.*?)\];/s', $content, $fillableMatch);
if ($fillableMatch) {
    $fillableContent = $fillableMatch[1];
    preg_match_all("/'([^']+)'/", $fillableContent, $fields);
    $allFields = $fields[1];

    // Remove duplicates while preserving order
    $uniqueFields = [];
    $seen = [];
    foreach ($allFields as $field) {
        if (!isset($seen[$field])) {
            $uniqueFields[] = $field;
            $seen[$field] = true;
        }
    }

    echo "Fillable array: Removed " . (count($allFields) - count($uniqueFields)) . " duplicates\n";
    echo "  Before: " . count($allFields) . " fields\n";
    echo "  After: " . count($uniqueFields) . " fields\n\n";

    // Rebuild fillable array
    $newFillable = "protected \$fillable = [\n";
    foreach ($uniqueFields as $field) {
        $newFillable .= "        '{$field}',\n";
    }
    $newFillable .= "    ];";

    // Replace in content
    $content = preg_replace('/protected \$fillable = \[.*?\];/s', $newFillable, $content);
}

// Step 2: Fix schema
preg_match('/protected function getSchema\(\) \{\s*return "CREATE TABLE IF NOT EXISTS \{\$this->table\} \((.*?)\)";/s', $content, $schemaMatch);
if ($schemaMatch) {
    $schemaFields = $schemaMatch[1];

    // Split by commas but preserve structure
    $lines = explode("\n", $schemaFields);
    $uniqueLines = [];
    $seenFields = [];

    foreach ($lines as $line) {
        $trimmed = trim($line);
        // Extract field name (first word before space or parenthesis)
        if (preg_match('/^\s*(\w+)[\s\(]/', $line, $fieldMatch)) {
            $fieldName = $fieldMatch[1];

            // Skip if already seen, unless it's a special keyword
            $specialKeywords = ['PRIMARY', 'FOREIGN', 'UNIQUE', 'INDEX', 'KEY', 'CONSTRAINT', 'CHECK'];
            $isSpecial = false;
            foreach ($specialKeywords as $keyword) {
                if (stripos($trimmed, $keyword) === 0) {
                    $isSpecial = true;
                    break;
                }
            }

            if ($isSpecial || !isset($seenFields[$fieldName])) {
                $uniqueLines[] = $line;
                $seenFields[$fieldName] = true;
            }
        } else {
            // Keep lines that don't match (like empty lines or special constructs)
            $uniqueLines[] = $line;
        }
    }

    $newSchema = implode("\n", $uniqueLines);

    echo "Schema: Processed fields\n";
    echo "  Before: " . count($lines) . " lines\n";
    echo "  After: " . count($uniqueLines) . " lines\n\n";

    // Replace schema in content
    $content = preg_replace(
        '/(protected function getSchema\(\) \{\s*return "CREATE TABLE IF NOT EXISTS \{\$this->table\} \().*?(\)";)/s',
        "$1{$newSchema}$2",
        $content
    );
}

// Save the file
file_put_contents($file, $content);

echo "Successfully removed duplicates and saved file!\n";
echo "\nYou can now run migrations/migrate.php to create the table.\n";