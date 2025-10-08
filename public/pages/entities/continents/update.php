<?php
require_once __DIR__ . '/../../../../bootstrap.php';

auth()->requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/pages/entities/continents/list.php');
}

require_once ENTITIES_PATH . '/Continent.php';

$id = $_POST['id'] ?? null;

if (!$id) {
    redirect('/pages/entities/continents/list.php');
}

$continent = new Continent();

// Validate
if (!$continent->validateData($_POST, $id)) {
    $_SESSION['errors'] = (new Validator($_POST))->errors();
    $_SESSION['old'] = $_POST;
    redirect('/pages/entities/continents/edit.php?id=' . $id);
}

try {
    $continent->update($id, [
        'name' => $_POST['name'],
    ]);

    success('Continent updated successfully!');
    redirect('/pages/entities/continents/detail.php?id=' . $id);

} catch (Exception $e) {
    error_log('Error updating continent: ' . $e->getMessage());
    $_SESSION['errors'] = ['general' => 'An error occurred while updating the continent.'];
    $_SESSION['old'] = $_POST;
    redirect('/pages/entities/continents/edit.php?id=' . $id);
}
