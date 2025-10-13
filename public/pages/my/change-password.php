<?php
require_once __DIR__ . '/../../../bootstrap.php';

Auth::requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Router::redirect('/my/profile');
}

// Validate CSRF token
if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Invalid security token. Please try again.';
    Router::redirect('/my/profile');
}

// Get form data
$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Validate inputs
if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    $_SESSION['error'] = 'All fields are required.';
    Router::redirect('/my/profile');
}

if (strlen($newPassword) < 8) {
    $_SESSION['error'] = 'New password must be at least 8 characters long.';
    Router::redirect('/my/profile');
}

if ($newPassword !== $confirmPassword) {
    $_SESSION['error'] = 'New passwords do not match.';
    Router::redirect('/my/profile');
}

try {
    $userId = Auth::id();

    // Get current user credentials
    $sql = "SELECT * FROM person_credentials WHERE id = ? AND deleted_at IS NULL";
    $user = Database::fetchOne($sql, [$userId]);

    if (!$user) {
        throw new Exception('User not found.');
    }

    // Verify current password
    if (!password_verify($currentPassword, $user['hashed_password'])) {
        throw new Exception('Current password is incorrect.');
    }

    // Hash new password
    $newPasswordHash = password_hash($newPassword, PASSWORD_ARGON2ID);

    // Update password
    $sql = "UPDATE person_credentials
            SET hashed_password = ?, updated_at = datetime('now')
            WHERE id = ?";

    Database::execute($sql, [$newPasswordHash, $userId]);

    $_SESSION['success'] = 'Password changed successfully!';
    Router::redirect('/my/profile');

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    Router::redirect('/my/profile');
}
