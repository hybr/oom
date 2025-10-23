<?php
/**
 * Get user's organizations
 * Returns all organizations where the user belongs via three membership types:
 * 1. Main Admin (ORGANIZATION.main_admin_id)
 * 2. Organization Admin (ORGANIZATION_ADMIN table)
 * 3. Employee (EMPLOYMENT_CONTRACT table)
 *
 * Following guide: @guides/ORGANIZATION_MEMBERSHIP_PERMISSIONS.md
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

    $user = Auth::user();

    if (empty($user['person_id'])) {
        echo json_encode(['success' => true, 'organizations' => []]);
        exit;
    }

    $personId = $user['person_id'];

    // Get all organizations with ALL membership types (using UNION)
    // This query implements the three-tier system from the guide
    $sql = "
        -- 1. Main Admin memberships
        SELECT
            o.id,
            o.short_name as name,
            o.subdomain,
            'MAIN_ADMIN' as membership_type,
            'SUPER_ADMIN' as role,
            NULL as job_title,
            NULL as position_id,
            1 as priority
        FROM organization o
        WHERE o.main_admin_id = ?
          AND o.deleted_at IS NULL

        UNION

        -- 2. Organization Admin memberships
        SELECT
            o.id,
            o.short_name as name,
            o.subdomain,
            'ORGANIZATION_ADMIN' as membership_type,
            oa.role,
            NULL as job_title,
            NULL as position_id,
            CASE
                WHEN oa.role = 'SUPER_ADMIN' THEN 2
                WHEN oa.role = 'ADMIN' THEN 3
                WHEN oa.role = 'MODERATOR' THEN 4
                ELSE 5
            END as priority
        FROM organization_admin oa
        INNER JOIN organization o ON oa.organization_id = o.id
        WHERE oa.person_id = ?
          AND oa.is_active = 1
          AND oa.deleted_at IS NULL
          AND o.deleted_at IS NULL

        UNION

        -- 3. Employee memberships
        SELECT
            o.id,
            o.short_name as name,
            o.subdomain,
            'EMPLOYEE' as membership_type,
            NULL as role,
            jo.position_title as job_title,
            NULL as position_id,
            6 as priority
        FROM employment_contract ec
        INNER JOIN organization o ON ec.organization_id = o.id
        LEFT JOIN job_offer jo ON ec.job_offer_id = jo.id
        WHERE ec.employee_id = ?
          AND ec.status = 'ACTIVE'
          AND ec.deleted_at IS NULL
          AND o.deleted_at IS NULL

        ORDER BY name ASC
    ";

    $allMemberships = Database::fetchAll($sql, [$personId, $personId, $personId]);

    // Group memberships by organization (a person can have multiple membership types per org)
    $organizationsMap = [];

    foreach ($allMemberships as $membership) {
        $orgId = $membership['id'];

        if (!isset($organizationsMap[$orgId])) {
            $organizationsMap[$orgId] = [
                'id' => $orgId,
                'name' => $membership['name'],
                'subdomain' => $membership['subdomain'],
                'memberships' => [],
                'highest_priority' => 999,
                'highest_level' => null
            ];
        }

        // Add this membership type
        $organizationsMap[$orgId]['memberships'][] = [
            'type' => $membership['membership_type'],
            'role' => $membership['role'],
            'job_title' => $membership['job_title'],
            'position_id' => $membership['position_id']
        ];

        // Track highest priority (lowest number = highest priority)
        if ($membership['priority'] < $organizationsMap[$orgId]['highest_priority']) {
            $organizationsMap[$orgId]['highest_priority'] = $membership['priority'];

            // Set highest level based on priority
            if ($membership['membership_type'] === 'MAIN_ADMIN') {
                $organizationsMap[$orgId]['highest_level'] = 'MAIN_ADMIN';
            } elseif ($membership['role']) {
                $organizationsMap[$orgId]['highest_level'] = $membership['role'];
            } else {
                $organizationsMap[$orgId]['highest_level'] = 'EMPLOYEE';
            }
        }
    }

    // Convert map to array
    $organizations = array_values($organizationsMap);

    // Sort by highest priority, then by name
    usort($organizations, function($a, $b) {
        if ($a['highest_priority'] === $b['highest_priority']) {
            return strcmp($a['name'], $b['name']);
        }
        return $a['highest_priority'] - $b['highest_priority'];
    });

    echo json_encode([
        'success' => true,
        'organizations' => $organizations,
        'current_organization_id' => $_SESSION['current_organization_id'] ?? null,
        'total_count' => count($organizations)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch organizations: ' . $e->getMessage()
    ]);
}
