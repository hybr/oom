<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * Credential Entity
 *
 * Manages user authentication credentials including login, signup, and password management.
 * Implements secure password hashing and account security features.
 */
class Credential extends BaseEntity {
    protected $table = 'credentials';
    protected $fillable = [
        'username',
        'password_hash',
        'person_id',
        'email',
        'email_verified',
        'email_verified_at',
        'phone_number',
        'phone_verified',
        'phone_verified_at',
        'two_factor_enabled',
        'two_factor_secret',
        'backup_codes',
        'failed_login_attempts',
        'last_failed_login_at',
        'locked_until',
        'is_locked',
        'last_login_at',
        'last_login_ip',
        'last_password_change_at',
        'password_reset_token',
        'password_reset_expires_at',
        'remember_token',
        'remember_token_expires_at',
        'account_status',
        'activation_token',
        'activation_expires_at',
        'is_active',
        'security_question_1',
        'security_answer_1_hash',
        'security_question_2',
        'security_answer_2_hash',
        'preferred_language',
        'timezone',
        'session_timeout',
        'password_history',
        'force_password_change',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        'password_hash',
        'two_factor_secret',
        'backup_codes',
        'password_reset_token',
        'remember_token',
        'activation_token',
        'security_answer_1_hash',
        'security_answer_2_hash',
        'password_history'
    ];

    /**
     * Login method - authenticate user
     */
    public function login($username, $password) {
        // Find credential by username or email
        $sql = "SELECT * FROM {$this->table} WHERE (username = ? OR email = ?) AND deleted_at IS NULL";
        $credential = $this->queryOne($sql, [$username, $username]);

        if (!$credential) {
            return ['success' => false, 'message' => 'Invalid username or password'];
        }

        // Check if account is locked
        if ($credential['is_locked'] || ($credential['locked_until'] && strtotime($credential['locked_until']) > time())) {
            return ['success' => false, 'message' => 'Account is locked. Please contact support or try again later.'];
        }

        // Check if account is active
        if (!$credential['is_active'] || $credential['account_status'] !== 'active') {
            return ['success' => false, 'message' => 'Account is not active'];
        }

        // Verify password
        if (!password_verify($password, $credential['password_hash'])) {
            $this->recordFailedLogin($credential['id']);
            return ['success' => false, 'message' => 'Invalid username or password'];
        }

        // Reset failed login attempts
        $this->resetFailedLoginAttempts($credential['id']);

        // Update last login
        $this->updateLastLogin($credential['id']);

        // Check if password needs rehashing (if algorithm changed)
        if (password_needs_rehash($credential['password_hash'], PASSWORD_DEFAULT)) {
            $this->updatePassword($credential['id'], $password);
        }

        return [
            'success' => true,
            'credential_id' => $credential['id'],
            'person_id' => $credential['person_id'],
            'two_factor_required' => $credential['two_factor_enabled'] == 1
        ];
    }

