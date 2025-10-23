<?php
/**
 * Common Process Graph Visualizer using Cytoscape.js
 *
 * Usage:
 * 1. Include this file in your page
 * 2. Provide $nodes and $edges arrays from database
 * 3. Optionally provide $currentNodeId to highlight current node
 * 4. Call initProcessGraph() in your JavaScript
 */

// This file expects the following variables to be set:
// $nodes - array of process_node records
// $edges - array of process_edge records
// $currentNodeId - (optional) ID of currently active node to highlight
// $graphContainerId - (optional) ID of the container div, defaults to 'cy'

$graphContainerId = $graphContainerId ?? 'cy';
$currentNodeId = $currentNodeId ?? null;
?>

<!-- Cytoscape.js Library -->
<script src="https://cdn.jsdelivr.net/npm/cytoscape@3.28.1/dist/cytoscape.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dagre@0.8.5/dist/dagre.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cytoscape-dagre@2.5.0/cytoscape-dagre.min.js"></script>

<script>
// Process graph data
const processGraphData = {
    nodes: <?php echo json_encode($nodes); ?>,
    edges: <?php echo json_encode($edges); ?>,
    currentNodeId: <?php echo json_encode($currentNodeId); ?>,
    containerId: <?php echo json_encode($graphContainerId); ?>
};

// Cytoscape instance
let cyInstance = null;

/**
 * Initialize Cytoscape process graph visualization
 * @param {string} containerId - ID of container element (optional, uses default from PHP)
 * @param {object} options - Additional options (optional)
 */
function initProcessGraph(containerId = null, options = {}) {
    const containerElem = containerId || processGraphData.containerId;

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
    processGraphData.nodes.forEach(node => {
        const isCurrentNode = (processGraphData.currentNodeId && node.id === processGraphData.currentNodeId);

        elements.push({
            data: {
                id: node.id,
                label: node.node_name || node.node_code,
                type: node.node_type,
                instructions: node.instructions,
                sla_hours: node.sla_hours,
                color: nodeColors[node.node_type] || '#6c757d',
                shape: nodeShapes[node.node_type] || 'roundrectangle',
                isCurrent: isCurrentNode
            }
        });
    });

    // Add edges
    processGraphData.edges.forEach(edge => {
        elements.push({
            data: {
                id: `edge-${edge.id}`,
                source: edge.from_node_id,
                target: edge.to_node_id,
                label: edge.edge_label || '',
                condition: edge.edge_condition,
                isDefault: edge.is_default == 1
            }
        });
    });

    // Initialize Cytoscape
    cyInstance = cytoscape({
        container: document.getElementById(containerElem),
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
                selector: 'node[isCurrent = true]',
                style: {
                    'border-width': 6,
                    'border-color': '#ff6b6b',
                    'border-style': 'double'
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
                selector: 'edge[isDefault = true]',
                style: {
                    'line-style': 'dashed'
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
            rankDir: options.rankDir || 'TB',
            nodeSep: options.nodeSep || 80,
            rankSep: options.rankSep || 100,
            padding: options.padding || 30
        },
        minZoom: 0.3,
        maxZoom: 3,
        wheelSensitivity: 0.2
    });

    // Add click event to show node details
    cyInstance.on('tap', 'node', function(evt) {
        const node = evt.target;
        const data = node.data();

        if (options.onNodeClick && typeof options.onNodeClick === 'function') {
            options.onNodeClick(data);
        } else {
            console.log('Node clicked:', data);
        }
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

    // If there's a current node, center on it
    if (processGraphData.currentNodeId) {
        setTimeout(() => {
            const currentNode = cyInstance.getElementById(processGraphData.currentNodeId);
            if (currentNode.length > 0) {
                cyInstance.animate({
                    center: {
                        eles: currentNode
                    },
                    zoom: 1.5,
                    duration: 1000
                });
            }
        }, 500);
    }

    return cyInstance;
}

// Helper function to fit graph
function fitProcessGraph() {
    if (cyInstance) {
        cyInstance.fit(null, 30);
    }
}

// Helper function to center graph
function centerProcessGraph() {
    if (cyInstance) {
        cyInstance.center();
    }
}

// Helper function to highlight a node
function highlightNode(nodeId) {
    if (cyInstance) {
        const node = cyInstance.getElementById(nodeId);
        if (node.length > 0) {
            cyInstance.animate({
                fit: {
                    eles: node,
                    padding: 100
                },
                duration: 500
            });
            node.select();
        }
    }
}
</script>
