<?php
/**
 * Create Credential Page
 */

use Entities\Person;

$pageTitle = 'Add New Credential';

// Get all persons for the dropdown
$persons = Person::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/credentials">Credentials</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Credential
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/credentials/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <?php
                            $fk_label = 'Person';
                            $fk_for = 'person_id';
                            $fk_entity = 'persons';
                            $fk_required = true;
                            $fk_icon = 'bi-person';
                            include __DIR__ . '/../../../../views/components/fk-label.php';
                            ?>
                            <select class="form-select <?= errors('person_id') ? 'is-invalid' : '' ?>"
                                    id="person_id"
                                    name="person_id"
                                    required
                                    autofocus>
                                <option value="">Select a person...</option>
                                <?php foreach ($persons as $person): ?>
                                    <option value="<?= $person->id ?>" <?= old('person_id') == $person->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($person->first_name . ' ' . $person->last_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'person_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the person for this credential</div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('username') ? 'is-invalid' : '' ?>"
                                   id="username"
                                   name="username"
                                   value="<?= old('username') ?>"
                                   required>
                            <?php $field = 'username'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter a unique username</div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password"
                                   class="form-control <?= errors('password') ? 'is-invalid' : '' ?>"
                                   id="password"
                                   name="password"
                                   required>
                            <?php $field = 'password'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter a secure password</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/credentials" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Credential
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>