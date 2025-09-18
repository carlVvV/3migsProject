<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Inventory Panel - 3 Migs Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/inventory-panel.css', 'resources/js/inventory-panel.js'])
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
                <a href="{{ route('admin.inventory') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-blue-600 text-white">
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
                    <form id="logout-form-inventory" action="{{ route('logout') }}" method="POST">
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
            <div class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-gray-900">Inventory</h1>
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-gray-700">Admin User</span>
                    </div>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="bg-white mx-6 mb-6 p-6 border border-gray-200 rounded-lg">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Inventory Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="fas fa-boxes text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-600">Total Items</p>
                                <p class="text-2xl font-bold text-blue-900" id="total-inventory-items">0</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-600">Available</p>
                                <p class="text-2xl font-bold text-green-900" id="available-inventory-items">0</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-yellow-600">Low Stock</p>
                                <p class="text-2xl font-bold text-yellow-900" id="low-stock-inventory-items">0</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-red-100 rounded-lg">
                                <i class="fas fa-times-circle text-red-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-600">Out of Stock</p>
                                <p class="text-2xl font-bold text-red-900" id="rented-inventory-items">0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Filters -->
            <div class="bg-white p-6">
                <div class="flex space-x-4" id="category-filters">
                    <!-- Categories will be loaded dynamically -->
                </div>
            </div>

            <!-- Inventory Section -->
            <div class="bg-white mx-6 mb-6 border border-blue-200">
                <div class="bg-blue-100 p-6 border-b border-blue-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-blue-600">In stock</h2>
                        <div class="flex items-center space-x-4">
                            <!-- Search Bar -->
                            <div class="relative">
                                <input type="text" id="inventory-search" placeholder="Quick search" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            

                            <button id="inventory-new-item" class="bg-green-600 text-white px-6 py-2 rounded-xl hover:bg-green-700 transition-colors flex items-center space-x-2">
                                <i class="fas fa-plus"></i>
                                <span>New Item +</span>
                            </button>
                            
                            
                            <button id="inventory-export" class="bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition-colors flex items-center space-x-2">
                                <i class="fas fa-download"></i>
                                <span>Export</span>
                            </button>
                            
                            <!-- Status Filter -->
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar text-gray-600"></i>
                                <select id="inventory-status-filter" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="all">All Status</option>
                                    <option value="available">In Stock</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                    <option value="low_stock">Low Stock</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Table -->
                <div class="p-6">
                    <table id="inventory-table" class="w-full">
                        <thead>
                            <tr class="border-b border-gray-300">
                                <th class="text-left py-3 px-4">
                                    <input type="checkbox" id="select-all-inventory" class="rounded border-gray-400">
                                </th>
                                <th class="text-left py-3 px-4 font-semibold">Item ID</th>
                                <th class="text-left py-3 px-4 font-semibold">Product</th>
                                <th class="text-left py-3 px-4 font-semibold">Category</th>
                                <th class="text-left py-3 px-4 font-semibold">Type</th>
                                <th class="text-left py-3 px-4 font-semibold">Items</th>
                                <th class="text-left py-3 px-4 font-semibold">Status</th>
                                <th class="text-left py-3 px-4 font-semibold">Indicators</th>
                                <th class="text-left py-3 px-4 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="inventory-table-body">
                            <!-- Inventory items will be loaded dynamically -->
                            <tr class="text-center">
                                <td colspan="9" class="px-6 py-12 text-gray-500">
                                    <i class="fas fa-boxes text-4xl mb-4 block"></i>
                                    <p class="text-lg font-medium">Loading inventory...</p>
                                    <p class="text-sm">Please wait while we fetch your data</p>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-4 px-4">
                                    <input type="checkbox" class="rounded border-gray-400">
                                </td>
                                <td class="py-4 px-4">#7677</td>
                                <td class="py-4 px-4">Lady Barong with Ethnic Sleeves</td>
                                <td class="py-4 px-4">Gowns</td>
                                <td class="py-4 px-4">Gown</td>
                                <td class="py-4 px-4">0/100</td>
                                <td class="py-4 px-4">
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                        Out of Stock
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Out of Stock
                                    </span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-4 px-4">
                                    <input type="checkbox" class="rounded border-gray-400">
                                </td>
                                <td class="py-4 px-4">#7678</td>
                                <td class="py-4 px-4">Womens Modern Inabel Filipiniana</td>
                                <td class="py-4 px-4">Gowns</td>
                                <td class="py-4 px-4">Gown</td>
                                <td class="py-4 px-4">76/100</td>
                                <td class="py-4 px-4">
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                        In Stock
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        In Stock
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between" id="pagination">
                        <!-- Pagination will be loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Item Modal -->
    <div id="new-item-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-screen overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-gray-900">Add New Inventory Item</h3>
                        <button id="close-new-item-modal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <form id="new-item-form" class="p-6">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                            <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                <!-- Categories will be loaded dynamically -->
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Type *</label>
                            <select name="product_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="gown">Gown</option>
                                <option value="barong">Barong</option>
                                <option value="accessory">Accessory</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Base Price *</label>
                            <input type="number" name="base_price" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                            <input type="number" name="sale_price" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="available">Available</option>
                                <option value="rented">Rented</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Status Indicators -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Status Indicators</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_new_arrival" class="mr-2 rounded border-gray-400">
                                <span class="text-sm text-gray-700">New Arrival</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_new_design" class="mr-2 rounded border-gray-400">
                                <span class="text-sm text-gray-700">New Design</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_featured" class="mr-2 rounded border-gray-400">
                                <span class="text-sm text-gray-700">Featured</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_customizable" class="mr-2 rounded border-gray-400">
                                <span class="text-sm text-gray-700">Customizable</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <!-- Image Upload -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div id="image-preview" class="w-32 h-32 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="main_image" id="main_image" accept="image/*" class="hidden" onchange="inventoryPanel.previewImage(this)">
                                <button type="button" onclick="document.getElementById('main_image').click()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-upload mr-2"></i>Choose Image
                                </button>
                                <p class="text-sm text-gray-500 mt-2">Recommended size: 800x800 pixels, Max: 2MB</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Variants Section -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Product Variants</h4>
                            <button type="button" id="add-variant-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Add Variant
                            </button>
                        </div>
                        
                        <div id="variants-container">
                            <!-- Variants will be added here dynamically -->
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <button type="button" id="cancel-new-item" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Add Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div id="edit-item-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-screen overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-gray-900">Edit Inventory Item</h3>
                        <button id="close-edit-item-modal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <form id="edit-item-form" class="p-6">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="product_id" id="edit-product-id">
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                            <input type="text" name="edit_name" id="edit-name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="edit_category_id" id="edit-category-id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                <!-- Categories will be loaded dynamically -->
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Type *</label>
                            <select name="edit_product_type" id="edit-product-type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="gown">Gown</option>
                                <option value="barong">Barong</option>
                                <option value="accessory">Accessory</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Base Price *</label>
                            <input type="number" name="edit_base_price" id="edit-base-price" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                            <input type="number" name="edit_sale_price" id="edit-sale-price" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="edit_status" id="edit-status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="available">Available</option>
                                <option value="rented">Rented</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Status Indicators -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Status Indicators</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="edit_is_new_arrival" id="edit-is-new-arrival" class="mr-2 rounded border-gray-400">
                                <span class="text-sm text-gray-700">New Arrival</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="edit_is_new_design" id="edit-is-new-design" class="mr-2 rounded border-gray-400">
                                <span class="text-sm text-gray-700">New Design</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="edit_is_featured" id="edit-is-featured" class="mr-2 rounded border-gray-400">
                                <span class="text-sm text-gray-700">Featured</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="edit_is_customizable" id="edit-is-customizable" class="mr-2 rounded border-gray-400">
                                <span class="text-sm text-gray-700">Customizable</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="edit_description" id="edit-description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <!-- Image Upload -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div id="edit-image-preview" class="w-32 h-32 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="edit_main_image" id="edit_main_image" accept="image/*" class="hidden" onchange="inventoryPanel.previewEditImage(this)">
                                <button type="button" onclick="document.getElementById('edit_main_image').click()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-upload mr-2"></i>Choose Image
                                </button>
                                <p class="text-sm text-gray-500 mt-2">Recommended size: 800x800 pixels, Max: 2MB</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Variants Section -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Product Variants</h4>
                            <button type="button" id="edit-add-variant-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Add Variant
                            </button>
                        </div>
                        
                        <div id="edit-variants-container">
                            <!-- Variants will be loaded here dynamically -->
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <button type="button" id="cancel-edit-item" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Update Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-gray-900">Delete Product</h3>
                            <p class="text-sm text-gray-500">Are you sure you want to delete this product? This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" id="cancel-delete" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="button" id="confirm-delete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    
</body>
</html>
