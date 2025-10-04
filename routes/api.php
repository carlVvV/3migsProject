<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\Api\CartController as ApiCartController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    
    // Public product routes
    Route::get('/products', [ApiProductController::class, 'index']);
    Route::get('/products/{id}', [ApiProductController::class, 'show']);
    Route::get('/products/category/{category}', [ApiProductController::class, 'getByCategory']);
    Route::get('/categories', [ApiProductController::class, 'getCategories']);
    
    // Public cart routes (using session)
    Route::get('/cart', [ApiCartController::class, 'index']);
    Route::post('/cart/add', [ApiCartController::class, 'add']);
    Route::put('/cart/update/{id}', [ApiCartController::class, 'update']);
    Route::delete('/cart/remove/{id}', [ApiCartController::class, 'remove']);
    Route::delete('/cart/clear', [ApiCartController::class, 'clear']);
});

// Protected API routes (require authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/user', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::get('/user/orders', [UserController::class, 'getOrders']);
    
    // Order routes
    Route::post('/orders', [ApiOrderController::class, 'store']);
    Route::get('/orders/{id}', [ApiOrderController::class, 'show']);
    Route::get('/orders', [ApiOrderController::class, 'index']);
    
    // Wishlist routes
    Route::get('/wishlist', [UserController::class, 'getWishlist']);
    Route::post('/wishlist/add', [UserController::class, 'addToWishlist']);
    Route::delete('/wishlist/remove/{id}', [UserController::class, 'removeFromWishlist']);
});

// Health check route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running',
        'timestamp' => now()
    ]);
});
