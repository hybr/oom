<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - V4L Vocal 4 Local Community Platform">
    <meta name="author" content="V4L - Vocal 4 Local">
    <title>Login - V4L</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="styles.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🔑</text></svg>">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-container {
            max-width: 400px;
            margin: 0 auto;
        }

        .login-card {
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

        .form-floating input {
            border-radius: 10px;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .security-badge {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.2);
            border-radius: 10px;
            padding: 0.5rem;
            font-size: 0.875rem;
            color: #28a745;
        }

        .forgot-link {
            color: #6c757d;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .forgot-link:hover {
            color: #495057;
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

        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-card">
                <div class="card-body p-5">
                    <!-- Brand -->
                    <div class="brand-logo">🔑</div>
                    <h2 class="text-center mb-1">Welcome Back</h2>
                    <p class="text-center text-muted mb-4">Sign in to your V4L account</p>

                    <!-- Login Form -->
                    <form id="loginForm">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username"
                                   placeholder="Username" required autocomplete="username">
                            <label for="username">Username</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="Password" required autocomplete="current-password">
                            <label for="password">Password</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                            <a href="forgot-password.php" class="forgot-link">Forgot password?</a>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-login" id="loginBtn">
                                <span id="loginText">Sign In</span>
                                <span id="loginSpinner" class="spinner-border spinner-border-sm d-none ms-2"></span>
                            </button>
                        </div>

                        <!-- Security Badge -->
                        <div class="security-badge text-center mb-3">
                            <small>🔒 Secure login with advanced protection</small>
                        </div>

                        <!-- Error/Success Messages -->
                        <div id="messageContainer"></div>
                    </form>

                    <!-- Divider -->
                    <div class="divider">
                        <span>New to V4L?</span>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="text-center">
                        <a href="signup.php" class="btn btn-outline-primary rounded-pill px-4">
                            Create Account
                        </a>
                    </div>

                    <!-- Back to App -->
                    <div class="text-center mt-3">
                        <a href="index.php" class="forgot-link">
                            ← Back to Dashboard
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

    <!-- Login JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const loginText = document.getElementById('loginText');
            const loginSpinner = document.getElementById('loginSpinner');
            const messageContainer = document.getElementById('messageContainer');

            // Handle form submission
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(loginForm);
                const loginData = {
                    username: formData.get('username'),
                    password: formData.get('password'),
                    rememberMe: formData.get('rememberMe') === 'on'
                };

                // Show loading state
                setLoadingState(true);
                clearMessages();

                try {
                    const response = await fetch('/api/auth/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(loginData)
                    });

                    const result = await response.json();

                    if (result.success) {
                        showMessage('Login successful! Redirecting...', 'success');

                        // Store session data if needed
                        if (result.token) {
                            localStorage.setItem('authToken', result.token);
                        }

                        // Redirect after success
                        setTimeout(() => {
                            window.location.href = result.redirect || 'index.php';
                        }, 1000);
                    } else {
                        showMessage(result.message || 'Login failed', 'danger');

                        // Handle specific error cases
                        if (result.locked) {
                            showMessage('Account is temporarily locked. Please try again later.', 'warning');
                        }
                    }
                } catch (error) {
                    console.error('Login error:', error);
                    showMessage('Connection error. Please try again.', 'danger');
                } finally {
                    setLoadingState(false);
                }
            });

            // Utility functions
            function setLoadingState(loading) {
                loginBtn.disabled = loading;
                if (loading) {
                    loginText.textContent = 'Signing in...';
                    loginSpinner.classList.remove('d-none');
                } else {
                    loginText.textContent = 'Sign In';
                    loginSpinner.classList.add('d-none');
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

                messageContainer.innerHTML = alertHtml;
            }

            function clearMessages() {
                messageContainer.innerHTML = '';
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

            // Focus on username field
            document.getElementById('username').focus();

            // Handle enter key in password field
            document.getElementById('password').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    loginForm.dispatchEvent(new Event('submit'));
                }
            });
        });
    </script>
</body>
</html>