<?php
// Additional scripts for this page
$additional_scripts = '
<script>
    async function loadReports() {
        const reportsSection = document.getElementById("reportsSection");
        const container = document.getElementById("reportsContainer");

        if (reportsSection.style.display === "none") {
            reportsSection.style.display = "block";

            try {
                // Load order summary
                const summaryResponse = await fetch("/api/reports/order_summary");
                const summaryData = await summaryResponse.json();

                // Load order trends
                const trendsResponse = await fetch("/api/reports/order_trends");
                const trendsData = await trendsResponse.json();

                container.innerHTML = `
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">${summaryData.data.title}</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                ${summaryData.data.columns.map(col => `<th>${col}</th>`).join("")}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${summaryData.data.data.map(row => `
                                                <tr>
                                                    <td><span class="badge status-${row.status}">${row.status}</span></td>
                                                    <td>${row.count}</td>
                                                    <td>$${parseFloat(row.total_amount || 0).toFixed(2)}</td>
                                                    <td>$${parseFloat(row.avg_amount || 0).toFixed(2)}</td>
                                                </tr>
                                            `).join("")}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">${trendsData.data.title}</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <p>Chart visualization would be rendered here</p>
                                    <small class="text-muted">Integration with Chart.js or similar library recommended</small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } catch (error) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <p>Reports are available but require data. Please create some orders first.</p>
                        </div>
                    </div>
                `;
            }
        } else {
            reportsSection.style.display = "none";
        }
    }
</script>';

require_once '../includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">🎙️ V4L Dashboard</h1>
            <p class="text-muted">Vocal 4 Local Community Platform</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button id="newOrderBtn" class="btn btn-primary">
                ➕ New Item
            </button>
            <button onclick="loadReports()" class="btn btn-outline-secondary">
                📊 Reports
            </button>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Community Content</h5>
                </div>
                <div class="card-body">
                    <div id="ordersTable">
                        <div class="text-center py-4">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 text-muted">Loading content...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Section -->
    <div class="row mt-4" id="reportsSection" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Reports Dashboard</h5>
                </div>
                <div class="card-body">
                    <div class="row" id="reportsContainer">
                        <!-- Reports will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Order Modal -->
<div class="modal fade" id="newOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="newOrderForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customer" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customer" name="customer" required>
                    </div>
                    <div class="mb-3">
                        <label for="total" class="form-label">Total Amount</label>
                        <input type="number" class="form-control" id="total" name="total" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="orderDetailsContent">
                    <!-- Order details will be loaded here -->
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