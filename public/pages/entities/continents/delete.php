<?php
require_once __DIR__ . '/../../../../bootstrap.php';

auth()->requireAuth();

require_once ENTITIES_PATH . '/Continent.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/pages/entities/continents/list.php');
}

$continent = new Continent();
$record = $continent->find($id);

if (!$record) {
    $_SESSION['error'] = 'Continent not found.';
    redirect('/pages/entities/continents/list.php');
}

try {
    // Soft delete
    $continent->delete($id);

    success('Continent "' . $record['name'] . '" deleted successfully!');
    redirect('/pages/entities/continents/list.php');

} catch (Exception $e) {
    error_log('Error deleting continent: ' . $e->getMessage());
    $_SESSION['error'] = 'An error occurred while deleting the continent. It may have related records.';
    redirect('/pages/entities/continents/detail.php?id=' . $id);
}
