<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Three Migs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 text-white p-6">
            <div class="mb-8">
                <h1 class="text-2xl font-bold">Three Migs</h1>
                <p class="text-gray-300 text-sm">Admin Dashboard</p>
            </div>
            
            <nav class="space-y-4">
                <a href="#" class="flex items-center space-x-3 p-3 rounded-lg bg-blue-600 text-white">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="flex items-center space-x-3 p-3 rounded-lg text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
                <a href="#" class="flex items-center space-x-3 p-3 rounded-lg text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="#" class="flex items-center space-x-3 p-3 rounded-lg text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
                <a href="#" class="flex items-center space-x-3 p-3 rounded-lg text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-chart-bar"></i>
                    <span>Analytics</span>
                </a>
                <a href="#" class="flex items-center space-x-3 p-3 rounded-lg text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard Overview</h2>
                    <div class="flex items-center space-x-4">
                        <button id="refresh-dashboard" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center space-x-2">
                            <i class="fas fa-sync-alt"></i>
                            <span>Refresh</span>
                        </button>
                        <div class="relative">
                            <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <div class="flex items-center space-x-2">
                            <img src="https://via.placeholder.com/40x40" alt="Profile" class="w-10 h-10 rounded-full">
                            <span class="text-gray-700">Admin User</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="p-6 space-y-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Orders Completed -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Orders Completed</p>
                                <p class="text-2xl font-bold text-gray-900" id="orders-completed">0</p>
                                <p class="text-sm text-green-600" id="orders-completed-change">+0% from last month</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Orders -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                                <p class="text-2xl font-bold text-gray-900" id="pending-orders">0</p>
                                <p class="text-sm text-yellow-600" id="pending-orders-change">+0% from last month</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Cancelled Orders -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Cancelled Orders</p>
                                <p class="text-2xl font-bold text-gray-900" id="cancelled-orders">0</p>
                                <p class="text-sm text-red-600" id="cancelled-orders-change">+0% from last month</p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-times-circle text-red-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Users -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Users</p>
                                <p class="text-2xl font-bold text-gray-900" id="total-users">0</p>
                                <p class="text-sm text-blue-600" id="total-users-change">+0% from last month</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Order Stats Table -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Order Stats</h3>
                            <div class="flex space-x-2">
                                <select id="order-filter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="all">All Orders</option>
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <select id="order-sort" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="date">Sort by Date</option>
                                    <option value="amount">Sort by Amount</option>
                                    <option value="status">Sort by Status</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Order ID</th>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Customer</th>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Amount</th>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Status</th>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Date</th>
                                    </tr>
                                </thead>
                                <tbody id="order-stats-body">
                                    <!-- Order data will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Website Stats -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Website Stats</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Visitors</p>
                                    <p class="text-lg font-bold text-gray-900" id="total-visitors">0</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-eye text-blue-600"></i>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Product Views</p>
                                    <p class="text-lg font-bold text-gray-900" id="product-views">0</p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-shopping-bag text-green-600"></i>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">New Orders</p>
                                    <p class="text-lg font-bold text-gray-900" id="new-orders">0</p>
                                </div>
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-plus text-purple-600"></i>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Cancelled</p>
                                    <p class="text-lg font-bold text-gray-900" id="cancelled-stats">0</p>
                                </div>
                                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-times text-red-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Best Sellers and Customer Retention -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Best Sellers -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Best Sellers</h3>
                        
                        <div class="space-y-4" id="best-sellers-container">
                            <!-- Best seller items will be populated here -->
                        </div>
                    </div>

                    <!-- Customer Retention -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Customer Retention</h3>
                        
                        <div class="chart-container">
                            <canvas id="retention-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dashboard Data Management
        class DashboardManager {
            constructor() {
                this.data = {
                    orders: {
                        completed: 0,
                        pending: 0,
                        cancelled: 0
                    },
                    users: {
                        total: 0
                    },
                    website: {
                        visitors: 0,
                        productViews: 0,
                        newOrders: 0,
                        cancelled: 0
                    },
                    bestSellers: [],
                    orderStats: []
                };
                
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.loadDashboardData();
                this.renderDashboard();
                this.initializeCharts();
            }

            setupEventListeners() {
                // Order filter change
                document.getElementById('order-filter').addEventListener('change', (e) => {
                    this.filterOrders(e.target.value);
                });

                // Order sort change
                document.getElementById('order-sort').addEventListener('change', (e) => {
                    this.sortOrders(e.target.value);
                });

                // Refresh dashboard button
                document.getElementById('refresh-dashboard').addEventListener('click', () => {
                    this.refreshDashboard();
                });
            }

            async loadDashboardData() {
                try {
                    // Connect to Laravel backend API
                    const response = await fetch('/admin/dashboard-data');
                    const result = await response.json();
                    
                    if (result.success) {
                        this.data = result.data;
                    } else {
                        console.error('Error loading dashboard data:', result.message);
                        // Fallback to empty data
                        this.data = {
                            orders: {
                                completed: 0,
                                pending: 0,
                                cancelled: 0
                            },
                            users: {
                                total: 0
                            },
                            website: {
                                visitors: 0,
                                productViews: 0,
                                newOrders: 0,
                                cancelled: 0
                            },
                            bestSellers: [],
                            orderStats: []
                        };
                    }
                } catch (error) {
                    console.error('Error fetching dashboard data:', error);
                    // Fallback to empty data
                    this.data = {
                        orders: {
                            completed: 0,
                            pending: 0,
                            cancelled: 0
                        },
                        users: {
                            total: 0
                        },
                        website: {
                            visitors: 0,
                            productViews: 0,
                            newOrders: 0,
                            cancelled: 0
                        },
                        bestSellers: [],
                        orderStats: []
                    };
                }
            }

            renderDashboard() {
                this.updateStatsCards();
                this.updateOrderStats();
                this.updateWebsiteStats();
                this.updateBestSellers();
            }

            updateStatsCards() {
                document.getElementById('orders-completed').textContent = this.data.orders.completed;
                document.getElementById('pending-orders').textContent = this.data.orders.pending;
                document.getElementById('cancelled-orders').textContent = this.data.orders.cancelled;
                document.getElementById('total-users').textContent = this.data.users.total;

                // Update percentage changes
                this.updatePercentageChange('orders-completed-change', this.data.orders.completedChange);
                this.updatePercentageChange('pending-orders-change', this.data.orders.pendingChange);
                this.updatePercentageChange('cancelled-orders-change', this.data.orders.cancelledChange);
                this.updatePercentageChange('total-users-change', this.data.users.change);
            }

            updatePercentageChange(elementId, change) {
                const element = document.getElementById(elementId);
                if (element && change !== undefined) {
                    const sign = change >= 0 ? '+' : '';
                    const color = change >= 0 ? 'text-green-600' : 'text-red-600';
                    element.textContent = `${sign}${change}% from last month`;
                    element.className = `text-sm ${color}`;
                }
            }

            updateOrderStats() {
                const tbody = document.getElementById('order-stats-body');
                tbody.innerHTML = '';

                if (this.data.orderStats.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                No orders found. Connect to database to see order data.
                            </td>
                        </tr>
                    `;
                    return;
                }

                this.data.orderStats.forEach(order => {
                    const row = document.createElement('tr');
                    row.className = 'border-b border-gray-100 hover:bg-gray-50';
                    row.innerHTML = `
                        <td class="py-3 px-4 text-sm text-gray-900">#${order.id}</td>
                        <td class="py-3 px-4 text-sm text-gray-900">${order.customer}</td>
                        <td class="py-3 px-4 text-sm text-gray-900">$${order.amount}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full ${this.getStatusClass(order.status)}">
                                ${order.status}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-500">${order.date}</td>
                    `;
                    tbody.appendChild(row);
                });
            }

            updateWebsiteStats() {
                document.getElementById('total-visitors').textContent = this.data.website.visitors;
                document.getElementById('product-views').textContent = this.data.website.productViews;
                document.getElementById('new-orders').textContent = this.data.website.newOrders;
                document.getElementById('cancelled-stats').textContent = this.data.website.cancelled;
            }

            updateBestSellers() {
                const container = document.getElementById('best-sellers-container');
                
                if (this.data.bestSellers.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            No best sellers data available. Connect to database to see product performance.
                        </div>
                    `;
                    return;
                }

                container.innerHTML = '';
                this.data.bestSellers.forEach(item => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'flex items-center justify-between';
                    itemDiv.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <img src="${item.image}" alt="${item.name}" class="w-10 h-10 rounded-lg object-cover">
                            <div>
                                <p class="text-sm font-medium text-gray-900">${item.name}</p>
                                <p class="text-xs text-gray-500">${item.category}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">${item.sales}</p>
                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                <div class="progress-bar h-2 rounded-full" style="width: ${item.percentage}%"></div>
                            </div>
                        </div>
                    `;
                    container.appendChild(itemDiv);
                });
            }

            getStatusClass(status) {
                const classes = {
                    'completed': 'bg-green-100 text-green-800',
                    'pending': 'bg-yellow-100 text-yellow-800',
                    'cancelled': 'bg-red-100 text-red-800'
                };
                return classes[status] || 'bg-gray-100 text-gray-800';
            }

            async filterOrders(filter) {
                try {
                    const response = await fetch(`/admin/filtered-orders?status=${filter}&sort=${this.currentSort || 'date'}`);
                    const result = await response.json();
                    
                    if (result.success) {
                        this.data.orderStats = result.data;
                        this.updateOrderStats();
                    }
                } catch (error) {
                    console.error('Error filtering orders:', error);
                }
            }

            async sortOrders(sortBy) {
                this.currentSort = sortBy;
                try {
                    const currentFilter = document.getElementById('order-filter').value;
                    const response = await fetch(`/admin/filtered-orders?status=${currentFilter}&sort=${sortBy}`);
                    const result = await response.json();
                    
                    if (result.success) {
                        this.data.orderStats = result.data;
                        this.updateOrderStats();
                    }
                } catch (error) {
                    console.error('Error sorting orders:', error);
                }
            }

            async initializeCharts() {
                try {
                    // Load customer retention data from API
                    const response = await fetch('/admin/customer-retention');
                    const result = await response.json();
                    
                    let labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                    let data = [0, 0, 0, 0, 0, 0];
                    
                    if (result.success) {
                        labels = result.data.labels;
                        data = result.data.values;
                    }
                    
                    // Customer Retention Chart
                    const ctx = document.getElementById('retention-chart').getContext('2d');
                    this.retentionChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Retention Rate',
                                data: data,
                                borderColor: '#3B82F6',
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
                                    max: 100,
                                    ticks: {
                                        callback: function(value) {
                                            return value + '%';
                                        }
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error loading chart data:', error);
                    // Fallback chart with empty data
                    this.initializeFallbackChart();
                }
            }

            initializeFallbackChart() {
                const ctx = document.getElementById('retention-chart').getContext('2d');
                this.retentionChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Retention Rate',
                            data: [0, 0, 0, 0, 0, 0],
                            borderColor: '#3B82F6',
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
                                max: 100,
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Method to update data from database
            async updateFromDatabase() {
                try {
                    // This is where you'll make API calls to your Laravel backend
                    // const response = await fetch('/api/admin/dashboard-data');
                    // this.data = await response.json();
                    
                    // For now, just reload the dashboard
                    this.renderDashboard();
                } catch (error) {
                    console.error('Error loading dashboard data:', error);
                }
            }

            async refreshDashboard() {
                const refreshButton = document.getElementById('refresh-dashboard');
                const icon = refreshButton.querySelector('i');
                
                // Show loading state
                icon.className = 'fas fa-spinner fa-spin';
                refreshButton.disabled = true;
                
                try {
                    await this.loadDashboardData();
                    await this.initializeCharts();
                    this.renderDashboard();
                    
                    // Show success state briefly
                    icon.className = 'fas fa-check';
                    setTimeout(() => {
                        icon.className = 'fas fa-sync-alt';
                        refreshButton.disabled = false;
                    }, 1000);
                } catch (error) {
                    console.error('Error refreshing dashboard:', error);
                    icon.className = 'fas fa-exclamation-triangle';
                    setTimeout(() => {
                        icon.className = 'fas fa-sync-alt';
                        refreshButton.disabled = false;
                    }, 2000);
                }
            }
        }

        // Initialize dashboard when page loads
        document.addEventListener('DOMContentLoaded', () => {
            window.dashboardManager = new DashboardManager();
        });

        // Example of how to update data (for when you connect to database)
        function updateDashboardData(newData) {
            if (window.dashboardManager) {
                window.dashboardManager.data = { ...window.dashboardManager.data, ...newData };
                window.dashboardManager.renderDashboard();
            }
        }
    </script>
</body>
</html>
