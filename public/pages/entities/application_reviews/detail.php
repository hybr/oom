<?php
/**
 * Application Review Detail Page
 */

use Entities\ApplicationReview;

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/application_reviews');
    exit;
}

$review = ApplicationReview::find($id);

if (!$review) {
    $_SESSION['error'] = 'Review not found';
    redirect('/application_reviews');
    exit;
}

$pageTitle = 'Review Details';

$application = $review->getApplication();
$reviewer = $review->getReviewer();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/application_reviews">Reviews</a></li>
                    <li class="breadcrumb-item active">Review #<?= $review->id ?></li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-clipboard-check"></i> Review #<?= $review->id ?>
                </h1>
                <div>
                    <a href="/application_reviews/<?= $review->id ?>/edit" class="btn btn-secondary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="/application_reviews/<?= $review->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="row">
                <!-- Main Info -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Review Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="250">ID:</th>
                                    <td><?= $review->id ?></td>
                                </tr>
                                <tr>
                                    <th>Application:</th>
                                    <td><?= $application ? '<a href="/vacancy_applications/' . $application->id . '">Application #' . $application->id . '</a>' : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Reviewer:</th>
                                    <td><?= $reviewer ? htmlspecialchars($reviewer->first_name . ' ' . $reviewer->last_name) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Review Date:</th>
                                    <td><?= $review->review_date ? date('F d, Y', strtotime($review->review_date)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Review Notes:</th>
                                    <td><?= $review->review_notes ? nl2br(htmlspecialchars($review->review_notes)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'Pending' => 'warning',
                                            'Approved' => 'success',
                                            'Rejected' => 'danger'
                                        ];
                                        $class = $statusClass[$review->status] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $class ?>"><?= htmlspecialchars($review->status) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($review->created_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($review->updated_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Version:</th>
                                    <td><?= $review->version ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="/application_reviews/<?= $review->id ?>/edit" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Edit Review
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
