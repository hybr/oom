<?php
/**
 * Popular Organization Position Detail Page
 */

use Entities\PopularOrganizationPosition;

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/popular_organization_positions');
    exit;
}

$position = PopularOrganizationPosition::find($id);

if (!$position) {
    $_SESSION['error'] = 'Position not found';
    redirect('/popular_organization_positions');
    exit;
}

$pageTitle = $position->name;

// Load related data
$department = $position->getDepartment();
$team = $position->getTeam();
$designation = $position->getDesignation();
$minimumSubject = $position->getMinimumSubject();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/popular_organization_positions">Positions</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($position->name) ?></li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-briefcase"></i> <?= htmlspecialchars($position->name) ?>
                </h1>
                <div>
                    <a href="/popular_organization_positions/<?= $position->id ?>/edit" class="btn btn-secondary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="/popular_organization_positions/<?= $position->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this position?');">
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
                            <h5 class="mb-0">Position Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="250">ID:</th>
                                    <td><?= $position->id ?></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><?= htmlspecialchars($position->name) ?></td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td><?= $department ? htmlspecialchars($department->name) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Team:</th>
                                    <td><?= $team ? htmlspecialchars($team->name) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Designation:</th>
                                    <td><?= $designation ? htmlspecialchars($designation->name) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Minimum Education Level:</th>
                                    <td><?= $position->minimum_education_level ? htmlspecialchars($position->minimum_education_level) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Minimum Subject:</th>
                                    <td><?= $minimumSubject ? htmlspecialchars($minimumSubject->name) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td><?= $position->description ? nl2br(htmlspecialchars($position->description)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($position->created_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($position->updated_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Version:</th>
                                    <td><?= $position->version ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="/popular_organization_positions/<?= $position->id ?>/edit" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Edit Position
                                </a>
                                <a href="/organization_vacancies/create?position_id=<?= $position->id ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-plus"></i> Create Vacancy
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- History -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Activity History</h5>
                        </div>
                        <div class="card-body">
                            <?php $history = $position->getHistory(); ?>
                            <?php if (empty($history)): ?>
                                <p class="text-muted mb-0"><small>No history available</small></p>
                            <?php else: ?>
                                <div class="timeline">
                                    <?php foreach (array_slice($history, 0, 5) as $entry): ?>
                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <?= date('M d, Y h:i A', strtotime($entry['created_at'])) ?>
                                            </small>
                                            <div>
                                                <span class="badge bg-secondary"><?= ucfirst($entry['action']) ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
