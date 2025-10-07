<?php
/**
 * Popular Organization Positions List Page
 */

use Entities\PopularOrganizationPosition;

$pageTitle = 'Popular Organization Positions';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Search
$search = $_GET['search'] ?? '';

// Get positions
if ($search) {
    $positions = PopularOrganizationPosition::searchByName($search);
    $total = count($positions);
    $positions = array_slice($positions, $offset, $perPage);
} else {
    $positions = PopularOrganizationPosition::all($perPage, $offset);
    $total = PopularOrganizationPosition::count();
}

$totalPages = ceil($total / $perPage);

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-briefcase"></i> Popular Organization Positions
                </h1>
                <a href="/popular_organization_positions/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Position
                </a>
            </div>

            <!-- Search & Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Search positions..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="per_page" class="form-select">
                                <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10 per page</option>
                                <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25 per page</option>
                                <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50 per page</option>
                                <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100 per page</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        All Positions (<?= $total ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Team</th>
                                    <th>Designation</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($positions)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> No positions found
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($positions as $position): ?>
                                        <tr>
                                            <td><?= $position->id ?></td>
                                            <td>
                                                <a href="/popular_organization_positions/<?= $position->id ?>" class="text-decoration-none">
                                                    <strong><?= htmlspecialchars($position->name) ?></strong>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if ($position->department_id): ?>
                                                    <?php $dept = $position->getDepartment(); ?>
                                                    <?= $dept ? htmlspecialchars($dept->name) : '-' ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($position->team_id): ?>
                                                    <?php $team = $position->getTeam(); ?>
                                                    <?= $team ? htmlspecialchars($team->name) : '-' ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($position->designation_id): ?>
                                                    <?php $designation = $position->getDesignation(); ?>
                                                    <?= $designation ? htmlspecialchars($designation->name) : '-' ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M d, Y', strtotime($position->created_at)) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="/popular_organization_positions/<?= $position->id ?>" class="btn btn-outline-primary" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="/popular_organization_positions/<?= $position->id ?>/edit" class="btn btn-outline-secondary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form method="POST" action="/popular_organization_positions/<?= $position->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this position?');">
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
                    $baseUrl = '/popular_organization_positions';
                    include __DIR__ . '/../../../../views/components/pagination.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
