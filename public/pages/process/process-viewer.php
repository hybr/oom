<?php
$pageTitle = 'Process Viewer';
require_once __DIR__ . '/../../../bootstrap.php';

Auth::requireAuth();

// Get graph ID from URL
$graphId = $_GET['graph_id'] ?? null;
if (!$graphId) {
    header('Location: /dashboard');
    exit;
}

// Get process graph details
$sql = "SELECT * FROM process_graph WHERE id = ? AND deleted_at IS NULL";
$graph = Database::fetchOne($sql, [$graphId]);

if (!$graph) {
    header('Location: /dashboard');
    exit;
}

// Get all nodes for this graph
$sql = "SELECT * FROM process_node WHERE graph_id = ? AND deleted_at IS NULL ORDER BY display_x, display_y";
$nodes = Database::fetchAll($sql, [$graphId]);

// Get all edges for this graph
$sql = "SELECT * FROM process_edge WHERE graph_id = ? AND deleted_at IS NULL ORDER BY edge_order";
$edges = Database::fetchAll($sql, [$graphId]);

// Get running instances of this process
$sql = "SELECT tfi.*, p.first_name, p.last_name, pn.node_name as current_node_name
        FROM task_flow_instance tfi
        LEFT JOIN person p ON tfi.started_by = p.id
        LEFT JOIN process_node pn ON tfi.current_node_id = pn.id
        WHERE tfi.graph_id = ?
        AND tfi.status IN ('RUNNING', 'SUSPENDED')
        AND tfi.deleted_at IS NULL
        ORDER BY tfi.created_at DESC
        LIMIT 20";
$runningInstances = Database::fetchAll($sql, [$graphId]);

// Get user's current organization from session
$userOrganizationId = Auth::currentOrganizationId();

require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Process Viewer</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Process Info Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-diagram-3"></i>
                            <?php echo htmlspecialchars($graph['name']); ?>
                        </h5>
                        <span class="badge bg-light text-dark">v<?php echo $graph['version_number']; ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($graph['description']): ?>
                        <p class="mb-2"><?php echo htmlspecialchars($graph['description']); ?></p>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">Category:</small>
                            <br><strong><?php echo htmlspecialchars($graph['category'] ?: 'General'); ?></strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Status:</small>
                            <br><strong class="text-success">
                                <?php echo $graph['is_published'] ? 'Published' : 'Draft'; ?>
                            </strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Nodes:</small>
                            <br><strong><?php echo count($nodes); ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flow Diagram Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-flowchart"></i> Process Flow Diagram</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary" onclick="cyInstance.fit()">
                                <i class="bi bi-arrows-fullscreen"></i> Fit
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="cyInstance.center()">
                                <i class="bi bi-bullseye"></i> Center
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="cy" style="width: 100%; height: 600px; border: 1px solid #ddd; background: #f8f9fa;"></div>
                    <div class="mt-3">
                        <h6>Legend:</h6>
                        <div class="d-flex flex-wrap gap-3">
                            <div><span class="badge" style="background-color: #28a745;">START</span> Start Node</div>
                            <div><span class="badge" style="background-color: #007bff;">TASK</span> Task Node</div>
                            <div><span class="badge" style="background-color: #ffc107; color: black;">DECISION</span> Decision Node</div>
                            <div><span class="badge" style="background-color: #17a2b8;">FORK</span> Fork Node</div>
                            <div><span class="badge" style="background-color: #6f42c1;">JOIN</span> Join Node</div>
                            <div><span class="badge" style="background-color: #dc3545;">END</span> End Node</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Process Steps Details -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-ol"></i> Process Steps</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php foreach ($nodes as $index => $node): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <span class="badge bg-secondary"><?php echo $index + 1; ?></span>
                                        <?php echo htmlspecialchars($node['node_name']); ?>
                                    </h6>
                                    <small>
                                        <span class="badge" style="background-color: <?php
                                            echo match($node['node_type']) {
                                                'START' => '#28a745',
                                                'TASK' => '#007bff',
                                                'DECISION' => '#ffc107',
                                                'FORK' => '#17a2b8',
                                                'JOIN' => '#6f42c1',
                                                'END' => '#dc3545',
                                                default => '#6c757d'
                                            };
                                        ?>; <?php echo $node['node_type'] === 'DECISION' ? 'color: black;' : ''; ?>">
                                            <?php echo $node['node_type']; ?>
                                        </span>
                                    </small>
                                </div>
                                <?php if ($node['instructions']): ?>
                                    <p class="mb-1 small"><?php echo htmlspecialchars($node['instructions']); ?></p>
                                <?php endif; ?>
                                <?php if ($node['sla_hours']): ?>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> SLA: <?php echo $node['sla_hours']; ?> hours
                                    </small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Start New Process -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-play-circle"></i> Start New Process</h5>
                </div>
                <div class="card-body">
                    <?php if ($userOrganizationId): ?>
                        <button class="btn btn-success w-100" onclick="showStartProcessModal()">
                            <i class="bi bi-plus-circle"></i> Start Process Instance
                        </button>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle"></i>
                            You need to be employed in an organization to start a process.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Running Instances -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-activity"></i> Running Instances
                        <span class="badge bg-primary"><?php echo count($runningInstances); ?></span>
                    </h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    <?php if (count($runningInstances) > 0): ?>
                        <div class="list-group">
                            <?php foreach ($runningInstances as $instance): ?>
                                <a href="/pages/process/flow-monitor.php?flow_id=<?php echo urlencode($instance['id']); ?>"
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($instance['reference_number']); ?></h6>
                                        <small>
                                            <span class="badge bg-<?php echo $instance['status'] === 'RUNNING' ? 'success' : 'warning'; ?>">
                                                <?php echo $instance['status']; ?>
                                            </span>
                                        </small>
                                    </div>
                                    <p class="mb-1 small">
                                        <strong>Current:</strong> <?php echo htmlspecialchars($instance['current_node_name'] ?? 'N/A'); ?>
                                    </p>
                                    <small class="text-muted">
                                        Started by: <?php echo htmlspecialchars(($instance['first_name'] ?? '') . ' ' . ($instance['last_name'] ?? '')); ?>
                                        <br>
                                        <?php echo date('M d, Y H:i', strtotime($instance['started_at'])); ?>
                                    </small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> No running instances.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Start Process Modal -->
