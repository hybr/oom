<?php
/**
 * Delete Popular Organization Position Action
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
    redirect('/popular_organization_positions');
    exit;
}

$position = PopularOrganizationPosition::find($id);

if (!$position) {
    $_SESSION['error'] = 'Position not found';
    redirect('/popular_organization_positions');
    exit;
}

// Soft delete
if ($position->delete()) {
    $_SESSION['success'] = 'Position deleted successfully!';
} else {
    $_SESSION['error'] = 'Failed to delete position';
}

redirect('/popular_organization_positions');
