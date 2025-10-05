<?php
/**
 * Continent Detail Page
 */

use Entities\Continent;

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/continents');
    exit;
}

$continent = Continent::find($id);

if (!$continent) {
    $_SESSION['error'] = 'Continent not found';
    redirect('/continents');
    exit;
}

$pageTitle = $continent->name;
$countries = $continent->getCountries();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/continents">Continents</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($continent->name) ?></li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-globe"></i> <?= htmlspecialchars($continent->name) ?>
                </h1>
                <div>
                    <a href="/continents/<?= $continent->id ?>/edit" class="btn btn-secondary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="/continents/<?= $continent->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this continent?');">
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
                                    <th width="200">ID:</th>
                                    <td><?= $continent->id ?></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><?= htmlspecialchars($continent->name) ?></td>
                                </tr>
                                <tr>
                                    <th>Total Countries:</th>
                                    <td>
                                        <span class="badge bg-primary"><?= $continent->countCountries() ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($continent->created_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($continent->updated_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Version:</th>
                                    <td><?= $continent->version ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Countries -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Countries (<?= count($countries) ?>)</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($countries)): ?>
                                <p class="text-muted mb-0">No countries in this continent</p>
                            <?php else: ?>
                                <div class="list-group">
                                    <?php foreach ($countries as $country): ?>
                                        <a href="/countries/<?= $country->id ?>" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?= htmlspecialchars($country->name) ?></h6>
                                                <small class="text-muted"><?= $country->countLanguages() ?> languages</small>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
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
                                <a href="/countries/create?continent_id=<?= $continent->id ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-plus"></i> Add Country
                                </a>
                                <a href="/continents/<?= $continent->id ?>/edit" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Edit Continent
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
                            <?php $history = $continent->getHistory(); ?>
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
