<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Get cart contents
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];

        foreach ($cart as $item) {
            $product = Product::with(['category', 'variants'])->find($item['product_id']);
            if ($product) {
                $variant = null;
                if (isset($item['variant_id'])) {
                    $variant = ProductVariant::find($item['variant_id']);
                }

                $cartItems[] = [
                    'id' => $item['id'],
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'price' => $variant ? $variant->price : $product->current_price,
                    'total' => ($variant ? $variant->price : $product->current_price) * $item['quantity']
                ];
            }
        }

        $total = array_sum(array_column($cartItems, 'total'));

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $cartItems,
                'total' => $total,
                'count' => count($cartItems)
            ]
        ]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $cart = Session::get('cart', []);
        $itemId = uniqid();

        $cart[] = [
            'id' => $itemId,
            'product_id' => $request->product_id,
            'variant_id' => $request->variant_id,
            'quantity' => $request->quantity,
            'added_at' => now()
        ];

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
            'data' => [
                'item_id' => $itemId,
                'cart_count' => count($cart)
            ]
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $cart = Session::get('cart', []);

        foreach ($cart as $key => $item) {
            if ($item['id'] === $id) {
                $cart[$key]['quantity'] = $request->quantity;
                break;
            }
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully'
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cart = Session::get('cart', []);

        $cart = array_filter($cart, function($item) use ($id) {
            return $item['id'] !== $id;
        });

        Session::put('cart', array_values($cart));

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart'
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        Session::forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }
}
