<?php
/**
 * Login Page
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Redirect if already logged in
if (auth()) {
    redirect('/');
    exit;
}

$pageTitle = 'Login';
include __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h1 class="h3 mb-3">
                            <i class="bi bi-box-arrow-in-right"></i> Welcome Back
                        </h1>
                        <p class="text-muted">Sign in to your V4L account</p>
                    </div>

                    <?php include __DIR__ . '/../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/login" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text"
                                       class="form-control <?= errors('username') ? 'is-invalid' : '' ?>"
                                       id="username"
                                       name="username"
                                       value="<?= old('username') ?>"
                                       autocomplete="username"
                                       required
                                       autofocus>
                            </div>
                            <?php $field = 'username'; include __DIR__ . '/../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password"
                                       class="form-control <?= errors('password') ? 'is-invalid' : '' ?>"
                                       id="password"
                                       name="password"
                                       autocomplete="current-password"
                                       required>
                            </div>
                            <?php $field = 'password'; include __DIR__ . '/../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Sign In
                            </button>
                        </div>

                        <div class="text-center">
                            <a href="/forgot-password" class="text-decoration-none">
                                <small>Forgot your password?</small>
                            </a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">
                            <small class="text-muted">Don't have an account?</small>
                            <a href="/signup" class="text-decoration-none">
                                <small><strong>Sign up now</strong></small>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../includes/footer.php'; ?>
