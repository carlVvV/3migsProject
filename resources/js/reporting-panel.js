// Reporting Panel JavaScript
class ReportingPanel {
    constructor() {
        this.currentPage = 1;
        this.itemsPerPage = 25;
        this.currentFilters = {
            date_from: '',
            date_to: '',
            status: '',
            payment_method: '',
            search: ''
        };
        this.init();
    }

    init() {
        this.setDefaultDates();
        this.bindEvents();
        this.loadTransactionData();
    }

    setDefaultDates() {
        const today = new Date();
        const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
        
        document.getElementById('date-from').value = thirtyDaysAgo.toISOString().split('T')[0];
        document.getElementById('date-to').value = today.toISOString().split('T')[0];
        
        this.currentFilters.date_from = thirtyDaysAgo.toISOString().split('T')[0];
        this.currentFilters.date_to = today.toISOString().split('T')[0];
    }

    bindEvents() {
        // Filter events
        document.getElementById('apply-filters').addEventListener('click', () => this.applyFilters());
        document.getElementById('reset-filters').addEventListener('click', () => this.resetFilters());
        
        // Export events
        document.getElementById('export-pdf').addEventListener('click', () => this.exportToPDF());
        document.getElementById('export-excel').addEventListener('click', () => this.exportToExcel());

        // Refresh
        const refreshBtn = document.getElementById('refresh-reporting');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => this.loadTransactionData());
        }
        
        // Search input
        document.getElementById('search-input').addEventListener('input', (e) => {
            this.currentFilters.search = e.target.value;
            this.debounce(() => this.loadTransactionData(), 500);
        });
        
        // Status filter
        document.getElementById('status-filter').addEventListener('change', (e) => {
            this.currentFilters.status = e.target.value;
            this.loadTransactionData();
        });
        
        // Payment method filter
        document.getElementById('payment-method-filter').addEventListener('change', (e) => {
            this.currentFilters.payment_method = e.target.value;
            this.loadTransactionData();
        });
        
        // Date filters
        document.getElementById('date-from').addEventListener('change', (e) => {
            this.currentFilters.date_from = e.target.value;
            this.loadTransactionData();
        });
        
        document.getElementById('date-to').addEventListener('change', (e) => {
            this.currentFilters.date_to = e.target.value;
            this.loadTransactionData();
        });
        
        // Modal events
        document.getElementById('close-modal').addEventListener('click', () => this.closeModal());
        document.getElementById('order-modal').addEventListener('click', (e) => {
            if (e.target.id === 'order-modal') {
                this.closeModal();
            }
        });
    }

    async loadTransactionData() {
        try {
            this.showLoading();
            
            const queryParams = new URLSearchParams({
                page: this.currentPage,
                per_page: this.itemsPerPage,
                ...this.currentFilters
            });

            const response = await fetch(`/admin/reporting/transactions?${queryParams}`);
            const data = await response.json();

            if (data.success) {
                this.updateSummaryCards(data.summary);
                this.renderTransactionsTable(data.data);
                this.renderPagination(data.data);
            } else {
                this.showError('Failed to load transaction data');
            }
        } catch (error) {
            console.error('Error loading transaction data:', error);
            this.showError('Failed to load transaction data');
        }
    }

    updateSummaryCards(summary) {
        document.getElementById('total-orders').textContent = summary.total_orders || 0;
        document.getElementById('total-revenue').textContent = `₱${(summary.total_revenue || 0).toLocaleString()}`;
        document.getElementById('completed-orders').textContent = summary.completed_orders || 0;
        document.getElementById('pending-orders').textContent = summary.pending_orders || 0;
    }

    renderTransactionsTable(data) {
        const tbody = document.getElementById('transactions-table-body');
        
        if (!data.data || data.data.length === 0) {
            tbody.innerHTML = `
                <tr class="text-center">
                    <td colspan="6" class="px-6 py-12 text-gray-500">
                        <i class="fas fa-chart-bar text-4xl mb-4 block"></i>
                        <p class="text-lg font-medium">No transactions found</p>
                        <p class="text-sm">Try adjusting your filters or date range</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = data.data.map(order => `
            <tr class="transaction-row border-b border-gray-200 hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${order.order_number}</div>
                            <div class="text-sm text-gray-500">${order.total_items_count} items</div>
                            <div class="text-sm text-gray-500">₱${parseFloat(order.total_amount).toLocaleString()}</div>
                        </div>
                    </div>
                </td>
                
                <td class="px-6 py-4">
                    <div class="text-sm">
                        <div class="font-medium text-gray-900">${order.user ? order.user.name : 'Guest'}</div>
                        <div class="text-gray-500">${order.user ? order.user.email : 'N/A'}</div>
                        <div class="text-gray-500">${order.user ? order.user.phone : 'N/A'}</div>
                    </div>
                </td>
                
                <td class="px-6 py-4">
                    <div class="text-sm">
                        <div class="mb-2">
                            <span class="payment-${order.payment_details.method || 'unknown'} px-2 py-1 rounded-full text-xs font-medium">
                                ${this.formatPaymentMethod(order.payment_details.method)}
                            </span>
                        </div>
                        <div class="text-gray-500">
                            ${order.payment_details.status === 'completed' ? 'Paid' : 'Pending'}
                        </div>
                        ${order.payment_details.gcash_payment_id ? `
                            <div class="mt-1">
                                <span class="gcash-payment-id">GCash ID: ${order.payment_details.gcash_payment_id}</span>
                            </div>
                        ` : ''}
                    </div>
                </td>
                
                <td class="px-6 py-4">
                    <span class="status-${order.status} px-2 py-1 rounded-full text-xs font-medium">
                        ${this.formatStatus(order.status)}
                    </span>
                </td>
                
                <td class="px-6 py-4 text-sm text-gray-500">
                    ${new Date(order.created_at).toLocaleDateString()}
                </td>
                
                <td class="px-6 py-4 text-sm font-medium">
                    <button 
                        onclick="reportingPanel.viewOrderDetails('${order.id}')"
                        class="text-blue-600 hover:text-blue-900 mr-3"
                    >
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button 
                        onclick="reportingPanel.printOrder('${order.id}')"
                        class="text-green-600 hover:text-green-900"
                    >
                        <i class="fas fa-print"></i> Print
                    </button>
                </td>
            </tr>
        `).join('');
    }

    renderPagination(data) {
        const container = document.getElementById('pagination');
        if (!container || !data) return;

        const { current_page, last_page, per_page, total, from, to } = data;

        container.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-700">
                        Showing ${from || 0} to ${to || 0} of ${total || 0} results
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <button 
                        class="pagination-button ${current_page === 1 ? 'disabled' : ''}"
                        ${current_page === 1 ? 'disabled' : ''}
                        onclick="reportingPanel.goToPage(${current_page - 1})"
                    >
                        Previous
                    </button>
                    <span class="pagination-info">
                        Page ${current_page} of ${last_page}
                    </span>
                    <button 
                        class="pagination-button ${current_page === last_page ? 'disabled' : ''}"
                        ${current_page === last_page ? 'disabled' : ''}
                        onclick="reportingPanel.goToPage(${current_page + 1})"
                    >
                        Next
                    </button>
                </div>
            </div>
        `;
    }

    goToPage(page) {
        this.currentPage = page;
        this.loadTransactionData();
    }

    applyFilters() {
        this.currentPage = 1;
        this.loadTransactionData();
    }

    resetFilters() {
        this.currentFilters = {
            date_from: '',
            date_to: '',
            status: '',
            payment_method: '',
            search: ''
        };
        
        this.setDefaultDates();
        
        // Reset form inputs
        document.getElementById('status-filter').value = '';
        document.getElementById('payment-method-filter').value = '';
        document.getElementById('search-input').value = '';
        
        this.loadTransactionData();
    }

    async viewOrderDetails(orderId) {
        try {
            const response = await fetch(`/admin/reporting/order/${orderId}`);
            const data = await response.json();

            if (data.success) {
                this.showOrderModal(data.data);
            } else {
                this.showError('Failed to load order details');
            }
        } catch (error) {
            console.error('Error loading order details:', error);
            this.showError('Failed to load order details');
        }
    }

    showOrderModal(order) {
        const modal = document.getElementById('order-modal');
        const content = document.getElementById('order-modal-content');

        content.innerHTML = `
            <div class="order-details-grid">
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900">Order Information</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Order Number:</span>
                                <p class="text-gray-900">${order.order_number}</p>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Order Date:</span>
                                <p class="text-gray-900">${new Date(order.created_at).toLocaleDateString()}</p>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Status:</span>
                                <span class="status-${order.status} px-2 py-1 rounded-full text-xs font-medium">
                                    ${this.formatStatus(order.status)}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Total Amount:</span>
                                <p class="text-gray-900">₱${parseFloat(order.total_amount).toLocaleString()}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900">Customer Information</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Name:</span>
                                <p class="text-gray-900">${order.user ? order.user.name : 'Guest'}</p>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Email:</span>
                                <p class="text-gray-900">${order.user ? order.user.email : 'N/A'}</p>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Phone:</span>
                                <p class="text-gray-900">${order.user ? order.user.phone : 'N/A'}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900">Payment Details</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Method:</span>
                                <span class="payment-${order.payment_details.method || 'unknown'} px-2 py-1 rounded-full text-xs font-medium">
                                    ${this.formatPaymentMethod(order.payment_details.method)}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Status:</span>
                                <p class="text-gray-900">${order.payment_details.status}</p>
                            </div>
                            ${order.payment_details.gcash_payment_id ? `
                                <div>
                                    <span class="font-medium text-gray-700">GCash Payment ID:</span>
                                    <p class="gcash-payment-id mt-1">${order.payment_details.gcash_payment_id}</p>
                                </div>
                            ` : ''}
                            ${order.payment_details.transaction_id ? `
                                <div>
                                    <span class="font-medium text-gray-700">Transaction ID:</span>
                                    <p class="text-gray-900 font-mono text-sm">${order.payment_details.transaction_id}</p>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900">Order Items</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="space-y-3">
                            ${order.items.map(item => `
                                <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                                    <div>
                                        <p class="font-medium text-gray-900">${item.product ? item.product.name : item.product_name}</p>
                                        <p class="text-sm text-gray-500">Qty: ${item.quantity}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900">₱${parseFloat(item.unit_price).toLocaleString()}</p>
                                        <p class="text-sm text-gray-500">Total: ₱${parseFloat(item.total_price).toLocaleString()}</p>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;

        modal.classList.remove('hidden');
    }

    closeModal() {
        document.getElementById('order-modal').classList.add('hidden');
    }

    async printOrder(orderId) {
        try {
            const response = await fetch(`/admin/reporting/export-transactions?order_id=${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `order_${orderId}_${new Date().toISOString().split('T')[0]}.pdf`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            } else {
                this.showError('Failed to generate PDF');
            }
        } catch (error) {
            console.error('Error printing order:', error);
            this.showError('Failed to print order');
        }
    }

    async exportToPDF() {
        try {
            const queryParams = new URLSearchParams(this.currentFilters);
            const response = await fetch(`/admin/reporting/export-transactions?${queryParams}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `transactions_${new Date().toISOString().split('T')[0]}.pdf`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            } else {
                this.showError('Failed to generate PDF');
            }
        } catch (error) {
            console.error('Error exporting to PDF:', error);
            this.showError('Failed to export to PDF');
        }
    }

    async exportToExcel() {
        try {
            const queryParams = new URLSearchParams(this.currentFilters);
            const response = await fetch(`/admin/reporting/export-excel?${queryParams}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `transactions_${new Date().toISOString().split('T')[0]}.xlsx`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            } else {
                this.showError('Failed to generate Excel file');
            }
        } catch (error) {
            console.error('Error exporting to Excel:', error);
            this.showError('Failed to export to Excel');
        }
    }

    formatStatus(status) {
        const statusMap = {
            'pending': 'Pending',
            'confirmed': 'Confirmed',
            'processing': 'Processing',
            'shipped': 'Shipped',
            'delivered': 'Delivered',
            'cancelled': 'Cancelled',
            'refunded': 'Refunded'
        };
        return statusMap[status] || status;
    }

    formatPaymentMethod(method) {
        const methodMap = {
            'gcash': 'GCash',
            'credit_card': 'Credit Card',
            'debit_card': 'Debit Card',
            'cod': 'Cash on Delivery',
            'pickup': 'Pickup'
        };
        return methodMap[method] || method;
    }

    showLoading() {
        const tbody = document.getElementById('transactions-table-body');
        tbody.innerHTML = `
            <tr class="text-center">
                <td colspan="6" class="px-6 py-12 text-gray-500">
                    <i class="fas fa-spinner fa-spin text-4xl mb-4 block"></i>
                    <p class="text-lg font-medium">Loading transactions...</p>
                    <p class="text-sm">Please wait while we fetch your data</p>
                </td>
            </tr>
        `;
    }

    showError(message) {
        // You can implement a proper notification system here
        alert(message);
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Initialize the reporting panel
document.addEventListener('DOMContentLoaded', () => {
    window.reportingPanel = new ReportingPanel();
});
