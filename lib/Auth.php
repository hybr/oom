<?php
/**
 * Authentication and Session Management
 */

class Auth
{
    /**
     * Check if user is authenticated
     */
    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current user ID
     */
    public static function id()
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user data
     */
    public static function user()
    {
        if (!self::check()) {
            return null;
        }

        $userId = self::id();
        $sql = "SELECT * FROM person_credential WHERE id = ? AND deleted_at IS NULL";
        return Database::fetchOne($sql, [$userId]);
    }

    /**
     * Get current organization ID
     */
    public static function currentOrganizationId()
    {
        return $_SESSION['current_organization_id'] ?? null;
    }

    /**
     * Get current organization data
     */
    public static function currentOrganization()
    {
        return $_SESSION['current_organization'] ?? null;
    }

    /**
     * Set current organization
     */
    public static function setCurrentOrganization($organizationId, $organizationData = [])
    {
        $_SESSION['current_organization_id'] = $organizationId;
        $_SESSION['current_organization'] = $organizationData;
    }

    /**
     * Clear current organization
     */
    public static function clearCurrentOrganization()
    {
        unset($_SESSION['current_organization_id']);
        unset($_SESSION['current_organization']);
    }

    /**
     * Initialize user's default organization on login
     * Sets the first active organization as current if user has any
     * Priority: Main Admin > Org Admin > Employee
     */
    public static function initializeDefaultOrganization($userId)
    {
        $sql = "SELECT pc.person_id FROM person_credential pc WHERE pc.id = ? AND pc.deleted_at IS NULL";
        $user = Database::fetchOne($sql, [$userId]);

        if (empty($user['person_id'])) {
            return;
        }

        // Get first organization with priority order
        // 1 = Main Admin (highest), 2 = Org Admin, 3 = Employee (lowest)
        $sql = "SELECT o.id, o.short_name as name,
                       CASE
                           WHEN o.main_admin_id = ? THEN 1
                           WHEN oa.person_id IS NOT NULL THEN 2
                           ELSE 3
                       END as priority,
                       CASE
                           WHEN o.main_admin_id = ? THEN 'MAIN_ADMIN'
                           WHEN oa.role IS NOT NULL THEN oa.role
                           ELSE 'EMPLOYEE'
                       END as role
                FROM organization o
                LEFT JOIN organization_admin oa ON o.id = oa.organization_id
                    AND oa.person_id = ? AND oa.is_active = 1 AND oa.deleted_at IS NULL
                WHERE (o.main_admin_id = ? OR oa.person_id IS NOT NULL)
                  AND o.deleted_at IS NULL
                ORDER BY priority ASC, o.short_name ASC
                LIMIT 1";

        $personId = $user['person_id'];
        $org = Database::fetchOne($sql, [$personId, $personId, $personId, $personId]);

        if ($org) {
            self::setCurrentOrganization($org['id'], [
                'name' => $org['name'],
                'role' => $org['role']
            ]);
        }
    }

    /**
     * Check if user belongs to organization (any capacity)
     * @param string $organizationId
     * @param string|null $userId Optional, defaults to current user
     * @return bool
     */
    public static function belongsToOrganization($organizationId, $userId = null)
    {
        if ($userId === null) {
            $user = self::user();
            if (!$user || empty($user['person_id'])) {
                return false;
            }
            $personId = $user['person_id'];
        } else {
            $sql = "SELECT person_id FROM person_credential WHERE id = ? AND deleted_at IS NULL";
            $user = Database::fetchOne($sql, [$userId]);
            if (!$user || empty($user['person_id'])) {
                return false;
            }
            $personId = $user['person_id'];
        }

        $sql = "SELECT COUNT(*) as count FROM (
                    SELECT 1 FROM organization
                    WHERE id = ? AND main_admin_id = ? AND deleted_at IS NULL
                    UNION
                    SELECT 1 FROM organization_admin
                    WHERE organization_id = ? AND person_id = ?
                      AND is_active = 1 AND deleted_at IS NULL
                    UNION
                    SELECT 1 FROM employment_contract
                    WHERE organization_id = ? AND employee_id = ?
                      AND status = 'ACTIVE' AND deleted_at IS NULL
                ) memberships";

