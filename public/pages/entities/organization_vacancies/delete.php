<?php
/**
 * Delete Organization Vacancy Action
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
    redirect('/organization_vacancies');
    exit;
}

$vacancy = OrganizationVacancy::find($id);

if (!$vacancy) {
    $_SESSION['error'] = 'Vacancy not found';
    redirect('/organization_vacancies');
    exit;
}

// Soft delete
if ($vacancy->delete()) {
    $_SESSION['success'] = 'Vacancy deleted successfully!';
} else {
    $_SESSION['error'] = 'Failed to delete vacancy';
}

redirect('/organization_vacancies');
