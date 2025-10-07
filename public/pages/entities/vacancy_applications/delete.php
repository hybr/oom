<?php
/**
 * Delete Vacancy Application Action
 */

use Entities\VacancyApplication;

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
    redirect('/vacancy_applications');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/vacancy_applications');
    exit;
}

$app = VacancyApplication::find($id);

if (!$app) {
    $_SESSION['error'] = 'Application not found';
    redirect('/vacancy_applications');
    exit;
}

// Soft delete
if ($app->delete()) {
    $_SESSION['success'] = 'Application deleted successfully!';
} else {
    $_SESSION['error'] = 'Failed to delete application';
}

redirect('/vacancy_applications');
