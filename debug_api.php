<?php
echo "Testing department API debug...\n";

// Set up environment like API
$_SERVER['REQUEST_METHOD'] = 'GET';
$_GET['action'] = 'list';

try {
    require_once 'entities/PopularOrganizationDepartment.php';
    echo "PopularOrganizationDepartment loaded successfully\n";

    $departments = PopularOrganizationDepartment::all();
    echo "Found " . count($departments) . " departments\n";

    require_once 'entities/Organization.php';
    echo "Organization loaded successfully\n";

    $organizations = Organization::all();
    echo "Found " . count($organizations) . " organizations\n";

    echo "All entities working correctly!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>