<?php
/**
 * Store Interview Stage Action
 */

use Entities\InterviewStage;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/interview_stages');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/interview_stages/create');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Create stage
$stage = new InterviewStage();
$stage->fill($_POST);

if ($stage->save()) {
    $_SESSION['success'] = 'Interview stage created successfully!';
    redirect('/interview_stages/' . $stage->id);
} else {
    $_SESSION['_errors'] = $stage->getErrors();
    redirect('/interview_stages/create');
}
