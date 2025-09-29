<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sign Up - V4L Vocal 4 Local Community Platform">
    <meta name="author" content="V4L - Vocal 4 Local">
    <title>Sign Up - V4L</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="styles.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📝</text></svg>">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .signup-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .signup-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .brand-logo {
            font-size: 3rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .form-floating input,
        .form-floating select {
            border-radius: 10px;
        }

        .btn-signup {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 1rem;
            font-weight: 600;
            color: #6c757d;
            position: relative;
        }

        .step.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .step.completed {
            background: #28a745;
            color: white;
        }

        .step::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            width: 2rem;
            height: 2px;
            background: #e9ecef;
            transform: translateY(-50%);
        }

        .step:last-child::after {
            display: none;
        }

        .step.completed::after {
            background: #28a745;
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e9ecef;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: #dc3545; width: 25%; }
        .strength-fair { background: #fd7e14; width: 50%; }
        .strength-good { background: #ffc107; width: 75%; }
        .strength-strong { background: #28a745; width: 100%; }

        .security-questions {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
        }

        .divider span {
            background: rgba(255, 255, 255, 0.95);
            padding: 0 1rem;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .progress-bar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="signup-container">
            <div class="signup-card">
                <div class="card-body p-5">
                    <!-- Brand -->
                    <div class="brand-logo">📝</div>
                    <h2 class="text-center mb-1">Create Account</h2>
                    <p class="text-center text-muted mb-4">Join the V4L community</p>

                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step active" id="step1">1</div>
                        <div class="step" id="step2">2</div>
                        <div class="step" id="step3">3</div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress mb-4" style="height: 4px;">
                        <div class="progress-bar progress-bar-custom" id="progressBar" style="width: 33%"></div>
                    </div>

                    <!-- Signup Form -->
                    <form id="signupForm">
                        <!-- Step 1: Personal Information -->
                        <div class="form-step active" id="formStep1">
                            <h5 class="mb-3">Personal Information</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                               placeholder="First Name" required>
                                        <label for="first_name">First Name *</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                               placeholder="Last Name" required>
                                        <label for="last_name">Last Name *</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="Email" required>
                                <label for="email">Email Address *</label>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                               placeholder="Phone">
                                        <label for="phone">Phone Number</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                                        <label for="date_of_birth">Date of Birth</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="button" class="btn btn-signup" onclick="nextStep()">
                                    Continue to Account Setup
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Account Setup -->
                        <div class="form-step" id="formStep2">
                            <h5 class="mb-3">Account Setup</h5>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name="username"
                                       placeholder="Username" required pattern="[a-zA-Z0-9_.]+"
                                       maxlength="50">
                                <label for="username">Username *</label>
                                <div class="form-text">Letters, numbers, dots and underscores only</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Password" required minlength="8">
                                <label for="password">Password *</label>
                                <div class="password-strength">
                                    <div class="strength-bar">
                                        <div class="strength-fill" id="strengthFill"></div>
                                    </div>
                                    <small class="text-muted" id="strengthText">Choose a strong password</small>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                       placeholder="Confirm Password" required minlength="8">
                                <label for="confirm_password">Confirm Password *</label>
                                <div class="invalid-feedback">Passwords do not match</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-signup" onclick="nextStep()">
                                    Continue to Security
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep()">
                                    Back
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Security Questions -->
                        <div class="form-step" id="formStep3">
                            <h5 class="mb-3">Security Questions</h5>
                            <p class="text-muted mb-4">Set up security questions to help recover your account</p>

                            <div class="security-questions">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="security_question_1" name="security_question_1">
                                                <option value="">Select Question</option>
                                                <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                                                <option value="What city were you born in?">What city were you born in?</option>
                                                <option value="What was your mother's maiden name?">What was your mother's maiden name?</option>
                                                <option value="What was the name of your first school?">What was the name of your first school?</option>
                                                <option value="What is your favorite food?">What is your favorite food?</option>
                                            </select>
                                            <label for="security_question_1">Security Question 1</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="security_answer_1" name="security_answer_1">
                                            <label for="security_answer_1">Answer 1</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="security_question_2" name="security_question_2">
                                                <option value="">Select Question</option>
                                                <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                                                <option value="What city were you born in?">What city were you born in?</option>
                                                <option value="What was your mother's maiden name?">What was your mother's maiden name?</option>
                                                <option value="What was the name of your first school?">What was the name of your first school?</option>
                                                <option value="What is your favorite food?">What is your favorite food?</option>
                                            </select>
                                            <label for="security_question_2">Security Question 2</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="security_answer_2" name="security_answer_2">
                                            <label for="security_answer_2">Answer 2</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="security_question_3" name="security_question_3">
                                                <option value="">Select Question</option>
                                                <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                                                <option value="What city were you born in?">What city were you born in?</option>
                                                <option value="What was your mother's maiden name?">What was your mother's maiden name?</option>
                                                <option value="What was the name of your first school?">What was the name of your first school?</option>
                                                <option value="What is your favorite food?">What is your favorite food?</option>
                                            </select>
                                            <label for="security_question_3">Security Question 3</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="security_answer_3" name="security_answer_3">
                                            <label for="security_answer_3">Answer 3</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="terms.php" target="_blank">Terms of Service</a> and
                                    <a href="privacy.php" target="_blank">Privacy Policy</a> *
                                </label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-signup" id="signupBtn">
                                    <span id="signupText">Create Account</span>
                                    <span id="signupSpinner" class="spinner-border spinner-border-sm d-none ms-2"></span>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep()">
                                    Back
                                </button>
                            </div>
                        </div>

                        <!-- Error/Success Messages -->
                        <div id="messageContainer" class="mt-3"></div>
                    </form>

                    <!-- Divider -->
                    <div class="divider">
                        <span>Already have an account?</span>
                    </div>

                    <!-- Sign In Link -->
                    <div class="text-center">
                        <a href="login.php" class="btn btn-outline-primary rounded-pill px-4">
                            Sign In
                        </a>
                    </div>
                </div>
            </div>

            <!-- Additional Links -->
            <div class="text-center mt-4">
                <div class="text-white-50">
                    <a href="privacy.php" class="text-white-50 me-3">Privacy Policy</a>
                    <a href="terms.php" class="text-white-50">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

    <!-- Signup JavaScript -->
    <script>
        let currentStep = 1;
        const totalSteps = 3;

        document.addEventListener('DOMContentLoaded', function() {
            const signupForm = document.getElementById('signupForm');
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('confirm_password');

            // Password strength checking
            passwordField.addEventListener('input', checkPasswordStrength);
            confirmPasswordField.addEventListener('input', checkPasswordMatch);

            // Form submission
            signupForm.addEventListener('submit', handleSignup);

            // Focus on first field
            document.getElementById('first_name').focus();
        });

        function nextStep() {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    // Hide current step
                    document.getElementById(`formStep${currentStep}`).classList.remove('active');
                    document.getElementById(`step${currentStep}`).classList.remove('active');
                    document.getElementById(`step${currentStep}`).classList.add('completed');

                    currentStep++;

                    // Show next step
                    document.getElementById(`formStep${currentStep}`).classList.add('active');
                    document.getElementById(`step${currentStep}`).classList.add('active');

                    // Update progress bar
                    const progress = (currentStep / totalSteps) * 100;
                    document.getElementById('progressBar').style.width = `${progress}%`;
                }
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                // Hide current step
                document.getElementById(`formStep${currentStep}`).classList.remove('active');
                document.getElementById(`step${currentStep}`).classList.remove('active');

                currentStep--;

                // Show previous step
                document.getElementById(`formStep${currentStep}`).classList.add('active');
                document.getElementById(`step${currentStep}`).classList.remove('completed');
                document.getElementById(`step${currentStep}`).classList.add('active');

                // Update progress bar
                const progress = (currentStep / totalSteps) * 100;
                document.getElementById('progressBar').style.width = `${progress}%`;
            }
        }

        function validateCurrentStep() {
            const currentStepElement = document.getElementById(`formStep${currentStep}`);
            const requiredFields = currentStepElement.querySelectorAll('input[required], select[required]');

            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Additional validation for step 2
            if (currentStep === 2) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm_password').value;

                if (password !== confirmPassword) {
                    document.getElementById('confirm_password').classList.add('is-invalid');
                    isValid = false;
                }
            }

            return isValid;
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;
            let feedback = '';

            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            // Remove all strength classes
            strengthFill.className = 'strength-fill';

            switch(strength) {
                case 0:
                case 1:
                    strengthFill.classList.add('strength-weak');
                    feedback = 'Weak password';
                    break;
                case 2:
                    strengthFill.classList.add('strength-fair');
                    feedback = 'Fair password';
                    break;
                case 3:
                case 4:
                    strengthFill.classList.add('strength-good');
                    feedback = 'Good password';
                    break;
                case 5:
                    strengthFill.classList.add('strength-strong');
                    feedback = 'Strong password';
                    break;
            }

            strengthText.textContent = feedback;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const confirmField = document.getElementById('confirm_password');

            if (confirmPassword && password !== confirmPassword) {
                confirmField.classList.add('is-invalid');
            } else {
                confirmField.classList.remove('is-invalid');
            }
        }

        async function handleSignup(e) {
            e.preventDefault();

            if (!validateCurrentStep()) {
                return;
            }

            const formData = new FormData(signupForm);
            const signupData = {};

            // Convert form data to object
            for (let [key, value] of formData.entries()) {
                signupData[key] = value;
            }

            // Show loading state
            setLoadingState(true);
            clearMessages();

            try {
                const response = await fetch('/api/auth/signup', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(signupData)
                });

                const result = await response.json();

                if (result.success) {
                    showMessage('Account created successfully! Please check your email for verification.', 'success');

                    // Redirect after success
                    setTimeout(() => {
                        window.location.href = 'login.php?message=signup-success';
                    }, 2000);
                } else {
                    showMessage(result.message || 'Signup failed', 'danger');
                }
            } catch (error) {
                console.error('Signup error:', error);
                showMessage('Connection error. Please try again.', 'danger');
            } finally {
                setLoadingState(false);
            }
        }

        function setLoadingState(loading) {
            const signupBtn = document.getElementById('signupBtn');
            const signupText = document.getElementById('signupText');
            const signupSpinner = document.getElementById('signupSpinner');

            signupBtn.disabled = loading;
            if (loading) {
                signupText.textContent = 'Creating Account...';
                signupSpinner.classList.remove('d-none');
            } else {
                signupText.textContent = 'Create Account';
                signupSpinner.classList.add('d-none');
            }
        }

        function showMessage(message, type) {
            const alertClass = type === 'danger' ? 'alert-danger' :
                              type === 'warning' ? 'alert-warning' :
                              type === 'success' ? 'alert-success' : 'alert-info';

            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${escapeHtml(message)}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            document.getElementById('messageContainer').innerHTML = alertHtml;
        }

        function clearMessages() {
            document.getElementById('messageContainer').innerHTML = '';
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    </script>
</body>
</html>