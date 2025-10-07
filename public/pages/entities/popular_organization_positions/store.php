<?php
/**
 * Store Popular Organization Position Action
 */

use Entities\PopularOrganizationPosition;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/popular_organization_positions');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/popular_organization_positions/create');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Create position
$position = new PopularOrganizationPosition();
$position->fill($_POST);

if ($position->save()) {
    $_SESSION['success'] = 'Position created successfully!';
    redirect('/popular_organization_positions/' . $position->id);
} else {
    $_SESSION['_errors'] = $position->getErrors();
    redirect('/popular_organization_positions/create');
}
