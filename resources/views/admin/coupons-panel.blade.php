<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Coupons Panel - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/coupons-panel.css', 'resources/js/coupons-panel.js'])
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
                <a href="{{ route('admin.coupons') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-blue-600 text-white">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Coupons</span>
                </a>
                
                <a href="{{ route('admin.reporting') }}" class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:shadow-md hover:scale-105 transition-all duration-200">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reporting</span>
                </a>
                
                <!-- Logout Button -->
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <form id="logout-form-coupons" action="{{ route('logout') }}" method="POST">
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
                    <h2 class="text-xl font-semibold text-gray-900">Coupons Management</h2>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-gray-700">Admin User</span>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Create/Edit Coupon Modal -->
                <div id="coupon-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50">
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="bg-white rounded-lg shadow-xl w-full max-w-xl">
                            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <h3 id="coupon-modal-title" class="text-lg font-semibold text-gray-900">New Coupon</h3>
                                <button id="coupon-modal-close" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <input type="hidden" id="coupon-id">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                                    <input id="coupon-code" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., SAVE10">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                        <select id="coupon-type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="percentage">Percentage</option>
                                            <option value="fixed">Fixed Amount</option>
                                            <option value="free-shipping">Free Shipping</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                                        <input id="coupon-value" type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 10 or 100.00">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea id="coupon-description" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="2" placeholder="Optional description"></textarea>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Usage</label>
                                        <input id="coupon-max-usage" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 100">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Min Order Amount</label>
                                        <input id="coupon-min-amount" type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 500.00">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                                        <input id="coupon-expiry" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select id="coupon-status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                                <button id="coupon-modal-cancel" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200">Cancel</button>
                                <button id="coupon-modal-save" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Coupons</p>
                                <p class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Active Coupons</p>
                                <p class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Usage</p>
                                <p class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Savings</p>
                                <p class="text-2xl font-bold text-gray-900">₱0</p>
                            </div>
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <i class="fas fa-peso-sign text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 mb-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 flex gap-3">
                            <input type="text" placeholder="Search coupons..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button id="new-coupon-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>New Coupon
                            </button>
                        </div>
                        <div class="flex gap-3">
                            <select id="coupon-type-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Types</option>
                                <option value="percentage">Percentage</option>
                                <option value="fixed">Fixed Amount</option>
                                <option value="free-shipping">Free Shipping</option>
                            </select>
                            <select id="coupon-status-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="expired">Expired</option>
                            </select>
                            <button id="filter-coupons-btn" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Coupons Table -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="text-center">
                                    <td colspan="7" class="px-6 py-12 text-gray-500">
                                        <i class="fas fa-ticket-alt text-4xl mb-4 block"></i>
                                        <p class="text-lg font-medium">No coupons found</p>
                                        <p class="text-sm">Create your first coupon to get started</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    
</body>
</html>
