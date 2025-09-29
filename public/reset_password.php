<?php
require_once '../includes/header.php';

// Check if token is provided
$token = $_GET['token'] ?? '';
$valid_token = false;
$user = null;

if ($token) {
    try {
        require_once '../entities/PersonCredential.php';
        $user = PersonCredential::findByResetToken($token);

        if ($user && $user->password_reset_expires && strtotime($user->password_reset_expires) > time()) {
            $valid_token = true;
        }
    } catch (Exception $e) {
        error_log("Reset password error: " . $e->getMessage());
    }
}
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title mb-0">🔄 Reset Password</h4>
                    <p class="text-muted mt-2">Enter your new password below</p>
                </div>
                <div class="card-body">
                    <?php if (!$token): ?>
                        <!-- No token provided -->
                        <div class="alert alert-danger" role="alert">
                            <strong>Invalid Request!</strong> No reset token provided.
                        </div>
                        <div class="text-center">
                            <a href="forgot_password.php" class="btn btn-primary">
                                🔐 Request Password Reset
                            </a>
                        </div>

                    <?php elseif (!$valid_token): ?>
                        <!-- Invalid or expired token -->
                        <div class="alert alert-danger" role="alert">
                            <strong>Invalid or Expired Link!</strong> This password reset link is no longer valid.
                        </div>
                        <div class="text-center">
                            <a href="forgot_password.php" class="btn btn-primary">
                                🔐 Request New Reset Link
                            </a>
                        </div>

                    <?php else: ?>
                        <!-- Valid token - show reset form -->
                        <?php if (isset($_GET['message'])): ?>
                            <?php if ($_GET['message'] === 'success'): ?>
                                <div class="alert alert-success" role="alert">
                                    <strong>Success!</strong> Your password has been reset. You can now log in with your new password.
                                </div>
                                <div class="text-center">
                                    <a href="login.php" class="btn btn-primary">
                                        🔑 Go to Login
                                    </a>
                                </div>
                            <?php elseif ($_GET['message'] === 'error'): ?>
                                <div class="alert alert-danger" role="alert">
                                    <strong>Error!</strong> There was a problem resetting your password. Please try again.
                                </div>
                            <?php elseif ($_GET['message'] === 'mismatch'): ?>
                                <div class="alert alert-warning" role="alert">
                                    <strong>Password Mismatch!</strong> The passwords you entered don't match.
                                </div>
                            <?php elseif ($_GET['message'] === 'weak'): ?>
                                <div class="alert alert-warning" role="alert">
                                    <strong>Weak Password!</strong> Password must be at least 8 characters long.
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <form id="resetPasswordForm" method="post" action="">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password *</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required
                                       minlength="8" placeholder="Enter new password">
                                <div class="form-text">Password must be at least 8 characters long.</div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password *</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                                       minlength="8" placeholder="Confirm new password">
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="show_passwords">
                                    <label class="form-check-label" for="show_passwords">
                                        👁️ Show passwords
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" name="reset_password">
                                    🔄 Reset Password
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>

                    <?php if ($valid_token): ?>
                        <hr class="my-4">
                        <div class="text-center">
                            <a href="login.php" class="btn btn-outline-secondary btn-sm">
                                🔑 Back to Login
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Handle form submission
if (isset($_POST['reset_password']) && $valid_token && $user) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords
    if (strlen($new_password) < 8) {
        header('Location: reset_password.php?token=' . urlencode($token) . '&message=weak');
        exit();
    }

    if ($new_password !== $confirm_password) {
        header('Location: reset_password.php?token=' . urlencode($token) . '&message=mismatch');
        exit();
    }

    try {
        // Update password
        $user->setPassword($new_password);
        $user->password_reset_token = null;
        $user->password_reset_expires = null;
        $user->resetLoginAttempts();
        $user->save();

        header('Location: reset_password.php?token=' . urlencode($token) . '&message=success');
        exit();

    } catch (Exception $e) {
        error_log("Password reset error: " . $e->getMessage());
        header('Location: reset_password.php?token=' . urlencode($token) . '&message=error');
        exit();
    }
}

require_once '../includes/footer.php';
require_once '../includes/scripts.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('resetPasswordForm');
    const showPasswordsCheckbox = document.getElementById('show_passwords');
    const newPasswordField = document.getElementById('new_password');
    const confirmPasswordField = document.getElementById('confirm_password');

    // Show/hide passwords
    if (showPasswordsCheckbox) {
        showPasswordsCheckbox.addEventListener('change', function() {
            const type = this.checked ? 'text' : 'password';
            if (newPasswordField) newPasswordField.type = type;
            if (confirmPasswordField) confirmPasswordField.type = type;
        });
    }

    // Form validation
    if (form) {
        form.addEventListener('submit', function(e) {
            const newPassword = newPasswordField.value;
            const confirmPassword = confirmPasswordField.value;

            if (newPassword.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long.');
                return false;
            }

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match.');
                return false;
            }
        });
    }

    // Real-time password matching feedback
    if (confirmPasswordField) {
        confirmPasswordField.addEventListener('input', function() {
            const newPassword = newPasswordField.value;
            const confirmPassword = this.value;

            if (confirmPassword && newPassword !== confirmPassword) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
});
</script>