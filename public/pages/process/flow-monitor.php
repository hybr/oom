<?php
require_once __DIR__ . '/../../../bootstrap.php';

Auth::requireAuth();

$pageTitle = 'Flow Monitor';
$user = Auth::user();

if (!$user || !is_array($user)) {
    Router::redirect('/login');
    exit;
}

// Get flow instance ID
$flowId = $_GET['flow_id'] ?? null;
if (!$flowId) {
    Router::redirect('/dashboard');
    exit;
}

// Get flow instance details
$sql = "SELECT tfi.*, pg.name as process_name, pg.code as process_code,
               pg.description as process_description, pg.category,
               pn.node_name as current_node_name, pn.node_type as current_node_type,
               p.first_name || ' ' || p.last_name as started_by_name
        FROM task_flow_instance tfi
        JOIN process_graph pg ON tfi.graph_id = pg.id
        LEFT JOIN process_node pn ON tfi.current_node_id = pn.id
        LEFT JOIN person p ON tfi.started_by = p.id
        WHERE tfi.id = ? AND tfi.deleted_at IS NULL";
$flowInstance = Database::fetchOne($sql, [$flowId]);

if (!$flowInstance) {
    Router::redirect('/dashboard');
    exit;
}

// Get all tasks for this flow
$sql = "SELECT ti.*, pn.node_name, pn.node_type, pn.instructions, pn.sla_hours,
               p.first_name || ' ' || p.last_name as assigned_to_name
        FROM task_instance ti
        JOIN process_node pn ON ti.node_id = pn.id
        LEFT JOIN person p ON ti.assigned_to = p.id
        WHERE ti.flow_instance_id = ? AND ti.deleted_at IS NULL
        ORDER BY ti.created_at ASC";
$tasks = Database::fetchAll($sql, [$flowId]);

// Get audit log
$sql = "SELECT tal.*, p.first_name || ' ' || p.last_name as actor_name,
               pn.node_name
        FROM task_audit_log tal
        LEFT JOIN person p ON tal.actor_id = p.id
        LEFT JOIN process_node pn ON tal.node_id = pn.id
        WHERE tal.flow_instance_id = ? AND tal.deleted_at IS NULL
        ORDER BY tal.created_at DESC";
$auditLog = Database::fetchAll($sql, [$flowId]);

// Get process graph structure for visualization
$sql = "SELECT * FROM process_node
        WHERE graph_id = ? AND deleted_at IS NULL
        ORDER BY display_order, created_at";
$nodes = Database::fetchAll($sql, [$flowInstance['graph_id']]);

$sql = "SELECT * FROM process_edge
        WHERE graph_id = ? AND deleted_at IS NULL
        ORDER BY edge_order";
$edges = Database::fetchAll($sql, [$flowInstance['graph_id']]);

// Calculate progress percentage
$totalNodes = count($nodes);
$completedNodes = 0;
foreach ($nodes as $node) {
    if ($node['node_type'] === 'TASK') {
        $completed = false;
        foreach ($tasks as $task) {
            if ($task['node_id'] === $node['id'] && $task['status'] === 'COMPLETED') {
                $completed = true;
                break;
            }
        }
        if ($completed) {
            $completedNodes++;
        }
    }
}
$taskNodeCount = count(array_filter($nodes, fn($n) => $n['node_type'] === 'TASK'));
$progressPercent = $taskNodeCount > 0 ? round(($completedNodes / $taskNodeCount) * 100) : 0;

// Check if current user has any pending tasks in this flow
$myPendingTasks = array_filter($tasks, function($task) use ($user) {
    return $task['assigned_to'] === $user['person_id']
           && in_array($task['status'], ['PENDING', 'IN_PROGRESS']);
});

