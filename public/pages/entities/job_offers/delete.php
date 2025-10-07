<?php
/**
 * Delete Job Offer
 */

use Entities\JobOffer;

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id || !verify_csrf()) {
    redirect('/');
    exit;
}

$entity = Entities\JobOffer::find($id);

if ($entity && $entity->delete()) {
    $_SESSION['success'] = 'Job Offer deleted successfully!';
} else {
    $_SESSION['error'] = 'Failed to delete Job Offer';
}

redirect('/' . basename(dirname(__FILE__)));