    /**
     * Sign up - create new user account
     */
    public function signUp($data) {
        // Validate data
        $validation = $this->validateSignUp($data);
        if (!$validation['success']) {
            return $validation;
        }

        // Check if username already exists
        if ($this->usernameExists($data['username'])) {
            return ['success' => false, 'message' => 'Username already exists'];
        }

        // Check if email already exists
        if (!empty($data['email']) && $this->emailExists($data['email'])) {
            return ['success' => false, 'message' => 'Email already exists'];
        }

        // Hash password
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

        // Generate activation token
        $activationToken = bin2hex(random_bytes(32));

        // Create credential
        $credentialData = [
            'username' => $data['username'],
            'password_hash' => $passwordHash,
            'person_id' => $data['person_id'],
            'email' => $data['email'] ?? null,
            'phone_number' => $data['phone_number'] ?? null,
            'activation_token' => $activationToken,
            'activation_expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours')),
            'account_status' => 'pending_activation',
            'is_active' => 0
        ];

        $credentialId = $this->create($credentialData);

        if ($credentialId) {
            return [
                'success' => true,
                'credential_id' => $credentialId,
                'activation_token' => $activationToken
            ];
        }

        return ['success' => false, 'message' => 'Failed to create account'];
    }

    /**
     * Forgot password - generate reset token
     */
    public function forgotPassword($usernameOrEmail) {
        $sql = "SELECT * FROM {$this->table} WHERE (username = ? OR email = ?) AND deleted_at IS NULL";
        $credential = $this->queryOne($sql, [$usernameOrEmail, $usernameOrEmail]);

        if (!$credential) {
            // Don't reveal whether user exists
            return ['success' => true, 'message' => 'If the account exists, a password reset link has been sent'];
        }

        // Generate reset token
        $resetToken = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $sql = "UPDATE {$this->table}
                SET password_reset_token = ?, password_reset_expires_at = ?, updated_at = datetime('now')
                WHERE id = ?";
        $this->db->update($sql, [$resetToken, $expiresAt, $credential['id']]);

        return [
            'success' => true,
            'reset_token' => $resetToken,
            'email' => $credential['email'],
            'message' => 'Password reset link has been sent'
        ];
    }

    /**
     * Reset password using token
     */
    public function resetPassword($token, $newPassword) {
        $sql = "SELECT * FROM {$this->table}
                WHERE password_reset_token = ? AND password_reset_expires_at > datetime('now') AND deleted_at IS NULL";
        $credential = $this->queryOne($sql, [$token]);

        if (!$credential) {
            return ['success' => false, 'message' => 'Invalid or expired reset token'];
        }

        // Validate password strength
        $validation = $this->validatePassword($newPassword);
        if (!$validation['success']) {
            return $validation;
        }

        // Hash new password
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password and clear reset token
        $sql = "UPDATE {$this->table}
                SET password_hash = ?, password_reset_token = NULL, password_reset_expires_at = NULL,
                    last_password_change_at = datetime('now'), force_password_change = 0, updated_at = datetime('now')
                WHERE id = ?";
        $this->db->update($sql, [$passwordHash, $credential['id']]);

        // Add to password history
        $this->addToPasswordHistory($credential['id'], $passwordHash);

        return ['success' => true, 'message' => 'Password has been reset successfully'];
    }

    /**
     * Change password (authenticated user)
     */
    public function changePassword($credentialId, $currentPassword, $newPassword) {
        $credential = $this->find($credentialId);
        if (!$credential) {
            return ['success' => false, 'message' => 'Credential not found'];
        }

        // Verify current password
        if (!password_verify($currentPassword, $credential['password_hash'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }

        // Validate new password
        $validation = $this->validatePassword($newPassword);
        if (!$validation['success']) {
            return $validation;
        }

        // Check if new password is same as current
        if (password_verify($newPassword, $credential['password_hash'])) {
            return ['success' => false, 'message' => 'New password must be different from current password'];
        }

        // Check password history
        if ($this->isPasswordInHistory($credentialId, $newPassword)) {
            return ['success' => false, 'message' => 'Password has been used recently. Please choose a different password'];
        }

        // Hash new password
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password
        $sql = "UPDATE {$this->table}
                SET password_hash = ?, last_password_change_at = datetime('now'), force_password_change = 0, updated_at = datetime('now')
                WHERE id = ?";
        $this->db->update($sql, [$passwordHash, $credentialId]);

        // Add to password history
        $this->addToPasswordHistory($credentialId, $passwordHash);

        return ['success' => true, 'message' => 'Password changed successfully'];
    }

    /**
     * Get person associated with this credential
     */
    public function getPerson($credentialId) {
        $credential = $this->find($credentialId);
        if (!$credential || !$credential['person_id']) {
            return null;
        }

        $sql = "SELECT * FROM persons WHERE id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$credential['person_id']]);
    }

    /**
     * Activate account
     */
    public function activateAccount($token) {
        $sql = "SELECT * FROM {$this->table}
                WHERE activation_token = ? AND activation_expires_at > datetime('now') AND deleted_at IS NULL";
        $credential = $this->queryOne($sql, [$token]);

        if (!$credential) {
            return ['success' => false, 'message' => 'Invalid or expired activation token'];
        }

        $sql = "UPDATE {$this->table}
                SET is_active = 1, account_status = 'active', activation_token = NULL, activation_expires_at = NULL,
                    email_verified = 1, email_verified_at = datetime('now'), updated_at = datetime('now')
                WHERE id = ?";
        $this->db->update($sql, [$credential['id']]);

        return ['success' => true, 'message' => 'Account activated successfully'];
    }

    /**
     * Lock account
     */
    public function lockAccount($credentialId, $reason = null, $duration = null) {
        $lockedUntil = $duration ? date('Y-m-d H:i:s', strtotime("+$duration minutes")) : null;

        $sql = "UPDATE {$this->table}
                SET is_locked = 1, locked_until = ?, notes = ?, updated_at = datetime('now')
                WHERE id = ?";
        return $this->db->update($sql, [$lockedUntil, $reason, $credentialId]);
    }

    /**
     * Unlock account
     */
    public function unlockAccount($credentialId) {
        $sql = "UPDATE {$this->table}
                SET is_locked = 0, locked_until = NULL, failed_login_attempts = 0, updated_at = datetime('now')
                WHERE id = ?";
        return $this->db->update($sql, [$credentialId]);
    }

    /**
     * Enable two-factor authentication
     */
    public function enableTwoFactor($credentialId, $secret, $backupCodes) {
        $backupCodesJson = json_encode($backupCodes);

        $sql = "UPDATE {$this->table}
                SET two_factor_enabled = 1, two_factor_secret = ?, backup_codes = ?, updated_at = datetime('now')
                WHERE id = ?";
        return $this->db->update($sql, [$secret, $backupCodesJson, $credentialId]);
    }

    /**
     * Disable two-factor authentication
     */
    public function disableTwoFactor($credentialId) {
        $sql = "UPDATE {$this->table}
                SET two_factor_enabled = 0, two_factor_secret = NULL, backup_codes = NULL, updated_at = datetime('now')
                WHERE id = ?";
        return $this->db->update($sql, [$credentialId]);
    }

    /**
     * Record failed login attempt
     */
    private function recordFailedLogin($credentialId) {
        $sql = "UPDATE {$this->table}
                SET failed_login_attempts = failed_login_attempts + 1,
                    last_failed_login_at = datetime('now'),
                    updated_at = datetime('now')
                WHERE id = ?";
        $this->db->update($sql, [$credentialId]);

        // Check if account should be locked
        $credential = $this->find($credentialId);
        if ($credential && $credential['failed_login_attempts'] >= 5) {
            $this->lockAccount($credentialId, 'Too many failed login attempts', 30);
        }
    }

    /**
     * Reset failed login attempts
     */
    private function resetFailedLoginAttempts($credentialId) {
        $sql = "UPDATE {$this->table}
                SET failed_login_attempts = 0, updated_at = datetime('now')
                WHERE id = ?";
        return $this->db->update($sql, [$credentialId]);
    }

    /**
     * Update last login timestamp
     */
    private function updateLastLogin($credentialId) {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        $sql = "UPDATE {$this->table}
                SET last_login_at = datetime('now'), last_login_ip = ?, updated_at = datetime('now')
                WHERE id = ?";
        return $this->db->update($sql, [$ipAddress, $credentialId]);
    }

    /**
     * Check if username exists
     */
    public function usernameExists($username, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE username = ? AND id != ? AND deleted_at IS NULL";
            $result = $this->queryOne($sql, [$username, $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE username = ? AND deleted_at IS NULL";
            $result = $this->queryOne($sql, [$username]);
        }

        return $result['count'] > 0;
    }

    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = ? AND id != ? AND deleted_at IS NULL";
            $result = $this->queryOne($sql, [$email, $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = ? AND deleted_at IS NULL";
            $result = $this->queryOne($sql, [$email]);
        }

        return $result['count'] > 0;
    }

    /**
     * Validate signup data
     */
    private function validateSignUp($data) {
        $errors = [];

        if (empty($data['username']) || strlen($data['username']) < 3) {
            $errors[] = 'Username must be at least 3 characters';
        }

        if (empty($data['person_id'])) {
            $errors[] = 'Person ID is required';
        }

        $passwordValidation = $this->validatePassword($data['password'] ?? '');
        if (!$passwordValidation['success']) {
            $errors[] = $passwordValidation['message'];
        }

        if (!empty($errors)) {
            return ['success' => false, 'message' => implode(', ', $errors)];
        }

        return ['success' => true];
    }

    /**
     * Validate password strength
     */
    private function validatePassword($password) {
        if (strlen($password) < 8) {
            return ['success' => false, 'message' => 'Password must be at least 8 characters'];
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return ['success' => false, 'message' => 'Password must contain at least one uppercase letter'];
        }

        if (!preg_match('/[a-z]/', $password)) {
            return ['success' => false, 'message' => 'Password must contain at least one lowercase letter'];
        }

        if (!preg_match('/[0-9]/', $password)) {
            return ['success' => false, 'message' => 'Password must contain at least one number'];
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            return ['success' => false, 'message' => 'Password must contain at least one special character'];
        }

        return ['success' => true];
    }

    /**
     * Add password to history
     */
    private function addToPasswordHistory($credentialId, $passwordHash) {
        $credential = $this->find($credentialId);
        $history = $credential['password_history'] ? json_decode($credential['password_history'], true) : [];

        array_unshift($history, [
            'hash' => $passwordHash,
            'changed_at' => date('Y-m-d H:i:s')
        ]);

        // Keep only last 5 passwords
        $history = array_slice($history, 0, 5);

        $sql = "UPDATE {$this->table} SET password_history = ?, updated_at = datetime('now') WHERE id = ?";
        return $this->db->update($sql, [json_encode($history), $credentialId]);
    }

    /**
     * Check if password is in history
     */
    private function isPasswordInHistory($credentialId, $password) {
        $credential = $this->find($credentialId);
        if (!$credential || !$credential['password_history']) {
            return false;
        }

        $history = json_decode($credential['password_history'], true);
        foreach ($history as $entry) {
            if (password_verify($password, $entry['hash'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Update password (internal method)
     */
    private function updatePassword($credentialId, $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE {$this->table} SET password_hash = ?, updated_at = datetime('now') WHERE id = ?";
        return $this->db->update($sql, [$passwordHash, $credentialId]);
    }

    /**
     * Get active credentials
     */
    public function getActiveCredentials() {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 AND account_status = 'active' AND deleted_at IS NULL ORDER BY username";
        return $this->query($sql);
    }

    /**
     * Get locked credentials
     */
    public function getLockedCredentials() {
        $sql = "SELECT * FROM {$this->table} WHERE is_locked = 1 AND deleted_at IS NULL ORDER BY locked_until DESC";
        return $this->query($sql);
    }

    /**
     * Validate credential data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'username' => 'required|min:3|max:50' . ($id ? "|unique:credentials,username,$id" : '|unique:credentials,username'),
            'person_id' => 'required|exists:persons,id',
            'email' => 'email' . ($id ? "|unique:credentials,email,$id" : '|unique:credentials,email'),
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $credential = $this->find($id);
        if (!$credential) {
            return 'N/A';
        }

        return $credential['username'];
    }
}
