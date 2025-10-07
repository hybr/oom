<?php
/**
 * Interview Stage Detail Page
 */

use Entities\InterviewStage;

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/interview_stages');
    exit;
}

$stage = InterviewStage::find($id);

if (!$stage) {
    $_SESSION['error'] = 'Interview stage not found';
    redirect('/interview_stages');
    exit;
}

$pageTitle = $stage->name;

$organization = $stage->getOrganization();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/interview_stages">Interview Stages</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($stage->name) ?></li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-list-ol"></i> <?= htmlspecialchars($stage->name) ?>
                </h1>
                <div>
                    <a href="/interview_stages/<?= $stage->id ?>/edit" class="btn btn-secondary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="/interview_stages/<?= $stage->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                            <h5 class="mb-0">Stage Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="250">ID:</th>
                                    <td><?= $stage->id ?></td>
                                </tr>
                                <tr>
                                    <th>Organization:</th>
                                    <td><?= $organization ? htmlspecialchars($organization->name) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><?= htmlspecialchars($stage->name) ?></td>
                                </tr>
                                <tr>
                                    <th>Order Number:</th>
                                    <td><span class="badge bg-info"><?= $stage->order_number ?></span></td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($stage->created_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($stage->updated_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Version:</th>
                                    <td><?= $stage->version ?></td>
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
                                <a href="/interview_stages/<?= $stage->id ?>/edit" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Edit Stage
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
