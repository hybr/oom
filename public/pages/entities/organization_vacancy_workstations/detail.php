<?php
/**
 * Organization Vacancy Workstation Detail Page
 */

use Entities\OrganizationVacancyWorkstation;

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/organization_vacancy_workstations');
    exit;
}

$ws = OrganizationVacancyWorkstation::find($id);

if (!$ws) {
    $_SESSION['error'] = 'Vacancy workstation not found';
    redirect('/organization_vacancy_workstations');
    exit;
}

$pageTitle = 'Vacancy Workstation Details';

$vacancy = $ws->getVacancy();
$workstation = $ws->getWorkstation();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/organization_vacancy_workstations">Vacancy Workstations</a></li>
                    <li class="breadcrumb-item active">Details #<?= $ws->id ?></li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-building"></i> Vacancy Workstation #<?= $ws->id ?>
                </h1>
                <div>
                    <a href="/organization_vacancy_workstations/<?= $ws->id ?>/edit" class="btn btn-secondary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="/organization_vacancy_workstations/<?= $ws->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                            <h5 class="mb-0">Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="250">ID:</th>
                                    <td><?= $ws->id ?></td>
                                </tr>
                                <tr>
                                    <th>Vacancy:</th>
                                    <td><?= $vacancy ? '<a href="/organization_vacancies/' . $vacancy->id . '">Vacancy #' . $vacancy->id . '</a>' : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Workstation:</th>
                                    <td><?= $workstation ? htmlspecialchars($workstation->name) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($ws->created_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($ws->updated_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Version:</th>
                                    <td><?= $ws->version ?></td>
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
                                <a href="/organization_vacancy_workstations/<?= $ws->id ?>/edit" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Edit
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
