<?php
require_once __DIR__ . '/../../../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
$validator = Validator::make($_POST);
$valid = $validator->validate([
    'username' => 'required',
    'password' => 'required',
]);

if (!$valid) {
    $_SESSION['errors'] = $validator->errors();
    $_SESSION['old'] = $_POST;
    header('Location: login.php');
    exit;
}

// Attempt login
if (auth()->login($username, $password)) {
    // Check for intended URL
    $intendedUrl = $_SESSION['intended_url'] ?? '/pages/dashboard.php';
    unset($_SESSION['intended_url']);

    success('Welcome back! You have successfully logged in.');
    redirect($intendedUrl);
} else {
    $_SESSION['errors'] = ['username' => 'Invalid username or password.'];
    $_SESSION['old'] = $_POST;
    header('Location: login.php');
    exit;
}
