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

$pageTitle = 'Edit Continent: ' . $record['name'];
require_once __DIR__ . '/../../../../includes/header.php';
?>

<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/'); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo url('pages/dashboard.php'); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="list.php">Continents</a></li>
            <li class="breadcrumb-item"><a href="detail.php?id=<?php echo $id; ?>"><?php echo escape($record['name']); ?></a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0"><i class="bi bi-pencil"></i> Edit Continent</h2>
                </div>
                <div class="card-body">
                    <form action="update.php" method="POST" class="needs-validation" novalidate data-confirm-leave>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Continent Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control <?php echo error('name') ? 'is-invalid' : ''; ?>"
                                   id="name"
                                   name="name"
                                   value="<?php echo old('name', $record['name']); ?>"
                                   required
                                   autofocus>
                            <?php if ($err = error('name')): ?>
                                <div class="invalid-feedback"><?php echo escape($err); ?></div>
                            <?php else: ?>
                                <div class="form-text">Enter the name of the continent</div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="detail.php?id=<?php echo $id; ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Continent
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Created:</strong><br><?php echo escape($record['created_at']); ?></p>
                    <p><strong>Updated:</strong><br><?php echo escape($record['updated_at']); ?></p>
                    <p class="mb-0"><small class="text-muted">Changes will be saved immediately.</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
