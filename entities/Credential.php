<?php

namespace Entities;

/**
 * Credential Entity
 * Manages user authentication credentials
 */
class Credential extends BaseEntity
{
    protected ?string $username = null;
    protected ?string $password_hash = null;
    protected ?int $person_id = null;
    protected ?string $reset_token = null;
    protected ?string $reset_token_expires = null;
    protected ?string $remember_token = null;
    protected ?int $failed_login_attempts = 0;
    protected ?string $locked_until = null;

    public static function getTableName(): string
    {
        return 'credential';
    }

    protected function getFillableAttributes(): array
    {
        return ['username', 'password_hash', 'person_id'];
    }

    protected function getValidationRules(): array
    {
        return [
            'username' => ['required', 'min:3', 'max:100'],
            'person_id' => ['required', 'numeric'],
        ];
    }

    /**
     * Get the person associated with this credential
     */
    public function getPerson(): ?Person
    {
        return Person::find($this->person_id);
    }

    /**
     * Hash and set password
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = password_hash($password, PASSWORD_ARGON2ID);
    }

    /**
     * Verify password
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password_hash);
    }

    /**
     * Check if password needs rehashing
     */
    public function needsRehash(): bool
    {
        return password_needs_rehash($this->password_hash, PASSWORD_ARGON2ID);
    }

    /**
     * Login method
     */
    public static function login(string $username, string $password): ?self
    {
        $credentials = static::where('username = :username', ['username' => $username], 1);
        $credential = $credentials[0] ?? null;

        if (!$credential) {
            return null;
        }

        // Check if account is locked
        if ($credential->isLocked()) {
            return null;
        }

        // Verify password
        if (!$credential->verifyPassword($password)) {
            $credential->incrementFailedAttempts();
            return null;
        }

        // Reset failed attempts on successful login
        $credential->resetFailedAttempts();

        // Rehash password if needed
        if ($credential->needsRehash()) {
            $credential->setPassword($password);
            $credential->save();
        }

        return $credential;
    }

    /**
     * Sign up (create new credential)
     */
    public static function signUp(int $personId, string $username, string $password): ?self
    {
        // Check if username already exists
        if (static::usernameExists($username)) {
            return null;
        }

        $credential = new static();
        $credential->person_id = $personId;
        $credential->username = $username;
        $credential->setPassword($password);

        if ($credential->save()) {
            return $credential;
        }

        return null;
    }

    /**
     * Check if username exists
     */
    public static function usernameExists(string $username): bool
    {
        return static::count('username = :username', ['username' => $username]) > 0;
    }

    /**
     * Generate password reset token
     */
    public function forgotPassword(): string
    {
        $this->reset_token = bin2hex(random_bytes(32));
        $this->reset_token_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $this->save();

        return $this->reset_token;
    }

    /**
     * Reset password with token
     */
    public function resetPassword(string $token, string $newPassword): bool
    {
        if ($this->reset_token !== $token) {
            return false;
        }

        if (strtotime($this->reset_token_expires) < time()) {
            return false; // Token expired
        }

        $this->setPassword($newPassword);
        $this->reset_token = null;
        $this->reset_token_expires = null;
        $this->resetFailedAttempts();

        return $this->save();
    }

    /**
     * Change password (requires current password)
     */
    public function changePassword(string $currentPassword, string $newPassword): bool
    {
        if (!$this->verifyPassword($currentPassword)) {
            return false;
        }

        $this->setPassword($newPassword);
        return $this->save();
    }

    /**
     * Generate remember token
     */
    public function generateRememberToken(): string
    {
        $this->remember_token = bin2hex(random_bytes(32));
        $this->save();

        return $this->remember_token;
    }

    /**
     * Verify remember token
     */
    public function verifyRememberToken(string $token): bool
    {
        return $this->remember_token === $token;
    }

    /**
     * Increment failed login attempts
     */
    public function incrementFailedAttempts(): void
    {
        $this->failed_login_attempts++;

        // Lock account after 5 failed attempts
        if ($this->failed_login_attempts >= 5) {
            $this->locked_until = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        }

        $this->save();
    }

    /**
     * Reset failed login attempts
     */
    public function resetFailedAttempts(): void
    {
        $this->failed_login_attempts = 0;
        $this->locked_until = null;
        $this->save();
    }

    /**
     * Check if account is locked
     */
    public function isLocked(): bool
    {
        if (!$this->locked_until) {
            return false;
        }

        if (strtotime($this->locked_until) < time()) {
            // Lock period expired, unlock account
            $this->resetFailedAttempts();
            return false;
        }

        return true;
    }

    /**
     * Find credential by username
     */
    public static function findByUsername(string $username): ?self
    {
        $credentials = static::where('username = :username', ['username' => $username], 1);
        return $credentials[0] ?? null;
    }

    /**
     * Override toArray to exclude sensitive data
     */
    public function toArray(bool $excludeNull = false): array
    {
        $data = parent::toArray($excludeNull);

        // Remove sensitive fields
        unset($data['password_hash'], $data['reset_token'], $data['remember_token']);

        return $data;
    }
}
