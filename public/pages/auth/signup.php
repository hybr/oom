<?php
/**
 * Signup Page
 */

// Redirect if already logged in
if (auth()) {
    redirect('/');
    exit;
}

$pageTitle = 'Sign Up';
include __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h1 class="h3 mb-3">
                            <i class="bi bi-person-plus"></i> Create Account
                        </h1>
                        <p class="text-muted">Join the V4L platform today</p>
                    </div>

                    <?php include __DIR__ . '/../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/signup" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control <?= errors('first_name') ? 'is-invalid' : '' ?>"
                                       id="first_name"
                                       name="first_name"
                                       value="<?= old('first_name') ?>"
                                       required>
                                <?php $field = 'first_name'; include __DIR__ . '/../../../views/components/form-errors.php'; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control <?= errors('last_name') ? 'is-invalid' : '' ?>"
                                       id="last_name"
                                       name="last_name"
                                       value="<?= old('last_name') ?>"
                                       required>
                                <?php $field = 'last_name'; include __DIR__ . '/../../../views/components/form-errors.php'; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="middle_name"
                                   name="middle_name"
                                   value="<?= old('middle_name') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date"
                                   class="form-control <?= errors('date_of_birth') ? 'is-invalid' : '' ?>"
                                   id="date_of_birth"
                                   name="date_of_birth"
                                   value="<?= old('date_of_birth') ?>">
                            <?php $field = 'date_of_birth'; include __DIR__ . '/../../../views/components/form-errors.php'; ?>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text"
                                       class="form-control <?= errors('username') ? 'is-invalid' : '' ?>"
                                       id="username"
                                       name="username"
                                       value="<?= old('username') ?>"
                                       required>
                            </div>
                            <?php $field = 'username'; include __DIR__ . '/../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Choose a unique username (min 3 characters)</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password"
                                           class="form-control <?= errors('password') ? 'is-invalid' : '' ?>"
                                           id="password"
                                           name="password"
                                           required>
                                </div>
                                <?php $field = 'password'; include __DIR__ . '/../../../views/components/form-errors.php'; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password"
                                           class="form-control <?= errors('password_confirmation') ? 'is-invalid' : '' ?>"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           required>
                                </div>
                                <?php $field = 'password_confirmation'; include __DIR__ . '/../../../views/components/form-errors.php'; ?>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="/terms" target="_blank">Terms & Conditions</a>
                            </label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus"></i> Create Account
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">
                            <small class="text-muted">Already have an account?</small>
                            <a href="/login" class="text-decoration-none">
                                <small><strong>Sign in here</strong></small>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../includes/footer.php'; ?>
