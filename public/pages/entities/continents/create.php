<?php
require_once __DIR__ . '/../../../../bootstrap.php';

auth()->requireAuth();

$pageTitle = 'Create Continent';
require_once __DIR__ . '/../../../../includes/header.php';
?>

<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/'); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo url('pages/dashboard.php'); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="list.php">Continents</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0"><i class="bi bi-plus-circle"></i> Create New Continent</h2>
                </div>
                <div class="card-body">
                    <form action="store.php" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Continent Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control <?php echo error('name') ? 'is-invalid' : ''; ?>"
                                   id="name"
                                   name="name"
                                   value="<?php echo old('name'); ?>"
                                   required
                                   autofocus>
                            <?php if ($err = error('name')): ?>
                                <div class="invalid-feedback"><?php echo escape($err); ?></div>
                            <?php else: ?>
                                <div class="form-text">Enter the name of the continent (e.g., Asia, Europe, Africa)</div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="list.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Continent
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Help</h5>
                </div>
                <div class="card-body">
                    <p><strong>Continent</strong> represents one of the major land masses on Earth.</p>
                    <p>After creating a continent, you can add countries that belong to it.</p>
                    <p class="mb-0"><small class="text-muted">Fields marked with <span class="text-danger">*</span> are required.</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
