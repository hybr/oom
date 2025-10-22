<?php
/**
 * PositionResolver - Resolves positions to actual people using employment chain
 *
 * Handles:
 * - Resolving position → person through employment contract chain
 * - Checking position permissions
 * - Fallback assignment when position is vacant
 * - Finding organization admin as last resort
 *
 * Resolution chain:
 * EMPLOYMENT_CONTRACT → JOB_OFFER → VACANCY_APPLICATION → ORGANIZATION_VACANCY → POPULAR_ORGANIZATION_POSITION
 */

class PositionResolver
{
    /**
     * Resolve who should be assigned a task based on position and permission
     *
     * @param string|null $positionId Position ID from process node
     * @param string|null $permissionTypeId Permission type ID (optional)
     * @param string $organizationId Organization context
     * @return array ['success' => bool, 'person_id' => string, 'assignment_type' => string, 'error' => string]
     */
    public static function resolveAssignment($positionId, $permissionTypeId, $organizationId)
    {
        try {
            // If no position specified, assign to organization admin
            if (!$positionId) {
                return self::getOrganizationAdmin($organizationId);
            }

            // Try to find someone currently holding this position in the organization
            $personId = self::findPersonInPosition($positionId, $organizationId);

            if ($personId) {
                // Verify they have the required permission if specified
                if ($permissionTypeId) {
                    $hasPermission = self::hasPermission($personId, $positionId, $permissionTypeId);
                    if (!$hasPermission) {
                        // Has position but not permission - try fallback
                        return self::getFallbackAssignment($positionId, $organizationId);
                    }
                }

                return [
                    'success' => true,
                    'person_id' => $personId,
                    'assignment_type' => 'AUTO'
                ];
            }

            // Position is vacant - try fallback assignment
            return self::getFallbackAssignment($positionId, $organizationId);

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Find a person currently holding a position in an organization
     *
     * Uses the chain: employment_contract → job_offer → vacancy_application → organization_vacancy → position
     */
    private static function findPersonInPosition($positionId, $organizationId)
    {
        $sql = "SELECT DISTINCT ec.employee_id
                FROM employment_contract ec
                JOIN job_offer jo ON ec.job_offer_id = jo.id
                JOIN vacancy_application va ON jo.application_id = va.id
                JOIN organization_vacancy ov ON va.vacancy_id = ov.id
                WHERE ov.popular_position_id = ?
                AND ec.organization_id = ?
                AND ec.status = 'ACTIVE'
                AND ec.deleted_at IS NULL
                AND (ec.end_date IS NULL OR ec.end_date > datetime('now'))
                LIMIT 1";

        $result = Database::fetchOne($sql, [$positionId, $organizationId]);

        return $result ? $result['employee_id'] : null;
    }

    /**
     * Check if a person has a specific permission for a position
     */
    private static function hasPermission($personId, $positionId, $permissionTypeId)
    {
        // Get the entity permission definition
        $sql = "SELECT COUNT(*) as cnt
                FROM entity_permission_definition
                WHERE position_id = ?
                AND permission_type_id = ?
                AND is_allowed = 1
                AND deleted_at IS NULL";

        $result = Database::fetchOne($sql, [$positionId, $permissionTypeId]);

        return ($result && $result['cnt'] > 0);
    }

    /**
     * Get fallback assignment when primary position is vacant or lacks permission
     */
    private static function getFallbackAssignment($positionId, $organizationId)
    {
        // Check for configured fallback assignments (ordered by priority)
        $sql = "SELECT *
                FROM process_fallback_assignment
                WHERE position_id = ?
                AND organization_id = ?
                AND is_active = 1
                AND deleted_at IS NULL
                ORDER BY priority ASC";

        $fallbacks = Database::fetchAll($sql, [$positionId, $organizationId]);

        foreach ($fallbacks as $fallback) {
            if ($fallback['fallback_type'] === 'PERSON' && $fallback['fallback_person_id']) {
                // Direct person assignment
                return [
                    'success' => true,
                    'person_id' => $fallback['fallback_person_id'],
                    'assignment_type' => 'FALLBACK'
                ];
            } elseif ($fallback['fallback_type'] === 'POSITION' && $fallback['fallback_position_id']) {
                // Try fallback position
                $personId = self::findPersonInPosition($fallback['fallback_position_id'], $organizationId);
                if ($personId) {
                    return [
                        'success' => true,
                        'person_id' => $personId,
                        'assignment_type' => 'FALLBACK'
                    ];
                }
            } elseif ($fallback['fallback_type'] === 'AUTO_ADMIN') {
                // Fallback to organization admin
                return self::getOrganizationAdmin($organizationId);
            }
        }

        // No fallback found - default to organization admin
        return self::getOrganizationAdmin($organizationId);
    }

    /**
     * Get organization admin as last resort
     */
    private static function getOrganizationAdmin($organizationId)
    {
        $sql = "SELECT admin_id FROM organization WHERE id = ? AND deleted_at IS NULL";
        $org = Database::fetchOne($sql, [$organizationId]);

        if (!$org || !$org['admin_id']) {
            return [
                'success' => false,
                'error' => "Organization admin not found for organization: {$organizationId}"
            ];
        }

        return [
            'success' => true,
            'person_id' => $org['admin_id'],
            'assignment_type' => 'FALLBACK'
        ];
    }

    /**
     * Get all people in a specific position across all organizations
     */
    public static function getPeopleInPosition($positionId, $organizationId = null)
    {
        $sql = "SELECT DISTINCT
                    ec.employee_id,
                    p.first_name,
                    p.last_name,
                    p.email,
                    o.short_name as organization_name
                FROM employment_contract ec
                JOIN job_offer jo ON ec.job_offer_id = jo.id
                JOIN vacancy_application va ON jo.application_id = va.id
                JOIN organization_vacancy ov ON va.vacancy_id = ov.id
                JOIN person p ON ec.employee_id = p.id
                JOIN organization o ON ec.organization_id = o.id
                WHERE ov.popular_position_id = ?
                AND ec.status = 'ACTIVE'
                AND ec.deleted_at IS NULL
                AND (ec.end_date IS NULL OR ec.end_date > datetime('now'))";

        $params = [$positionId];

        if ($organizationId) {
            $sql .= " AND ec.organization_id = ?";
            $params[] = $organizationId;
        }

        return Database::fetchAll($sql, $params);
    }

    /**
     * Get positions held by a person in an organization
     */
    public static function getPersonPositions($personId, $organizationId)
    {
        $sql = "SELECT DISTINCT
                    pop.id as position_id,
                    pop.position_name,
                    pop.code as position_code,
                    pod.name as department_name,
                    podesig.name as designation_name
                FROM employment_contract ec
                JOIN job_offer jo ON ec.job_offer_id = jo.id
                JOIN vacancy_application va ON jo.application_id = va.id
                JOIN organization_vacancy ov ON va.vacancy_id = ov.id
                JOIN popular_organization_position pop ON ov.popular_position_id = pop.id
                LEFT JOIN popular_organization_department pod ON pop.department_id = pod.id
                LEFT JOIN popular_organization_designation podesig ON pop.designation_id = podesig.id
                WHERE ec.employee_id = ?
                AND ec.organization_id = ?
                AND ec.status = 'ACTIVE'
                AND ec.deleted_at IS NULL
                AND (ec.end_date IS NULL OR ec.end_date > datetime('now'))";

        return Database::fetchAll($sql, [$personId, $organizationId]);
    }

    /**
     * Check if a person has any active employment in an organization
     */
    public static function isEmployed($personId, $organizationId)
    {
        $sql = "SELECT COUNT(*) as cnt
                FROM employment_contract
                WHERE employee_id = ?
                AND organization_id = ?
                AND status = 'ACTIVE'
                AND deleted_at IS NULL
                AND (end_date IS NULL OR end_date > datetime('now'))";

        $result = Database::fetchOne($sql, [$personId, $organizationId]);

        return ($result && $result['cnt'] > 0);
    }

    /**
     * Get all permissions for a person in an organization
     */
    public static function getPersonPermissions($personId, $organizationId)
    {
        $sql = "SELECT DISTINCT
                    epd.entity_id,
                    ed.code as entity_code,
                    ed.name as entity_name,
                    epd.permission_type_id,
                    ept.code as permission_code,
                    ept.name as permission_name,
                    epd.is_allowed
                FROM employment_contract ec
                JOIN job_offer jo ON ec.job_offer_id = jo.id
                JOIN vacancy_application va ON jo.application_id = va.id
                JOIN organization_vacancy ov ON va.vacancy_id = ov.id
                JOIN entity_permission_definition epd ON ov.popular_position_id = epd.position_id
                LEFT JOIN entity_definition ed ON epd.entity_id = ed.id
                LEFT JOIN enum_entity_permission_type ept ON epd.permission_type_id = ept.id
                WHERE ec.employee_id = ?
                AND ec.organization_id = ?
                AND ec.status = 'ACTIVE'
                AND epd.is_allowed = 1
                AND ec.deleted_at IS NULL
                AND epd.deleted_at IS NULL
                AND (ec.end_date IS NULL OR ec.end_date > datetime('now'))";

        return Database::fetchAll($sql, [$personId, $organizationId]);
    }

    /**
     * Check if person can perform action on entity in organization
     */
    public static function canPerformAction($personId, $organizationId, $entityCode, $permissionCode)
    {
        // Get entity ID
        $entity = EntityManager::getEntity($entityCode);
        if (!$entity) {
            return false;
        }

        // Get permission type ID
        $sql = "SELECT id FROM enum_entity_permission_type WHERE code = ?";
        $permType = Database::fetchOne($sql, [$permissionCode]);
        if (!$permType) {
            return false;
        }

        // Check if person has this permission through their position
        $sql = "SELECT COUNT(*) as cnt
                FROM employment_contract ec
                JOIN job_offer jo ON ec.job_offer_id = jo.id
                JOIN vacancy_application va ON jo.application_id = va.id
                JOIN organization_vacancy ov ON va.vacancy_id = ov.id
                JOIN entity_permission_definition epd ON ov.popular_position_id = epd.position_id
                WHERE ec.employee_id = ?
                AND ec.organization_id = ?
                AND epd.entity_id = ?
                AND epd.permission_type_id = ?
                AND epd.is_allowed = 1
                AND ec.status = 'ACTIVE'
                AND ec.deleted_at IS NULL
                AND epd.deleted_at IS NULL
                AND (ec.end_date IS NULL OR ec.end_date > datetime('now'))";

        $result = Database::fetchOne($sql, [
            $personId,
            $organizationId,
            $entity['id'],
            $permType['id']
        ]);

        return ($result && $result['cnt'] > 0);
    }
}
