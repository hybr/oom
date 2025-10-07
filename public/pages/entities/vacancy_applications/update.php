<?php
/**
 * Update Vacancy Application Action
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
    redirect('/vacancy_applications/' . $id . '/edit');
    exit;
}

$app = VacancyApplication::find($id);

if (!$app) {
    $_SESSION['error'] = 'Application not found';
    redirect('/vacancy_applications');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Update application
$app->fill($_POST);

if ($app->save()) {
    $_SESSION['success'] = 'Application updated successfully!';
    redirect('/vacancy_applications/' . $app->id);
} else {
    $_SESSION['_errors'] = $app->getErrors();
    redirect('/vacancy_applications/' . $id . '/edit');
}
