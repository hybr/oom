<?php
/**
 * Store Vacancy Application Action
 */

use Entities\VacancyApplication;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/vacancy_applications');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/vacancy_applications/create');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Create application
$app = new VacancyApplication();
$app->fill($_POST);

if ($app->save()) {
    $_SESSION['success'] = 'Application created successfully!';
    redirect('/vacancy_applications/' . $app->id);
} else {
    $_SESSION['_errors'] = $app->getErrors();
    redirect('/vacancy_applications/create');
}
