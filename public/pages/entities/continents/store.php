<?php
/**
 * Store Continent Action
 */

use Entities\Continent;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/continents');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/continents/create');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Create continent
$continent = new Continent();
$continent->fill($_POST);

if ($continent->save()) {
    $_SESSION['success'] = 'Continent created successfully!';
    redirect('/continents/' . $continent->id);
} else {
    $_SESSION['_errors'] = $continent->getErrors();
    redirect('/continents/create');
}
