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
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
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

    currentTask = { id: taskId };

    // Show loading state
    document.getElementById('taskModalTitle').textContent = 'Loading Task...';
    document.getElementById('taskModalBody').innerHTML = '<div class="text-center p-4"><div class="spinner-border" role="status"></div></div>';
    document.getElementById('taskModalFooter').innerHTML = '';

    const modal = new bootstrap.Modal(document.getElementById('taskDetailModal'));
    modal.show();

    try {
        // Load task form
        const response = await fetch(`/api/process/get-task-form.php?task_instance_id=${taskId}`);
        const data = await response.json();

        if (!data.success) {
            throw new Error(data.error);
        }

        currentTask = data.task;

        // Update modal title
        document.getElementById('taskModalTitle').textContent = data.task.node_name;

        // Build form HTML
        let formHtml = '';

        // Task instructions
        if (data.task.instructions) {
            formHtml += `
                <div class="alert alert-info">
                    <strong>Instructions:</strong> ${escapeHtml(data.task.instructions)}
                </div>
            `;
        }

        // Task info
        formHtml += `
            <div class="mb-3">
                <strong>Process:</strong> ${escapeHtml(data.task.reference_number || 'N/A')}<br>
                <strong>Status:</strong> <span class="badge bg-${data.task.status === 'PENDING' ? 'warning' : 'info'}">${data.task.status}</span>
                ${data.task.due_date ? `<br><strong>Due:</strong> ${formatDate(data.task.due_date)}` : ''}
            </div>
            <hr>
        `;

        // Render form sections for each entity
        if (data.form_sections && data.form_sections.length > 0) {
            data.form_sections.forEach((section, index) => {
                formHtml += `
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">${escapeHtml(section.entity_name)}</h6>
                        </div>
                        <div class="card-body">
                            ${section.form_html}
                        </div>
                    </div>
                `;
            });
        } else {
            formHtml += `
                <div class="alert alert-warning">
                    <strong>No form configured:</strong> This task does not have any entity forms configured.
                    You can still add comments and complete the task.
                </div>
            `;
        }

        // Comments section
        formHtml += `
            <div class="mb-3">
                <label class="form-label">Comments</label>
                <textarea class="form-control" id="taskComments" rows="3">${data.task.completion_comments || ''}</textarea>
            </div>
        `;

        document.getElementById('taskModalBody').innerHTML = formHtml;

        // Update footer with action buttons
        document.getElementById('taskModalFooter').innerHTML = `
            <button type="button" class="btn btn-secondary" onclick="saveTaskDraft()">
                <i class="bi bi-save"></i> Save Draft
            </button>
            <button type="button" class="btn btn-success" onclick="completeTask('APPROVE')">
                <i class="bi bi-check-circle"></i> Approve
            </button>
            <button type="button" class="btn btn-danger" onclick="completeTask('REJECT')">
                <i class="bi bi-x-circle"></i> Reject
            </button>
            <button type="button" class="btn btn-primary" onclick="completeTask('COMPLETE')">
                <i class="bi bi-check2"></i> Complete
            </button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        `;

    } catch (error) {
        document.getElementById('taskModalBody').innerHTML =
            `<div class="alert alert-danger">Error loading task: ${error.message}</div>`;
        document.getElementById('taskModalFooter').innerHTML =
            `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>`;
    }
}

async function saveTaskDraft() {
    if (!currentTask) return;

    const formData = collectFormData();
    const comments = document.getElementById('taskComments').value;

    try {
        const response = await fetch('/api/process/save-task-data.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                task_instance_id: currentTask.id,
                action: 'save',
                form_data: formData,
                comments: comments
            })
        });

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.error);
        }

        alert('Draft saved successfully!');

        // Reload task lists
        loadTasks('PENDING');
        loadTasks('IN_PROGRESS');
        loadTasks('COMPLETED');

    } catch (error) {
        alert('Error saving draft: ' + error.message);
    }
}

async function completeTask(action) {
    if (!currentTask) return;

    const formData = collectFormData();
    const comments = document.getElementById('taskComments').value;

    // Client-side validation for required fields
    const requiredFields = document.querySelectorAll('[required]');
    let hasErrors = false;
    requiredFields.forEach(field => {
        if (!field.value || field.value.trim() === '') {
            field.classList.add('is-invalid');
            hasErrors = true;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    if (hasErrors) {
        alert('Please fill in all required fields before completing the task.');
        return;
    }

    try {
        const response = await fetch('/api/process/save-task-data.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                task_instance_id: currentTask.id,
                action: 'complete',
                form_data: formData,
                comments: comments,
                completion_action: action
            })
        });

        const data = await response.json();

        if (!data.success) {
            if (data.validation_errors) {
                let errorMsg = 'Validation errors:\n';
                for (const [field, error] of Object.entries(data.validation_errors)) {
                    errorMsg += `- ${error}\n`;
                }
                alert(errorMsg);
                return;
            }
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

function collectFormData() {
    const formData = {};
    const modalBody = document.getElementById('taskModalBody');

    // Collect all input, select, and textarea values
    modalBody.querySelectorAll('input, select, textarea').forEach(element => {
        if (element.id === 'taskComments') return; // Skip comments field

        const name = element.name || element.id;
        if (!name) return;

        if (element.type === 'checkbox') {
            if (element.checked) {
                // Handle checkbox arrays
                if (name.endsWith('[]')) {
                    const arrayName = name.slice(0, -2);
                    if (!formData[arrayName]) {
                        formData[arrayName] = [];
                    }
                    formData[arrayName].push(element.value);
                } else {
                    formData[name] = element.value || '1';
                }
            }
        } else if (element.type === 'radio') {
            if (element.checked) {
                formData[name] = element.value;
            }
        } else {
            formData[name] = element.value;
        }
    });

    return formData;
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
