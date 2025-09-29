<?php
require_once '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title mb-0">🔐 Forgot Password</h4>
                    <p class="text-muted mt-2">Enter your email to receive a password reset link</p>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['message'])): ?>
                        <?php if ($_GET['message'] === 'sent'): ?>
                            <div class="alert alert-success" role="alert">
                                <strong>Email Sent!</strong> If an account with that email exists, we've sent you a password reset link.
                            </div>
                        <?php elseif ($_GET['message'] === 'error'): ?>
                            <div class="alert alert-danger" role="alert">
                                <strong>Error!</strong> There was a problem sending the reset email. Please try again.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <form id="forgotPasswordForm" method="post" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                   placeholder="Enter your email address">
                            <div class="form-text">We'll send a password reset link to this email.</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" name="send_reset">
                                📧 Send Reset Link
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-2">Remember your password?</p>
                        <a href="login.php" class="btn btn-outline-secondary btn-sm">
                            🔑 Back to Login
                        </a>
                    </div>

                    <div class="text-center mt-3">
                        <p class="mb-0">Don't have an account?</p>
                        <a href="signup.php" class="btn btn-link btn-sm">
                            📝 Sign Up Here
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Handle form submission
if (isset($_POST['send_reset'])) {
    $email = trim($_POST['email']);

    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Check if user exists
            require_once '../entities/PersonCredential.php';
            $user = PersonCredential::findByEmail($email);

            if ($user) {
                // Generate reset token
                $reset_token = bin2hex(random_bytes(32));
                $reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Save reset token to user record
                $user->password_reset_token = $reset_token;
                $user->password_reset_expires = $reset_expires;
                $user->save();

                // In a real application, you would send an email here
                // For this demo, we'll just show a success message

                // Simulate email sending
                $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=" . $reset_token;

                // Log the reset attempt (in production, send actual email)
                error_log("Password reset requested for: $email");
                error_log("Reset link: $reset_link");
            }

            // Always show success message for security (don't reveal if email exists)
            header('Location: forgot_password.php?message=sent');
            exit();

        } catch (Exception $e) {
            error_log("Forgot password error: " . $e->getMessage());
            header('Location: forgot_password.php?message=error');
            exit();
        }
    } else {
        header('Location: forgot_password.php?message=error');
        exit();
    }
}

require_once '../includes/footer.php';
require_once '../includes/scripts.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forgotPasswordForm');

    form.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();

        if (!email || !email.includes('@')) {
            e.preventDefault();
            alert('Please enter a valid email address.');
            return false;
        }
    });
});
</script>