        $result = Database::fetchOne($sql, [
            $organizationId, $personId,
            $organizationId, $personId,
            $organizationId, $personId
        ]);

        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Get user's membership type in organization
     * @param string $organizationId
     * @param string|null $userId
     * @return string|null 'MAIN_ADMIN', 'ORGANIZATION_ADMIN', 'EMPLOYEE', or null
     */
    public static function getOrganizationMembershipType($organizationId, $userId = null)
    {
        if ($userId === null) {
            $user = self::user();
            if (!$user || empty($user['person_id'])) {
                return null;
            }
            $personId = $user['person_id'];
        } else {
            $sql = "SELECT person_id FROM person_credential WHERE id = ? AND deleted_at IS NULL";
            $user = Database::fetchOne($sql, [$userId]);
            if (!$user || empty($user['person_id'])) {
                return null;
            }
            $personId = $user['person_id'];
        }

        // Check in priority order
        if (self::isMainAdmin($organizationId, $userId)) {
            return 'MAIN_ADMIN';
        }

        if (self::isOrganizationAdmin($organizationId, $userId)) {
            return 'ORGANIZATION_ADMIN';
        }

        // Check employment
        $sql = "SELECT COUNT(*) as count FROM employment_contract
                WHERE organization_id = ? AND employee_id = ?
                  AND status = 'ACTIVE' AND deleted_at IS NULL";
        $result = Database::fetchOne($sql, [$organizationId, $personId]);

        if (($result['count'] ?? 0) > 0) {
            return 'EMPLOYEE';
        }

        return null;
    }

    /**
     * Get user's highest permission level in organization
     * @param string $organizationId
     * @param string|null $userId
     * @return string|null 'MAIN_ADMIN', 'SUPER_ADMIN', 'ADMIN', 'MODERATOR', 'EMPLOYEE'
     */
    public static function getOrganizationPermissionLevel($organizationId, $userId = null)
    {
        if ($userId === null) {
            $user = self::user();
            if (!$user || empty($user['person_id'])) {
                return null;
            }
            $personId = $user['person_id'];
        } else {
            $sql = "SELECT person_id FROM person_credential WHERE id = ? AND deleted_at IS NULL";
            $user = Database::fetchOne($sql, [$userId]);
            if (!$user || empty($user['person_id'])) {
                return null;
            }
            $personId = $user['person_id'];
        }

        // Check main admin first (highest)
        if (self::isMainAdmin($organizationId, $userId)) {
            return 'MAIN_ADMIN';
        }

        // Check organization admin role
        $role = self::getAdminRole($organizationId, $userId);
        if ($role) {
            return $role; // SUPER_ADMIN, ADMIN, or MODERATOR
        }

        // Check employment (lowest)
        $sql = "SELECT COUNT(*) as count FROM employment_contract
                WHERE organization_id = ? AND employee_id = ?
                  AND status = 'ACTIVE' AND deleted_at IS NULL";
        $result = Database::fetchOne($sql, [$organizationId, $personId]);

        if (($result['count'] ?? 0) > 0) {
            return 'EMPLOYEE';
        }

        return null;
    }

    /**
     * Check if user has full admin access (main admin or org admin with SUPER_ADMIN/ADMIN role)
     * @param string $organizationId
     * @param string|null $userId
     * @return bool
     */
    public static function hasAdminAccess($organizationId, $userId = null)
    {
        $level = self::getOrganizationPermissionLevel($organizationId, $userId);
        return in_array($level, ['MAIN_ADMIN', 'SUPER_ADMIN', 'ADMIN']);
    }

