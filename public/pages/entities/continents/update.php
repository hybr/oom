<?php
/**
 * Update Continent Action
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
    redirect('/continents/' . $id . '/edit');
    exit;
}

$continent = Continent::find($id);

if (!$continent) {
    $_SESSION['error'] = 'Continent not found';
    redirect('/continents');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Update continent
$continent->fill($_POST);

if ($continent->save()) {
    $_SESSION['success'] = 'Continent updated successfully!';
    redirect('/continents/' . $continent->id);
} else {
    $_SESSION['_errors'] = $continent->getErrors();
    redirect('/continents/' . $id . '/edit');
}
