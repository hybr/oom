<?php
require_once __DIR__ . '/../../../bootstrap.php';

Auth::requireGuest();

$pageTitle = 'Sign Up';
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);

require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">
                        <i class="bi bi-person-plus"></i> Create Account
                    </h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/auth/signup-process" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">

                        <!-- Personal Information Section -->
                        <h5 class="mb-3"><i class="bi bi-person-badge"></i> Personal Information</h5>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="name_prefix" class="form-label">Prefix</label>
                                <select class="form-select" id="name_prefix" name="name_prefix">
                                    <option value="">Select</option>
                                    <option value="Mr">Mr</option>
                                    <option value="Mrs">Mrs</option>
                                    <option value="Ms">Ms</option>
                                    <option value="Dr">Dr</option>
                                    <option value="Prof">Prof</option>
                                </select>
                            </div>

                            <div class="col-md-9 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required autofocus>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="primary_email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="primary_email" name="primary_email">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="primary_phone" class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="tel" class="form-control" id="primary_phone" name="primary_phone">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Account Credentials Section -->
                        <h5 class="mb-3"><i class="bi bi-shield-lock"></i> Account Credentials</h5>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-text">3-20 characters, letters and numbers only</div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required minlength="8">
                            </div>
                            <div class="form-text">At least 8 characters</div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Security Questions Section -->
                        <h5 class="mb-3"><i class="bi bi-shield-check"></i> Security Questions</h5>
                        <p class="text-muted small">These questions will help you recover your account if you forget your password.</p>

                        <div class="mb-3">
                            <label for="security_question_1" class="form-label">Security Question 1 <span class="text-danger">*</span></label>
                            <select class="form-select" id="security_question_1" name="security_question_1" required>
                                <option value="">Select a question</option>
                                <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                                <option value="What city were you born in?">What city were you born in?</option>
                                <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                                <option value="What was the name of your first school?">What was the name of your first school?</option>
                                <option value="What is your favorite book?">What is your favorite book?</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="security_answer_1" class="form-label">Answer 1 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="security_answer_1" name="security_answer_1" required>
                        </div>

                        <div class="mb-3">
                            <label for="security_question_2" class="form-label">Security Question 2 <span class="text-danger">*</span></label>
                            <select class="form-select" id="security_question_2" name="security_question_2" required>
                                <option value="">Select a question</option>
                                <option value="What was your childhood nickname?">What was your childhood nickname?</option>
                                <option value="What street did you grow up on?">What street did you grow up on?</option>
                                <option value="What is the name of your favorite teacher?">What is the name of your favorite teacher?</option>
                                <option value="What was your first car model?">What was your first car model?</option>
                                <option value="What is your favorite movie?">What is your favorite movie?</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="security_answer_2" class="form-label">Answer 2 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="security_answer_2" name="security_answer_2" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="/terms" target="_blank">Terms of Service</a>
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus"></i> Create Account
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <p class="text-center mb-0">
                        Already have an account? <a href="/auth/login">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
