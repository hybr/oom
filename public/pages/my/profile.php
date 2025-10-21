<?php
require_once __DIR__ . '/../../../bootstrap.php';

Auth::requireAuth();

// Get logged-in user's credential and person data
$userId = Auth::id();
$credential = Auth::user();

// Get person data
$sql = "SELECT * FROM person WHERE id = ? AND deleted_at IS NULL";
$person = Database::fetchOne($sql, [$credential['person_id']]);

if (!$person) {
    $_SESSION['error'] = 'Person profile not found.';
    Router::redirect('/dashboard');
    exit;
}

$pageTitle = 'My Profile';
require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-person-circle"></i> My Profile</h2>
                <a href="/entities/person/edit/<?= htmlspecialchars($person['id']) ?>" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Profile
                </a>
            </div>

            <!-- Basic Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-badge"></i> Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <?php if (!empty($person['name_prefix'])): ?>
                                <dt class="col-sm-4">Prefix:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($person['name_prefix']) ?></dd>
                                <?php endif; ?>

                                <dt class="col-sm-4">First Name:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($person['first_name']) ?></dd>

                                <?php if (!empty($person['middle_name'])): ?>
                                <dt class="col-sm-4">Middle Name:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($person['middle_name']) ?></dd>
                                <?php endif; ?>

                                <dt class="col-sm-4">Last Name:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($person['last_name']) ?></dd>

                                <?php if (!empty($person['name_suffix'])): ?>
                                <dt class="col-sm-4">Suffix:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($person['name_suffix']) ?></dd>
                                <?php endif; ?>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <?php if (!empty($person['gender'])): ?>
                                <dt class="col-sm-4">Gender:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($person['gender']) ?></dd>
                                <?php endif; ?>

                                <?php if (!empty($person['date_of_birth'])): ?>
                                <dt class="col-sm-4">Date of Birth:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($person['date_of_birth']) ?></dd>
                                <?php endif; ?>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-telephone"></i> Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <?php if (!empty($person['primary_email'])): ?>
                                <dt class="col-sm-4">Email:</dt>
                                <dd class="col-sm-8">
                                    <a href="mailto:<?= htmlspecialchars($person['primary_email']) ?>">
                                        <?= htmlspecialchars($person['primary_email']) ?>
                                    </a>
                                </dd>
                                <?php endif; ?>

                                <?php if (!empty($person['primary_phone'])): ?>
                                <dt class="col-sm-4">Phone:</dt>
                                <dd class="col-sm-8">
                                    <a href="tel:<?= htmlspecialchars($person['primary_phone']) ?>">
                                        <?= htmlspecialchars($person['primary_phone']) ?>
                                    </a>
                                </dd>
                                <?php endif; ?>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Username:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($credential['username']) ?></dd>

                                <dt class="col-sm-4">Email:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($credential['email']) ?></dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <?php if (!empty($credential['last_login_at'])): ?>
                                <dt class="col-sm-4">Last Login:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($credential['last_login_at']) ?></dd>
                                <?php endif; ?>

                                <dt class="col-sm-4">Account Created:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($credential['created_at']) ?></dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-key"></i> Change Password
                        </a>
                    </div>
                </div>
            </div>

            <?php
            // Get person's education records with organization and education level names
            $sql = "SELECT
                        pe.*,
                        o.short_name as institution,
                        eel.name as education_level_name
                    FROM person_education pe
                    LEFT JOIN organization o ON pe.organization_id = o.id
                    LEFT JOIN enum_education_level eel ON pe.education_level = eel.id
                    WHERE pe.person_id = ? AND pe.deleted_at IS NULL
                    ORDER BY pe.start_date DESC";
            $educations = Database::fetchAll($sql, [$person['id']]);
            ?>

            <!-- Education History Card -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-mortarboard"></i> Education History</h5>
                        <a href="/entities/person_education/create" class="btn btn-sm btn-dark">
                            <i class="bi bi-plus"></i> Add Education
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($educations)): ?>
                        <p class="text-muted">No education records found.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Institution</th>
                                        <th>Level</th>
                                        <th>Start Date</th>
                                        <th>Completion Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($educations as $edu): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($edu['institution'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($edu['education_level_name'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($edu['start_date'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($edu['complete_date'] ?? 'In Progress') ?></td>
                                        <td>
                                            <a href="/entities/person_education/detail/<?= htmlspecialchars($edu['id']) ?>" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="/entities/person_education/edit/<?= htmlspecialchars($edu['id']) ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php
            // Get person's skills with organization and level names
            $sql = "SELECT ps.*,
                        sk.name as skill_name,
                        o.short_name as institution,
                        esl.name as level_name
                    FROM person_skill ps
                    LEFT JOIN popular_skill sk ON ps.subject_id = sk.id
                    LEFT JOIN organization o ON ps.organization_id = o.id
                    LEFT JOIN enum_skill_level esl ON ps.level = esl.id
                    WHERE ps.person_id = ? AND ps.deleted_at IS NULL
                    ORDER BY ps.start_date DESC";
            $skills = Database::fetchAll($sql, [$person['id']]);
            ?>

            <!-- Skills Card -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-tools"></i> Skills & Certifications</h5>
                        <a href="/entities/person_skill/create" class="btn btn-sm btn-light">
                            <i class="bi bi-plus"></i> Add Skill
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($skills)): ?>
                        <p class="text-muted">No skills recorded.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Skill</th>
                                        <th>Level</th>
                                        <th>Institution</th>
                                        <th>Duration</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($skills as $skill): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($skill['skill_name'] ?? 'N/A') ?></td>
                                        <td>
                                            <?php if (!empty($skill['level_name'])): ?>
                                                <span class="badge bg-primary"><?= htmlspecialchars($skill['level_name']) ?></span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($skill['institution'] ?? '-') ?></td>
                                        <td>
                                            <?= htmlspecialchars($skill['start_date'] ?? '-') ?>
                                            to
                                            <?= htmlspecialchars($skill['complete_date'] ?? 'Present') ?>
                                        </td>
                                        <td>
                                            <a href="/entities/person_skill/detail/<?= htmlspecialchars($skill['id']) ?>" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="/entities/person_skill/edit/<?= htmlspecialchars($skill['id']) ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- System Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> System Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-2">Person ID:</dt>
                        <dd class="col-sm-4"><code><?= htmlspecialchars($person['id']) ?></code></dd>

                        <dt class="col-sm-2">Created:</dt>
                        <dd class="col-sm-4"><?= htmlspecialchars($person['created_at']) ?></dd>

                        <dt class="col-sm-2">Last Updated:</dt>
                        <dd class="col-sm-4"><?= htmlspecialchars($person['updated_at']) ?></dd>

                        <dt class="col-sm-2">Version:</dt>
                        <dd class="col-sm-4"><?= htmlspecialchars($person['version_no'] ?? '1') ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm" method="POST" action="/my/change-password">
                    <input type="hidden" name="csrf_token" value="<?= Auth::generateCsrfToken() ?>">

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                        <div class="form-text">Minimum 8 characters</div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
