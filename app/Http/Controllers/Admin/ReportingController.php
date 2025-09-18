<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class ReportingController extends Controller
{
    /**
     * Display the reporting panel.
     */
    public function index()
    {
        return view('admin.reporting-panel');
    }

    /**
     * Get comprehensive transaction data for reporting.
     */
    public function getTransactionData(Request $request): JsonResponse
    {
        try {
            $query = Order::with([
                'user:id,name,email,phone',
                'items.product:id,name,slug',
                'items.productVariant:id,name,sku',
                'payment'
            ]);

            // Apply filters
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }

            // Get paginated results
            $perPage = $request->get('per_page', 25);
            $orders = $query->orderBy('created_at', 'desc')->paginate($perPage);

            // Transform data to include payment details
            $orders->getCollection()->transform(function ($order) {
                $order->payment_details = $this->getPaymentDetails($order);
                $order->total_items_count = $order->items->sum('quantity');
                return $order;
            });

            return response()->json([
                'success' => true,
                'data' => $orders,
                'summary' => $this->getTransactionSummary($request)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch transaction data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed payment information including GCash payment ID.
     */
    private function getPaymentDetails(Order $order): array
    {
        $payment = $order->payment()->first();
        
        if (!$payment) {
            return [
                'status' => 'no_payment',
                'method' => null,
                'amount' => 0,
                'gcash_payment_id' => null,
                'transaction_id' => null,
                'payment_reference' => null,
                'paid_at' => null
            ];
        }

        $details = [
            'status' => $payment->status,
            'method' => $payment->payment_method,
            'amount' => $payment->amount,
            'transaction_id' => $payment->transaction_id,
            'payment_reference' => $payment->payment_reference,
            'paid_at' => $payment->paid_at,
            'gcash_payment_id' => null
        ];

        // Extract GCash payment ID if payment method is GCash
        if ($payment->payment_method === 'gcash') {
            $details['gcash_payment_id'] = $payment->gcash_payment_id;
        }

        return $details;
    }

    /**
     * Get transaction summary statistics.
     */
    private function getTransactionSummary(Request $request): array
    {
        $query = Order::query();

        // Apply date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $totalOrders = $query->count();
        $totalRevenue = $query->sum('total_amount');
        $completedOrders = $query->where('status', 'delivered')->count();
        $pendingOrders = $query->where('status', 'pending')->count();

        // Payment method breakdown
        $paymentMethods = $query->select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->get()
            ->pluck('count', 'payment_method')
            ->toArray();

        // Status breakdown
        $statusBreakdown = $query->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        return [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'completed_orders' => $completedOrders,
            'pending_orders' => $pendingOrders,
            'payment_methods' => $paymentMethods,
            'status_breakdown' => $statusBreakdown
        ];
    }

    /**
     * Export transactions to PDF.
     */
    public function exportTransactions(Request $request)
    {
        try {
            $query = Order::with([
                'user:id,name,email,phone',
                'items.product:id,name,slug',
                'items.productVariant:id,name,sku',
                'payment'
            ]);

            // Apply filters
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }

            $orders = $query->orderBy('created_at', 'desc')->get();

            // Transform data for PDF
            $orders->transform(function ($order) {
                $order->payment_details = $this->getPaymentDetails($order);
                $order->total_items_count = $order->items->sum('quantity');
                return $order;
            });

            $summary = $this->getTransactionSummary($request);

            $pdf = PDF::loadView('admin.reports.transactions-pdf', [
                'orders' => $orders,
                'summary' => $summary,
                'filters' => $request->all()
            ]);

            $filename = 'transactions_' . now()->format('Y-m-d_H-i-s') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export transactions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed order information for reporting.
     */
    public function getOrderDetails($orderId): JsonResponse
    {
        try {
            $order = Order::with([
                'user:id,name,email,phone,address',
                'items.product:id,name,slug,main_image',
                'items.productVariant:id,name,sku',
                'payment'
            ])->findOrFail($orderId);

            $order->payment_details = $this->getPaymentDetails($order);
            $order->total_items_count = $order->items->sum('quantity');

            return response()->json([
                'success' => true,
                'data' => $order
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch order details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sales analytics data.
     */
    public function getSalesAnalytics(Request $request): JsonResponse
    {
        try {
            $dateFrom = $request->get('date_from', now()->subDays(30)->format('Y-m-d'));
            $dateTo = $request->get('date_to', now()->format('Y-m-d'));

            // Daily sales data
            $dailySales = Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', '!=', 'cancelled')
                ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as orders')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Payment method breakdown
            $paymentMethods = Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', '!=', 'cancelled')
                ->selectRaw('payment_method, SUM(total_amount) as total, COUNT(*) as orders')
                ->groupBy('payment_method')
                ->get();

            // Top selling products
            $topProducts = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
                ->where('orders.status', '!=', 'cancelled')
                ->selectRaw('products.name, SUM(order_items.quantity) as total_quantity, SUM(order_items.total_price) as total_revenue')
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_quantity')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'daily_sales' => $dailySales,
                    'payment_methods' => $paymentMethods,
                    'top_products' => $topProducts
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sales analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get inventory report data.
     */
    public function getInventoryReport(): JsonResponse
    {
        try {
            // Category breakdown
            $categories = Category::withCount(['products', 'products as active_products' => function ($query) {
                $query->where('status', 'available');
            }])->get();

            // Stock status breakdown
            $stockStatus = Product::selectRaw('
                CASE 
                    WHEN (SELECT SUM(stock_quantity) FROM product_variants WHERE product_id = products.id) = 0 THEN "out_of_stock"
                    WHEN (SELECT SUM(stock_quantity) FROM product_variants WHERE product_id = products.id) <= 10 THEN "low_stock"
                    ELSE "in_stock"
                END as status,
                COUNT(*) as count
            ')
            ->groupBy('status')
            ->get();

            // Low stock products
            $lowStockProducts = Product::with(['category:id,name', 'variants'])
                ->whereHas('variants', function ($query) {
                    $query->where('stock_quantity', '>', 0)
                          ->where('stock_quantity', '<=', 10);
                })
                ->get()
                ->map(function ($product) {
                    $product->total_stock = $product->variants->sum('stock_quantity');
                    return $product;
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'categories' => $categories,
                    'stock_status' => $stockStatus,
                    'low_stock_products' => $lowStockProducts
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch inventory report: ' . $e->getMessage()
            ], 500);
        }
    }
}
