<?php
/**
 * Delete Entity Process Authorization
 */

use Entities\EntityProcessAuthorization;

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id || !verify_csrf()) {
    redirect('/');
    exit;
}

$entity = Entities\EntityProcessAuthorization::find($id);

if ($entity && $entity->delete()) {
    $_SESSION['success'] = 'Entity Process Authorization deleted successfully!';
} else {
    $_SESSION['error'] = 'Failed to delete Entity Process Authorization';
}

redirect('/' . basename(dirname(__FILE__)));
