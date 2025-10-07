<?php
/**
 * Update Organization Vacancy Workstation Action
 */

use Entities\OrganizationVacancyWorkstation;

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
    redirect('/organization_vacancy_workstations');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/organization_vacancy_workstations/' . $id . '/edit');
    exit;
}

$ws = OrganizationVacancyWorkstation::find($id);

if (!$ws) {
    $_SESSION['error'] = 'Vacancy workstation not found';
    redirect('/organization_vacancy_workstations');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Update vacancy workstation
$ws->fill($_POST);

if ($ws->save()) {
    $_SESSION['success'] = 'Vacancy workstation updated successfully!';
    redirect('/organization_vacancy_workstations/' . $ws->id);
} else {
    $_SESSION['_errors'] = $ws->getErrors();
    redirect('/organization_vacancy_workstations/' . $id . '/edit');
}
