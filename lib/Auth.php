<?php

/**
 * Auth class - handles user authentication
 */
class Auth {
    private $db;
    private $sessionKey = 'user_id';
    private $orgSessionKey = 'organization_id';

    public function __construct() {
        $this->db = db();
    }

    /**
     * Attempt to log in a user
     */
    public function login($username, $password) {
        $sql = "SELECT c.*, p.id as person_id, p.first_name, p.last_name
                FROM credentials c
                JOIN persons p ON c.person_id = p.id
                WHERE c.username = ? AND c.deleted_at IS NULL";

        $credential = $this->db->selectOne($sql, [$username]);

        if (!$credential) {
            return false;
        }

        if (!password_verify($password, $credential['password_hash'])) {
            return false;
        }

        // Set session
        $_SESSION[$this->sessionKey] = $credential['person_id'];
        $_SESSION['username'] = $credential['username'];
        $_SESSION['full_name'] = trim($credential['first_name'] . ' ' . $credential['last_name']);

        // Regenerate session ID for security
        session_regenerate_id(true);

        return true;
    }

    /**
     * Log out the current user
     */
    public function logout() {
        unset($_SESSION[$this->sessionKey]);
        unset($_SESSION['username']);
        unset($_SESSION['full_name']);
        unset($_SESSION[$this->orgSessionKey]);
        session_destroy();
    }

    /**
     * Check if user is authenticated
     */
    public function check() {
        return isset($_SESSION[$this->sessionKey]);
    }

    /**
     * Get the current user ID
     */
    public function id() {
        return $_SESSION[$this->sessionKey] ?? null;
    }

    /**
     * Get the current user's full name
     */
    public function user() {
        if (!$this->check()) {
            return null;
        }

        return [
            'id' => $_SESSION[$this->sessionKey] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'full_name' => $_SESSION['full_name'] ?? null,
        ];
    }

    /**
     * Require authentication (redirect to login if not authenticated)
     */
    public function requireAuth() {
        if (!$this->check()) {
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
            redirect('/pages/auth/login.php');
        }
    }

    /**
     * Register a new user
     */
    public function register($username, $password, $personId) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO credentials (username, password_hash, person_id, created_at)
                VALUES (?, ?, ?, datetime('now'))";

        try {
            $this->db->insert($sql, [$username, $passwordHash, $personId]);
            return true;
        } catch (Exception $e) {
            error_log('Registration failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if username already exists
     */
    public function usernameExists($username) {
        $sql = "SELECT id FROM credentials WHERE username = ?";
        $result = $this->db->selectOne($sql, [$username]);
        return !empty($result);
    }

    /**
     * Set the active organization
     */
    public function setOrganization($organizationId) {
        $_SESSION[$this->orgSessionKey] = $organizationId;
    }

    /**
     * Get the active organization ID
     */
    public function organizationId() {
        return $_SESSION[$this->orgSessionKey] ?? null;
    }

    /**
     * Get all organizations the user is admin of
     */
    public function getUserOrganizations() {
        if (!$this->check()) {
            return [];
        }

        $sql = "SELECT o.*, olc.name as legal_category_name
                FROM organizations o
                LEFT JOIN organization_legal_categories olc ON o.legal_category_id = olc.id
                WHERE o.admin_id = ? AND o.deleted_at IS NULL
                ORDER BY o.short_name";

        return $this->db->select($sql, [$this->id()]);
    }
}
