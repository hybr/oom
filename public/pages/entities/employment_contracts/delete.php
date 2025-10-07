<?php
/**
 * Delete Employment Contract
 */

use Entities\EmploymentContract;

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id || !verify_csrf()) {
    redirect('/');
    exit;
}

$entity = Entities\EmploymentContract::find($id);

if ($entity && $entity->delete()) {
    $_SESSION['success'] = 'Employment Contract deleted successfully!';
} else {
    $_SESSION['error'] = 'Failed to delete Employment Contract';
}

redirect('/' . basename(dirname(__FILE__)));
