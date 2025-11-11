<?php
/**
 * Authentication and Authorization Handler
 */

namespace V4L\Core;

class Auth
{
    /**
     * Start secure session
     */
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', !IS_DEVELOPMENT ? 1 : 0);
            ini_set('session.cookie_samesite', 'Strict');
            ini_set('session.gc_maxlifetime', SESSION_LIFETIME);

            session_name(SESSION_NAME);
            session_start();

            // Regenerate session ID periodically to prevent session fixation
            if (!isset($_SESSION['created'])) {
                $_SESSION['created'] = time();
            } elseif (time() - $_SESSION['created'] > 1800) {
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            }
        }
    }

    /**
     * Register a new user
     */
    public static function register(array $data): array
    {
        $errors = [];

        // Validate required fields
        if (empty($data['username'])) {
            $errors['username'] = 'Username is required';
        }
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($data['password']) < PASSWORD_MIN_LENGTH) {
            $errors['password'] = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Check if username already exists
        $existing = Database::fetchOne(
            'SELECT id FROM person WHERE username = :username',
            [':username' => $data['username']]
        );

        if ($existing) {
            return [
                'success' => false,
                'errors' => ['username' => 'Username already exists']
            ];
        }

        // Create person record
        $personId = Database::generateUuid();
        $hashedPassword = password_hash($data['password'], PASSWORD_ARGON2ID);

        try {
            Database::beginTransaction();

            $personData = [
                'id' => $personId,
                'username' => $data['username'],
                'password_hash' => $hashedPassword,
                'first_name' => $data['first_name'] ?? null,
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'primary_email_address' => $data['email'] ?? null,
                'primary_phone_number' => $data['mobile_number'] ?? null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            Database::insert('person', $personData);

            Database::commit();

            return [
                'success' => true,
                'person_id' => $personId,
                'message' => 'Registration successful'
            ];
        } catch (\Exception $e) {
            Database::rollback();
            return [
                'success' => false,
                'errors' => ['general' => 'Registration failed: ' . $e->getMessage()]
            ];
        }
    }

    /**
     * Login user
     */
    public static function login(string $username, string $password): array
    {
        $user = Database::fetchOne(
            'SELECT * FROM person WHERE username = :username AND is_active = 1',
            [':username' => $username]
        );

        if (!$user) {
            return [
                'success' => false,
                'error' => 'Invalid username or password'
            ];
        }

        if (!password_verify($password, $user['password_hash'])) {
            return [
                'success' => false,
                'error' => 'Invalid username or password'
            ];
        }

        // Set session data
        self::startSession();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['logged_in_at'] = time();

        // Update last login
        Database::update(
            'person',
            ['last_login_at' => date('Y-m-d H:i:s')],
            'id = :id',
            [':id' => $user['id']]
        );

        return [
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['primary_email_address']
            ]
        ];
    }

    /**
     * Logout user
     */
    public static function logout(): void
    {
        self::startSession();
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn(): bool
    {
        self::startSession();
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current user ID
     */
    public static function getUserId(): ?string
    {
        self::startSession();
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user data
     */
    public static function getCurrentUser(): ?array
    {
        $userId = self::getUserId();
        if (!$userId) {
            return null;
        }

        $user = Database::fetchOne(
            'SELECT id, username, first_name, middle_name, last_name, primary_email_address, primary_phone_number, date_of_birth, gender
             FROM person WHERE id = :id AND is_active = 1',
            [':id' => $userId]
        );

        return $user ?: null;
    }

    /**
     * Check if user has permission for entity operation
     */
    public static function hasPermission(string $entityCode, string $permissionType): bool
    {
        $userId = self::getUserId();
        if (!$userId) {
            return false;
        }

        // Super admin has all permissions
        if (self::isSuperAdmin()) {
            return true;
        }

        // Get entity ID
        $entity = MetadataLoader::getEntity($entityCode);
        if (!$entity) {
            return false;
        }

        // Get user's positions through employment contracts
        $positions = Database::fetchAll(
            'SELECT position_id
             FROM employment_contract
             WHERE employee_id = :person_id AND status = \'ACTIVE\'',
            [':person_id' => $userId]
        );

        if (empty($positions)) {
            return false;
        }

        $positionIds = array_column($positions, 'position_id');

        // Check if any position has the required permission
        $placeholders = implode(',', array_fill(0, count($positionIds), '?'));
        $sql = "SELECT COUNT(*) as count
                FROM entity_permission_definition
                WHERE entity_id = ?
                AND enum_entity_permission_type_id = ?
                AND popular_organization_position_id IN ($placeholders)";

        $params = array_merge(
            [$entity['id'], $permissionType],
            $positionIds
        );

        $result = Database::fetchOne($sql, $params);
        return $result && $result['count'] > 0;
    }

    /**
     * Check if current user is super admin
     */
    public static function isSuperAdmin(): bool
    {
        $userId = self::getUserId();
        if (!$userId) {
            return false;
        }

        // Check if user is owner of any organization
        $result = Database::fetchOne(
            'SELECT COUNT(*) as count FROM organization WHERE main_admin_id = :user_id',
            [':user_id' => $userId]
        );

        if ($result && $result['count'] > 0) {
            return true;
        }

        // Check if user is in organization_admin table
        $result = Database::fetchOne(
            'SELECT COUNT(*) as count FROM organization_admin WHERE person_id = :user_id',
            [':user_id' => $userId]
        );

        return $result && $result['count'] > 0;
    }

    /**
     * Get user's organizations
     */
    public static function getUserOrganizations(): array
    {
        $userId = self::getUserId();
        if (!$userId) {
            return [];
        }

        $sql = "SELECT DISTINCT o.*, COALESCE(o.short_name || ' ' || lt.name, o.short_name, 'Unknown') as name
                FROM organization o
                LEFT JOIN popular_organization_legal_types lt ON o.legal_type_id = lt.id
                WHERE o.main_admin_id = :user_id
                   OR o.id IN (
                       SELECT organization_id FROM organization_admin WHERE person_id = :user_id
                   )
                   OR o.id IN (
                       SELECT organization_id FROM employment_contract WHERE employee_id = :user_id AND status = 'ACTIVE'
                   )";

        return Database::fetchAll($sql, [':user_id' => $userId]);
    }

    /**
     * Generate CSRF token
     */
    public static function generateCsrfToken(): string
    {
        self::startSession();
        $token = bin2hex(random_bytes(32));
        $_SESSION[CSRF_TOKEN_NAME] = $token;
        return $token;
    }

    /**
     * Verify CSRF token
     */
    public static function verifyCsrfToken(string $token): bool
    {
        self::startSession();
        return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }

    /**
     * Require login (redirect to login page if not logged in)
     */
    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: /login.php');
            exit;
        }
    }

    /**
     * Require permission (return 403 if not authorized)
     */
    public static function requirePermission(string $entityCode, string $permissionType): void
    {
        self::requireLogin();

        if (!self::hasPermission($entityCode, $permissionType)) {
            http_response_code(403);
            die('Access denied');
        }
    }
}
