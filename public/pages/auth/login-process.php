<?php
require_once __DIR__ . '/../../../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Router::redirect('/auth/login');
}

// Validate CSRF token
if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Invalid security token. Please try again.';
    Router::redirect('/auth/login');
}

$username = Validator::sanitize($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validate inputs
$validator = Validator::make([
    'username' => $username,
    'password' => $password,
], [
    'username' => 'required',
    'password' => 'required',
]);

if ($validator->fails()) {
    $_SESSION['error'] = 'Please provide both username and password.';
    Router::redirect('/auth/login');
}

// Attempt login
if (Auth::attempt($username, $password)) {
    Router::redirect('/dashboard');
} else {
    $_SESSION['error'] = 'Invalid credentials or account locked. Please try again.';
    Router::redirect('/auth/login');
}
