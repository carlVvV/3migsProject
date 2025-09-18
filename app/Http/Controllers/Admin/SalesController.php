<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller
{
    /**
     * Display the sales panel view
     */
    public function index()
    {
        return view('admin.sales-panel');
    }

    /**
     * Get sales data for the dashboard
     */
    public function getSalesData(): JsonResponse
    {
        // TODO: Replace with actual database queries
        $data = $this->getSalesStatistics();
        
        return response()->json($data);
    }

    /**
     * Get sales chart data
     */
    public function getSalesChart(Request $request): JsonResponse
    {
        $period = $request->get('period', 7);
        
        // TODO: Replace with actual database queries
        $data = $this->getChartData($period);
        
        return response()->json($data);
    }

    /**
     * Search orders
     */
    public function searchOrders(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        // TODO: Replace with actual database queries
        $data = $this->searchOrdersData($query);
        
        return response()->json($data);
    }

    /**
     * Filter orders by status
     */
    public function filterOrders(Request $request): JsonResponse
    {
        $status = $request->get('status', '');
        
        // TODO: Replace with actual database queries
        $data = $this->filterOrdersData($status);
        
        return response()->json($data);
    }

    /**
     * Sort orders
     */
    public function sortOrders(Request $request): JsonResponse
    {
        $sortBy = $request->get('sort', 'newest');
        
        // TODO: Replace with actual database queries
        $data = $this->sortOrdersData($sortBy);
        
        return response()->json($data);
    }

    /**
     * Get sales statistics
     */
    private function getSalesStatistics(): array
    {
        try {
            // Current period (today)
            $totalSales = Order::where('status', '!=', 'cancelled')->sum('total_amount');
            $ordersToday = Order::whereDate('created_at', today())->count();
            $pendingOrders = Order::where('status', 'pending')->count();
            $completedOrders = Order::where('status', 'delivered')->count();
            
            // Previous period (yesterday)
            $yesterdaySales = Order::where('status', '!=', 'cancelled')
                ->whereDate('created_at', today()->subDay())
                ->sum('total_amount');
            $yesterdayOrders = Order::whereDate('created_at', today()->subDay())->count();
            $yesterdayPending = Order::where('status', 'pending')
                ->whereDate('created_at', today()->subDay())
                ->count();
            $yesterdayCompleted = Order::where('status', 'delivered')
                ->whereDate('created_at', today()->subDay())
                ->count();
            
            // Calculate percentage changes
            $salesChange = $this->calculatePercentageChange($totalSales, $yesterdaySales);
            $ordersChange = $this->calculatePercentageChange($ordersToday, $yesterdayOrders);
            $pendingChange = $this->calculatePercentageChange($pendingOrders, $yesterdayPending);
            $completedChange = $this->calculatePercentageChange($completedOrders, $yesterdayCompleted);
            
            return [
                'totalSales' => $totalSales,
                'ordersToday' => $ordersToday,
                'pendingOrders' => $pendingOrders,
                'completedOrders' => $completedOrders,
                'salesChange' => $salesChange,
                'ordersChange' => $ordersChange,
                'pendingChange' => $pendingChange,
                'completedChange' => $completedChange,
                'orders' => $this->getOrdersData(),
                'totalOrders' => Order::count()
            ];
        } catch (\Exception $e) {
            return [
                'totalSales' => 0,
                'ordersToday' => 0,
                'pendingOrders' => 0,
                'completedOrders' => 0,
                'salesChange' => 0,
                'ordersChange' => 0,
                'pendingChange' => 0,
                'completedChange' => 0,
                'orders' => [],
                'totalOrders' => 0
            ];
        }
    }

    /**
     * Get chart data
     */
    private function getChartData(int $period): array
    {
        try {
            $startDate = now()->subDays($period);
            $endDate = now();
            
            $data = Order::where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as orders')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            $labels = [];
            $values = [];
            $orderCounts = [];
            
            // Generate labels for the period
            for ($i = $period; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $labels[] = now()->subDays($i)->format('M d');
                
                $dayData = $data->where('date', $date)->first();
                $values[] = $dayData ? $dayData->total : 0;
                $orderCounts[] = $dayData ? $dayData->orders : 0;
            }
            
            return [
                'labels' => $labels,
                'values' => $values,
                'orderCounts' => $orderCounts
            ];
        } catch (\Exception $e) {
            return [
                'labels' => [],
                'values' => [],
                'orderCounts' => []
            ];
        }
    }

    /**
     * Search orders data
     */
    private function searchOrdersData(string $query): array
    {
        try {
            $orders = Order::with(['user:id,name,email', 'items.product:id,name'])
                ->where(function($q) use ($query) {
                    $q->where('order_number', 'like', "%{$query}%")
                      ->orWhereHas('user', function($q) use ($query) {
                          $q->where('name', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%");
                      })
                      ->orWhereHas('items.product', function($q) use ($query) {
                          $q->where('name', 'like', "%{$query}%");
                      });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            $formattedOrders = $orders->getCollection()->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->user ? $order->user->name : 'Guest',
                    'customer_email' => $order->user ? $order->user->email : 'N/A',
                    'product_name' => $order->items->first() ? $order->items->first()->product->name : 'N/A',
                    'amount' => $order->total_amount,
                    'status' => $order->status,
                    'date' => $order->created_at->format('M d, Y'),
                    'items_count' => $order->items->sum('quantity')
                ];
            });
            
            return [
                'orders' => $formattedOrders,
                'totalOrders' => $orders->total(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total()
                ]
            ];
        } catch (\Exception $e) {
            return [
                'orders' => [],
                'totalOrders' => 0,
                'pagination' => []
            ];
        }
    }

    /**
     * Filter orders data by status
     */
    private function filterOrdersData(string $status): array
    {
        try {
            $query = Order::with(['user:id,name,email', 'items.product:id,name']);
            
            if ($status !== 'all') {
                $query->where('status', $status);
            }
            
            $orders = $query->orderBy('created_at', 'desc')->paginate(10);
            
            $formattedOrders = $orders->getCollection()->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->user ? $order->user->name : 'Guest',
                    'customer_email' => $order->user ? $order->user->email : 'N/A',
                    'product_name' => $order->items->first() ? $order->items->first()->product->name : 'N/A',
                    'amount' => $order->total_amount,
                    'status' => $order->status,
                    'date' => $order->created_at->format('M d, Y'),
                    'items_count' => $order->items->sum('quantity')
                ];
            });
            
            return [
                'orders' => $formattedOrders,
                'totalOrders' => $orders->total(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total()
                ]
            ];
        } catch (\Exception $e) {
            return [
                'orders' => [],
                'totalOrders' => 0,
                'pagination' => []
            ];
        }
    }

    /**
     * Sort orders data
     */
    private function sortOrdersData(string $sortBy): array
    {
        try {
            $query = Order::with(['user:id,name,email', 'items.product:id,name']);
            
            switch ($sortBy) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'amount-high':
                    $query->orderBy('total_amount', 'desc');
                    break;
                case 'amount-low':
                    $query->orderBy('total_amount', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
            
            $orders = $query->paginate(10);
            
            $formattedOrders = $orders->getCollection()->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->user ? $order->user->name : 'Guest',
                    'customer_email' => $order->user ? $order->user->email : 'N/A',
                    'product_name' => $order->items->first() ? $order->items->first()->product->name : 'N/A',
                    'amount' => $order->total_amount,
                    'status' => $order->status,
                    'date' => $order->created_at->format('M d, Y'),
                    'items_count' => $order->items->sum('quantity')
                ];
            });
            
            return [
                'orders' => $formattedOrders,
                'totalOrders' => $orders->total(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total()
                ]
            ];
        } catch (\Exception $e) {
            return [
                'orders' => [],
                'totalOrders' => 0,
                'pagination' => []
            ];
        }
    }

    /**
     * Get orders data
     */
    private function getOrdersData(): array
    {
        try {
            $orders = Order::with(['user:id,name,email', 'items.product:id,name'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($order) {
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_name' => $order->user ? $order->user->name : 'Guest',
                        'product_name' => $order->items->first() ? $order->items->first()->product->name : 'N/A',
                        'amount' => $order->total_amount,
                        'status' => $order->status,
                        'date' => $order->created_at->format('M d, Y')
                    ];
                });
            
            return $orders->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get date range for filtering
     */
    private function getDateRange(string $range): ?array
    {
        switch ($range) {
            case 'today':
                return [now()->startOfDay(), now()->endOfDay()];
            case 'yesterday':
                return [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()];
            case 'week':
                return [now()->startOfWeek(), now()->endOfWeek()];
            case 'month':
                return [now()->startOfMonth(), now()->endOfMonth()];
            case 'quarter':
                return [now()->startOfQuarter(), now()->endOfQuarter()];
            case 'year':
                return [now()->startOfYear(), now()->endOfYear()];
            default:
                return null;
        }
    }

    /**
     * Calculate percentage change
     */
    private function calculatePercentageChange(float $current, float $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 2);
    }
}
