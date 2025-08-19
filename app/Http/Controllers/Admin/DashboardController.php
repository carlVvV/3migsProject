<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getDashboardData()
    {
        try {
            // Get current month and last month for comparison
            $currentMonth = Carbon::now()->startOfMonth();
            $lastMonth = Carbon::now()->subMonth()->startOfMonth();

            // Orders data
            $ordersData = $this->getOrdersData($currentMonth, $lastMonth);
            
            // Users data
            $usersData = $this->getUsersData($currentMonth, $lastMonth);
            
            // Website stats
            $websiteStats = $this->getWebsiteStats($currentMonth, $lastMonth);
            
            // Best sellers
            $bestSellers = $this->getBestSellers();
            
            // Order statistics for table
            $orderStats = $this->getOrderStats();

            return response()->json([
                'success' => true,
                'data' => [
                    'orders' => $ordersData,
                    'users' => $usersData,
                    'website' => $websiteStats,
                    'bestSellers' => $bestSellers,
                    'orderStats' => $orderStats
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard data: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getOrdersData($currentMonth, $lastMonth)
    {
        // Get orders for current month
        $currentMonthOrders = Order::whereBetween('created_at', [
            $currentMonth,
            Carbon::now()->endOfMonth()
        ])->get();

        // Get orders for last month
        $lastMonthOrders = Order::whereBetween('created_at', [
            $lastMonth,
            $currentMonth->copy()->subSecond()
        ])->get();

        // Calculate current month stats
        $completed = $currentMonthOrders->where('status', 'completed')->count();
        $pending = $currentMonthOrders->where('status', 'pending')->count();
        $cancelled = $currentMonthOrders->where('status', 'cancelled')->count();

        // Calculate last month stats for comparison
        $lastMonthCompleted = $lastMonthOrders->where('status', 'completed')->count();
        $lastMonthPending = $lastMonthOrders->where('status', 'pending')->count();
        $lastMonthCancelled = $lastMonthOrders->where('status', 'cancelled')->count();

        // Calculate percentage changes
        $completedChange = $this->calculatePercentageChange($lastMonthCompleted, $completed);
        $pendingChange = $this->calculatePercentageChange($lastMonthPending, $pending);
        $cancelledChange = $this->calculatePercentageChange($lastMonthCancelled, $cancelled);

        return [
            'completed' => $completed,
            'pending' => $pending,
            'cancelled' => $cancelled,
            'completedChange' => $completedChange,
            'pendingChange' => $pendingChange,
            'cancelledChange' => $cancelledChange
        ];
    }

    private function getUsersData($currentMonth, $lastMonth)
    {
        // Get total users
        $totalUsers = User::count();
        
        // Get new users this month
        $newUsersThisMonth = User::whereBetween('created_at', [
            $currentMonth,
            Carbon::now()->endOfMonth()
        ])->count();
        
        // Get new users last month
        $newUsersLastMonth = User::whereBetween('created_at', [
            $lastMonth,
            $currentMonth->copy()->subSecond()
        ])->count();

        // Calculate percentage change
        $usersChange = $this->calculatePercentageChange($newUsersLastMonth, $newUsersThisMonth);

        return [
            'total' => $totalUsers,
            'newThisMonth' => $newUsersThisMonth,
            'change' => $usersChange
        ];
    }

    private function getWebsiteStats($currentMonth, $lastMonth)
    {
        // This is where you would integrate with your analytics system
        // For now, returning placeholder data structure
        
        // You can replace these with actual database queries or API calls
        $visitors = 0; // Replace with actual visitor tracking
        $productViews = 0; // Replace with actual product view tracking
        $newOrders = Order::whereBetween('created_at', [
            $currentMonth,
            Carbon::now()->endOfMonth()
        ])->count();
        $cancelled = Order::whereBetween('created_at', [
            $currentMonth,
            Carbon::now()->endOfMonth()
        ])->where('status', 'cancelled')->count();

        return [
            'visitors' => $visitors,
            'productViews' => $productViews,
            'newOrders' => $newOrders,
            'cancelled' => $cancelled
        ];
    }

    private function getBestSellers()
    {
        // Get best selling products based on order items
        $bestSellers = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                'products.category_id',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as order_count')
            )
            ->groupBy('products.id', 'products.name', 'products.image', 'products.category_id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Calculate percentages for progress bars
        $maxSales = $bestSellers->max('total_sold') ?: 1;
        
        return $bestSellers->map(function ($product) use ($maxSales) {
            $percentage = ($product->total_sold / $maxSales) * 100;
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image ?: 'https://via.placeholder.com/40x40',
                'category' => 'Category', // You can join with categories table
                'sales' => $product->total_sold,
                'percentage' => round($percentage, 1)
            ];
        });
    }

    private function getOrderStats()
    {
        // Get recent orders for the table
        $orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'customer' => $order->user ? $order->user->name : 'Guest',
                'amount' => number_format($order->total_amount, 2),
                'status' => $order->status,
                'date' => $order->created_at->format('M d, Y')
            ];
        });
    }

    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        
        $change = (($newValue - $oldValue) / $oldValue) * 100;
        return round($change, 1);
    }

    public function getFilteredOrders(Request $request)
    {
        $query = Order::with('user');

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'date':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'amount':
                    $query->orderBy('total_amount', 'desc');
                    break;
                case 'status':
                    $query->orderBy('status', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->limit(20)->get();

        $formattedOrders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'customer' => $order->user ? $order->user->name : 'Guest',
                'amount' => number_format($order->total_amount, 2),
                'status' => $order->status,
                'date' => $order->created_at->format('M d, Y')
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedOrders
        ]);
    }

    public function getCustomerRetention()
    {
        // Calculate customer retention over the last 6 months
        $months = collect();
        $retentionRates = collect();

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push($month->format('M'));
            
            // This is a simplified retention calculation
            // You might want to implement a more sophisticated retention metric
            $retentionRate = $this->calculateMonthlyRetention($month);
            $retentionRates->push($retentionRate);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $months->toArray(),
                'values' => $retentionRates->toArray()
            ]
        ]);
    }

    private function calculateMonthlyRetention($month)
    {
        // This is a placeholder implementation
        // You should implement your actual retention calculation logic here
        
        // For now, returning a random value between 60-95%
        return rand(60, 95);
    }
}
