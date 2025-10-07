<?php
/**
 * Delete Entity Definition
 */

use Entities\EntityDefinition;

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id || !verify_csrf()) {
    redirect('/');
    exit;
}

$entity = Entities\EntityDefinition::find($id);

if ($entity && $entity->delete()) {
    $_SESSION['success'] = 'Entity Definition deleted successfully!';
} else {
    $_SESSION['error'] = 'Failed to delete Entity Definition';
}

redirect('/' . basename(dirname(__FILE__)));
