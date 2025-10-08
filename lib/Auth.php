<?php

namespace App;

/**
 * Authentication Middleware
 * Handles route protection and authentication checks
 */
class Auth
{
    /**
     * Require authentication - redirect to login if not authenticated
     */
    public static function require(): void
    {
        if (!auth()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $_SESSION['error'] = 'Please login to access this page.';
            redirect('/login');
            exit;
        }
    }

    /**
     * Require guest - redirect to home if authenticated
     */
    public static function guest(): void
    {
        if (auth()) {
            redirect('/');
            exit;
        }
    }

    /**
     * Check if user is authenticated
     */
    public static function check(): bool
    {
        return auth() !== null;
    }

    /**
     * Get authenticated user ID
     */
    public static function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get authenticated user
     */
    public static function user(): ?object
    {
        return auth();
    }
}
