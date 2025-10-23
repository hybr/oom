<?php
/**
 * Switch current organization
 * Sets the current organization in the user's session
 */

require_once __DIR__ . '/../../../bootstrap.php';

header('Content-Type: application/json');

try {
    // Check authentication
    if (!Auth::check()) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit;
    }

    // Get request data
    $input = json_decode(file_get_contents('php://input'), true);
    $organizationId = $input['organization_id'] ?? null;

    if (empty($organizationId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Organization ID is required']);
        exit;
    }

    $user = Auth::user();

    if (empty($user['person_id'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'User has no person record']);
        exit;
    }

    // Verify user belongs to organization via ANY membership type
    // Following guide: @guides/ORGANIZATION_MEMBERSHIP_PERMISSIONS.md
    if (!Auth::belongsToOrganization($organizationId)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'You do not have access to this organization']);
        exit;
    }

    // Get organization details
    $sql = "SELECT id, short_name as name, subdomain FROM organization WHERE id = ? AND deleted_at IS NULL";
    $org = Database::fetchOne($sql, [$organizationId]);

    if (!$org) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Organization not found']);
        exit;
    }

    // Get membership details
    $membershipType = Auth::getOrganizationMembershipType($organizationId);
    $permissionLevel = Auth::getOrganizationPermissionLevel($organizationId);
    $adminRole = Auth::getAdminRole($organizationId);

    // Get job title if employee
    $jobTitle = null;
    if ($membershipType === 'EMPLOYEE' || $permissionLevel === 'EMPLOYEE') {
        $sql = "SELECT job_title FROM employment_contract
                WHERE organization_id = ? AND employee_id = ?
                  AND status = 'ACTIVE' AND deleted_at IS NULL";
        $empContract = Database::fetchOne($sql, [$organizationId, $user['person_id']]);
        $jobTitle = $empContract['job_title'] ?? null;
    }

    // Set current organization in session with membership info
    Auth::setCurrentOrganization($organizationId, [
        'name' => $org['name'],
        'subdomain' => $org['subdomain'],
        'membership_type' => $membershipType,
        'permission_level' => $permissionLevel,
        'role' => $adminRole,
        'job_title' => $jobTitle
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Organization switched successfully',
        'organization' => [
            'id' => $organizationId,
            'name' => $org['name'],
            'subdomain' => $org['subdomain'],
            'membership_type' => $membershipType,
            'permission_level' => $permissionLevel,
            'role' => $adminRole,
            'job_title' => $jobTitle,
            'is_admin' => Auth::hasAdminAccess($organizationId),
            'is_main_admin' => Auth::isMainAdmin($organizationId)
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to switch organization: ' . $e->getMessage()
    ]);
}
