<?php
/**
 * Create Person Page
 */

$pageTitle = 'Add New Person';

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/persons">Persons</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Person
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/persons/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('first_name') ? 'is-invalid' : '' ?>"
                                   id="first_name"
                                   name="first_name"
                                   value="<?= old('first_name') ?>"
                                   required
                                   autofocus>
                            <?php $field = 'first_name'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the person's first name</div>
                        </div>

                        <div class="mb-3">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text"
                                   class="form-control <?= errors('middle_name') ? 'is-invalid' : '' ?>"
                                   id="middle_name"
                                   name="middle_name"
                                   value="<?= old('middle_name') ?>">
                            <?php $field = 'middle_name'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the person's middle name (optional)</div>
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('last_name') ? 'is-invalid' : '' ?>"
                                   id="last_name"
                                   name="last_name"
                                   value="<?= old('last_name') ?>"
                                   required>
                            <?php $field = 'last_name'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the person's last name</div>
                        </div>

                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date"
                                   class="form-control <?= errors('date_of_birth') ? 'is-invalid' : '' ?>"
                                   id="date_of_birth"
                                   name="date_of_birth"
                                   value="<?= old('date_of_birth') ?>">
                            <?php $field = 'date_of_birth'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the person's date of birth</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/persons" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Person
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>