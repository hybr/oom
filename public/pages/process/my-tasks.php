<?php
$pageTitle = 'My Tasks';
require_once __DIR__ . '/../../../bootstrap.php';

Auth::requireAuth();

require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-5">
    <h1><i class="bi bi-list-task"></i> My Tasks</h1>
    <p class="lead">Tasks assigned to you from running processes.</p>

    <div class="row mt-4">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="taskTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                        Pending <span class="badge bg-primary" id="pending-count">0</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="inprogress-tab" data-bs-toggle="tab" data-bs-target="#inprogress" type="button">
                        In Progress <span class="badge bg-warning" id="inprogress-count">0</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button">
                        Completed <span class="badge bg-success" id="completed-count">0</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="taskTabContent">
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    <div id="pending-tasks"></div>
                </div>
                <div class="tab-pane fade" id="inprogress" role="tabpanel">
                    <div id="inprogress-tasks"></div>
                </div>
                <div class="tab-pane fade" id="completed" role="tabpanel">
                    <div id="completed-tasks"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Detail Modal -->
<div class="modal fade" id="taskDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalTitle">Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="taskModalBody">
                <!-- Task details will be loaded here -->
            </div>
            <div class="modal-footer" id="taskModalFooter">
                <!-- Action buttons will be added here -->
            </div>
        </div>
    </div>
</div>

<script>
let currentTask = null;

// Load tasks on page load
document.addEventListener('DOMContentLoaded', function() {
    loadTasks('PENDING');
    loadTasks('IN_PROGRESS');
    loadTasks('COMPLETED');
});

async function loadTasks(status) {
    const containerId = status === 'PENDING' ? 'pending-tasks' :
                       status === 'IN_PROGRESS' ? 'inprogress-tasks' : 'completed-tasks';
    const countId = status === 'PENDING' ? 'pending-count' :
                    status === 'IN_PROGRESS' ? 'inprogress-count' : 'completed-count';

    try {
        const response = await fetch(`/api/process/my-tasks.php?status=${status}`);
        const data = await response.json();

        if (!data.success) {
            throw new Error(data.error);
        }

        // Update count
        document.getElementById(countId).textContent = data.count;

        // Render tasks
        const container = document.getElementById(containerId);
        if (data.tasks.length === 0) {
            container.innerHTML = '<div class="alert alert-info">No tasks found.</div>';
            return;
        }

        let html = '<div class="list-group">';
        data.tasks.forEach(task => {
            const isOverdue = task.due_date && new Date(task.due_date) < new Date();
            const overdueClass = isOverdue ? 'border-danger' : '';

            html += `
                <a href="#" class="list-group-item list-group-item-action ${overdueClass}" onclick="showTaskDetail('${task.task_id}', event)">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${escapeHtml(task.node_name)}</h6>
                        <small class="text-muted">${escapeHtml(task.reference_number)}</small>
                    </div>
                    <p class="mb-1">${escapeHtml(task.process_name)}</p>
                    <small>
                        Created: ${formatDate(task.created_at)}
                        ${task.due_date ? ` | Due: ${formatDate(task.due_date)}` : ''}
                        ${isOverdue ? '<span class="badge bg-danger ms-2">OVERDUE</span>' : ''}
                    </small>
                </a>
            `;
        });
        html += '</div>';

        container.innerHTML = html;

    } catch (error) {
        document.getElementById(containerId).innerHTML =
            `<div class="alert alert-danger">Error loading tasks: ${error.message}</div>`;
    }
}

async function showTaskDetail(taskId, event) {
    event.preventDefault();

    // For now, just show basic form
    // In production, load full task details including entity data
    document.getElementById('taskModalTitle').textContent = 'Complete Task';
    document.getElementById('taskModalBody').innerHTML = `
        <div class="mb-3">
            <label class="form-label">Comments</label>
            <textarea class="form-control" id="taskComments" rows="3"></textarea>
        </div>
    `;
    document.getElementById('taskModalFooter').innerHTML = `
        <button type="button" class="btn btn-success" onclick="completeTask('${taskId}', 'APPROVE')">
            <i class="bi bi-check-circle"></i> Approve
        </button>
        <button type="button" class="btn btn-danger" onclick="completeTask('${taskId}', 'REJECT')">
            <i class="bi bi-x-circle"></i> Reject
        </button>
        <button type="button" class="btn btn-primary" onclick="completeTask('${taskId}', 'COMPLETE')">
            <i class="bi bi-check2"></i> Complete
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    `;

    const modal = new bootstrap.Modal(document.getElementById('taskDetailModal'));
    modal.show();
}

async function completeTask(taskId, action) {
    const comments = document.getElementById('taskComments').value;

    try {
        const response = await fetch('/api/process/task-complete.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                task_instance_id: taskId,
                completion_action: action,
                comments: comments,
                completion_data: {}
            })
        });

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.error);
        }

        alert('Task completed successfully!');
        bootstrap.Modal.getInstance(document.getElementById('taskDetailModal')).hide();

        // Reload all task lists
        loadTasks('PENDING');
        loadTasks('IN_PROGRESS');
        loadTasks('COMPLETED');

    } catch (error) {
        alert('Error completing task: ' + error.message);
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleString();
}
</script>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
