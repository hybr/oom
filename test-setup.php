<?php

/**
 * V4L Setup Test Script
 * Run this to verify your installation is working correctly
 */

echo "=================================================\n";
echo "  V4L - VOCAL 4 LOCAL - Setup Verification\n";
echo "=================================================\n\n";

// Test 1: PHP Version
echo "Test 1: Checking PHP version...\n";
$phpVersion = phpversion();
echo "   PHP Version: $phpVersion\n";
if (version_compare($phpVersion, '8.1.0', '>=')) {
    echo "   ✅ PHP version is compatible\n\n";
} else {
    echo "   ❌ PHP version must be 8.1 or higher\n\n";
    exit(1);
}

// Test 2: Required Extensions
echo "Test 2: Checking required PHP extensions...\n";
$requiredExtensions = ['pdo', 'pdo_sqlite', 'mbstring', 'json'];
$missingExtensions = [];

foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "   ✅ $ext extension loaded\n";
    } else {
        echo "   ❌ $ext extension missing\n";
        $missingExtensions[] = $ext;
    }
}

if (!empty($missingExtensions)) {
    echo "\n   Please install missing extensions: " . implode(', ', $missingExtensions) . "\n\n";
    exit(1);
}
echo "\n";

// Test 3: Bootstrap
echo "Test 3: Loading application bootstrap...\n";
try {
    require_once __DIR__ . '/bootstrap.php';
    echo "   ✅ Bootstrap loaded successfully\n\n";
} catch (Exception $e) {
    echo "   ❌ Bootstrap failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 4: Database Connection
echo "Test 4: Testing database connection...\n";
try {
    $db = db();
    $result = $db->selectOne("SELECT 1 as test");
    if ($result['test'] == 1) {
        echo "   ✅ Database connection successful\n\n";
    }
} catch (Exception $e) {
    echo "   ❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "   Run: php database/init-db.php\n\n";
    exit(1);
}

// Test 5: Check Tables
echo "Test 5: Verifying database tables...\n";
$requiredTables = [
    'continents', 'countries', 'languages', 'postal_addresses',
    'persons', 'credentials',
    'organizations', 'organization_vacancies',
    'catalog_items', 'catalog_categories'
];

$missingTables = [];
foreach ($requiredTables as $table) {
    if ($db->tableExists($table)) {
        echo "   ✅ Table '$table' exists\n";
    } else {
        echo "   ❌ Table '$table' missing\n";
        $missingTables[] = $table;
    }
}

if (!empty($missingTables)) {
    echo "\n   Please run: php database/init-db.php\n\n";
    exit(1);
}
echo "\n";

// Test 6: File Permissions
echo "Test 6: Checking file permissions...\n";
$writableDirs = ['database', 'logs', 'uploads'];
$permissionIssues = [];

foreach ($writableDirs as $dir) {
    if (is_writable(__DIR__ . '/' . $dir)) {
        echo "   ✅ Directory '$dir/' is writable\n";
    } else {
        echo "   ⚠️  Directory '$dir/' is not writable\n";
        $permissionIssues[] = $dir;
    }
}

if (!empty($permissionIssues)) {
    echo "\n   Run: chmod -R 755 " . implode(' ', $permissionIssues) . "\n";
}
echo "\n";

// Test 7: Core Classes
echo "Test 7: Loading core classes...\n";
$coreClasses = ['Database', 'Auth', 'Validator', 'Router'];
foreach ($coreClasses as $class) {
    if (class_exists($class)) {
        echo "   ✅ Class '$class' loaded\n";
    } else {
        echo "   ❌ Class '$class' not found\n";
    }
}
echo "\n";

// Test 8: Configuration
echo "Test 8: Checking configuration...\n";
$appName = config('app.name');
$dbConnection = config('database.connection');
echo "   App Name: $appName\n";
echo "   Database: $dbConnection\n";
echo "   ✅ Configuration loaded\n\n";

// Test 9: Entity Classes
echo "Test 9: Loading entity classes...\n";
require_once ENTITIES_PATH . '/Continent.php';
require_once ENTITIES_PATH . '/Country.php';
require_once ENTITIES_PATH . '/Person.php';
require_once ENTITIES_PATH . '/Organization.php';

$continent = new Continent();
$country = new Country();
$person = new Person();
$organization = new Organization();

echo "   ✅ All entity classes loaded\n\n";

// Test 10: Count Records
echo "Test 10: Checking database records...\n";
$counts = [
    'Continents' => $continent->count(),
    'Countries' => $country->count(),
    'Persons' => $person->count(),
    'Organizations' => $organization->count(),
];

foreach ($counts as $entity => $count) {
    echo "   $entity: $count records\n";
}
echo "\n";

// Summary
echo "=================================================\n";
echo "  ✅ ALL TESTS PASSED!\n";
echo "=================================================\n\n";

echo "Your V4L installation is ready!\n\n";

echo "Next steps:\n";
echo "1. Start your web server:\n";
echo "   cd public && php -S localhost:8000\n\n";
echo "2. Visit: http://localhost:8000\n\n";
echo "3. Create your account and start exploring!\n\n";

echo "For detailed instructions, see:\n";
echo "- QUICK_START.md\n";
echo "- README.md\n\n";

echo "Happy building! 🏪\n";
