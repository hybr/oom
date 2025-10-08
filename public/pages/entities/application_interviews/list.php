<?php
require_once __DIR__ . '/../../../../bootstrap.php';

use Entities\ApplicationInterview;
$pageTitle = 'Application Interviews';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;
$interviews = ApplicationInterview::all($perPage, $offset);
$total = ApplicationInterview::count();
$totalPages = ceil($total / $perPage);
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-calendar-event"></i> Application Interviews</h1>
                <a href="/application_interviews/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Interview</a>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-9">
                            <select name="per_page" class="form-select">
                                <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10 per page</option>
                                <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25 per page</option>
                                <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50 per page</option>
                                <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100 per page</option>
                            </select>
                        </div>
                        <div class="col-md-3"><button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search"></i> Apply</button></div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h5 class="mb-0">All Interviews (<?= $total ?>)</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr><th>ID</th><th>Application</th><th>Stage</th><th>Interviewer</th><th>Scheduled</th><th>Status</th><th>Rating</th><th>Actions</th></tr>
                            </thead>
                            <tbody>
                                <?php if (empty($interviews)): ?>
                                    <tr><td colspan="8" class="text-center text-muted py-4"><i class="bi bi-inbox"></i> No interviews found</td></tr>
                                <?php else: ?>
                                    <?php foreach ($interviews as $int): ?>
                                        <tr>
                                            <td><?= $int->id ?></td>
                                            <td><?php $app = $int->getApplication(); echo $app ? '<a href="/vacancy_applications/' . $app->id . '">App #' . $app->id . '</a>' : '-'; ?></td>
                                            <td><?php $stage = $int->getStage(); echo $stage ? htmlspecialchars($stage->name) : '-'; ?></td>
                                            <td><?php $interviewer = $int->getInterviewer(); echo $interviewer ? htmlspecialchars($interviewer->first_name . ' ' . $interviewer->last_name) : '-'; ?></td>
                                            <td><?= $int->scheduled_date ? date('M d, Y', strtotime($int->scheduled_date)) : '-' ?></td>
                                            <td><?php $sc = ['Scheduled'=>'info', 'Completed'=>'success', 'Cancelled'=>'danger']; $c = $sc[$int->status] ?? 'secondary'; ?>
                                                <span class="badge bg-<?= $c ?>"><?= htmlspecialchars($int->status) ?></span></td>
                                            <td><?= $int->rating ? $int->rating . '/10' : '-' ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/application_interviews/<?= $int->id ?>" class="btn btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                                    <a href="/application_interviews/<?= $int->id ?>/edit" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                                    <form method="POST" action="/application_interviews/<?= $int->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                                        <?= csrf_field() ?><button type="submit" class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php $currentPage = $page; $baseUrl = '/application_interviews'; include __DIR__ . '/../../../../views/components/pagination.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
