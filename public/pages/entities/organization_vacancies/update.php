<?php
/**
 * Update Organization Vacancy Action
 */

use Entities\OrganizationVacancy;

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
    redirect('/organization_vacancies');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/organization_vacancies/' . $id . '/edit');
    exit;
}

$vacancy = OrganizationVacancy::find($id);

if (!$vacancy) {
    $_SESSION['error'] = 'Vacancy not found';
    redirect('/organization_vacancies');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Update vacancy
$vacancy->fill($_POST);

if ($vacancy->save()) {
    $_SESSION['success'] = 'Vacancy updated successfully!';
    redirect('/organization_vacancies/' . $vacancy->id);
} else {
    $_SESSION['_errors'] = $vacancy->getErrors();
    redirect('/organization_vacancies/' . $id . '/edit');
}
