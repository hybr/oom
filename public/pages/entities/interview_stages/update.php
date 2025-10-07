<?php
/**
 * Update Interview Stage Action
 */

use Entities\InterviewStage;

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
    redirect('/interview_stages');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/interview_stages/' . $id . '/edit');
    exit;
}

$stage = InterviewStage::find($id);

if (!$stage) {
    $_SESSION['error'] = 'Interview stage not found';
    redirect('/interview_stages');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Update stage
$stage->fill($_POST);

if ($stage->save()) {
    $_SESSION['success'] = 'Interview stage updated successfully!';
    redirect('/interview_stages/' . $stage->id);
} else {
    $_SESSION['_errors'] = $stage->getErrors();
    redirect('/interview_stages/' . $id . '/edit');
}
