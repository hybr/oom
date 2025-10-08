<?php
/**
 * Signup Process Handler
 */

require_once __DIR__ . '/../../../bootstrap.php';

use Entities\Person;
use Entities\Credential;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/signup');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/signup');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

$errors = [];

// Validate person data
if (empty($_POST['first_name'])) {
    $errors['first_name'] = ['First name is required'];
}
if (empty($_POST['last_name'])) {
    $errors['last_name'] = ['Last name is required'];
}

// Validate credentials
if (empty($_POST['username'])) {
    $errors['username'] = ['Username is required'];
} elseif (strlen($_POST['username']) < 3) {
    $errors['username'] = ['Username must be at least 3 characters'];
} elseif (Credential::usernameExists($_POST['username'])) {
    $errors['username'] = ['Username already exists'];
}

if (empty($_POST['password'])) {
    $errors['password'] = ['Password is required'];
} elseif (strlen($_POST['password']) < 6) {
    $errors['password'] = ['Password must be at least 6 characters'];
}

if (empty($_POST['password_confirmation'])) {
    $errors['password_confirmation'] = ['Please confirm your password'];
} elseif ($_POST['password'] !== $_POST['password_confirmation']) {
    $errors['password_confirmation'] = ['Passwords do not match'];
}

if (!isset($_POST['terms'])) {
    $errors['terms'] = ['You must accept the terms and conditions'];
}

// If errors, redirect back
if (!empty($errors)) {
    $_SESSION['_errors'] = $errors;
    redirect('/signup');
    exit;
}

// Create person
$person = new Person();
$person->fill($_POST);

if (!$person->save()) {
    $_SESSION['_errors'] = $person->getErrors();
    redirect('/signup');
    exit;
}

// Create credential
$credential = Credential::signUp($person->id, $_POST['username'], $_POST['password']);

if (!$credential) {
    // Rollback - delete person
    $person->forceDelete();
    $_SESSION['error'] = 'Failed to create account. Please try again.';
    redirect('/signup');
    exit;
}

// Auto-login
$_SESSION['user_id'] = $person->id;
$_SESSION['credential_id'] = $credential->id;

$_SESSION['success'] = 'Account created successfully! Welcome to V4L.';
redirect('/');
