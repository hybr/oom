<?php
/**
 * Delete Application Review Action
 */

use Entities\ApplicationReview;

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
    redirect('/application_reviews');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/application_reviews');
    exit;
}

$review = ApplicationReview::find($id);

if (!$review) {
    $_SESSION['error'] = 'Review not found';
    redirect('/application_reviews');
    exit;
}

// Soft delete
if ($review->delete()) {
    $_SESSION['success'] = 'Review deleted successfully!';
} else {
    $_SESSION['error'] = 'Failed to delete review';
}

redirect('/application_reviews');
