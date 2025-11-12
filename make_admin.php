<?php
/**
 * Make User Super Admin
 *
 * This script creates a default organization and makes a user the admin
 * Useful for development and initial setup
 */

require_once __DIR__ . '/config/config.php';
require_once LIB_PATH . '/core/Autoloader.php';

use V4L\Core\Autoloader;
use V4L\Core\Database;

Autoloader::register();

echo "========================================\n";
echo "Make User Super Admin\n";
echo "========================================\n\n";

// Get username from command line or prompt
$username = $argv[1] ?? null;

if (!$username) {
    echo "Usage: php make_admin.php <username>\n";
    echo "Or run interactively:\n\n";
    echo "Enter username: ";
    $username = trim(fgets(STDIN));
}

if (empty($username)) {
    echo "ERROR: Username is required\n";
    exit(1);
}

try {
    // Find the user
    $user = Database::fetchOne(
        'SELECT * FROM person WHERE username = :username',
        [':username' => $username]
    );

    if (!$user) {
        echo "ERROR: User '$username' not found\n";
        exit(1);
    }

    echo "Found user: {$user['username']}\n";
    echo "  Name: {$user['first_name']} {$user['last_name']}\n";
    echo "  ID: {$user['id']}\n\n";

    // Check if user is already a super admin
    $isAdmin = Database::fetchOne(
        'SELECT COUNT(*) as count FROM organization_admin WHERE person_id = :user_id',
        [':user_id' => $user['id']]
    );

    if ($isAdmin && $isAdmin['count'] > 0) {
        echo "User is already a super admin!\n";
        exit(0);
    }

    // Create a default organization if it doesn't exist
    $org = Database::fetchOne(
        'SELECT * FROM organization WHERE code = :code',
        [':code' => 'DEFAULT_ORG']
    );

    if (!$org) {
        echo "Creating default organization...\n";

        $orgId = Database::generateUuid();
        $orgData = [
            'id' => $orgId,
            'code' => 'DEFAULT_ORG',
            'short_name' => 'Default Organization',
            'full_name' => 'Default Organization',
            'main_admin_id' => $user['id'],
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        Database::beginTransaction();
        Database::insert('organization', $orgData);
        Database::commit();

        echo "  [OK] Organization created with ID: $orgId\n\n";
    } else {
        $orgId = $org['id'];
        echo "Using existing organization: {$org['short_name']} (ID: $orgId)\n\n";
    }

    // Add user to organization_admin table
    echo "Making user a super admin...\n";

    $adminData = [
        'id' => Database::generateUuid(),
        'person_id' => $user['id'],
        'organization_id' => $orgId,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    Database::beginTransaction();
    Database::insert('organization_admin', $adminData);
    Database::commit();

    echo "  [OK] User is now a super admin!\n\n";

    echo "========================================\n";
    echo "Success!\n";
    echo "========================================\n";
    echo "User '$username' now has super admin privileges.\n";
    echo "They have full access to all entities and operations.\n\n";

} catch (Exception $e) {
    Database::rollback();
    echo "\n[ERROR] " . $e->getMessage() . "\n";
    exit(1);
}
