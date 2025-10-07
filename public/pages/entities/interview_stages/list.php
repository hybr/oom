<?php
/**
 * Interview Stages List Page
 */

use Entities\InterviewStage;

$pageTitle = 'Interview Stages';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Get stages
$stages = InterviewStage::all($perPage, $offset);
$total = InterviewStage::count();

$totalPages = ceil($total / $perPage);

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-list-ol"></i> Interview Stages
                </h1>
                <a href="/interview_stages/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Stage
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
                        All Interview Stages (<?= $total ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Organization</th>
                                    <th>Name</th>
                                    <th>Order</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($stages)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> No interview stages found
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($stages as $stage): ?>
                                        <tr>
                                            <td><?= $stage->id ?></td>
                                            <td>
                                                <?php $org = $stage->getOrganization(); ?>
                                                <?= $org ? htmlspecialchars($org->name) : '-' ?>
                                            </td>
                                            <td>
                                                <a href="/interview_stages/<?= $stage->id ?>" class="text-decoration-none">
                                                    <strong><?= htmlspecialchars($stage->name) ?></strong>
                                                </a>
                                            </td>
                                            <td><span class="badge bg-info"><?= $stage->order_number ?></span></td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M d, Y', strtotime($stage->created_at)) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="/interview_stages/<?= $stage->id ?>" class="btn btn-outline-primary" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="/interview_stages/<?= $stage->id ?>/edit" class="btn btn-outline-secondary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form method="POST" action="/interview_stages/<?= $stage->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                    $baseUrl = '/interview_stages';
                    include __DIR__ . '/../../../../views/components/pagination.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