require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/pages/process/process-viewer.php?graph_id=<?php echo urlencode($flowInstance['graph_id']); ?>">
                <?php echo htmlspecialchars($flowInstance['process_name']); ?>
            </a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($flowInstance['reference_number']); ?></li>
        </ol>
    </nav>

    <!-- Flow Instance Header -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-diagram-3-fill"></i>
                    <?php echo htmlspecialchars($flowInstance['process_name']); ?>
                </h4>
                <span class="badge badge-lg
                    <?php
                    echo match($flowInstance['status']) {
                        'ACTIVE' => 'bg-success',
                        'COMPLETED' => 'bg-info',
                        'SUSPENDED' => 'bg-warning text-dark',
                        'CANCELLED' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                    ?>">
                    <?php echo htmlspecialchars($flowInstance['status']); ?>
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-5">Reference Number:</dt>
                        <dd class="col-sm-7"><strong><?php echo htmlspecialchars($flowInstance['reference_number']); ?></strong></dd>

                        <dt class="col-sm-5">Category:</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-secondary"><?php echo htmlspecialchars($flowInstance['category'] ?? 'General'); ?></span>
                        </dd>

                        <dt class="col-sm-5">Current Step:</dt>
                        <dd class="col-sm-7">
                            <?php if ($flowInstance['current_node_name']): ?>
                                <span class="badge bg-info">
                                    <?php echo htmlspecialchars($flowInstance['current_node_name']); ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </dd>

                        <dt class="col-sm-5">Progress:</dt>
                        <dd class="col-sm-7">
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar" role="progressbar"
                                     style="width: <?php echo $progressPercent; ?>%;"
                                     aria-valuenow="<?php echo $progressPercent; ?>"
                                     aria-valuemin="0" aria-valuemax="100">
                                    <?php echo $progressPercent; ?>%
                                </div>
                            </div>
                        </dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-5">Started By:</dt>
                        <dd class="col-sm-7"><?php echo htmlspecialchars($flowInstance['started_by_name'] ?? 'Unknown'); ?></dd>

                        <dt class="col-sm-5">Started At:</dt>
                        <dd class="col-sm-7"><?php echo date('M d, Y h:i A', strtotime($flowInstance['started_at'])); ?></dd>

                        <?php if ($flowInstance['completed_at']): ?>
                            <dt class="col-sm-5">Completed At:</dt>
                            <dd class="col-sm-7"><?php echo date('M d, Y h:i A', strtotime($flowInstance['completed_at'])); ?></dd>
                        <?php endif; ?>

                        <?php if ($flowInstance['suspended_at']): ?>
                            <dt class="col-sm-5">Suspended At:</dt>
                            <dd class="col-sm-7"><?php echo date('M d, Y h:i A', strtotime($flowInstance['suspended_at'])); ?></dd>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Tasks and Diagram -->
        <div class="col-md-8">
            <!-- My Pending Tasks Alert -->
            <?php if (!empty($myPendingTasks)): ?>
                <div class="alert alert-warning" role="alert">
                    <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> You have <?php echo count($myPendingTasks); ?> pending task(s) in this flow</h5>
                    <hr>
                    <?php foreach ($myPendingTasks as $task): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong><?php echo htmlspecialchars($task['node_name']); ?></strong>
                                <?php if ($task['due_date']): ?>
                                    - Due: <?php echo date('M d, Y h:i A', strtotime($task['due_date'])); ?>
                                <?php endif; ?>
                            </div>
                            <a href="/pages/process/my-tasks.php" class="btn btn-sm btn-primary">
                                <i class="bi bi-check-circle"></i> Go to My Tasks
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Tasks List -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-task"></i> Tasks in this Flow</h5>
                </div>
                <div class="card-body">
                    <?php if (count($tasks) > 0): ?>
                        <div class="list-group">
                            <?php foreach ($tasks as $task): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="bi <?php echo $task['node_type'] === 'TASK' ? 'bi-check-square' : 'bi-gear'; ?>"></i>
                                                <?php echo htmlspecialchars($task['node_name']); ?>
                                            </h6>
                                            <?php if ($task['instructions']): ?>
                                                <p class="mb-1 text-muted small"><?php echo htmlspecialchars($task['instructions']); ?></p>
                                            <?php endif; ?>
                                            <small class="text-muted">
                                                <?php if ($task['assigned_to_name']): ?>
                                                    <i class="bi bi-person"></i> <?php echo htmlspecialchars($task['assigned_to_name']); ?> •
                                                <?php endif; ?>
                                                Created: <?php echo date('M d, Y h:i A', strtotime($task['created_at'])); ?>
                                                <?php if ($task['due_date']): ?>
                                                    • Due: <?php echo date('M d, Y h:i A', strtotime($task['due_date'])); ?>
                                                    <?php if (strtotime($task['due_date']) < time() && $task['status'] !== 'COMPLETED'): ?>
                                                        <span class="badge bg-danger">OVERDUE</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                        <span class="badge
                                            <?php
                                            echo match($task['status']) {
                                                'PENDING' => 'bg-warning text-dark',
                                                'IN_PROGRESS' => 'bg-info',
                                                'COMPLETED' => 'bg-success',
                                                'CANCELLED' => 'bg-danger',
                                                'ESCALATED' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                            ?>">
                                            <?php echo htmlspecialchars($task['status']); ?>
                                        </span>
                                    </div>
                                    <?php if ($task['completed_at']): ?>
                                        <small class="text-success">
                                            <i class="bi bi-check-circle-fill"></i> Completed: <?php echo date('M d, Y h:i A', strtotime($task['completed_at'])); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No tasks in this flow yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Flow Diagram -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Process Flow Diagram</h5>
                </div>
                <div class="card-body">
                    <div id="flowDiagram" class="border rounded p-3" style="min-height: 500px; background: #f8f9fa;"></div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>Legend:</strong>
                            <span class="badge bg-success ms-2">START</span>
                            <span class="badge bg-primary ms-2">TASK</span>
                            <span class="badge bg-warning text-dark ms-2">DECISION</span>
                            <span class="badge bg-info ms-2">FORK</span>
                            <span class="badge" style="background-color: #6f42c1;">JOIN</span>
                            <span class="badge bg-danger ms-2">END</span>
                            <span class="badge bg-dark ms-2">CURRENT</span>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Audit Log -->
        <div class="col-md-4">
            <div class="card" style="position: sticky; top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Audit Log</h5>
                </div>
                <div class="card-body" style="max-height: 800px; overflow-y: auto;">
                    <?php if (count($auditLog) > 0): ?>
                        <div class="timeline">
                            <?php foreach ($auditLog as $log): ?>
                                <div class="timeline-item mb-3">
                                    <div class="timeline-marker">
                                        <i class="bi <?php
                                            echo match($log['action']) {
                                                'START_PROCESS' => 'bi-play-circle-fill text-success',
                                                'CREATE_TASK' => 'bi-plus-circle-fill text-info',
                                                'START_TASK' => 'bi-hourglass-split text-warning',
                                                'COMPLETE_TASK' => 'bi-check-circle-fill text-success',
                                                'MOVE_FLOW' => 'bi-arrow-right-circle-fill text-primary',
                                                'COMPLETE_PROCESS' => 'bi-flag-fill text-success',
                                                'SUSPEND_FLOW' => 'bi-pause-circle-fill text-warning',
                                                'CANCEL_TASK', 'CANCEL_FLOW' => 'bi-x-circle-fill text-danger',
                                                'ESCALATE_TASK' => 'bi-exclamation-circle-fill text-danger',
                                                'REASSIGN_TASK' => 'bi-arrow-repeat text-info',
                                                default => 'bi-circle-fill text-secondary'
                                            };
                                        ?>"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted"><?php echo date('M d, h:i A', strtotime($log['created_at'])); ?></small>
                                        </div>
                                        <div><strong><?php echo str_replace('_', ' ', $log['action']); ?></strong></div>
                                        <?php if ($log['node_name']): ?>
                                            <small class="text-muted">Node: <?php echo htmlspecialchars($log['node_name']); ?></small><br>
                                        <?php endif; ?>
                                        <?php if ($log['actor_name']): ?>
                                            <small class="text-muted">By: <?php echo htmlspecialchars($log['actor_name']); ?></small><br>
                                        <?php endif; ?>
                                        <?php if ($log['comments']): ?>
                                            <small><?php echo htmlspecialchars($log['comments']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No audit entries yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 20px;
    height: 20px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -21px;
    top: 20px;
    bottom: -15px;
    width: 2px;
    background: #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    border-left: 3px solid #007bff;
}
</style>

<script>
const graphData = {
    nodes: <?php echo json_encode($nodes); ?>,
    edges: <?php echo json_encode($edges); ?>,
    currentNodeId: <?php echo json_encode($flowInstance['current_node_id']); ?>
};

function drawFlowDiagram() {
    const container = document.getElementById('flowDiagram');
    container.innerHTML = ''; // Clear existing content

    const width = container.clientWidth;
    const height = 500;

    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('width', width);
    svg.setAttribute('height', height);
    svg.style.display = 'block';

    // Define arrowhead marker
    const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
    const marker = document.createElementNS('http://www.w3.org/2000/svg', 'marker');
    marker.setAttribute('id', 'arrowhead');
    marker.setAttribute('markerWidth', '10');
    marker.setAttribute('markerHeight', '10');
    marker.setAttribute('refX', '9');
    marker.setAttribute('refY', '3');
    marker.setAttribute('orient', 'auto');
    const polygon = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
    polygon.setAttribute('points', '0 0, 10 3, 0 6');
    polygon.setAttribute('fill', '#6c757d');
    marker.appendChild(polygon);
    defs.appendChild(marker);
    svg.appendChild(defs);

    const nodeWidth = 120;
    const nodeHeight = 60;
    const horizontalGap = 150;
    const verticalGap = 100;

    // Calculate node positions
    const nodePositions = new Map();
    let currentX = 50;
    let currentY = 50;

    graphData.nodes.forEach((node, index) => {
        let x = node.display_x || currentX;
        let y = node.display_y || currentY;

        // Auto-layout if no position defined
        if (!node.display_x) {
            if (index > 0 && index % 3 === 0) {
                currentX = 50;
                currentY += verticalGap + nodeHeight;
            } else {
                currentX += horizontalGap + nodeWidth;
            }
            x = currentX;
            y = currentY;
        }

        nodePositions.set(node.id, { x, y, node });
    });

    // Draw edges first (so they appear behind nodes)
    graphData.edges.forEach(edge => {
        const fromPos = nodePositions.get(edge.from_node_id);
        const toPos = nodePositions.get(edge.to_node_id);

        if (fromPos && toPos) {
            const fromX = fromPos.x + nodeWidth / 2;
            const fromY = fromPos.y + nodeHeight / 2;
            const toX = toPos.x + nodeWidth / 2;
            const toY = toPos.y + nodeHeight / 2;

            // Calculate control points for curved path
            const dx = toX - fromX;
            const dy = toY - fromY;
            const controlX1 = fromX + dx * 0.5;
            const controlY1 = fromY;
            const controlX2 = fromX + dx * 0.5;
            const controlY2 = toY;

            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            path.setAttribute('d', `M ${fromX} ${fromY} C ${controlX1} ${controlY1}, ${controlX2} ${controlY2}, ${toX} ${toY}`);
            path.setAttribute('stroke', '#6c757d');
            path.setAttribute('stroke-width', '2');
            path.setAttribute('fill', 'none');
            path.setAttribute('marker-end', 'url(#arrowhead)');
            svg.appendChild(path);

            // Add edge label if exists
            if (edge.edge_label) {
                const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                text.setAttribute('x', (fromX + toX) / 2);
                text.setAttribute('y', (fromY + toY) / 2 - 5);
                text.setAttribute('text-anchor', 'middle');
                text.setAttribute('font-size', '11');
                text.setAttribute('fill', '#6c757d');
                text.textContent = edge.edge_label;
                svg.appendChild(text);
            }
        }
    });

    // Draw nodes
    const nodeTypeColors = {
        'START': '#28a745',
        'TASK': '#007bff',
        'DECISION': '#ffc107',
        'FORK': '#17a2b8',
        'JOIN': '#6f42c1',
        'END': '#dc3545'
    };

    nodePositions.forEach(({ x, y, node }) => {
        const isCurrent = node.id === graphData.currentNodeId;
        const color = isCurrent ? '#000000' : (nodeTypeColors[node.node_type] || '#6c757d');

        // Draw rectangle
        const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        rect.setAttribute('x', x);
        rect.setAttribute('y', y);
        rect.setAttribute('width', nodeWidth);
        rect.setAttribute('height', nodeHeight);
        rect.setAttribute('fill', color);
        rect.setAttribute('stroke', isCurrent ? '#ffd700' : color);
        rect.setAttribute('stroke-width', isCurrent ? '4' : '2');
        rect.setAttribute('rx', '5');
        svg.appendChild(rect);

        // Draw text (node name)
        const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        text.setAttribute('x', x + nodeWidth / 2);
        text.setAttribute('y', y + nodeHeight / 2 - 5);
        text.setAttribute('text-anchor', 'middle');
        text.setAttribute('font-size', '12');
        text.setAttribute('font-weight', 'bold');
        text.setAttribute('fill', '#ffffff');

        // Wrap text if too long
        const nodeName = node.node_name || node.node_code;
        if (nodeName.length > 15) {
            const words = nodeName.split(' ');
            let line1 = '';
            let line2 = '';
            words.forEach((word, i) => {
                if (i < words.length / 2) {
                    line1 += word + ' ';
                } else {
                    line2 += word + ' ';
                }
            });

            const tspan1 = document.createElementNS('http://www.w3.org/2000/svg', 'tspan');
            tspan1.setAttribute('x', x + nodeWidth / 2);
            tspan1.setAttribute('dy', '-5');
            tspan1.textContent = line1.trim();
            text.appendChild(tspan1);

            const tspan2 = document.createElementNS('http://www.w3.org/2000/svg', 'tspan');
            tspan2.setAttribute('x', x + nodeWidth / 2);
            tspan2.setAttribute('dy', '15');
            tspan2.textContent = line2.trim();
            text.appendChild(tspan2);
        } else {
            text.textContent = nodeName;
        }
        svg.appendChild(text);

        // Draw node type label
        const typeText = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        typeText.setAttribute('x', x + nodeWidth / 2);
        typeText.setAttribute('y', y + nodeHeight / 2 + 15);
        typeText.setAttribute('text-anchor', 'middle');
        typeText.setAttribute('font-size', '9');
        typeText.setAttribute('fill', '#ffffff');
        typeText.textContent = node.node_type;
        svg.appendChild(typeText);

        // Add "CURRENT" indicator
        if (isCurrent) {
            const currentText = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            currentText.setAttribute('x', x + nodeWidth / 2);
            currentText.setAttribute('y', y - 10);
            currentText.setAttribute('text-anchor', 'middle');
            currentText.setAttribute('font-size', '11');
            currentText.setAttribute('font-weight', 'bold');
            currentText.setAttribute('fill', '#ffd700');
            currentText.textContent = '▼ CURRENT';
            svg.appendChild(currentText);
        }
    });

    container.appendChild(svg);
}

// Draw diagram when page loads
document.addEventListener('DOMContentLoaded', drawFlowDiagram);
window.addEventListener('resize', drawFlowDiagram);
</script>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
