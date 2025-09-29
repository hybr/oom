<?php
session_start();
require_once '../includes/header.php';

// Check if user is logged in
$user_id = $_SESSION['user_id'] ?? null;
$user = null;

if ($user_id) {
    try {
        require_once '../entities/PersonCredential.php';
        $user = PersonCredential::find($user_id);
    } catch (Exception $e) {
        error_log("Change password error: " . $e->getMessage());
    }
}

// If not logged in, redirect to login
if (!$user) {
    header('Location: login.php?message=login_required');
    exit();
}
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">🔐 Change Password</h4>
                    <p class="text-muted mt-2">Update your account password</p>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['message'])): ?>
                        <?php if ($_GET['message'] === 'success'): ?>
                            <div class="alert alert-success" role="alert">
                                <strong>Success!</strong> Your password has been changed successfully.
                            </div>
                        <?php elseif ($_GET['message'] === 'wrong_current'): ?>
                            <div class="alert alert-danger" role="alert">
                                <strong>Incorrect Password!</strong> The current password you entered is incorrect.
                            </div>
                        <?php elseif ($_GET['message'] === 'mismatch'): ?>
                            <div class="alert alert-warning" role="alert">
                                <strong>Password Mismatch!</strong> The new passwords you entered don't match.
                            </div>
                        <?php elseif ($_GET['message'] === 'weak'): ?>
                            <div class="alert alert-warning" role="alert">
                                <strong>Weak Password!</strong> New password must be at least 8 characters long.
                            </div>
                        <?php elseif ($_GET['message'] === 'same'): ?>
                            <div class="alert alert-warning" role="alert">
                                <strong>Same Password!</strong> New password must be different from your current password.
                            </div>
                        <?php elseif ($_GET['message'] === 'error'): ?>
                            <div class="alert alert-danger" role="alert">
                                <strong>Error!</strong> There was a problem changing your password. Please try again.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- User Info -->
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Account Information</h6>
                                    <dl class="row">
                                        <dt class="col-sm-6">Username:</dt>
                                        <dd class="col-sm-6"><?php echo htmlspecialchars($user->username ?? 'N/A'); ?></dd>

                                        <dt class="col-sm-6">Email:</dt>
                                        <dd class="col-sm-6"><?php echo htmlspecialchars($user->email ?? 'N/A'); ?></dd>

                                        <dt class="col-sm-6">Last Login:</dt>
                                        <dd class="col-sm-6"><?php echo $user->last_login ? date('M j, Y g:i A', strtotime($user->last_login)) : 'Never'; ?></dd>

                                        <dt class="col-sm-6">Status:</dt>
                                        <dd class="col-sm-6">
                                            <?php if ($user->is_active): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </dd>
                                    </dl>
                                </div>
                            </div>

                            <!-- Security Tips -->
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h6 class="card-title">🔒 Security Tips</h6>
                                    <ul class="list-unstyled small">
                                        <li>✓ Use at least 8 characters</li>
                                        <li>✓ Include numbers and symbols</li>
                                        <li>✓ Mix uppercase and lowercase</li>
                                        <li>✓ Avoid personal information</li>
                                        <li>✓ Don't reuse old passwords</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Change Password Form -->
                            <form id="changePasswordForm" method="post" action="">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password *</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required
                                           placeholder="Enter your current password">
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password *</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required
                                           minlength="8" placeholder="Enter new password">
                                    <div class="form-text">Must be at least 8 characters long.</div>
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

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary" name="change_password">
                                        🔄 Change Password
                                    </button>
                                    <a href="personcredentials.php" class="btn btn-outline-secondary">
                                        ← Back to User Management
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Handle form submission
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate current password
    if (!$user->verifyPassword($current_password)) {
        header('Location: change_password.php?message=wrong_current');
        exit();
    }

    // Validate new password length
    if (strlen($new_password) < 8) {
        header('Location: change_password.php?message=weak');
        exit();
    }

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        header('Location: change_password.php?message=mismatch');
        exit();
    }

    // Check if new password is different from current
    if ($user->verifyPassword($new_password)) {
        header('Location: change_password.php?message=same');
        exit();
    }

    try {
        // Update password
        $user->setPassword($new_password);
        $user->password_changed_at = date('Y-m-d H:i:s');
        $user->save();

        // Log the password change
        error_log("Password changed for user ID: " . $user->id);

        header('Location: change_password.php?message=success');
        exit();

    } catch (Exception $e) {
        error_log("Change password error: " . $e->getMessage());
        header('Location: change_password.php?message=error');
        exit();
    }
}

require_once '../includes/footer.php';
require_once '../includes/scripts.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('changePasswordForm');
    const showPasswordsCheckbox = document.getElementById('show_passwords');
    const passwordFields = ['current_password', 'new_password', 'confirm_password'];

    // Show/hide passwords
    if (showPasswordsCheckbox) {
        showPasswordsCheckbox.addEventListener('change', function() {
            const type = this.checked ? 'text' : 'password';
            passwordFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) field.type = type;
            });
        });
    }

    // Real-time password matching feedback
    const newPasswordField = document.getElementById('new_password');
    const confirmPasswordField = document.getElementById('confirm_password');

    if (confirmPasswordField && newPasswordField) {
        function checkPasswordMatch() {
            const newPassword = newPasswordField.value;
            const confirmPassword = confirmPasswordField.value;

            if (confirmPassword && newPassword !== confirmPassword) {
                confirmPasswordField.classList.add('is-invalid');
                confirmPasswordField.classList.remove('is-valid');
            } else if (confirmPassword) {
                confirmPasswordField.classList.remove('is-invalid');
                confirmPasswordField.classList.add('is-valid');
            } else {
                confirmPasswordField.classList.remove('is-invalid', 'is-valid');
            }
        }

        confirmPasswordField.addEventListener('input', checkPasswordMatch);
        newPasswordField.addEventListener('input', checkPasswordMatch);
    }

    // Form validation
    if (form) {
        form.addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (!currentPassword) {
                e.preventDefault();
                alert('Please enter your current password.');
                return false;
            }

            if (newPassword.length < 8) {
                e.preventDefault();
                alert('New password must be at least 8 characters long.');
                return false;
            }

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New passwords do not match.');
                return false;
            }

            if (currentPassword === newPassword) {
                e.preventDefault();
                alert('New password must be different from your current password.');
                return false;
            }
        });
    }
});
</script>