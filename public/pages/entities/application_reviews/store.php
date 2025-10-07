<?php
/**
 * Store Application Review Action
 */

use Entities\ApplicationReview;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/application_reviews');
    exit;
}

// CSRF verification
if (!verify_csrf()) {
    $_SESSION['error'] = 'Invalid request';
    redirect('/application_reviews/create');
    exit;
}

// Store old input
$_SESSION['_old'] = $_POST;

// Create review
$review = new ApplicationReview();
$review->fill($_POST);

if ($review->save()) {
    $_SESSION['success'] = 'Review created successfully!';
    redirect('/application_reviews/' . $review->id);
} else {
    $_SESSION['_errors'] = $review->getErrors();
    redirect('/application_reviews/create');
}
