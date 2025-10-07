<?php
/**
 * Store Organization Vacancy Action
 */

use Entities\OrganizationVacancy;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/organization_vacancies');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/organization_vacancies/create');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Create vacancy
$vacancy = new OrganizationVacancy();
$vacancy->fill($_POST);

if ($vacancy->save()) {
    $_SESSION['success'] = 'Vacancy created successfully!';
    redirect('/organization_vacancies/' . $vacancy->id);
} else {
    $_SESSION['_errors'] = $vacancy->getErrors();
    redirect('/organization_vacancies/create');
}