    /**
     * Check if user can perform action based on ENTITY_PERMISSION_DEFINITION
     * For admins: automatically grants access
     * For employees: checks position-based permissions
     * @param string $entityCode Entity code (e.g., 'REQUISITION')
     * @param string $permissionType Permission type (e.g., 'APPROVER')
     * @param string $organizationId
     * @param string|null $userId
     * @return bool
     */
    public static function canPerformAction($entityCode, $permissionType, $organizationId, $userId = null)
    {
        // Admins can do everything (except MODERATOR)
        $level = self::getOrganizationPermissionLevel($organizationId, $userId);
        if (in_array($level, ['MAIN_ADMIN', 'SUPER_ADMIN', 'ADMIN'])) {
            return true;
        }

        if ($userId === null) {
            $user = self::user();
            if (!$user || empty($user['person_id'])) {
                return false;
            }
            $personId = $user['person_id'];
        } else {
            $sql = "SELECT person_id FROM person_credential WHERE id = ? AND deleted_at IS NULL";
            $user = Database::fetchOne($sql, [$userId]);
            if (!$user || empty($user['person_id'])) {
                return false;
            }
            $personId = $user['person_id'];
        }

        // Check employee position-based permissions
        $sql = "SELECT COUNT(*) as count
                FROM employment_contract ec
                JOIN entity_permission_definition epd ON epd.position_id = ec.position_id
                JOIN entity_definition ed ON ed.id = epd.entity_id
                JOIN enum_entity_permission_type ept ON ept.id = epd.permission_type_id
                WHERE ec.employee_id = ?
                  AND ec.organization_id = ?
                  AND ec.status = 'ACTIVE'
                  AND ec.deleted_at IS NULL
                  AND ed.code = ?
                  AND ept.code = ?
                  AND epd.is_allowed = 1";

        $result = Database::fetchOne($sql, [$personId, $organizationId, $entityCode, $permissionType]);
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Check if user is main admin of organization
     * @param string $organizationId
     * @param string|null $userId
     * @return bool
     */
    public static function isMainAdmin($organizationId, $userId = null)
    {
        if ($userId === null) {
            $user = self::user();
            if (!$user || empty($user['person_id'])) {
                return false;
            }
            $personId = $user['person_id'];
        } else {
            $sql = "SELECT person_id FROM person_credential WHERE id = ? AND deleted_at IS NULL";
            $user = Database::fetchOne($sql, [$userId]);
            if (!$user || empty($user['person_id'])) {
                return false;
            }
            $personId = $user['person_id'];
        }

        $sql = "SELECT COUNT(*) as count FROM organization
                WHERE id = ? AND main_admin_id = ? AND deleted_at IS NULL";
        $result = Database::fetchOne($sql, [$organizationId, $personId]);

        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Check if user is organization admin (any role)
     * @param string $organizationId
     * @param string|null $userId
     * @return bool
     */
    public static function isOrganizationAdmin($organizationId, $userId = null)
    {
        if ($userId === null) {
            $user = self::user();
            if (!$user || empty($user['person_id'])) {
                return false;
            }
            $personId = $user['person_id'];
        } else {
            $sql = "SELECT person_id FROM person_credential WHERE id = ? AND deleted_at IS NULL";
            $user = Database::fetchOne($sql, [$userId]);
            if (!$user || empty($user['person_id'])) {
                return false;
            }
            $personId = $user['person_id'];
        }

        $sql = "SELECT COUNT(*) as count FROM organization_admin
                WHERE organization_id = ? AND person_id = ?
                  AND is_active = 1 AND deleted_at IS NULL";
        $result = Database::fetchOne($sql, [$organizationId, $personId]);

        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Get admin role (for organization admins only)
     * @param string $organizationId
     * @param string|null $userId
     * @return string|null 'SUPER_ADMIN', 'ADMIN', 'MODERATOR', or null
     */
    public static function getAdminRole($organizationId, $userId = null)
    {
        if ($userId === null) {
            $user = self::user();
            if (!$user || empty($user['person_id'])) {
                return null;
            }
            $personId = $user['person_id'];
        } else {
            $sql = "SELECT person_id FROM person_credential WHERE id = ? AND deleted_at IS NULL";
            $user = Database::fetchOne($sql, [$userId]);
            if (!$user || empty($user['person_id'])) {
                return null;
            }
            $personId = $user['person_id'];
        }

        $sql = "SELECT role FROM organization_admin
                WHERE organization_id = ? AND person_id = ?
                  AND is_active = 1 AND deleted_at IS NULL";
        $result = Database::fetchOne($sql, [$organizationId, $personId]);

        return $result['role'] ?? null;
    }

    /**
     * Get all organizations user belongs to (any membership type)
     * @param string|null $userId
     * @return array Array of organizations with membership details
     */
    public static function getUserOrganizations($userId = null)
    {
        if ($userId === null) {
            $user = self::user();
            if (!$user || empty($user['person_id'])) {
                return [];
            }
            $personId = $user['person_id'];
        } else {
            $sql = "SELECT person_id FROM person_credential WHERE id = ? AND deleted_at IS NULL";
            $user = Database::fetchOne($sql, [$userId]);
            if (!$user || empty($user['person_id'])) {
                return [];
            }
            $personId = $user['person_id'];
        }

        $sql = "SELECT DISTINCT
                    o.id,
                    o.short_name as name,
                    CASE
                        WHEN o.main_admin_id = ? THEN 'MAIN_ADMIN'
                        WHEN oa.person_id IS NOT NULL THEN 'ORGANIZATION_ADMIN'
                        WHEN ec.employee_id IS NOT NULL THEN 'EMPLOYEE'
                    END as membership_type,
                    CASE
                        WHEN o.main_admin_id = ? THEN 'SUPER_ADMIN'
                        WHEN oa.role IS NOT NULL THEN oa.role
                        ELSE NULL
                    END as role,
                    ec.job_title,
                    ec.position_id
                FROM organization o
                LEFT JOIN organization_admin oa ON o.id = oa.organization_id
                    AND oa.person_id = ? AND oa.is_active = 1 AND oa.deleted_at IS NULL
                LEFT JOIN employment_contract ec ON o.id = ec.organization_id
                    AND ec.employee_id = ? AND ec.status = 'ACTIVE' AND ec.deleted_at IS NULL
                WHERE (o.main_admin_id = ? OR oa.person_id IS NOT NULL OR ec.employee_id IS NOT NULL)
                  AND o.deleted_at IS NULL
                ORDER BY o.short_name ASC";

        return Database::fetchAll($sql, [$personId, $personId, $personId, $personId, $personId]);
    }

    /**
     * Attempt to login a user
     */
    public static function attempt($username, $password)
    {
        $sql = "SELECT * FROM person_credential WHERE username = ? AND deleted_at IS NULL";
        $user = Database::fetchOne($sql, [$username]);

        if (!$user) {
            self::logFailedAttempt($username);
            return false;
        }

        // Check if account is locked
        if (self::isLocked($username)) {
            return false;
        }

        // Verify password
        if (!password_verify($password, $user['hashed_password'])) {
            self::logFailedAttempt($username);
            return false;
        }

        // Login successful - regenerate session
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        // Update last login timestamp
        $sql = "UPDATE person_credential SET last_login_at = datetime('now') WHERE id = ?";
        Database::execute($sql, [$user['id']]);

        // Clear failed attempts
        self::clearFailedAttempts($username);

        // Initialize default organization
        self::initializeDefaultOrganization($user['id']);

        return true;
    }

    /**
     * Logout the current user
     */
    public static function logout()
    {
        $_SESSION = [];
        session_destroy();
        session_start();
    }

    /**
     * Register a new user
     */
    public static function register($username, $email, $password)
    {
        // Check if username exists
        $sql = "SELECT COUNT(*) as cnt FROM person_credential WHERE username = ?";
        $result = Database::fetchOne($sql, [$username]);
        if ($result['cnt'] > 0) {
            return ['success' => false, 'error' => 'Username already exists'];
        }

        // Check if email exists
        $sql = "SELECT COUNT(*) as cnt FROM person_credential WHERE email = ?";
        $result = Database::fetchOne($sql, [$email]);
        if ($result['cnt'] > 0) {
            return ['success' => false, 'error' => 'Email already exists'];
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_ARGON2ID);

        // Generate UUID
        $id = self::generateUuid();

        // Insert user
        $sql = "INSERT INTO person_credential (id, username, email, hashed_password, created_at, updated_at)
                VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))";

        try {
            Database::execute($sql, [$id, $username, $email, $passwordHash]);
            return ['success' => true, 'user_id' => $id];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Registration failed: ' . $e->getMessage()];
        }
    }

    /**
     * Log failed login attempt
     */
    private static function logFailedAttempt($username)
    {
        if (!isset($_SESSION['failed_attempts'])) {
            $_SESSION['failed_attempts'] = [];
        }

        if (!isset($_SESSION['failed_attempts'][$username])) {
            $_SESSION['failed_attempts'][$username] = [
                'count' => 0,
                'last_attempt' => time(),
            ];
        }

        $_SESSION['failed_attempts'][$username]['count']++;
        $_SESSION['failed_attempts'][$username]['last_attempt'] = time();
    }

    /**
     * Clear failed login attempts
     */
    private static function clearFailedAttempts($username)
    {
        if (isset($_SESSION['failed_attempts'][$username])) {
            unset($_SESSION['failed_attempts'][$username]);
        }
    }

    /**
     * Check if account is locked due to failed attempts
     */
    private static function isLocked($username)
    {
        if (!isset($_SESSION['failed_attempts'][$username])) {
            return false;
        }

        $attempts = $_SESSION['failed_attempts'][$username];
        $maxAttempts = 5;
        $lockoutTime = 900; // 15 minutes

        if ($attempts['count'] >= $maxAttempts) {
            $timeSinceLastAttempt = time() - $attempts['last_attempt'];
            if ($timeSinceLastAttempt < $lockoutTime) {
                return true;
            } else {
                // Lockout expired, reset attempts
                self::clearFailedAttempts($username);
                return false;
            }
        }

        return false;
    }

    /**
     * Generate CSRF token
     */
    public static function generateCsrfToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF token
     */
    public static function validateCsrfToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Generate UUID v4
     */
    public static function generateUuid()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Require authentication (redirect if not logged in)
     */
    public static function requireAuth()
    {
        if (!self::check()) {
            Router::redirect('/auth/login');
        }
    }

    /**
     * Require guest (redirect if logged in)
     */
    public static function requireGuest()
    {
        if (self::check()) {
            Router::redirect('/dashboard');
        }
    }
}
