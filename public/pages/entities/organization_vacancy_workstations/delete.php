<?php
/**
 * Delete Organization Vacancy Workstation Action
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
    redirect('/organization_vacancy_workstations');
    exit;
}

$ws = OrganizationVacancyWorkstation::find($id);

if (!$ws) {
    $_SESSION['error'] = 'Vacancy workstation not found';
    redirect('/organization_vacancy_workstations');
    exit;
}

// Soft delete
if ($ws->delete()) {
    $_SESSION['success'] = 'Vacancy workstation deleted successfully!';
} else {
    $_SESSION['error'] = 'Failed to delete vacancy workstation';
}

redirect('/organization_vacancy_workstations');