<div class="modal fade" id="startProcessModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Start New Process Instance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="startProcessForm">
                    <div class="mb-3">
                        <label class="form-label">Reference Notes (Optional)</label>
                        <textarea class="form-control" id="processNotes" rows="3" placeholder="Add any reference notes for this process instance..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select class="form-select" id="processPriority">
                            <option value="0">Normal</option>
                            <option value="5">Medium</option>
                            <option value="10">High</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="startProcess()">
                    <i class="bi bi-play-circle"></i> Start Process
                </button>
            </div>
        </div>
    </div>
</div>

<?php
// Set variables for the process graph visualizer
$currentNodeId = null; // No current node in viewer mode
$graphContainerId = 'cy';
require_once __DIR__ . '/../../../includes/process-graph-visualizer.php';
?>

<script>
// Process data for starting new instances
const graphData = {
    graphId: <?php echo json_encode($graphId); ?>,
    organizationId: <?php echo json_encode($userOrganizationId); ?>
};

// Initialize Cytoscape graph
function initCytoscape_OLD() {
    // Register dagre layout
    if (typeof cytoscape !== 'undefined' && typeof dagre !== 'undefined' && typeof cytoscapeDagre !== 'undefined') {
        cytoscapeDagre(cytoscape);
    }

    // Color map for node types
    const nodeColors = {
        'START': '#28a745',
        'TASK': '#007bff',
        'DECISION': '#ffc107',
        'FORK': '#17a2b8',
        'JOIN': '#6f42c1',
        'END': '#dc3545'
    };

    // Shape map for node types
    const nodeShapes = {
        'START': 'ellipse',
        'TASK': 'roundrectangle',
        'DECISION': 'diamond',
        'FORK': 'roundrectangle',
        'JOIN': 'roundrectangle',
        'END': 'ellipse'
    };

    // Build Cytoscape elements
    const elements = [];

    // Add nodes
    graphData.nodes.forEach(node => {
        elements.push({
            data: {
                id: node.id,
                label: node.node_name,
                type: node.node_type,
                instructions: node.instructions,
                sla_hours: node.sla_hours,
                color: nodeColors[node.node_type] || '#6c757d',
                shape: nodeShapes[node.node_type] || 'roundrectangle'
            }
        });
    });

    // Add edges
    graphData.edges.forEach(edge => {
        elements.push({
            data: {
                id: `edge-${edge.id}`,
                source: edge.from_node_id,
                target: edge.to_node_id,
                label: edge.edge_label || '',
                condition: edge.edge_condition
            }
        });
    });

    // Initialize Cytoscape
    cyInstance = cytoscape({
        container: document.getElementById('cy'),
        elements: elements,
        style: [
            {
                selector: 'node',
                style: {
                    'background-color': 'data(color)',
                    'label': 'data(label)',
                    'text-valign': 'center',
                    'text-halign': 'center',
                    'color': '#ffffff',
                    'text-outline-color': 'data(color)',
                    'text-outline-width': 2,
                    'font-size': 14,
                    'font-weight': 'bold',
                    'width': 120,
                    'height': 60,
                    'shape': 'data(shape)',
                    'border-width': 3,
                    'border-color': '#ffffff',
                    'text-wrap': 'wrap',
                    'text-max-width': 100
                }
            },
            {
                selector: 'edge',
                style: {
                    'width': 3,
                    'line-color': '#6c757d',
                    'target-arrow-color': '#6c757d',
                    'target-arrow-shape': 'triangle',
                    'curve-style': 'bezier',
                    'arrow-scale': 1.5,
                    'label': 'data(label)',
                    'font-size': 11,
                    'text-background-color': '#f8f9fa',
                    'text-background-opacity': 1,
                    'text-background-padding': 3,
                    'color': '#495057',
                    'text-rotation': 'autorotate'
                }
            },
            {
                selector: 'node:selected',
                style: {
                    'border-width': 4,
                    'border-color': '#007bff',
                    'overlay-opacity': 0.2,
                    'overlay-color': '#007bff'
                }
            },
            {
                selector: 'edge:selected',
                style: {
                    'width': 4,
                    'line-color': '#007bff',
                    'target-arrow-color': '#007bff'
                }
            }
        ],
        layout: {
            name: 'dagre',
            rankDir: 'TB',
            nodeSep: 80,
            rankSep: 100,
            padding: 30
        },
        minZoom: 0.3,
        maxZoom: 3,
        wheelSensitivity: 0.2
    });

    // Add click event to show node details
    cyInstance.on('tap', 'node', function(evt) {
        const node = evt.target;
        const data = node.data();

        let details = `<strong>${data.label}</strong><br>`;
        details += `Type: <span class="badge" style="background-color: ${data.color};">${data.type}</span><br>`;

        if (data.instructions) {
            details += `<small>${data.instructions}</small><br>`;
        }

        if (data.sla_hours) {
            details += `<small><i class="bi bi-clock"></i> SLA: ${data.sla_hours} hours</small>`;
        }

        // Show tooltip (you can replace this with a proper modal or tooltip)
        console.log('Node clicked:', data);
    });

    // Add double-click to zoom to node
    cyInstance.on('dbltap', 'node', function(evt) {
        cyInstance.animate({
            fit: {
                eles: evt.target,
                padding: 100
            },
            duration: 500
        });
    });

    // Fit graph to viewport
    cyInstance.fit(null, 30);
}

// Show start process modal
function showStartProcessModal() {
    const modal = new bootstrap.Modal(document.getElementById('startProcessModal'));
    modal.show();
}

// Start process
async function startProcess() {
    const notes = document.getElementById('processNotes').value;
    const priority = document.getElementById('processPriority').value;

    try {
        const response = await fetch('/api/process/start.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                graph_code: <?php echo json_encode($graph['code']); ?>,
                organization_id: graphData.organizationId,
                entity_code: null,
                entity_record_id: null,
                variables: {
                    notes: notes,
                    priority: parseInt(priority)
                }
            })
        });

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.error);
        }

        alert('Process started successfully!\nReference: ' + data.reference_number);
        bootstrap.Modal.getInstance(document.getElementById('startProcessModal')).hide();

        // Redirect to flow monitor
        window.location.href = '/pages/process/flow-monitor.php?flow_id=' + data.flow_instance_id;

    } catch (error) {
        alert('Error starting process: ' + error.message);
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    initProcessGraph();
});
</script>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
