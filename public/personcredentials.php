<?php
require_once '../includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">🔐 User Management</h1>
            <p class="text-muted">Manage user credentials, security settings, and authentication</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <button id="newCredentialBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCredentialModal">
                    ➕ Add User
                </button>
                <a href="login.php" class="btn btn-outline-secondary">
                    🔑 Login Page
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Total Users</h6>
                            <h4 class="card-title mb-0" id="totalCredentials">0</h4>
                        </div>
                        <div class="stats-icon">👥</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Active Users</h6>
                            <h4 class="card-title mb-0" id="activeCredentials">0</h4>
                        </div>
                        <div class="stats-icon">✅</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Locked Users</h6>
                            <h4 class="card-title mb-0" id="lockedCredentials">0</h4>
                        </div>
                        <div class="stats-icon">🔒</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Recent Logins</h6>
                            <h4 class="card-title mb-0" id="recentLogins">0</h4>
                        </div>
                        <div class="stats-icon">🕐</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Failed Attempts</h6>
                            <h4 class="card-title mb-0" id="failedAttempts">0</h4>
                        </div>
                        <div class="stats-icon">⚠️</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Password Resets</h6>
                            <h4 class="card-title mb-0" id="passwordResets">0</h4>
                        </div>
                        <div class="stats-icon">🔄</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="row mb-3">
        <div class="col-lg-4 col-12 mb-2 mb-lg-0">
            <div class="input-group">
                <span class="input-group-text">🔍</span>
                <input type="text" id="searchCredentials" class="form-control" placeholder="Search usernames, persons...">
            </div>
        </div>
        <div class="col-lg-8 col-12">
            <div class="d-flex gap-2 flex-wrap">
                <select id="statusFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="locked">Locked</option>
                </select>
                <select id="securityFilter" class="form-select" style="max-width: 160px;">
                    <option value="">All Security</option>
                    <option value="complete">Complete Security</option>
                    <option value="incomplete">Incomplete Security</option>
                </select>
                <select id="loginFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Logins</option>
                    <option value="recent">Recent Logins</option>
                    <option value="never">Never Logged In</option>
                </select>
                <button id="exportBtn" class="btn btn-outline-secondary">
                    📊 Export
                </button>
            </div>
        </div>
    </div>

    <!-- Credentials Display -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">User Credentials</h5>
                    <small class="text-muted" id="credentialCount">0 users</small>
                </div>
                <div class="card-body">
                    <div id="credentialsDisplay">
                        <div class="text-center py-4">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 text-muted">Loading user credentials...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Credential Modal -->
