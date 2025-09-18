<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\ReportingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Temporarily removed role middleware for testing
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
        Route::get('/filtered-orders', [DashboardController::class, 'getFilteredOrders'])->name('dashboard.orders');
        Route::get('/customer-retention', [DashboardController::class, 'getCustomerRetention'])->name('dashboard.retention');
        
        // Sales Panel Routes
        Route::get('/sales', [SalesController::class, 'index'])->name('sales');
        Route::get('/sales-data', [SalesController::class, 'getSalesData'])->name('sales.data');
        Route::get('/sales-chart', [SalesController::class, 'getSalesChart'])->name('sales.chart');
        Route::get('/search-orders', [SalesController::class, 'searchOrders'])->name('sales.search');
        Route::get('/filter-orders', [SalesController::class, 'filterOrders'])->name('sales.filter');
        Route::get('/sort-orders', [SalesController::class, 'sortOrders'])->name('sales.sort');
        
        // Inventory Panel Routes
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
        Route::get('/inventory-data', [InventoryController::class, 'getInventoryData'])->name('inventory.data');
        Route::get('/search-inventory', [InventoryController::class, 'searchInventory'])->name('inventory.search');
        Route::get('/filter-inventory-category', [InventoryController::class, 'filterByCategory'])->name('inventory.filter-category');
        Route::get('/filter-inventory-status', [InventoryController::class, 'filterByStatus'])->name('inventory.filter-status');
        Route::get('/paginated-inventory', [InventoryController::class, 'getPaginatedInventory'])->name('inventory.paginated');
        Route::get('/inventory-item/{itemId}', [InventoryController::class, 'getInventoryItemDetails'])->name('inventory.item-details');
        Route::patch('/inventory-item/{itemId}', [InventoryController::class, 'updateInventoryItem'])->name('inventory.update');
        Route::post('/inventory-item/{itemId}/update', [InventoryController::class, 'updateInventoryItem'])->name('inventory.update.post');
        Route::delete('/inventory-item/{itemId}', [InventoryController::class, 'deleteInventoryItem'])->name('inventory.delete');
        Route::post('/inventory-item', [InventoryController::class, 'addInventoryItem'])->name('inventory.add');
        Route::post('/inventory/add-stock', [InventoryController::class, 'addStock'])->name('inventory.add-stock');
        Route::get('/export-inventory', [InventoryController::class, 'exportInventory'])->name('inventory.export');
        // JSON endpoint for inventory forms; avoid conflict with CategoryController resource
        Route::get('/inventory/categories', [InventoryController::class, 'getCategories'])->name('inventory.categories');
        
        // Coupons Panel Routes
        Route::get('/coupons', [CouponsController::class, 'index'])->name('coupons');
        Route::get('/coupons-data', [CouponsController::class, 'getCouponsData'])->name('coupons.data');
        Route::get('/search-coupons', [CouponsController::class, 'searchCoupons'])->name('coupons.search');
        Route::get('/filter-coupons', [CouponsController::class, 'filterCoupons'])->name('coupons.filter');
        Route::get('/paginated-coupons', [CouponsController::class, 'getPaginatedCoupons'])->name('coupons.paginated');
        Route::get('/coupon/{couponId}', [CouponsController::class, 'getCouponDetails'])->name('coupons.details');
        Route::post('/coupon', [CouponsController::class, 'store'])->name('coupons.store');
        Route::patch('/coupon/{couponId}', [CouponsController::class, 'update'])->name('coupons.update');
        Route::delete('/coupon/{couponId}', [CouponsController::class, 'destroy'])->name('coupons.delete');
        Route::get('/coupon-stats', [CouponsController::class, 'getCouponStats'])->name('coupons.stats');
        Route::get('/export-coupons', [CouponsController::class, 'exportCoupons'])->name('coupons.export');
            Route::patch('/bulk-update-coupon-status', [CouponsController::class, 'bulkUpdateStatus'])->name('coupons.bulk-update');
    Route::get('/coupon-types', [CouponsController::class, 'getCouponTypes'])->name('coupons.types');
    Route::get('/coupon-statuses', [CouponsController::class, 'getCouponStatuses'])->name('coupons.statuses');
    
    // Reporting Panel Routes
    Route::get('/reporting', [ReportingController::class, 'index'])->name('reporting');
    Route::get('/reporting/transactions', [ReportingController::class, 'getTransactionData'])->name('reporting.transactions');
    Route::get('/reporting/order/{orderId}', [ReportingController::class, 'getOrderDetails'])->name('reporting.order.details');
    Route::post('/reporting/export-transactions', [ReportingController::class, 'exportTransactions'])->name('reporting.export.pdf');
    Route::post('/reporting/export-excel', [ReportingController::class, 'exportTransactions'])->name('reporting.export.excel');
    Route::get('/reporting/sales-analytics', [ReportingController::class, 'getSalesAnalytics'])->name('reporting.sales.analytics');
    Route::get('/reporting/inventory-report', [ReportingController::class, 'getInventoryReport'])->name('reporting.inventory');
        
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
    });


