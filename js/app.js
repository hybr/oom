class OrderManagementApp {
    constructor() {
        this.baseUrl = '/api';
        this.currentTheme = localStorage.getItem('theme') || 'light';
        this.wsConnection = null;
        this.orders = [];
        this.init();
    }

    async init() {
        this.setupTheme();
        this.setupEventListeners();
        this.setupWebSocket();
        await this.loadOrders();
        this.renderOrders();
    }

    setupTheme() {
        document.documentElement.setAttribute('data-theme', this.currentTheme);
        const toggleBtn = document.getElementById('themeToggle');
        if (toggleBtn) {
            toggleBtn.textContent = this.currentTheme === 'dark' ? '☀️' : '🌙';
        }
    }

    setupEventListeners() {
        // Theme toggle
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }

        // Refresh button
        const refreshBtn = document.getElementById('refreshOrders');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => this.refreshOrders());
        }

        // New order button
        const newOrderBtn = document.getElementById('newOrderBtn');
        if (newOrderBtn) {
            newOrderBtn.addEventListener('click', () => this.showNewOrderModal());
        }

        // Form submissions
        const newOrderForm = document.getElementById('newOrderForm');
        if (newOrderForm) {
            newOrderForm.addEventListener('submit', (e) => this.handleNewOrder(e));
        }
    }

    toggleTheme() {
        this.currentTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        localStorage.setItem('theme', this.currentTheme);
        this.setupTheme();
    }

    setupWebSocket() {
        // Simulate WebSocket with polling for demo
        this.startPolling();
        this.updateConnectionStatus(true);
    }

    startPolling() {
        setInterval(async () => {
            try {
                const notifications = await this.fetchNotifications();
                if (notifications.length > 0) {
                    notifications.forEach(notification => this.showNotification(notification));
                }
            } catch (error) {
                console.error('Polling error:', error);
            }
        }, 5000);
    }

    updateConnectionStatus(connected) {
        const indicator = document.querySelector('.connection-status');
        if (indicator) {
            indicator.className = `connection-status ${connected ? 'connected' : 'disconnected'}`;
        }
    }

    async fetchNotifications() {
        try {
            const response = await fetch(`${this.baseUrl}/notifications`);
            const data = await response.json();
            return data.success ? data.data : [];
        } catch (error) {
            console.error('Failed to fetch notifications:', error);
            return [];
        }
    }

    showNotification(notification) {
        const toast = this.createToast(notification.message, notification.type);
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    createToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `
            <div class="toast-header">
                <strong class="me-auto">${type === 'order_status_change' ? 'Order Update' : 'Notification'}</strong>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
            <div class="toast-body">${message}</div>
        `;

        const container = document.querySelector('.toast-container') || this.createToastContainer();
        container.appendChild(toast);
        return toast;
    }

    createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
        return container;
    }

    async loadOrders() {
        try {
            this.showLoading(true);
            const response = await fetch(`${this.baseUrl}/entities/Order`);
            const data = await response.json();

            if (data.success) {
                this.orders = data.data;
            } else {
                throw new Error(data.error);
            }
        } catch (error) {
            console.error('Failed to load orders:', error);
            this.showNotification({message: 'Failed to load orders', type: 'error'});
        } finally {
            this.showLoading(false);
        }
    }

    async refreshOrders() {
        await this.loadOrders();
        this.renderOrders();
        this.showNotification({message: 'Orders refreshed', type: 'success'});
    }

    renderOrders() {
        const container = document.getElementById('ordersTable');
        if (!container) return;

        if (this.orders.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <p class="text-muted">No orders found</p>
                    <button class="btn btn-primary" onclick="app.showNewOrderModal()">Create First Order</button>
                </div>
            `;
            return;
        }

        const tableHtml = `
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${this.orders.map(order => this.renderOrderRow(order)).join('')}
                    </tbody>
                </table>
            </div>
        `;

        container.innerHTML = tableHtml;
    }

    renderOrderRow(order) {
        const statusBadge = `<span class="badge status-${order.status}">${order.status}</span>`;
        const actions = this.renderOrderActions(order);

        return `
            <tr id="order-${order.id}">
                <td>${order.id}</td>
                <td>${order.customer}</td>
                <td>${statusBadge}</td>
                <td>$${parseFloat(order.total || 0).toFixed(2)}</td>
                <td>${new Date(order.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="action-buttons">
                        ${actions}
                    </div>
                </td>
            </tr>
        `;
    }

    renderOrderActions(order) {
        const buttons = [];

        buttons.push(`<button class="btn btn-sm btn-outline-primary" onclick="app.viewOrder(${order.id})">View</button>`);

        // Add transition buttons based on current state
        const transitions = this.getAvailableTransitions(order.status);
        transitions.forEach(transition => {
            const btnClass = this.getTransitionButtonClass(transition);
            buttons.push(`<button class="btn btn-sm ${btnClass}" onclick="app.transitionOrder(${order.id}, '${transition}')">${transition}</button>`);
        });

        return buttons.join(' ');
    }

    getAvailableTransitions(currentStatus) {
        const stateFlow = {
            'draft': ['pending'],
            'pending': ['paid', 'cancelled'],
            'paid': ['shipped', 'refunded'],
            'shipped': ['delivered', 'returned'],
            'delivered': ['closed']
        };

        return stateFlow[currentStatus] || [];
    }

    getTransitionButtonClass(transition) {
        const classMap = {
            'pending': 'btn-outline-warning',
            'paid': 'btn-outline-info',
            'shipped': 'btn-outline-primary',
            'delivered': 'btn-outline-success',
            'closed': 'btn-outline-secondary',
            'cancelled': 'btn-outline-danger',
            'refunded': 'btn-outline-warning',
            'returned': 'btn-outline-warning'
        };

        return classMap[transition] || 'btn-outline-secondary';
    }

    async transitionOrder(orderId, toState) {
        try {
            const response = await fetch(`${this.baseUrl}/processes/order/${orderId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    to_state: toState,
                    role: 'admin',
                    note: `Transition to ${toState} via UI`
                })
            });

            const data = await response.json();

            if (data.success) {
                await this.refreshOrders();
                this.showNotification({message: `Order transitioned to ${toState}`, type: 'success'});
            } else {
                throw new Error(data.error);
            }
        } catch (error) {
            console.error('Transition failed:', error);
            this.showNotification({message: `Failed to transition order: ${error.message}`, type: 'error'});
        }
    }

    async viewOrder(orderId) {
        try {
            const response = await fetch(`${this.baseUrl}/entities/Order/${orderId}`);
            const data = await response.json();

            if (data.success) {
                this.showOrderDetailsModal(data.data);
            } else {
                throw new Error(data.error);
            }
        } catch (error) {
            console.error('Failed to load order details:', error);
            this.showNotification({message: 'Failed to load order details', type: 'error'});
        }
    }

    showOrderDetailsModal(order) {
        const modal = document.getElementById('orderDetailsModal');
        if (!modal) return;

        document.getElementById('orderDetailsContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Order Information</h6>
                    <p><strong>ID:</strong> ${order.id}</p>
                    <p><strong>Customer:</strong> ${order.customer}</p>
                    <p><strong>Status:</strong> <span class="badge status-${order.status}">${order.status}</span></p>
                    <p><strong>Total:</strong> $${parseFloat(order.total || 0).toFixed(2)}</p>
                    <p><strong>Created:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                </div>
                <div class="col-md-6">
                    <h6>Process Flow</h6>
                    ${this.renderProcessFlow(order.status)}
                </div>
            </div>
        `;

        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    }

    renderProcessFlow(currentStatus) {
        const states = ['draft', 'pending', 'paid', 'shipped', 'delivered', 'closed'];
        const currentIndex = states.indexOf(currentStatus);

        return `
            <div class="process-flow">
                ${states.map((state, index) => {
                    let className = 'process-state';
                    if (index < currentIndex) className += ' completed';
                    if (index === currentIndex) className += ' current';

                    return `
                        <span class="${className}">${state}</span>
                        ${index < states.length - 1 ? '<span class="process-arrow">→</span>' : ''}
                    `;
                }).join('')}
            </div>
        `;
    }

    showNewOrderModal() {
        const modal = document.getElementById('newOrderModal');
        if (modal) {
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
        }
    }

    async handleNewOrder(event) {
        event.preventDefault();

        const formData = new FormData(event.target);
        const orderData = {
            customer: formData.get('customer'),
            total: parseFloat(formData.get('total') || 0)
        };

        try {
            const response = await fetch(`${this.baseUrl}/entities/Order`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(orderData)
            });

            const data = await response.json();

            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('newOrderModal')).hide();
                event.target.reset();
                await this.refreshOrders();
                this.showNotification({message: 'Order created successfully', type: 'success'});
            } else {
                throw new Error(data.error);
            }
        } catch (error) {
            console.error('Failed to create order:', error);
            this.showNotification({message: `Failed to create order: ${error.message}`, type: 'error'});
        }
    }

    showLoading(show) {
        const indicator = document.getElementById('loadingIndicator');
        if (indicator) {
            indicator.style.display = show ? 'inline-block' : 'none';
        }
    }
}

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.app = new OrderManagementApp();
});