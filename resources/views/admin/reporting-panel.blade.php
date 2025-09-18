<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reporting Panel - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/reporting-panel.css') }}">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-white border-r border-gray-200 p-6">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">3Migs Gowns & Barong</h1>
                <p class="text-gray-600 text-sm">Admin Dashboard</p>
            </div>
            
            <nav class="space-y-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:shadow-md hover:scale-105 transition-all duration-200">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.sales') }}" class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:shadow-md hover:scale-105 transition-all duration-200">
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
                
                <a href="{{ route('admin.reporting') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-blue-600 text-white">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reporting</span>
                </a>
                
                <!-- Logout Button -->
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <form id="logout-form-reporting" action="{{ route('logout') }}" method="POST">
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
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Transaction Reporting & Analytics</h2>
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-gray-700">Admin User</span>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Orders</p>
                                <p id="total-orders" class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                                <p id="total-revenue" class="text-2xl font-bold text-gray-900">₱0</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-peso-sign text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Completed Orders</p>
                                <p id="completed-orders" class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                                <p id="pending-orders" class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                            <div class="flex space-x-2">
                                <input type="date" id="date-from" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <span class="text-gray-500 self-center">to</span>
                                <input type="date" id="date-to" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                            <select id="status-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <select id="payment-method-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Methods</option>
                                <option value="gcash">GCash</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="debit_card">Debit Card</option>
                                <option value="cod">Cash on Delivery</option>
                                <option value="pickup">Pickup</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" id="search-input" placeholder="Order #, Customer..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mt-4">
                        <div class="flex gap-3">
                            <button id="apply-filters" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-filter mr-2"></i>Apply Filters
                            </button>
                            <button id="reset-filters" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                                Reset
                            </button>
                        </div>
                        
                        <div class="flex gap-2">
                            <button id="export-pdf" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-file-pdf mr-2"></i>Export PDF
                            </button>
                            <button id="export-excel" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-file-excel mr-2"></i>Export Excel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="transactions-table-body" class="bg-white divide-y divide-gray-200">
                                <tr class="text-center">
                                    <td colspan="6" class="px-6 py-12 text-gray-500">
                                        <i class="fas fa-chart-bar text-4xl mb-4 block"></i>
                                        <p class="text-lg font-medium">Loading transactions...</p>
                                        <p class="text-sm">Please wait while we fetch your data</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200" id="pagination">
                        <!-- Pagination will be loaded dynamically -->
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="order-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Order Details</h3>
                        <button id="close-modal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <div class="px-6 py-4" id="order-modal-content">
                    <!-- Order details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/reporting-panel.js') }}"></script>
</body>
</html>
