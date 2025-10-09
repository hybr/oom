<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * Credential Entity
 * Handles user authentication and password management
 */
class Credential extends BaseEntity {
    protected $table = 'credentials';
    protected $fillable = ['username', 'password_hash', 'person_id'];
    protected $hidden = ['password_hash']; // Don't expose password hash

    /**
     * Get person associated with this credential
     */
    public function getPerson($credentialId) {
        $sql = "SELECT p.* FROM persons p
                JOIN credentials c ON c.person_id = p.id
                WHERE c.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$credentialId]);
    }

    /**
     * Get credential by username
     */
    public function getByUsername($username) {
        $sql = "SELECT * FROM credentials WHERE username = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$username]);
    }

    /**
     * Get credential by person ID
     */
    public function getByPersonId($personId) {
        $sql = "SELECT * FROM credentials WHERE person_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$personId]);
    }

    /**
     * Check if username exists
     */
    public function usernameExists($username, $exceptId = null) {
        $sql = "SELECT id FROM credentials WHERE username = ? AND deleted_at IS NULL";
        $params = [$username];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return !empty($result);
    }

    /**
     * Login - verify credentials
     */
    public function login($username, $password) {
        $credential = $this->getByUsername($username);

        if (!$credential) {
            return [
                'success' => false,
                'message' => 'Invalid username or password'
            ];
        }

        // Verify password
        if (!password_verify($password, $credential['password_hash'])) {
            return [
                'success' => false,
                'message' => 'Invalid username or password'
            ];
        }

        // Get person details
        $person = $this->getPerson($credential['id']);

        if (!$person) {
            return [
                'success' => false,
                'message' => 'User account not found'
            ];
        }

        // Update last login time (if you have that column)
        // $this->updateLastLogin($credential['id']);

        return [
            'success' => true,
            'credential' => $credential,
            'person' => $person,
            'message' => 'Login successful'
        ];
    }

    /**
     * Sign up - create new credential
     */
    public function signUp($username, $password, $personId) {
        // Check if username already exists
        if ($this->usernameExists($username)) {
            return [
                'success' => false,
                'message' => 'Username already exists'
            ];
        }

        // Check if person already has credentials
        if ($this->getByPersonId($personId)) {
            return [
                'success' => false,
                'message' => 'Person already has credentials'
            ];
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Create credential
        $credentialId = $this->create([
            'username' => $username,
            'password_hash' => $passwordHash,
            'person_id' => $personId
        ]);

        if ($credentialId) {
            return [
                'success' => true,
                'credential_id' => $credentialId,
                'message' => 'Account created successfully'
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to create account'
        ];
    }

    /**
     * Change password
     */
    public function changePassword($credentialId, $oldPassword, $newPassword) {
        $credential = $this->find($credentialId);

        if (!$credential) {
            return [
                'success' => false,
                'message' => 'Credential not found'
            ];
        }

        // Verify old password
        if (!password_verify($oldPassword, $credential['password_hash'])) {
            return [
                'success' => false,
                'message' => 'Current password is incorrect'
            ];
        }

        // Hash new password
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password
        $success = $this->update($credentialId, ['password_hash' => $newPasswordHash]);

        if ($success) {
            return [
                'success' => true,
                'message' => 'Password changed successfully'
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to change password'
        ];
    }

    /**
     * Reset password (admin or forgot password)
     */
    public function resetPassword($credentialId, $newPassword) {
        $credential = $this->find($credentialId);

        if (!$credential) {
            return [
                'success' => false,
                'message' => 'Credential not found'
            ];
        }

        // Hash new password
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password
        $success = $this->update($credentialId, ['password_hash' => $newPasswordHash]);

        if ($success) {
            return [
                'success' => true,
                'message' => 'Password reset successfully'
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to reset password'
        ];
    }

    /**
     * Generate password reset token
     */
    public function generateResetToken($username) {
        $credential = $this->getByUsername($username);

        if (!$credential) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }

        // Generate random token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token (you'll need to add reset_token and reset_token_expiry columns)
        // For now, we'll just return the token
        // $this->update($credential['id'], [
        //     'reset_token' => $token,
        //     'reset_token_expiry' => $expiry
        // ]);

        return [
            'success' => true,
            'token' => $token,
            'credential_id' => $credential['id'],
            'message' => 'Reset token generated'
        ];
    }

    /**
     * Verify reset token
     */
    public function verifyResetToken($token) {
        // This would query the reset_token column
        // For now, return a placeholder
        return [
            'success' => false,
            'message' => 'Token verification not implemented'
        ];
    }

    /**
     * Get all credentials with person information
     */
    public function getAllWithPersonInfo($limit = null, $offset = null) {
        $sql = "SELECT c.*, p.first_name, p.middle_name, p.last_name
                FROM credentials c
                LEFT JOIN persons p ON c.person_id = p.id
                WHERE c.deleted_at IS NULL
                ORDER BY c.username ASC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->query($sql, [$limit, $offset ?? 0]);
        }

        return $this->query($sql);
    }

    /**
     * Search credentials by username or person name
     */
    public function searchCredentials($term, $limit = 50) {
        $sql = "SELECT c.*, p.first_name, p.middle_name, p.last_name
                FROM credentials c
                LEFT JOIN persons p ON c.person_id = p.id
                WHERE c.deleted_at IS NULL
                AND (c.username LIKE ? OR p.first_name LIKE ? OR p.last_name LIKE ?)
                ORDER BY c.username ASC
                LIMIT ?";

        return $this->query($sql, ["%$term%", "%$term%", "%$term%", $limit]);
    }

    /**
     * Get credentials created in date range
     */
    public function getCreatedInRange($startDate, $endDate) {
        $sql = "SELECT c.*, p.first_name, p.middle_name, p.last_name
                FROM credentials c
                LEFT JOIN persons p ON c.person_id = p.id
                WHERE c.created_at BETWEEN ? AND ?
                AND c.deleted_at IS NULL
                ORDER BY c.created_at DESC";

        return $this->query($sql, [$startDate, $endDate]);
    }

    /**
     * Get credential statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_credentials,
                    COUNT(CASE WHEN created_at >= date('now', '-7 days') THEN 1 END) as new_this_week,
                    COUNT(CASE WHEN created_at >= date('now', '-30 days') THEN 1 END) as new_this_month
                FROM credentials
                WHERE deleted_at IS NULL";

        return $this->queryOne($sql);
    }

    /**
     * Check password strength
     */
    public function checkPasswordStrength($password) {
        $strength = 0;
        $feedback = [];

        // Length check
        if (strlen($password) >= 8) {
            $strength += 1;
        } else {
            $feedback[] = 'Password should be at least 8 characters';
        }

        // Uppercase check
        if (preg_match('/[A-Z]/', $password)) {
            $strength += 1;
        } else {
            $feedback[] = 'Include at least one uppercase letter';
        }

        // Lowercase check
        if (preg_match('/[a-z]/', $password)) {
            $strength += 1;
        } else {
            $feedback[] = 'Include at least one lowercase letter';
        }

        // Number check
        if (preg_match('/[0-9]/', $password)) {
            $strength += 1;
        } else {
            $feedback[] = 'Include at least one number';
        }

        // Special character check
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            $strength += 1;
        } else {
            $feedback[] = 'Include at least one special character';
        }

        $labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];

        return [
            'strength' => $strength,
            'label' => $labels[$strength] ?? 'Unknown',
            'feedback' => $feedback
        ];
    }

    /**
     * Validate credential data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'username' => 'required|min:3|max:50|alphanumeric' . ($id ? "|unique:credentials,username,$id" : '|unique:credentials,username'),
            'person_id' => 'required|integer',
        ];

        // Password validation only for new records or if password is being changed
        if (!$id || isset($data['password'])) {
            $rules['password'] = 'required|min:8|max:100';
            $rules['password_confirmation'] = 'required|same:password';
        }

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel to return username
     */
    public function getLabel($id) {
        $credential = $this->find($id);
        return $credential ? $credential['username'] : 'N/A';
    }
}