<div class="modal fade" id="addCredentialModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User Credential</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCredentialForm">
                <div class="modal-body">
                    <!-- Person Selection -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="person_id" class="form-label">Person *</label>
                                <select class="form-select" id="person_id" name="person_id" required>
                                    <option value="">Select Person</option>
                                </select>
                                <div class="form-text">Select the person to create credentials for</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" required
                                       pattern="[a-zA-Z0-9_.]+" maxlength="50">
                                <div class="form-text">Letters, numbers, dots and underscores only</div>
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required
                                           minlength="8">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        👁️
                                    </button>
                                </div>
                                <div class="form-text">Minimum 8 characters</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required
                                       minlength="8">
                                <div class="invalid-feedback">Passwords do not match</div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Questions -->
                    <h6 class="mb-3 mt-4">Security Questions</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="security_question_1" class="form-label">Security Question 1</label>
                                <select class="form-select" id="security_question_1" name="security_question_1">
                                    <option value="">Select Question</option>
                                    <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                                    <option value="What city were you born in?">What city were you born in?</option>
                                    <option value="What was your mother's maiden name?">What was your mother's maiden name?</option>
                                    <option value="What was the name of your first school?">What was the name of your first school?</option>
                                    <option value="What is your favorite food?">What is your favorite food?</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="security_answer_1" class="form-label">Answer 1</label>
                                <input type="text" class="form-control" id="security_answer_1" name="security_answer_1">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="security_question_2" class="form-label">Security Question 2</label>
                                <select class="form-select" id="security_question_2" name="security_question_2">
                                    <option value="">Select Question</option>
                                    <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                                    <option value="What city were you born in?">What city were you born in?</option>
                                    <option value="What was your mother's maiden name?">What was your mother's maiden name?</option>
                                    <option value="What was the name of your first school?">What was the name of your first school?</option>
                                    <option value="What is your favorite food?">What is your favorite food?</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="security_answer_2" class="form-label">Answer 2</label>
                                <input type="text" class="form-control" id="security_answer_2" name="security_answer_2">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="security_question_3" class="form-label">Security Question 3</label>
                                <select class="form-select" id="security_question_3" name="security_question_3">
                                    <option value="">Select Question</option>
                                    <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                                    <option value="What city were you born in?">What city were you born in?</option>
                                    <option value="What was your mother's maiden name?">What was your mother's maiden name?</option>
                                    <option value="What was the name of your first school?">What was the name of your first school?</option>
                                    <option value="What is your favorite food?">What is your favorite food?</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="security_answer_3" class="form-label">Answer 3</label>
                                <input type="text" class="form-control" id="security_answer_3" name="security_answer_3">
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Active Account
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Credential Modal -->
<div class="modal fade" id="editCredentialModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User Credential</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCredentialForm">
                <input type="hidden" id="editCredentialId" name="id">
                <div class="modal-body">
                    <!-- Person and Username (Read-only) -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editPersonName" class="form-label">Person</label>
                                <input type="text" class="form-control" id="editPersonName" readonly>
                                <input type="hidden" id="editPersonId" name="person_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editUsername" class="form-label">Username</label>
                                <input type="text" class="form-control" id="editUsername" name="username" readonly>
                                <div class="form-text">Username cannot be changed</div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Reset -->
                    <h6 class="mb-3 mt-4">Password Management</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editNewPassword" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="editNewPassword" name="new_password"
                                           minlength="8">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleEditPassword">
                                        👁️
                                    </button>
                                </div>
                                <div class="form-text">Leave empty to keep current password</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editConfirmPassword" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="editConfirmPassword" name="confirm_password"
                                       minlength="8">
                                <div class="invalid-feedback">Passwords do not match</div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Questions -->
                    <h6 class="mb-3 mt-4">Security Questions</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editSecurityQuestion1" class="form-label">Security Question 1</label>
                                <select class="form-select" id="editSecurityQuestion1" name="security_question_1">
                                    <option value="">Select Question</option>
                                    <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                                    <option value="What city were you born in?">What city were you born in?</option>
                                    <option value="What was your mother's maiden name?">What was your mother's maiden name?</option>
                                    <option value="What was the name of your first school?">What was the name of your first school?</option>
                                    <option value="What is your favorite food?">What is your favorite food?</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editSecurityAnswer1" class="form-label">Answer 1</label>
                                <input type="text" class="form-control" id="editSecurityAnswer1" name="security_answer_1">
                                <div class="form-text">Leave empty to keep current answer</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editSecurityQuestion2" class="form-label">Security Question 2</label>
                                <select class="form-select" id="editSecurityQuestion2" name="security_question_2">
                                    <option value="">Select Question</option>
                                    <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                                    <option value="What city were you born in?">What city were you born in?</option>
                                    <option value="What was your mother's maiden name?">What was your mother's maiden name?</option>
                                    <option value="What was the name of your first school?">What was the name of your first school?</option>
                                    <option value="What is your favorite food?">What is your favorite food?</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editSecurityAnswer2" class="form-label">Answer 2</label>
                                <input type="text" class="form-control" id="editSecurityAnswer2" name="security_answer_2">
                                <div class="form-text">Leave empty to keep current answer</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editSecurityQuestion3" class="form-label">Security Question 3</label>
                                <select class="form-select" id="editSecurityQuestion3" name="security_question_3">
                                    <option value="">Select Question</option>
                                    <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                                    <option value="What city were you born in?">What city were you born in?</option>
                                    <option value="What was your mother's maiden name?">What was your mother's maiden name?</option>
                                    <option value="What was the name of your first school?">What was the name of your first school?</option>
                                    <option value="What is your favorite food?">What is your favorite food?</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editSecurityAnswer3" class="form-label">Answer 3</label>
                                <input type="text" class="form-control" id="editSecurityAnswer3" name="security_answer_3">
                                <div class="form-text">Leave empty to keep current answer</div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Management -->
                    <h6 class="mb-3 mt-4">Account Management</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editIsActive" name="is_active">
                                <label class="form-check-label" for="editIsActive">
                                    Active Account
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-warning btn-sm" id="resetLoginAttemptsBtn">
                                🔓 Reset Login Attempts
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-info btn-sm" id="generateResetTokenBtn">
                                🔄 Generate Reset Token
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Credential Modal -->
<div class="modal fade" id="viewCredentialModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🔐 User Credential Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-8 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">User Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-5">Username:</dt>
                                            <dd class="col-sm-7" id="viewUsername">-</dd>

                                            <dt class="col-sm-5">Person:</dt>
                                            <dd class="col-sm-7" id="viewPersonName">-</dd>

                                            <dt class="col-sm-5">Status:</dt>
                                            <dd class="col-sm-7" id="viewStatus">-</dd>

                                            <dt class="col-sm-5">Last Login:</dt>
                                            <dd class="col-sm-7" id="viewLastLogin">-</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-5">Login Attempts:</dt>
                                            <dd class="col-sm-7" id="viewLoginAttempts">-</dd>

                                            <dt class="col-sm-5">Locked Until:</dt>
                                            <dd class="col-sm-7" id="viewLockedUntil">-</dd>

                                            <dt class="col-sm-5">Created:</dt>
                                            <dd class="col-sm-7" id="viewCreatedAt">-</dd>

                                            <dt class="col-sm-5">Updated:</dt>
                                            <dd class="col-sm-7" id="viewUpdatedAt">-</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-primary btn-sm" id="viewEditBtn">
                                        ✏️ Edit User
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" id="viewResetPasswordBtn">
                                        🔑 Reset Password
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm" id="viewUnlockBtn">
                                        🔓 Unlock Account
                                    </button>
                                    <hr>
                                    <button type="button" class="btn btn-outline-danger btn-sm" id="viewDeleteBtn">
                                        🗑️ Delete User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Questions -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Security Questions</h6>
                            </div>
                            <div class="card-body">
                                <div id="viewSecurityQuestions">
                                    <p class="text-muted">No security questions configured</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Reset Token -->
                <div class="row mt-3" id="passwordResetSection" style="display: none;">
                    <div class="col-12">
                        <div class="card border-warning">
                            <div class="card-header bg-warning bg-opacity-10">
                                <h6 class="card-title mb-0 text-warning">Password Reset Token</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <strong>Token:</strong> <code id="viewResetToken">-</code><br>
                                    <strong>Expires:</strong> <span id="viewResetExpires">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
require_once '../includes/scripts.php';
?>