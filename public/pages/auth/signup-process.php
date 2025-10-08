<?php
require_once __DIR__ . '/../../../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signup.php');
    exit;
}

// Validate input
$validator = Validator::make($_POST);
$valid = $validator->validate([
    'first_name' => 'required|min:2|max:100',
    'last_name' => 'required|min:2|max:100',
    'username' => 'required|min:3|max:50|unique:credentials,username',
    'password' => 'required|min:8|confirmed',
]);

if (!$valid) {
    $_SESSION['errors'] = $validator->errors();
    $_SESSION['old'] = $_POST;
    header('Location: signup.php');
    exit;
}

try {
    $db = db();
    $db->beginTransaction();

    // Create person record
    $personId = $db->insert(
        "INSERT INTO persons (first_name, middle_name, last_name, date_of_birth, created_at, updated_at)
         VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))",
        [
            $_POST['first_name'],
            $_POST['middle_name'] ?? null,
            $_POST['last_name'],
            $_POST['date_of_birth'] ?? null,
        ]
    );

    // Create credential record
    if (!auth()->register($_POST['username'], $_POST['password'], $personId)) {
        throw new Exception('Failed to create credentials');
    }

    $db->commit();

    // Auto-login after signup
    auth()->login($_POST['username'], $_POST['password']);

    success('Account created successfully! Welcome to V4L.');
    redirect('/pages/dashboard.php');

} catch (Exception $e) {
    $db->rollBack();
    error_log('Signup error: ' . $e->getMessage());
    $_SESSION['errors'] = ['general' => 'An error occurred during signup. Please try again.'];
    $_SESSION['old'] = $_POST;
    header('Location: signup.php');
    exit;
}
