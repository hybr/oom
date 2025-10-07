<?php
/**
 * Update Popular Organization Position Action
 */

use Entities\PopularOrganizationPosition;

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
    redirect('/popular_organization_positions');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/popular_organization_positions/' . $id . '/edit');
    exit;
}

$position = PopularOrganizationPosition::find($id);

if (!$position) {
    $_SESSION['error'] = 'Position not found';
    redirect('/popular_organization_positions');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Update position
$position->fill($_POST);

if ($position->save()) {
    $_SESSION['success'] = 'Position updated successfully!';
    redirect('/popular_organization_positions/' . $position->id);
} else {
    $_SESSION['_errors'] = $position->getErrors();
    redirect('/popular_organization_positions/' . $id . '/edit');
}
