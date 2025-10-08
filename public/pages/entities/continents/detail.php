<?php
require_once __DIR__ . '/../../../../bootstrap.php';

auth()->requireAuth();

require_once ENTITIES_PATH . '/Continent.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/pages/entities/continents/list.php');
}

$continent = new Continent();
$record = $continent->find($id);

if (!$record) {
    $_SESSION['error'] = 'Continent not found.';
    redirect('/pages/entities/continents/list.php');
}

// Get related countries
$countries = $continent->getCountries($id);
$countryCount = $continent->getCountryCount($id);

$pageTitle = 'Continent: ' . $record['name'];
require_once __DIR__ . '/../../../../includes/header.php';
?>

<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/'); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo url('pages/dashboard.php'); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="list.php">Continents</a></li>
            <li class="breadcrumb-item active"><?php echo escape($record['name']); ?></li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-globe"></i> <?php echo escape($record['name']); ?></h1>
        <div>
            <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="delete.php?id=<?php echo $id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this continent?')">
                <i class="bi bi-trash"></i> Delete
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>ID:</strong></div>
                        <div class="col-md-8"><?php echo escape($record['id']); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Name:</strong></div>
                        <div class="col-md-8"><?php echo escape($record['name']); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Created At:</strong></div>
                        <div class="col-md-8"><?php echo escape($record['created_at']); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Updated At:</strong></div>
                        <div class="col-md-8"><?php echo escape($record['updated_at']); ?></div>
                    </div>
                </div>
            </div>

            <!-- Related Countries -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-flag"></i> Countries (<?php echo $countryCount; ?>)</h5>
                    <a href="<?php echo url('pages/entities/countries/create.php?continent_id=' . $id); ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Country
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($countries)): ?>
                        <p class="text-muted">No countries added yet.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($countries as $country): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="<?php echo url('pages/entities/countries/detail.php?id=' . $country['id']); ?>">
                                        <?php echo escape($country['name']); ?>
                                    </a>
                                    <span class="badge bg-primary rounded-pill"><?php echo $country['id']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="stats-card">
                        <h3><?php echo $countryCount; ?></h3>
                        <p>Countries</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-link"></i> Quick Links</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="list.php" class="btn btn-outline-secondary">
                            <i class="bi bi-list"></i> All Continents
                        </a>
                        <a href="<?php echo url('pages/entities/countries/list.php?continent_id=' . $id); ?>" class="btn btn-outline-primary">
                            <i class="bi bi-flag"></i> View Countries
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
