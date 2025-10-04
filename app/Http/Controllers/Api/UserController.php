<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Get user profile
     */
    public function profile()
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->pluck('name')->first() ?? 'customer',
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check current password if changing password
        if ($request->new_password) {
            if (!$request->current_password || !Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 400);
            }
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email
        ];

        if ($request->new_password) {
            $updateData['password'] = Hash::make($request->new_password);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->pluck('name')->first() ?? 'customer'
            ]
        ]);
    }

    /**
     * Get user's orders
     */
    public function getOrders(Request $request)
    {
        $user = Auth::user();
        $orders = Order::with(['items.product', 'items.productVariant'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'has_more' => $orders->hasMorePages()
            ]
        ]);
    }

    /**
     * Get user's wishlist
     */
    public function getWishlist()
    {
        $user = Auth::user();
        
        // For now, return empty wishlist since we don't have wishlist table yet
        // You can implement this when you add wishlist functionality
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Wishlist feature coming soon'
        ]);
    }

    /**
     * Add product to wishlist
     */
    public function addToWishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // For now, return success message since we don't have wishlist table yet
        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist (feature coming soon)'
        ]);
    }

    /**
     * Remove product from wishlist
     */
    public function removeFromWishlist($id)
    {
        // For now, return success message since we don't have wishlist table yet
        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist (feature coming soon)'
        ]);
    }
}
