<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sales Panel - 3 Migs Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/sales-panel.css') }}">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-white p-6 border-r border-gray-200">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">3Migs Gowns & Barong</h1>
                <p class="text-gray-600 text-sm">Admin Dashboard</p>
            </div>
            
            <nav class="space-y-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:shadow-md hover:scale-105 transition-all duration-200">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.sales') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-blue-600 text-white">
                    <i class="fas fa-chart-line"></i>
                    <span>Sales Panel</span>
                </a>
                                                            <a href="{{ route('admin.inventory') }}" class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:shadow-md hover:scale-105 transition-all duration-200">
                    <i class="fas fa-boxes"></i>
                    <span>Inventory</span>
                </a>
                <a href="{{ route('admin.coupons') }}" class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:shadow-md hover:scale-105 transition-all duration-200">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Coupons</span>
                </a>
                
                <a href="{{ route('admin.reporting') }}" class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:shadow-md hover:scale-105 transition-all duration-200">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reporting</span>
                </a>
                
                <!-- Logout Button -->
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <form id="logout-form-sales" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-3 p-3 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Sales Panel</h2>
                        <p class="text-gray-600">Track and manage your orders</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-gray-700">Admin User</span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Sales</p>
                                <p class="text-2xl font-bold text-gray-900" id="totalSales">₱0</p>
                                <p class="text-sm text-green-600" id="salesChange">+0%</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-peso-sign text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Orders Today</p>
                                <p class="text-2xl font-bold text-gray-900" id="ordersToday">0</p>
                                <p class="text-sm text-blue-600" id="ordersChange">+0%</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="fas fa-shopping-bag text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                                <p class="text-2xl font-bold text-gray-900" id="pendingOrders">0</p>
                                <p class="text-sm text-yellow-600" id="pendingChange">+0%</p>
                            </div>
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Completed Orders</p>
                                <p class="text-2xl font-bold text-gray-900" id="completedOrders">0</p>
                                <p class="text-sm text-green-600" id="completedChange">+0%</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Chart -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Sales Overview</h3>
                        <div class="flex items-center space-x-2">
                            <select id="chartPeriod" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="7">Last 7 days</option>
                                <option value="30">Last 30 days</option>
                                <option value="90">Last 90 days</option>
                            </select>
                        </div>
                    </div>
                    <div class="h-80">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <input type="text" id="searchOrders" placeholder="Search orders..." 
                                           class="border border-gray-300 rounded-lg px-4 py-2 pl-10 text-sm w-64">
                                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                                <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <select id="sortOrders" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                    <option value="newest">Newest First</option>
                                    <option value="oldest">Oldest First</option>
                                    <option value="amount-high">Amount High to Low</option>
                                    <option value="amount-low">Amount Low to High</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Orders will be populated here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing <span id="showingStart">0</span> to <span id="showingEnd">0</span> of <span id="totalOrders">0</span> orders
                            </div>
                            <div class="flex items-center space-x-2">
                                <button id="prevPage" class="px-3 py-2 border border-gray-300 rounded-lg text-sm disabled:opacity-50">Previous</button>
                                <span class="px-3 py-2 text-sm text-gray-700">Page <span id="currentPage">1</span></span>
                                <button id="nextPage" class="px-3 py-2 border border-gray-300 rounded-lg text-sm disabled:opacity-50">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        class SalesPanelManager {
            constructor() {
                this.currentPage = 1;
                this.ordersPerPage = 10;
                this.salesChart = null;
                this.initializeEventListeners();
                this.loadSalesData();
                this.initializeCharts();
            }

            initializeEventListeners() {
                // Refresh button removed
                document.getElementById('chartPeriod').addEventListener('change', (e) => this.updateChart(e.target.value));
                document.getElementById('searchOrders').addEventListener('input', (e) => this.searchOrders(e.target.value));
                document.getElementById('statusFilter').addEventListener('change', (e) => this.filterOrders(e.target.value));
                document.getElementById('sortOrders').addEventListener('change', (e) => this.sortOrders(e.target.value));
                document.getElementById('prevPage').addEventListener('click', () => this.previousPage());
                document.getElementById('nextPage').addEventListener('click', () => this.nextPage());
            }

            async loadSalesData() {
                try {
                    const response = await fetch('/admin/sales-data');
                    const data = await response.json();
                    this.renderSalesData(data);
                } catch (error) {
                    console.error('Error loading sales data:', error);
                    this.renderSalesData(this.getEmptyData());
                }
            }

            renderSalesData(data) {
                // Update stats cards
                document.getElementById('totalSales').textContent = `₱${data.totalSales.toLocaleString()}`;
                document.getElementById('ordersToday').textContent = data.ordersToday;
                document.getElementById('pendingOrders').textContent = data.pendingOrders;
                document.getElementById('completedOrders').textContent = data.completedOrders;

                // Update percentage changes
                this.updatePercentageChange('salesChange', data.salesChange);
                this.updatePercentageChange('ordersChange', data.ordersChange);
                this.updatePercentageChange('pendingChange', data.pendingChange);
                this.updatePercentageChange('completedChange', data.completedChange);

                // Render orders table
                this.renderOrdersTable(data.orders);
                this.updatePagination(data.totalOrders);
            }

            renderOrdersTable(orders) {
                const tbody = document.getElementById('ordersTableBody');
                tbody.innerHTML = '';

                if (orders.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No orders found
                            </td>
                        </tr>
                    `;
                    return;
                }

                orders.forEach(order => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#${order.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${order.customer_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${order.product_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱${order.amount.toLocaleString()}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${this.getStatusClass(order.status)}">
                                ${order.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${order.date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                            <button class="text-green-600 hover:text-green-900 mr-3">Edit</button>
                            <button class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }

            getStatusClass(status) {
                const statusClasses = {
                    'pending': 'bg-yellow-100 text-yellow-800',
                    'processing': 'bg-blue-100 text-blue-800',
                    'shipped': 'bg-purple-100 text-purple-800',
                    'delivered': 'bg-green-100 text-green-800',
                    'cancelled': 'bg-red-100 text-red-800'
                };
                return statusClasses[status] || 'bg-gray-100 text-gray-800';
            }

            updatePercentageChange(elementId, change) {
                const element = document.getElementById(elementId);
                if (change >= 0) {
                    element.textContent = `+${change}%`;
                    element.className = 'text-sm text-green-600';
                } else {
                    element.textContent = `${change}%`;
                    element.className = 'text-sm text-red-600';
                }
            }

            updatePagination(totalOrders) {
                const totalPages = Math.ceil(totalOrders / this.ordersPerPage);
                const start = (this.currentPage - 1) * this.ordersPerPage + 1;
                const end = Math.min(this.currentPage * this.ordersPerPage, totalOrders);

                document.getElementById('showingStart').textContent = start;
                document.getElementById('showingEnd').textContent = end;
                document.getElementById('totalOrders').textContent = totalOrders;
                document.getElementById('currentPage').textContent = this.currentPage;

                document.getElementById('prevPage').disabled = this.currentPage === 1;
                document.getElementById('nextPage').disabled = this.currentPage === totalPages;
            }

            async searchOrders(query) {
                try {
                    const response = await fetch(`/admin/search-orders?q=${encodeURIComponent(query)}`);
                    const data = await response.json();
                    this.renderOrdersTable(data.orders);
                    this.updatePagination(data.totalOrders);
                } catch (error) {
                    console.error('Error searching orders:', error);
                }
            }

            async filterOrders(status) {
                try {
                    const response = await fetch(`/admin/filter-orders?status=${status}`);
                    const data = await response.json();
                    this.renderOrdersTable(data.orders);
                    this.updatePagination(data.totalOrders);
                } catch (error) {
                    console.error('Error filtering orders:', error);
                }
            }

            async sortOrders(sortBy) {
                try {
                    const response = await fetch(`/admin/sort-orders?sort=${sortBy}`);
                    const data = await response.json();
                    this.renderOrdersTable(data.orders);
                    this.updatePagination(data.totalOrders);
                } catch (error) {
                    console.error('Error sorting orders:', error);
                }
            }

            previousPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.loadSalesData();
                }
            }

            nextPage() {
                this.currentPage++;
                this.loadSalesData();
            }

            async updateChart(period) {
                try {
                    const response = await fetch(`/admin/sales-chart?period=${period}`);
                    const data = await response.json();
                    this.updateChartData(data);
                } catch (error) {
                    console.error('Error updating chart:', error);
                }
            }

            updateChartData(data) {
                if (this.salesChart) {
                    this.salesChart.data.labels = data.labels;
                    this.salesChart.data.datasets[0].data = data.values;
                    this.salesChart.update();
                }
            }

            initializeCharts() {
                const ctx = document.getElementById('salesChart').getContext('2d');
                this.salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Sales',
                            data: [0, 0, 0, 0, 0, 0, 0],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            refreshSalesPanel() {
                this.loadSalesData();
                this.updateChart(document.getElementById('chartPeriod').value);
            }

            getEmptyData() {
                return {
                    totalSales: 0,
                    ordersToday: 0,
                    pendingOrders: 0,
                    completedOrders: 0,
                    salesChange: 0,
                    ordersChange: 0,
                    pendingChange: 0,
                    completedChange: 0,
                    orders: [],
                    totalOrders: 0
                };
            }
        }

        // Initialize the sales panel when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            new SalesPanelManager();
        });
    </script>
</body>
</html>
