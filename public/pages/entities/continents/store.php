<?php
require_once __DIR__ . '/../../../../bootstrap.php';

auth()->requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/pages/entities/continents/create.php');
}

require_once ENTITIES_PATH . '/Continent.php';

$continent = new Continent();

// Validate
if (!$continent->validateData($_POST)) {
    $_SESSION['errors'] = (new Validator($_POST))->errors();
    $_SESSION['old'] = $_POST;
    redirect('/pages/entities/continents/create.php');
}

try {
    $id = $continent->create([
        'name' => $_POST['name'],
    ]);

    success('Continent created successfully!');
    redirect('/pages/entities/continents/detail.php?id=' . $id);

} catch (Exception $e) {
    error_log('Error creating continent: ' . $e->getMessage());
    $_SESSION['errors'] = ['general' => 'An error occurred while creating the continent.'];
    $_SESSION['old'] = $_POST;
    redirect('/pages/entities/continents/create.php');
}
