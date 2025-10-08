<?php
/**
 * Application Reviews List Page
 */

require_once __DIR__ . '/../../../../bootstrap.php';

use Entities\ApplicationReview;

$pageTitle = 'Application Reviews';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Get reviews
$reviews = ApplicationReview::all($perPage, $offset);
$total = ApplicationReview::count();

$totalPages = ceil($total / $perPage);

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-clipboard-check"></i> Application Reviews
                </h1>
                <a href="/application_reviews/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Review
                </a>
            </div>

            <!-- Search & Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-9">
                            <select name="per_page" class="form-select">
                                <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10 per page</option>
                                <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25 per page</option>
                                <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50 per page</option>
                                <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100 per page</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="bi bi-search"></i> Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        All Reviews (<?= $total ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Application</th>
                                    <th>Reviewer</th>
                                    <th>Review Date</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($reviews)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> No reviews found
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($reviews as $review): ?>
                                        <tr>
                                            <td><?= $review->id ?></td>
                                            <td>
                                                <?php $app = $review->getApplication(); ?>
                                                <?= $app ? '<a href="/vacancy_applications/' . $app->id . '">Application #' . $app->id . '</a>' : '-' ?>
                                            </td>
                                            <td>
                                                <?php $reviewer = $review->getReviewer(); ?>
                                                <?= $reviewer ? htmlspecialchars($reviewer->first_name . ' ' . $reviewer->last_name) : '-' ?>
                                            </td>
                                            <td><?= $review->review_date ? date('M d, Y', strtotime($review->review_date)) : '-' ?></td>
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
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M d, Y', strtotime($review->created_at)) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="/application_reviews/<?= $review->id ?>" class="btn btn-outline-primary" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="/application_reviews/<?= $review->id ?>/edit" class="btn btn-outline-secondary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form method="POST" action="/application_reviews/<?= $review->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php
                    $currentPage = $page;
                    $baseUrl = '/application_reviews';
                    include __DIR__ . '/../../../../views/components/pagination.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
