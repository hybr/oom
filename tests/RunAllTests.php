<?php

require_once 'tests/EntityTest.php';
require_once 'tests/ProcessTest.php';

echo "=== PHP Order Management System - Test Suite ===\n\n";

$allPassed = true;

echo "1. Running Entity Tests...\n";
$entityTestsPassed = EntityTest::runTests();
echo "\n";

echo "2. Running Process Tests...\n";
$processTestsPassed = ProcessTest::runTests();
echo "\n";

$allPassed = $entityTestsPassed && $processTestsPassed;

echo "=== TEST SUMMARY ===\n";
if ($allPassed) {
    echo "✓ All tests passed!\n";
    exit(0);
} else {
    echo "✗ Some tests failed!\n";
    exit(1);
}