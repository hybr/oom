<?php
/**
 * Logout Handler
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Clear session
session_destroy();

// Clear remember me cookies
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
    setcookie('user_id', '', time() - 3600, '/');
}

// Start new session for flash message
session_start();
$_SESSION['success'] = 'You have been logged out successfully.';

redirect('/login');
