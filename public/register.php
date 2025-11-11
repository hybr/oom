<?php
require_once __DIR__ . '/bootstrap.php';

use V4L\Core\Auth;

// Redirect if already logged in
if (Auth::isLoggedIn()) {
    redirect('dashboard.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = Auth::register($_POST);

    if ($result['success']) {
        flash('success', 'Registration successful! Please log in.');
        redirect('login.php');
    } else {
        $errors = $result['errors'];
    }
}

$pageTitle = 'Sign Up - ' . APP_NAME;
ob_start();
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">
                        <i class="bi bi-person-plus text-primary"></i>
                        <br>Create Account
                    </h2>

                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i><?= e($errors['general']) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>"
                                       id="first_name" name="first_name" value="<?= e($_POST['first_name'] ?? '') ?>">
                                <?php if (isset($errors['first_name'])): ?>
                                    <div class="invalid-feedback"><?= e($errors['first_name']) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>"
                                       id="last_name" name="last_name" value="<?= e($_POST['last_name'] ?? '') ?>">
                                <?php if (isset($errors['last_name'])): ?>
                                    <div class="invalid-feedback"><?= e($errors['last_name']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username *</label>
                            <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                                   id="username" name="username" value="<?= e($_POST['username'] ?? '') ?>" required>
                            <?php if (isset($errors['username'])): ?>
                                <div class="invalid-feedback"><?= e($errors['username']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                   id="email" name="email" value="<?= e($_POST['email'] ?? '') ?>">
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= e($errors['email']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="mobile_number" class="form-label">Mobile Number</label>
                            <input type="tel" class="form-control <?= isset($errors['mobile_number']) ? 'is-invalid' : '' ?>"
                                   id="mobile_number" name="mobile_number" value="<?= e($_POST['mobile_number'] ?? '') ?>">
                            <?php if (isset($errors['mobile_number'])): ?>
                                <div class="invalid-feedback"><?= e($errors['mobile_number']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                                   id="password" name="password" required>
                            <div class="form-text">Minimum <?= PASSWORD_MIN_LENGTH ?> characters</div>
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback"><?= e($errors['password']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="terms.php" target="_blank">Terms of Service</a>
                                and <a href="privacy.php" target="_blank">Privacy Policy</a>
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-plus"></i> Create Account
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">
                            Already have an account?
                            <a href="login.php" class="text-decoration-none">Login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
render('layouts/main', compact('pageTitle', 'content'));
