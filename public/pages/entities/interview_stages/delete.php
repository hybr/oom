<?php
/**
 * Delete Interview Stage Action
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
    redirect('/interview_stages');
    exit;
}

$stage = InterviewStage::find($id);

if (!$stage) {
    $_SESSION['error'] = 'Interview stage not found';
    redirect('/interview_stages');
    exit;
}

// Soft delete
if ($stage->delete()) {
    $_SESSION['success'] = 'Interview stage deleted successfully!';
} else {
    $_SESSION['error'] = 'Failed to delete interview stage';
}

redirect('/interview_stages');
