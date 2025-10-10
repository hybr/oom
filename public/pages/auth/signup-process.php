<?php
require_once __DIR__ . '/../../../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Router::redirect('/auth/signup');
}

// Validate CSRF token
if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Invalid security token. Please try again.';
    Router::redirect('/auth/signup');
}

$username = Validator::sanitize($_POST['username'] ?? '');
$email = Validator::sanitize($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirmation = $_POST['password_confirmation'] ?? '';

// Validate inputs
$validator = Validator::make([
    'username' => $username,
    'email' => $email,
    'password' => $password,
    'password_confirmation' => $passwordConfirmation,
], [
    'username' => 'required|min:3|max:20|alphanumeric',
    'email' => 'required|email',
    'password' => 'required|min:8',
    'password_confirmation' => 'required',
]);

if ($validator->fails()) {
    $errors = $validator->errors();
    $firstError = reset($errors);
    $_SESSION['error'] = is_array($firstError) ? $firstError[0] : $firstError;
    Router::redirect('/auth/signup');
}

// Check password confirmation
if ($password !== $passwordConfirmation) {
    $_SESSION['error'] = 'Passwords do not match.';
    Router::redirect('/auth/signup');
}

// Register user
$result = Auth::register($username, $email, $password);

if ($result['success']) {
    $_SESSION['success'] = 'Account created successfully! Please login.';
    Router::redirect('/auth/login');
} else {
    $_SESSION['error'] = $result['error'];
    Router::redirect('/auth/signup');
}
