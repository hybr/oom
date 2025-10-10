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
        $sql = "SELECT * FROM credential WHERE id = ? AND deleted_at IS NULL";
        return Database::fetchOne($sql, [$userId]);
    }

    /**
     * Attempt to login a user
     */
    public static function attempt($username, $password)
    {
        $sql = "SELECT * FROM credential WHERE username = ? AND deleted_at IS NULL";
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
        if (!password_verify($password, $user['password_hash'])) {
            self::logFailedAttempt($username);
            return false;
        }

        // Login successful - regenerate session
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        // Clear failed attempts
        self::clearFailedAttempts($username);

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
        $sql = "SELECT COUNT(*) as cnt FROM credential WHERE username = ?";
        $result = Database::fetchOne($sql, [$username]);
        if ($result['cnt'] > 0) {
            return ['success' => false, 'error' => 'Username already exists'];
        }

        // Check if email exists
        $sql = "SELECT COUNT(*) as cnt FROM credential WHERE email = ?";
        $result = Database::fetchOne($sql, [$email]);
        if ($result['cnt'] > 0) {
            return ['success' => false, 'error' => 'Email already exists'];
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_ARGON2ID);

        // Generate UUID
        $id = self::generateUuid();

        // Insert user
        $sql = "INSERT INTO credential (id, username, email, password_hash, created_at, updated_at)
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
