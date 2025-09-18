<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CouponsController extends Controller
{
    /**
     * Display the coupons panel
     */
    public function index()
    {
        return view('admin.coupons-panel');
    }

    /**
     * Get coupons data for the panel
     */
    public function getCouponsData(): JsonResponse
    {
        try {
            $stats = [
                'totalCoupons' => Coupon::count(),
                'activeCoupons' => Coupon::active()->count(),
                'totalUsage' => Coupon::sum('used_count'),
                'totalSavings' => 0 // This would need to be calculated from orders
            ];

            $coupons = Coupon::orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($coupon) {
                    return [
                        'id' => $coupon->id,
                        'code' => $coupon->code,
                        'type' => $coupon->type,
                        'value' => $coupon->value,
                        'formatted_value' => $coupon->formatted_value,
                        'description' => $coupon->description,
                        'max_usage' => $coupon->max_usage,
                        'used_count' => $coupon->used_count,
                        'min_order_amount' => $coupon->min_order_amount,
                        'expiry_date' => $coupon->expiry_date?->format('M d, Y'),
                        'status' => $coupon->status,
                        'status_badge_class' => $coupon->status_badge_class,
                        'can_be_used' => $coupon->canBeUsed(),
                        'created_at' => $coupon->created_at->format('M d, Y')
                    ];
                });

            $data = [
                'stats' => $stats,
                'coupons' => $coupons
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'stats' => [
                    'totalCoupons' => 0,
                    'activeCoupons' => 0,
                    'totalUsage' => 0,
                    'totalSavings' => 0
                ],
                'coupons' => []
            ]);
        }
    }

    /**
     * Search coupons
     */
    public function searchCoupons(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            
            $coupons = Coupon::where('code', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($coupon) {
                    return [
                        'id' => $coupon->id,
                        'code' => $coupon->code,
                        'type' => $coupon->type,
                        'value' => $coupon->value,
                        'formatted_value' => $coupon->formatted_value,
                        'description' => $coupon->description,
                        'max_usage' => $coupon->max_usage,
                        'used_count' => $coupon->used_count,
                        'min_order_amount' => $coupon->min_order_amount,
                        'expiry_date' => $coupon->expiry_date?->format('M d, Y'),
                        'status' => $coupon->status,
                        'status_badge_class' => $coupon->status_badge_class,
                        'can_be_used' => $coupon->canBeUsed(),
                        'created_at' => $coupon->created_at->format('M d, Y')
                    ];
                });

            return response()->json(['coupons' => $coupons]);
        } catch (\Exception $e) {
            return response()->json(['coupons' => []]);
        }
    }

    /**
     * Filter coupons by type and status
     */
    public function filterCoupons(Request $request): JsonResponse
    {
        try {
            $type = $request->get('type');
            $status = $request->get('status');
            
            $query = Coupon::query();
            
            if ($type && $type !== 'all') {
                $query->where('type', $type);
            }
            
            if ($status && $status !== 'all') {
                if ($status === 'expired') {
                    $query->expired();
                } else {
                    $query->where('status', $status);
                }
            }
            
            $coupons = $query->orderBy('created_at', 'desc')
                ->get()
                ->map(function($coupon) {
                    return [
                        'id' => $coupon->id,
                        'code' => $coupon->code,
                        'type' => $coupon->type,
                        'value' => $coupon->value,
                        'formatted_value' => $coupon->formatted_value,
                        'description' => $coupon->description,
                        'max_usage' => $coupon->max_usage,
                        'used_count' => $coupon->used_count,
                        'min_order_amount' => $coupon->min_order_amount,
                        'expiry_date' => $coupon->expiry_date?->format('M d, Y'),
                        'status' => $coupon->status,
                        'status_badge_class' => $coupon->status_badge_class,
                        'can_be_used' => $coupon->canBeUsed(),
                        'created_at' => $coupon->created_at->format('M d, Y')
                    ];
                });

            return response()->json(['coupons' => $coupons]);
        } catch (\Exception $e) {
            return response()->json(['coupons' => []]);
        }
    }

    /**
     * Get paginated coupons
     */
    public function getPaginatedCoupons(Request $request): JsonResponse
    {
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            
            $coupons = Coupon::orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            $formattedCoupons = $coupons->getCollection()->map(function($coupon) {
                return [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'formatted_value' => $coupon->formatted_value,
                    'description' => $coupon->description,
                    'max_usage' => $coupon->max_usage,
                    'used_count' => $coupon->used_count,
                    'min_order_amount' => $coupon->min_order_amount,
                    'expiry_date' => $coupon->expiry_date?->format('M d, Y'),
                    'status' => $coupon->status,
                    'status_badge_class' => $coupon->status_badge_class,
                    'can_be_used' => $coupon->canBeUsed(),
                    'created_at' => $coupon->created_at->format('M d, Y')
                ];
            });

            $data = [
                'data' => $formattedCoupons,
                'current_page' => $coupons->currentPage(),
                'last_page' => $coupons->lastPage(),
                'per_page' => $coupons->perPage(),
                'total' => $coupons->total()
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'current_page' => $page,
                'last_page' => 1,
                'per_page' => $perPage,
                'total' => 0
            ]);
        }
    }

    /**
     * Get coupon details
     */
    public function getCouponDetails($couponId): JsonResponse
    {
        try {
            $coupon = Coupon::findOrFail($couponId);

            $formattedCoupon = [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'formatted_value' => $coupon->formatted_value,
                'description' => $coupon->description,
                'max_usage' => $coupon->max_usage,
                'used_count' => $coupon->used_count,
                'min_order_amount' => $coupon->min_order_amount,
                'expiry_date' => $coupon->expiry_date?->format('Y-m-d'),
                'status' => $coupon->status,
                'status_badge_class' => $coupon->status_badge_class,
                'can_be_used' => $coupon->canBeUsed(),
                'created_at' => $coupon->created_at->format('M d, Y'),
                'updated_at' => $coupon->updated_at->format('M d, Y')
            ];

            return response()->json(['coupon' => $formattedCoupon]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Coupon not found'], 404);
        }
    }

    /**
     * Create new coupon
     */
    public function store(Request $request): JsonResponse
    {
        // Validate first so validation errors return 422 instead of a generic 500
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percentage,fixed,free-shipping',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'max_usage' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $coupon = Coupon::create($validated);

            return response()->json([
                'message' => 'Coupon created successfully',
                'coupon' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'formatted_value' => $coupon->formatted_value,
                    'description' => $coupon->description,
                    'max_usage' => $coupon->max_usage,
                    'used_count' => $coupon->used_count,
                    'min_order_amount' => $coupon->min_order_amount,
                    'expiry_date' => $coupon->expiry_date?->format('M d, Y'),
                    'status' => $coupon->status,
                    'status_badge_class' => $coupon->status_badge_class,
                    'can_be_used' => $coupon->canBeUsed(),
                    'created_at' => $coupon->created_at->format('M d, Y')
                ]
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to create coupon',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update coupon
     */
    public function update(Request $request, $couponId): JsonResponse
    {
        // Validate first
        $validated = $request->validate([
            'code' => 'sometimes|required|string|max:50|unique:coupons,code,' . $couponId,
            'type' => 'sometimes|required|in:percentage,fixed,free-shipping',
            'value' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'max_usage' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'status' => 'sometimes|required|in:active,inactive'
        ]);

        try {
            $coupon = Coupon::findOrFail($couponId);
            $coupon->update($validated);

            return response()->json([
                'message' => 'Coupon updated successfully',
                'coupon' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'formatted_value' => $coupon->formatted_value,
                    'description' => $coupon->description,
                    'max_usage' => $coupon->max_usage,
                    'used_count' => $coupon->used_count,
                    'min_order_amount' => $coupon->min_order_amount,
                    'expiry_date' => $coupon->expiry_date?->format('M d, Y'),
                    'status' => $coupon->status,
                    'status_badge_class' => $coupon->status_badge_class,
                    'can_be_used' => $coupon->canBeUsed(),
                    'updated_at' => $coupon->updated_at->format('M d, Y')
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to update coupon',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete coupon
     */
    public function destroy($couponId): JsonResponse
    {
        try {
            $coupon = Coupon::findOrFail($couponId);
            $coupon->delete();

            return response()->json([
                'message' => 'Coupon deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete coupon',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get coupon statistics
     */
    public function getCouponStats(): JsonResponse
    {
        try {
            $stats = [
                'totalCoupons' => Coupon::count(),
                'activeCoupons' => Coupon::active()->count(),
                'inactiveCoupons' => Coupon::where('status', 'inactive')->count(),
                'expiredCoupons' => Coupon::expired()->count(),
                'totalUsage' => Coupon::sum('used_count'),
                'totalSavings' => 0, // This would need to be calculated from orders
                'averageDiscount' => Coupon::avg('value') ?? 0,
                'topUsedCoupon' => Coupon::orderBy('used_count', 'desc')->first()?->code
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'totalCoupons' => 0,
                'activeCoupons' => 0,
                'inactiveCoupons' => 0,
                'expiredCoupons' => 0,
                'totalUsage' => 0,
                'totalSavings' => 0,
                'averageDiscount' => 0,
                'topUsedCoupon' => null
            ]);
        }
    }

    /**
     * Export coupons data
     */
    public function exportCoupons(Request $request): JsonResponse
    {
        $format = $request->get('format', 'csv');
        
        // TODO: Implement actual export functionality
        // $coupons = Coupon::all();
        // 
        // if ($format === 'csv') {
        //     return $this->exportToCsv($coupons);
        // } elseif ($format === 'excel') {
        //     return $this->exportToExcel($coupons);
        // }

        return response()->json([
            'message' => 'Export functionality will be implemented here',
            'format' => $format
        ]);
    }

    /**
     * Bulk update coupon status
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'coupon_ids' => 'required|array',
                'coupon_ids.*' => 'integer|exists:coupons,id',
                'status' => 'required|in:active,inactive'
            ]);

            $updatedCount = Coupon::whereIn('id', $validated['coupon_ids'])
                ->update(['status' => $validated['status']]);

            return response()->json([
                'message' => "{$updatedCount} coupons updated successfully",
                'updated_count' => $updatedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update coupons',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get coupon types
     */
    public function getCouponTypes(): JsonResponse
    {
        $types = [
            ['value' => 'percentage', 'label' => 'Percentage Discount'],
            ['value' => 'fixed', 'label' => 'Fixed Amount Discount'],
            ['value' => 'free-shipping', 'label' => 'Free Shipping']
        ];

        return response()->json($types);
    }

    /**
     * Get coupon statuses
     */
    public function getCouponStatuses(): JsonResponse
    {
        $statuses = [
            ['value' => 'active', 'label' => 'Active'],
            ['value' => 'inactive', 'label' => 'Inactive'],
            ['value' => 'expired', 'label' => 'Expired']
        ];

        return response()->json($statuses);
    }
}
