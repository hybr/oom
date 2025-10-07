<?php
/**
 * Create Interview Stage Page
 */

use Entities\Organization;

$pageTitle = 'Add New Interview Stage';

// Get all organizations for dropdown
$organizations = Organization::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/interview_stages">Interview Stages</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Interview Stage
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/interview_stages/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="organization_id" class="form-label">Organization <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('organization_id') ? 'is-invalid' : '' ?>"
                                    id="organization_id"
                                    name="organization_id"
                                    required>
                                <option value="">Select an organization...</option>
                                <?php foreach ($organizations as $org): ?>
                                    <option value="<?= $org->id ?>" <?= old('organization_id') == $org->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($org->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'organization_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Stage Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('name') ? 'is-invalid' : '' ?>"
                                   id="name"
                                   name="name"
                                   value="<?= old('name') ?>"
                                   required
                                   autofocus>
                            <?php $field = 'name'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">E.g., "Initial Screening", "Technical Round", "HR Interview"</div>
                        </div>

                        <div class="mb-3">
                            <label for="order_number" class="form-label">Order Number <span class="text-danger">*</span></label>
                            <input type="number"
                                   class="form-control <?= errors('order_number') ? 'is-invalid' : '' ?>"
                                   id="order_number"
                                   name="order_number"
                                   value="<?= old('order_number') ?>"
                                   required>
                            <?php $field = 'order_number'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the order number for this stage</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/interview_stages" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Stage
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
