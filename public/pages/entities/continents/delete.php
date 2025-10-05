<?php
/**
 * Delete Continent Action
 */

use Entities\Continent;

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
    redirect('/continents');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/continents');
    exit;
}

$continent = Continent::find($id);

if (!$continent) {
    $_SESSION['error'] = 'Continent not found';
    redirect('/continents');
    exit;
}

// Soft delete
if ($continent->delete()) {
    $_SESSION['success'] = 'Continent deleted successfully!';
} else {
    $_SESSION['error'] = 'Failed to delete continent';
}

redirect('/continents');
