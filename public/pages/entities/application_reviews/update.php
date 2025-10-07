<?php
/**
 * Update Application Review Action
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
    redirect('/application_reviews/' . $id . '/edit');
    exit;
}

$review = ApplicationReview::find($id);

if (!$review) {
    $_SESSION['error'] = 'Review not found';
    redirect('/application_reviews');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Update review
$review->fill($_POST);

if ($review->save()) {
    $_SESSION['success'] = 'Review updated successfully!';
    redirect('/application_reviews/' . $review->id);
} else {
    $_SESSION['_errors'] = $review->getErrors();
    redirect('/application_reviews/' . $id . '/edit');
}
