<?php
require_once __DIR__ . '/../../../bootstrap.php';

// Redirect if already logged in
if (auth()->check()) {
    header('Location: ../dashboard.php');
    exit;
}

$pageTitle = 'Sign Up';
require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm mt-5">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">
                        <i class="bi bi-person-plus"></i> Create Your Account
                    </h2>

                    <form action="signup-process.php" method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo error('first_name') ? 'is-invalid' : ''; ?>"
                                       id="first_name" name="first_name" value="<?php echo old('first_name'); ?>" required>
                                <?php if ($err = error('first_name')): ?>
                                    <div class="invalid-feedback"><?php echo escape($err); ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo error('last_name') ? 'is-invalid' : ''; ?>"
                                       id="last_name" name="last_name" value="<?php echo old('last_name'); ?>" required>
                                <?php if ($err = error('last_name')): ?>
                                    <div class="invalid-feedback"><?php echo escape($err); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo old('middle_name'); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo old('date_of_birth'); ?>">
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo error('username') ? 'is-invalid' : ''; ?>"
                                   id="username" name="username" value="<?php echo old('username'); ?>" required>
                            <?php if ($err = error('username')): ?>
                                <div class="invalid-feedback"><?php echo escape($err); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control <?php echo error('password') ? 'is-invalid' : ''; ?>"
                                   id="password" name="password" required>
                            <div class="form-text">At least 8 characters</div>
                            <?php if ($err = error('password')): ?>
                                <div class="invalid-feedback"><?php echo escape($err); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control <?php echo error('password_confirmation') ? 'is-invalid' : ''; ?>"
                                   id="password_confirmation" name="password_confirmation" required>
                            <?php if ($err = error('password_confirmation')): ?>
                                <div class="invalid-feedback"><?php echo escape($err); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus"></i> Create Account
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted mb-2">Already have an account?</p>
                        <a href="login.php" class="btn btn-outline-secondary">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
