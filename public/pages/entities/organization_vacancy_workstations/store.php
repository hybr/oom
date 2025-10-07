<?php
/**
 * Store Organization Vacancy Workstation Action
 */

use Entities\OrganizationVacancyWorkstation;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/organization_vacancy_workstations');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/organization_vacancy_workstations/create');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Create vacancy workstation
$ws = new OrganizationVacancyWorkstation();
$ws->fill($_POST);

if ($ws->save()) {
    $_SESSION['success'] = 'Vacancy workstation created successfully!';
    redirect('/organization_vacancy_workstations/' . $ws->id);
} else {
    $_SESSION['_errors'] = $ws->getErrors();
    redirect('/organization_vacancy_workstations/create');
}
