<?php
require_once __DIR__ . '/../../../bootstrap.php';

// Redirect if already logged in
if (auth()->check()) {
    header('Location: ../dashboard.php');
    exit;
}

$pageTitle = 'Login';
require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm mt-5">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">
                        <i class="bi bi-box-arrow-in-right"></i> Login to V4L
                    </h2>

                    <form action="login-process.php" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control <?php echo error('username') ? 'is-invalid' : ''; ?>"
                                   id="username" name="username" value="<?php echo old('username'); ?>" required autofocus>
                            <?php if ($err = error('username')): ?>
                                <div class="invalid-feedback"><?php echo escape($err); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control <?php echo error('password') ? 'is-invalid' : ''; ?>"
                                   id="password" name="password" required>
                            <?php if ($err = error('password')): ?>
                                <div class="invalid-feedback"><?php echo escape($err); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted mb-2">Don't have an account?</p>
                        <a href="signup.php" class="btn btn-outline-secondary">
                            <i class="bi bi-person-plus"></i> Sign Up
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
