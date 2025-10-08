<?php
require_once __DIR__ . '/../../../../bootstrap.php';

auth()->requireAuth();

$pageTitle = 'Create Organization';
require_once __DIR__ . '/../../../../includes/header.php';
?>

<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/'); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo url('pages/dashboard.php'); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="list.php">Organizations</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0"><i class="bi bi-plus-circle"></i> Create New Organization</h2>
                </div>
                <div class="card-body">
                    <form action="store.php" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="short_name" class="form-label">
                                Organization Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control <?php echo error('short_name') ? 'is-invalid' : ''; ?>"
                                   id="short_name"
                                   name="short_name"
                                   value="<?php echo old('short_name'); ?>"
                                   required
                                   autofocus>
                            <?php if ($err = error('short_name')): ?>
                                <div class="invalid-feedback"><?php echo escape($err); ?></div>
                            <?php else: ?>
                                <div class="form-text">Enter the name of your organization</div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">
                                Full Name
                            </label>
                            <input type="text"
                                   class="form-control <?php echo error('full_name') ? 'is-invalid' : ''; ?>"
                                   id="full_name"
                                   name="full_name"
                                   value="<?php echo old('full_name'); ?>">
                            <?php if ($err = error('full_name')): ?>
                                <div class="invalid-feedback"><?php echo escape($err); ?></div>
                            <?php else: ?>
                                <div class="form-text">Optional: Enter the full legal name</div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="list.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Create Organization
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
                    <p><strong>Organizations</strong> can be companies, non-profits, or any type of group.</p>
                    <p>After creating an organization, you can add members, create job postings, and more.</p>
                    <p class="mb-0"><small class="text-muted">Fields marked with <span class="text-danger">*</span> are required.</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
