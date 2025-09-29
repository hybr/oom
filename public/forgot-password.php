<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Forgot Password - V4L Vocal 4 Local Community Platform">
    <meta name="author" content="V4L - Vocal 4 Local">
    <title>Forgot Password - V4L</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="styles.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🔄</text></svg>">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .reset-container {
            max-width: 500px;
            margin: 0 auto;
        }

        .reset-card {
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

        .btn-reset {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .method-selector {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .method-option {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .method-option:hover {
            border-color: #667eea;
        }

        .method-option.selected {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .security-questions {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

        .progress-step {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 0.5rem;
            font-weight: 600;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .step-number.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .step-line {
            width: 50px;
            height: 2px;
            background: #e9ecef;
        }

        .back-link {
            color: #6c757d;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .back-link:hover {
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="reset-container">
            <div class="reset-card">
                <div class="card-body p-5">
                    <!-- Brand -->
                    <div class="brand-logo">🔄</div>
                    <h2 class="text-center mb-1">Reset Password</h2>
                    <p class="text-center text-muted mb-4">Choose how you want to reset your password</p>

                    <!-- Progress Steps -->
                    <div class="progress-step">
                        <div class="step-number active" id="stepNum1">1</div>
                        <div class="step-line"></div>
                        <div class="step-number" id="stepNum2">2</div>
                        <div class="step-line"></div>
                        <div class="step-number" id="stepNum3">3</div>
                    </div>

                    <!-- Step 1: Username -->
                    <div class="step-content active" id="step1">
                        <form id="usernameForm">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="username" name="username"
                                       placeholder="Username" required>
                                <label for="username">Username</label>
                                <div class="form-text">Enter your username to continue</div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-reset" id="usernameBtn">
                                    <span id="usernameText">Continue</span>
                                    <span id="usernameSpinner" class="spinner-border spinner-border-sm d-none ms-2"></span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Step 2: Reset Method -->
                    <div class="step-content" id="step2">
                        <h5 class="mb-3">Choose Reset Method</h5>
                        <p class="text-muted mb-4">How would you like to reset your password?</p>

                        <div class="method-selector">
                            <div class="method-option" data-method="email" onclick="selectMethod('email')">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">📧</div>
                                    <div>
                                        <h6 class="mb-1">Email Reset Link</h6>
                                        <small class="text-muted">We'll send a reset link to your email</small>
                                    </div>
                                </div>
                            </div>

                            <div class="method-option" data-method="security" onclick="selectMethod('security')">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">🔐</div>
                                    <div>
                                        <h6 class="mb-1">Security Questions</h6>
                                        <small class="text-muted">Answer your security questions</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-reset" id="methodBtn" onclick="proceedWithMethod()" disabled>
                                Continue
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="goToStep(1)">
                                Back
                            </button>
                        </div>
                    </div>

                    <!-- Step 3a: Email Reset -->
                    <div class="step-content" id="step3email">
                        <div class="text-center">
                            <div class="mb-4">
                                <div style="font-size: 4rem;">📧</div>
                                <h5 class="mt-3">Check Your Email</h5>
                                <p class="text-muted">
                                    We've sent a password reset link to your email address.
                                    Click the link in the email to reset your password.
                                </p>
                            </div>

                            <div class="alert alert-info">
                                <small>
                                    <strong>Didn't receive the email?</strong><br>
                                    Check your spam folder or wait a few minutes for it to arrive.
                                </small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary" onclick="resendEmail()">
                                    Resend Email
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="goToStep(2)">
                                    Try Another Method
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3b: Security Questions -->
                    <div class="step-content" id="step3security">
                        <h5 class="mb-3">Security Questions</h5>
                        <p class="text-muted mb-4">Answer at least 2 out of 3 security questions correctly</p>

                        <form id="securityForm">
                            <div class="security-questions">
                                <div id="securityQuestionsContainer">
                                    <!-- Security questions will be loaded here -->
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="new_password" name="new_password"
                                           placeholder="New Password" required minlength="8">
                                    <label for="new_password">New Password</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password"
                                           placeholder="Confirm New Password" required minlength="8">
                                    <label for="confirm_new_password">Confirm New Password</label>
                                    <div class="invalid-feedback">Passwords do not match</div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-reset" id="securityBtn">
                                    <span id="securityText">Reset Password</span>
                                    <span id="securitySpinner" class="spinner-border spinner-border-sm d-none ms-2"></span>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="goToStep(2)">
                                    Back
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Error/Success Messages -->
                    <div id="messageContainer" class="mt-3"></div>

                    <!-- Back to Login -->
                    <div class="text-center mt-4">
                        <a href="login.php" class="back-link">
                            ← Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

    <!-- Reset Password JavaScript -->
    <script>
        let currentStep = 1;
        let selectedMethod = null;
        let userInfo = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Form submissions
            document.getElementById('usernameForm').addEventListener('submit', handleUsernameSubmit);
            document.getElementById('securityForm').addEventListener('submit', handleSecuritySubmit);

            // Password confirmation
            document.getElementById('confirm_new_password').addEventListener('input', checkPasswordMatch);

            // Focus on username field
            document.getElementById('username').focus();
        });

        async function handleUsernameSubmit(e) {
            e.preventDefault();

            const username = document.getElementById('username').value;
            setUsernameLoading(true);
            clearMessages();

            try {
                const response = await fetch('/api/auth/forgot-password/check-user', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username })
                });

                const result = await response.json();

                if (result.success) {
                    userInfo = result.data;
                    goToStep(2);
                } else {
                    showMessage(result.message || 'User not found', 'danger');
                }
            } catch (error) {
                console.error('Username check error:', error);
                showMessage('Connection error. Please try again.', 'danger');
            } finally {
                setUsernameLoading(false);
            }
        }

        function selectMethod(method) {
            selectedMethod = method;

            // Update UI
            document.querySelectorAll('.method-option').forEach(option => {
                option.classList.remove('selected');
            });
            document.querySelector(`[data-method="${method}"]`).classList.add('selected');

            // Enable continue button
            document.getElementById('methodBtn').disabled = false;
        }

        async function proceedWithMethod() {
            if (!selectedMethod) return;

            if (selectedMethod === 'email') {
                await sendEmailReset();
            } else if (selectedMethod === 'security') {
                await loadSecurityQuestions();
            }
        }

        async function sendEmailReset() {
            try {
                const response = await fetch('/api/auth/forgot-password/email', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: userInfo.username })
                });

                const result = await response.json();

                if (result.success) {
                    goToStep('3email');
                } else {
                    showMessage(result.message || 'Failed to send email', 'danger');
                }
            } catch (error) {
                console.error('Email reset error:', error);
                showMessage('Connection error. Please try again.', 'danger');
            }
        }

        async function loadSecurityQuestions() {
            try {
                const response = await fetch(`/api/auth/forgot-password/security-questions/${userInfo.username}`);
                const result = await response.json();

                if (result.success && result.data.length > 0) {
                    displaySecurityQuestions(result.data);
                    goToStep('3security');
                } else {
                    showMessage('No security questions found for this account', 'warning');
                }
            } catch (error) {
                console.error('Security questions error:', error);
                showMessage('Connection error. Please try again.', 'danger');
            }
        }

        function displaySecurityQuestions(questions) {
            const container = document.getElementById('securityQuestionsContainer');
            container.innerHTML = '';

            questions.forEach((question, index) => {
                if (question.question) {
                    const questionHtml = `
                        <div class="mb-3">
                            <label class="form-label fw-bold">${escapeHtml(question.question)}</label>
                            <input type="text" class="form-control" name="security_answer_${index + 1}"
                                   placeholder="Your answer" required>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', questionHtml);
                }
            });
        }

        async function handleSecuritySubmit(e) {
            e.preventDefault();

            if (!checkPasswordMatch()) {
                return;
            }

            const formData = new FormData(e.target);
            const securityData = {
                username: userInfo.username,
                new_password: formData.get('new_password'),
                security_answers: {}
            };

            // Collect security answers
            for (let i = 1; i <= 3; i++) {
                const answer = formData.get(`security_answer_${i}`);
                if (answer) {
                    securityData.security_answers[i] = answer;
                }
            }

            setSecurityLoading(true);
            clearMessages();

            try {
                const response = await fetch('/api/auth/forgot-password/reset', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(securityData)
                });

                const result = await response.json();

                if (result.success) {
                    showMessage('Password reset successfully! Redirecting to login...', 'success');
                    setTimeout(() => {
                        window.location.href = 'login.php?message=password-reset-success';
                    }, 2000);
                } else {
                    showMessage(result.message || 'Password reset failed', 'danger');
                }
            } catch (error) {
                console.error('Security reset error:', error);
                showMessage('Connection error. Please try again.', 'danger');
            } finally {
                setSecurityLoading(false);
            }
        }

        async function resendEmail() {
            try {
                const response = await fetch('/api/auth/forgot-password/resend-email', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: userInfo.username })
                });

                const result = await response.json();

                if (result.success) {
                    showMessage('Email sent again!', 'success');
                } else {
                    showMessage(result.message || 'Failed to resend email', 'danger');
                }
            } catch (error) {
                console.error('Resend email error:', error);
                showMessage('Connection error. Please try again.', 'danger');
            }
        }

        function goToStep(step) {
            // Hide all steps
            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.remove('active');
            });

            // Reset step numbers
            document.querySelectorAll('.step-number').forEach(num => {
                num.classList.remove('active');
            });

            // Show target step
            if (typeof step === 'number') {
                document.getElementById(`step${step}`).classList.add('active');
                document.getElementById(`stepNum${step}`).classList.add('active');
                currentStep = step;
            } else {
                document.getElementById(`step${step}`).classList.add('active');
                if (step === '3email' || step === '3security') {
                    document.getElementById('stepNum3').classList.add('active');
                    currentStep = 3;
                }
            }

            clearMessages();
        }

        function checkPasswordMatch() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_new_password').value;
            const confirmField = document.getElementById('confirm_new_password');

            if (confirmPassword && password !== confirmPassword) {
                confirmField.classList.add('is-invalid');
                return false;
            } else {
                confirmField.classList.remove('is-invalid');
                return true;
            }
        }

        function setUsernameLoading(loading) {
            const btn = document.getElementById('usernameBtn');
            const text = document.getElementById('usernameText');
            const spinner = document.getElementById('usernameSpinner');

            btn.disabled = loading;
            if (loading) {
                text.textContent = 'Checking...';
                spinner.classList.remove('d-none');
            } else {
                text.textContent = 'Continue';
                spinner.classList.add('d-none');
            }
        }

        function setSecurityLoading(loading) {
            const btn = document.getElementById('securityBtn');
            const text = document.getElementById('securityText');
            const spinner = document.getElementById('securitySpinner');

            btn.disabled = loading;
            if (loading) {
                text.textContent = 'Resetting...';
                spinner.classList.remove('d-none');
            } else {
                text.textContent = 'Reset Password';
                spinner.classList.add('d-none');
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