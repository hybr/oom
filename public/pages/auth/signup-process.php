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

// Sanitize person data
$personData = [
    'name_prefix' => Validator::sanitize($_POST['name_prefix'] ?? ''),
    'first_name' => Validator::sanitize($_POST['first_name'] ?? ''),
    'middle_name' => Validator::sanitize($_POST['middle_name'] ?? ''),
    'last_name' => Validator::sanitize($_POST['last_name'] ?? ''),
    'gender' => Validator::sanitize($_POST['gender'] ?? ''),
    'date_of_birth' => Validator::sanitize($_POST['date_of_birth'] ?? ''),
    'primary_email' => Validator::sanitize($_POST['primary_email'] ?? ''),
    'primary_phone' => Validator::sanitize($_POST['primary_phone'] ?? ''),
];

// Sanitize credential data
$username = Validator::sanitize($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirmation = $_POST['password_confirmation'] ?? '';

// Sanitize security questions data
$securityQuestion1 = Validator::sanitize($_POST['security_question_1'] ?? '');
$securityAnswer1 = $_POST['security_answer_1'] ?? '';
$securityQuestion2 = Validator::sanitize($_POST['security_question_2'] ?? '');
$securityAnswer2 = $_POST['security_answer_2'] ?? '';

// Validate person data
$validator = Validator::make($personData, [
    'first_name' => 'required|min:2|max:50',
    'last_name' => 'required|min:2|max:50',
    'primary_email' => 'required|email',
]);

if ($validator->fails()) {
    $errors = $validator->errors();
    $firstError = reset($errors);
    $_SESSION['error'] = is_array($firstError) ? $firstError[0] : $firstError;
    Router::redirect('/auth/signup');
}

// Validate credentials and security questions
$credValidator = Validator::make([
    'username' => $username,
    'password' => $password,
    'password_confirmation' => $passwordConfirmation,
    'security_question_1' => $securityQuestion1,
    'security_answer_1' => $securityAnswer1,
    'security_question_2' => $securityQuestion2,
    'security_answer_2' => $securityAnswer2,
], [
    'username' => 'required|min:3|max:20|alphanumeric',
    'password' => 'required|min:8',
    'password_confirmation' => 'required',
    'security_question_1' => 'required',
    'security_answer_1' => 'required|min:2',
    'security_question_2' => 'required',
    'security_answer_2' => 'required|min:2',
]);

if ($credValidator->fails()) {
    $errors = $credValidator->errors();
    $firstError = reset($errors);
    $_SESSION['error'] = is_array($firstError) ? $firstError[0] : $firstError;
    Router::redirect('/auth/signup');
}

// Check password confirmation
if ($password !== $passwordConfirmation) {
    $_SESSION['error'] = 'Passwords do not match.';
    Router::redirect('/auth/signup');
}

try {
    Database::beginTransaction();

    // Check if username already exists
    $sql = "SELECT COUNT(*) as cnt FROM person_credential WHERE username = ?";
    $result = Database::fetchOne($sql, [$username]);
    if ($result['cnt'] > 0) {
        throw new Exception('Username already exists');
    }

    // Check if email already exists
    $sql = "SELECT COUNT(*) as cnt FROM person_credential WHERE email = ?";
    $result = Database::fetchOne($sql, [$personData['primary_email']]);
    if ($result['cnt'] > 0) {
        throw new Exception('Email already exists');
    }

    // Generate person ID
    $personId = Auth::generateUuid();

    // Insert person record
    $sql = "INSERT INTO person (
        id, name_prefix, first_name, middle_name, last_name, name_suffix,
        gender, date_of_birth, primary_email, primary_phone,
        created_at, updated_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'))";

    Database::execute($sql, [
        $personId,
        $personData['name_prefix'] ?: null,
        $personData['first_name'],
        $personData['middle_name'] ?: null,
        $personData['last_name'],
        null, // name_suffix
        $personData['gender'] ?: null,
        $personData['date_of_birth'] ?: null,
        $personData['primary_email'],
        $personData['primary_phone'] ?: null,
    ]);

    // Generate credential ID
    $credentialId = Auth::generateUuid();

    // Hash password and security answers
    $passwordHash = password_hash($password, PASSWORD_ARGON2ID);
    $securityAnswer1Hash = password_hash(strtolower(trim($securityAnswer1)), PASSWORD_ARGON2ID);
    $securityAnswer2Hash = password_hash(strtolower(trim($securityAnswer2)), PASSWORD_ARGON2ID);

    // Insert credential record
    $sql = "INSERT INTO person_credential (
        id, person_id, username, email, hashed_password,
        security_question_1, security_answer_1_hash,
        security_question_2, security_answer_2_hash,
        created_at, updated_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'))";

    Database::execute($sql, [
        $credentialId,
        $personId,
        $username,
        $personData['primary_email'],
        $passwordHash,
        $securityQuestion1,
        $securityAnswer1Hash,
        $securityQuestion2,
        $securityAnswer2Hash,
    ]);

    Database::commit();

    $_SESSION['success'] = 'Account created successfully! Please login.';
    Router::redirect('/auth/login');

} catch (Exception $e) {
    Database::rollback();
    $_SESSION['error'] = $e->getMessage();
    Router::redirect('/auth/signup');
}
