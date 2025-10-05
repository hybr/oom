<?php
/**
 * Login Process Handler
 */

use Entities\Credential;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/login');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/login');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// Validate
if (empty($username) || empty($password)) {
    $_SESSION['_errors'] = [
        'username' => empty($username) ? ['Username is required'] : [],
        'password' => empty($password) ? ['Password is required'] : [],
    ];
    redirect('/login');
    exit;
}

// Attempt login
$credential = Credential::login($username, $password);

if (!$credential) {
    $_SESSION['_errors'] = ['login' => ['Invalid username or password']];
    redirect('/login');
    exit;
}

// Login successful
$_SESSION['user_id'] = $credential->person_id;
$_SESSION['credential_id'] = $credential->id;

// Remember me
if ($remember) {
    $token = $credential->generateRememberToken();
    setcookie('remember_token', $token, time() + (86400 * 30), '/'); // 30 days
    setcookie('user_id', $credential->person_id, time() + (86400 * 30), '/');
}

$_SESSION['success'] = 'Welcome back!';
redirect('/');
