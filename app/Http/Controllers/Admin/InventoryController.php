<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use App\Models\InventoryMovement;

class InventoryController extends Controller
{
    /**
     * Display the inventory panel
     */
    public function index()
    {
        return view('admin.inventory-panel');
    }

    /**
     * Get inventory data for the panel
     */
    public function getInventoryData(): JsonResponse
    {
        try {
            $data = [
                'statistics' => $this->getInventoryStatistics(),
                'inventory' => $this->getInventoryList(),
                'categories' => $this->getCategoriesArray(),
                'statuses' => $this->getStatuses()
            ];

            return response()->json([
                'success' => true,
                'message' => 'Inventory loaded',
                'statistics' => $data['statistics'],
                'inventory' => $data['inventory'],
                'categories' => $data['categories'],
                'statuses' => $data['statuses']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching inventory data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search inventory items
     */
    public function searchInventory(Request $request): JsonResponse
    {
        try {
            $query = $request->get('query', '');
            
            $results = $this->searchInventoryData($query);
            
            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching inventory: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filter inventory by category
     */
    public function filterByCategory(Request $request): JsonResponse
    {
        try {
            $category = $request->get('category', '');
            
            $results = $this->filterInventoryByCategory($category);
            
            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error filtering by category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filter inventory by status
     */
    public function filterByStatus(Request $request): JsonResponse
    {
        try {
            $status = $request->get('status', '');
            
            $results = $this->filterInventoryByStatus($status);
            
            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error filtering by status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get paginated inventory data
     */
    public function getPaginatedInventory(Request $request): JsonResponse
    {
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            
            $results = $this->getPaginatedInventoryData($page, $perPage);
            
            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching paginated inventory: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get inventory item details
     */
    public function getInventoryItemDetails($itemId): JsonResponse
    {
        try {
            $item = $this->getInventoryItemById($itemId);
            
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inventory item not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                // Keep both keys for compatibility with existing frontend code
                'product' => $item,
                'data' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching inventory item details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update inventory item
     */
    public function updateInventoryItem(Request $request, $itemId): JsonResponse
    {
        // Normalize checkbox fields before validation
        $request->merge([
            'edit_is_featured' => filter_var($request->input('edit_is_featured', false), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'edit_is_customizable' => filter_var($request->input('edit_is_customizable', false), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'edit_is_new_arrival' => filter_var($request->input('edit_is_new_arrival', false), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'edit_is_new_design' => filter_var($request->input('edit_is_new_design', false), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
        ]);

        $validated = $request->validate([
            'edit_name' => 'required|string|max:255',
            'edit_description' => 'nullable|string',
            'edit_short_description' => 'nullable|string|max:500',
            'edit_category_id' => 'required|exists:categories,id',
            'edit_base_price' => 'required|numeric|min:0',
            'edit_sale_price' => 'nullable|numeric|min:0',
            'edit_product_type' => 'required|in:gown,barong,accessory,other',
            'edit_is_featured' => 'sometimes|in:0,1',
            'edit_is_customizable' => 'sometimes|in:0,1',
            'edit_is_new_arrival' => 'sometimes|in:0,1',
            'edit_is_new_design' => 'sometimes|in:0,1',
            'edit_status' => 'required|in:available,rented,out_of_stock',
            'edit_main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'nullable|string' // JSON string from form
        ]);

        try {
            $product = Product::findOrFail($itemId);
            
            // Handle image upload
            $imagePath = $product->main_image;
            if ($request->hasFile('edit_main_image')) {
                $image = $request->file('edit_main_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/products'), $imageName);
                $imagePath = 'uploads/products/' . $imageName;
            }
            
            $product->update([
                'name' => $validated['edit_name'],
                'slug' => Str::slug($validated['edit_name']),
                'description' => $validated['edit_description'] ?? '',
                'short_description' => $validated['edit_short_description'] ?? '',
                'category_id' => $validated['edit_category_id'],
                'base_price' => $validated['edit_base_price'],
                'sale_price' => $validated['edit_sale_price'] ?? null,
                'product_type' => $validated['edit_product_type'],
                'status' => $validated['edit_status'],
                'is_featured' => filter_var($request->input('edit_is_featured', false), FILTER_VALIDATE_BOOLEAN),
                'is_customizable' => filter_var($request->input('edit_is_customizable', false), FILTER_VALIDATE_BOOLEAN),
                'is_new_arrival' => filter_var($request->input('edit_is_new_arrival', false), FILTER_VALIDATE_BOOLEAN),
                'is_new_design' => filter_var($request->input('edit_is_new_design', false), FILTER_VALIDATE_BOOLEAN),
                'main_image' => $imagePath
            ]);

            // Parse variants from JSON string
            $variants = [];
            if ($request->has('variants') && $request->variants) {
                $variants = json_decode($request->variants, true);
                if (!is_array($variants)) {
                    $variants = [];
                }
            }

            // Update variants if provided
            if (!empty($variants)) {
                // Get existing variant IDs
                $existingVariantIds = $product->variants->pluck('id')->toArray();
                $updatedVariantIds = [];
                
                foreach ($variants as $variantData) {
                    if (isset($variantData['id']) && in_array($variantData['id'], $existingVariantIds)) {
                        // Update existing variant
                        $variant = ProductVariant::find($variantData['id']);
                        if ($variant) {
                            $variant->update([
                                'name' => $variantData['name'],
                                'sku' => $variantData['sku'] ?? $variant->sku,
                                'stock_quantity' => $variantData['stock_quantity'],
                                'price_adjustment' => $variantData['price_adjustment'] ?? 0,
                                'is_active' => $variantData['is_active'] ?? true
                            ]);
                            $updatedVariantIds[] = $variant->id;
                        }
                    } else {
                        // Create new variant
                        $newVariant = $product->variants()->create([
                            'name' => $variantData['name'],
                            'sku' => $variantData['sku'] ?? Str::random(8),
                            'stock_quantity' => $variantData['stock_quantity'],
                            'price_adjustment' => $variantData['price_adjustment'] ?? 0,
                            'is_active' => $variantData['is_active'] ?? true
                        ]);
                        $updatedVariantIds[] = $newVariant->id;
                    }
                }
                
                // Delete variants that are no longer in the list
                $variantsToDelete = array_diff($existingVariantIds, $updatedVariantIds);
                if (!empty($variantsToDelete)) {
                    ProductVariant::whereIn('id', $variantsToDelete)->delete();
                }
            }

            // Refresh the product to get updated data
            $product->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category ? $product->category->name : 'Uncategorized',
                    'current_stock' => $product->total_stock,
                    'status' => $product->stock_status,
                    'price' => $product->current_price,
                    'product_type' => $product->product_type_label,
                    'status_badges' => $product->status_badges
                ]
            ], 200, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500, ['Content-Type' => 'application/json']);
        }
    }

    /**
     * Delete inventory item
     */
    public function deleteInventoryItem($itemId): JsonResponse
    {
        try {
            $product = Product::findOrFail($itemId);
            
            // Delete variants first (due to foreign key constraints)
            $product->variants()->delete();
            
            // Delete the product
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export inventory data
     */
    public function exportInventory(Request $request): JsonResponse
    {
        try {
            $format = $request->get('format', 'csv');
            
            $exportData = $this->prepareExportData();
            
            return response()->json([
                'success' => true,
                'data' => $exportData,
                'format' => $format
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error exporting inventory: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all categories for dropdowns
     */
    public function getCategories(): JsonResponse
    {
        try {
            $categories = Category::select('id', 'name')->orderBy('name')->get();
            
            return response()->json([
                'success' => true,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add new inventory item
     */
    public function addInventoryItem(Request $request): JsonResponse
    {
        // Normalize checkbox fields before validation
        $request->merge([
            'is_featured' => filter_var($request->input('is_featured', false), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'is_customizable' => filter_var($request->input('is_customizable', false), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'is_new_arrival' => filter_var($request->input('is_new_arrival', false), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'is_new_design' => filter_var($request->input('is_new_design', false), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'product_type' => 'required|in:gown,barong,accessory,other',
            'is_featured' => 'sometimes|in:0,1',
            'is_customizable' => 'sometimes|in:0,1',
            'is_new_arrival' => 'sometimes|in:0,1',
            'is_new_design' => 'sometimes|in:0,1',
            'status' => 'required|in:available,rented,out_of_stock',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'nullable|string' // JSON string from form
        ]);

        try {
            // Handle image upload
            $imagePath = 'default.jpg';
            if ($request->hasFile('main_image')) {
                $image = $request->file('main_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/products'), $imageName);
                $imagePath = 'uploads/products/' . $imageName;
            }

            // Parse variants from JSON string
            $variants = [];
            if ($request->has('variants') && $request->variants) {
                $variants = json_decode($request->variants, true);
            }

            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name']),
                'description' => $validated['description'] ?? '',
                'short_description' => $validated['short_description'] ?? '',
                'category_id' => $validated['category_id'],
                'base_price' => $validated['base_price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'product_type' => $validated['product_type'],
                'status' => $validated['status'],
                'is_featured' => (int) ($validated['is_featured'] ?? 0),
                'is_customizable' => (int) ($validated['is_customizable'] ?? 0),
                'is_new_arrival' => (int) ($validated['is_new_arrival'] ?? 0),
                'is_new_design' => (int) ($validated['is_new_design'] ?? 0),
                'main_image' => $imagePath
            ]);

            // Create variants if provided; validate minimally per-variant
            if (!empty($variants) && is_array($variants)) {
                $hasPriceAdjustment = Schema::hasColumn('product_variants', 'price_adjustment');
                $hasIsActive = Schema::hasColumn('product_variants', 'is_active');
                $hasAttributes = Schema::hasColumn('product_variants', 'attributes');
                $hasVariantData = Schema::hasColumn('product_variants', 'variant_data');
                $hasPriceCol = Schema::hasColumn('product_variants', 'price');
                foreach ($variants as $variantData) {
                    if (!isset($variantData['name'])) {
                        continue;
                    }
                    $payload = [
                        'name' => (string) $variantData['name'],
                        'sku' => isset($variantData['sku']) && $variantData['sku'] !== '' ? (string) $variantData['sku'] : Str::random(8),
                        'stock_quantity' => isset($variantData['stock_quantity']) && is_numeric($variantData['stock_quantity']) ? (int) $variantData['stock_quantity'] : 0,
                    ];
                    if ($hasPriceAdjustment) {
                        $payload['price_adjustment'] = isset($variantData['price_adjustment']) && is_numeric($variantData['price_adjustment']) ? (float) $variantData['price_adjustment'] : 0;
                    }
                    if ($hasIsActive) {
                        $payload['is_active'] = isset($variantData['is_active']) ? (bool) $variantData['is_active'] : true;
                    }
                    if ($hasPriceCol) {
                        // If explicit price provided, use it; else compute base_price + price_adjustment
                        if (isset($variantData['price']) && is_numeric($variantData['price'])) {
                            $payload['price'] = (float) $variantData['price'];
                        } else {
                            $base = (float) $product->base_price;
                            $adj = 0.0;
                            if ($hasPriceAdjustment && isset($payload['price_adjustment'])) {
                                $adj = (float) $payload['price_adjustment'];
                            }
                            $payload['price'] = $base + $adj;
                        }
                    }
                    if ($hasAttributes) {
                        // Default to empty JSON object/array depending on expectation; use empty array
                        $payload['attributes'] = json_encode($variantData['attributes'] ?? []);
                    }
                    if ($hasVariantData) {
                        $payload['variant_data'] = $variantData['variant_data'] ?? [];
                    }
                    $product->variants()->create($payload);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added successfully',
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category ? $product->category->name : 'Uncategorized',
                    'current_stock' => $product->total_stock,
                    'status' => $product->stock_status,
                    'price' => $product->current_price,
                    'product_type' => $product->product_type_label,
                    'status_badges' => $product->status_badges
                ]
            ], 201, ['Content-Type' => 'application/json']);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product (database error)',
                'error' => $e->getMessage()
            ], 500, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product',
                'error' => $e->getMessage()
            ], 500, ['Content-Type' => 'application/json']);
        }
    }

    private function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $base.'-'.($counter++);
        }
        return $slug;
    }

	/**
	 * Add new stock to a product variant and record movement
	 */
	public function addStock(Request $request): JsonResponse
	{
		$validated = $request->validate([
			'variant_id' => 'required|integer|exists:product_variants,id',
			'quantity' => 'required|integer|min:1',
			'reason' => 'nullable|string|max:255',
			'reference_type' => 'nullable|string|max:50',
			'reference_id' => 'nullable|integer',
		]);

		try {
			$variant = ProductVariant::findOrFail($validated['variant_id']);
			$before = (int) $variant->stock_quantity;
			$after = $before + (int) $validated['quantity'];

			DB::transaction(function () use ($variant, $validated, $before, $after) {
				$variant->update(['stock_quantity' => $after]);

				InventoryMovement::create([
					'product_variant_id' => $variant->id,
					'movement_type' => 'in',
					'quantity' => (int) $validated['quantity'],
					'quantity_before' => $before,
					'quantity_after' => $after,
					'reference_type' => $validated['reference_type'] ?? null,
					'reference_id' => $validated['reference_id'] ?? null,
					'reason' => $validated['reason'] ?? null,
					'user_id' => auth()->id(),
				]);
			});

			return response()->json([
				'success' => true,
				'message' => 'Stock added successfully',
				'variant_id' => $variant->id,
				'new_stock' => $after,
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Failed to add stock',
				'error' => $e->getMessage(),
			], 500);
		}
	}

	// Private helper methods with placeholder data

    private function getInventoryStatistics(): array
    {
        return [
            'total_items' => Product::count(),
            'available_items' => Product::where('status', 'available')->count(),
            'rented_items' => Product::where('status', 'rented')->count(),
            'low_stock_items' => Product::where('status', 'out_of_stock')->count()
        ];
    }

    private function getInventoryList(): array
    {
        $products = Product::with(['category:id,name', 'variants'])
            ->orderBy('name')
            ->get();

        return $products->map(function ($product) {
            $totalStock = $product->variants->sum('stock_quantity');
            $maxStock = $product->variants->sum('stock_quantity') + 50; // Assuming max capacity is current + 50
            
            return [
                'id' => $product->id,
                'product_name' => $product->name,
                'name' => $product->name,
                'category' => $product->category ? $product->category->name : 'Uncategorized',
                'product_type' => $product->product_type_label,
                'product_type_label' => $product->product_type_label,
                'current_stock' => $totalStock,
                'max_capacity' => $maxStock,
                'status' => $product->stock_status,
                'price' => $product->current_price,
                'is_new_arrival' => $product->is_new_arrival,
                'is_new_design' => $product->is_new_design,
                'is_featured' => $product->is_featured,
                'status_badges' => $product->status_badges,
                'last_updated' => $product->updated_at->format('Y-m-d')
            ];
        })->toArray();
    }

    private function getStatuses(): array
    {
        return [
            'in_stock' => 'In Stock',
            'out_of_stock' => 'Out of Stock',
            'low_stock' => 'Low Stock',
            'discontinued' => 'Discontinued'
        ];
    }

    private function searchInventoryData(string $query): array
    {
        $products = Product::with(['category:id,name', 'variants'])
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->get();

        return $products->map(function ($product) {
            $totalStock = $product->variants->sum('stock_quantity');
            return [
                'id' => $product->id,
                'product_name' => $product->name,
                'category' => $product->category ? $product->category->name : 'Uncategorized',
                'current_stock' => $totalStock,
                'max_capacity' => $totalStock + 50,
                'status' => $product->stock_status,
                'price' => $product->current_price,
                'last_updated' => $product->updated_at->format('Y-m-d')
            ];
        })->toArray();
    }

    private function filterInventoryByCategory(string $category): array
    {
        $products = Product::with(['category:id,name', 'variants'])
            ->whereHas('category', function ($q) use ($category) {
                $q->where('id', $category);
            })
            ->get();

        return $products->map(function ($product) {
            $totalStock = $product->variants->sum('stock_quantity');
            return [
                'id' => $product->id,
                'product_name' => $product->name,
                'category' => $product->category ? $product->category->name : 'Uncategorized',
                'current_stock' => $totalStock,
                'max_capacity' => $totalStock + 50,
                'status' => $product->stock_status,
                'price' => $product->current_price,
                'last_updated' => $product->updated_at->format('Y-m-d')
            ];
        })->toArray();
    }

    private function filterInventoryByStatus(string $status): array
    {
        $products = Product::with(['category:id,name', 'variants']);

        switch ($status) {
            case 'in_stock':
                $products->whereHas('variants', function ($q) {
                    $q->where('stock_quantity', '>', 10);
                });
                break;
            case 'out_of_stock':
                $products->whereDoesntHave('variants', function ($q) {
                    $q->where('stock_quantity', '>', 0);
                });
                break;
            case 'low_stock':
                $products->whereHas('variants', function ($q) {
                    $q->where('stock_quantity', '>', 0)
                      ->where('stock_quantity', '<=', 10);
                });
                break;
        }

        $products = $products->get();

        return $products->map(function ($product) {
            $totalStock = $product->variants->sum('stock_quantity');
            return [
                'id' => $product->id,
                'product_name' => $product->name,
                'category' => $product->category ? $product->category->name : 'Uncategorized',
                'current_stock' => $totalStock,
                'max_capacity' => $totalStock + 50,
                'status' => $product->stock_status,
                'price' => $product->current_price,
                'last_updated' => $product->updated_at->format('Y-m-d')
            ];
        })->toArray();
    }

    private function getPaginatedInventoryData(int $page, int $perPage): array
    {
        $products = Product::with(['category:id,name', 'variants'])
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'page', $page);

        $items = $products->getCollection()->map(function ($product) {
            $totalStock = $product->variants->sum('stock_quantity');
            return [
                'id' => $product->id,
                'product_name' => $product->name,
                'category' => $product->category ? $product->category->name : 'Uncategorized',
                'current_stock' => $totalStock,
                'max_capacity' => $totalStock + 50,
                'status' => $product->stock_status,
                'price' => $product->current_price,
                'last_updated' => $product->updated_at->format('Y-m-d')
            ];
        });

        return [
            'items' => $items,
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ]
        ];
    }

    private function getInventoryItemById(string $itemId): ?array
    {
        $product = Product::with(['category:id,name', 'variants'])
            ->find($itemId);

        if (!$product) {
            return null;
        }

        $totalStock = $product->variants->sum('stock_quantity');
        
        return [
            'id' => $product->id,
            'product_name' => $product->name,
            'description' => $product->description,
            'category' => $product->category ? $product->category->name : 'Uncategorized',
            'category_id' => $product->category_id,
            'current_stock' => $totalStock,
            'max_capacity' => $totalStock + 50,
            // Return both the persisted status and the computed stock_status
            'status' => $product->status,
            'stock_status' => $product->stock_status,
            'price' => $product->current_price,
            'sale_price' => $product->sale_price,
            'product_type' => $product->product_type,
            'product_type_label' => $product->product_type_label,
            'main_image' => $product->main_image,
            'last_updated' => $product->updated_at->format('Y-m-d'),
            'variants' => $product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'sku' => $variant->sku,
                    'stock_quantity' => $variant->stock_quantity,
                    // Include optional fields if they exist on the model
                    'price_adjustment' => $variant->getAttribute('price_adjustment') ?? 0,
                    'is_active' => (bool) ($variant->getAttribute('is_active') ?? true)
                ];
            })
        ];
    }

    private function updateInventoryItemData(string $itemId, array $data): bool
    {
        try {
            $product = Product::find($itemId);
            if (!$product) {
                return false;
            }

            $product->update([
                'name' => $data['name'] ?? $product->name,
                'description' => $data['description'] ?? $product->description,
                'base_price' => $data['base_price'] ?? $product->base_price,
                'sale_price' => $data['sale_price'] ?? $product->sale_price,
                'status' => $data['status'] ?? $product->status,
                'category_id' => $data['category_id'] ?? $product->category_id
            ]);

            // Update variants if provided
            if (isset($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    if (isset($variantData['id'])) {
                        $variant = ProductVariant::find($variantData['id']);
                        if ($variant) {
                            $variant->update([
                                'stock_quantity' => $variantData['stock_quantity'] ?? $variant->stock_quantity,
                                'price_adjustment' => $variantData['price_adjustment'] ?? $variant->price_adjustment,
                                'is_active' => $variantData['is_active'] ?? $variant->is_active
                            ]);
                        }
                    }
                }
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function deleteInventoryItemData(string $itemId): bool
    {
        try {
            $product = Product::find($itemId);
            if (!$product) {
                return false;
            }

            // Delete variants first
            $product->variants()->delete();
            
            // Delete the product
            $product->delete();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function addInventoryItemData(array $data): bool
    {
        try {
            $product = Product::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'] ?? '',
                'category_id' => $data['category_id'],
                'base_price' => $data['base_price'],
                'sale_price' => $data['sale_price'] ?? null,
                'status' => $data['status'] ?? 'available',
                'main_image' => $data['main_image'] ?? 'default.jpg'
            ]);

            // Create variants if provided
            if (isset($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    $product->variants()->create([
                        'name' => $variantData['name'],
                        'sku' => $variantData['sku'] ?? Str::random(8),
                        'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                        'price_adjustment' => $variantData['price_adjustment'] ?? 0,
                        'is_active' => $variantData['is_active'] ?? true
                    ]);
                }
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function prepareExportData(): array
    {
        try {
            $products = Product::with(['category:id,name', 'variants'])
                ->orderBy('name')
                ->get();

            return $products->map(function ($product) {
                $totalStock = $product->variants->sum('stock_quantity');
                $maxStock = $product->variants->sum('stock_quantity') + 50;
                
                return [
                    'id' => $product->id,
                    'product_name' => $product->name,
                    'category' => $product->category ? $product->category->name : 'Uncategorized',
                    'current_stock' => $totalStock,
                    'max_capacity' => $maxStock,
                    'status' => $product->stock_status,
                    'price' => $product->current_price,
                    'last_updated' => $product->updated_at->format('Y-m-d'),
                    'variants_count' => $product->variants->count()
                ];
            })->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getCategoriesArray(): array
    {
        return Category::select('id','name')->orderBy('name')->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        })->toArray();
    }
}
