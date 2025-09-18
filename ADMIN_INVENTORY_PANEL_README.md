# Admin Inventory Panel - 3 Migs Admin System

## Overview

The Admin Inventory Panel is a comprehensive inventory management interface designed for the 3 Migs Gowns & Barong admin system. It provides administrators with tools to track stock levels, manage product categories, monitor inventory status, and maintain product information.

## Features

### 🏷️ **Category Management**
- **Gowns** - Traditional Filipino gowns and formal wear
- **Barong** - Traditional Filipino formal shirts
- **Pants** - Formal pants and trousers
- **Other Formal Wear** - Additional formal clothing items

### 📊 **Inventory Tracking**
- **Stock Levels** - Current stock vs. maximum capacity (e.g., 32/100)
- **Status Monitoring** - In Stock, Out of Stock, Low Stock indicators
- **Item Identification** - Unique item IDs for each product
- **Product Details** - Complete product information and descriptions

### 🔍 **Search & Filtering**
- **Quick Search** - Real-time search across all inventory items
- **Category Filters** - Filter items by product category
- **Status Filters** - Filter by stock status (In Stock, Out of Stock, etc.)
- **Advanced Search** - Search by product name, ID, or category

### 📈 **Inventory Operations**
- **Add New Stock** - Add new inventory items with full details
- **Update Stock** - Modify existing inventory information
- **Delete Items** - Remove discontinued or obsolete items
- **Export Data** - Export inventory data in various formats

### 🎨 **User Interface**
- **Responsive Design** - Works on desktop, tablet, and mobile devices
- **Modern UI** - Clean, intuitive interface with Tailwind CSS
- **Interactive Elements** - Hover effects, smooth transitions, and animations
- **Status Badges** - Color-coded status indicators for quick recognition

## File Structure

```
three-migs/
├── resources/views/admin/
│   └── inventory-panel.blade.php      # Main inventory panel view
├── public/css/
│   └── inventory-panel.css            # Custom styling for inventory panel
├── app/Http/Controllers/Admin/
│   └── InventoryController.php        # Backend logic and API endpoints
└── routes/
    └── web.php                        # Route definitions
```

## Installation

### 1. **Ensure Laravel Project is Set Up**
```bash
cd three-migs
composer install
npm install
```

### 2. **Verify Routes are Registered**
The inventory routes should be automatically registered in `routes/web.php`:

```php
// Inventory Panel Routes
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
Route::get('/inventory-data', [InventoryController::class, 'getInventoryData'])->name('inventory.data');
Route::get('/search-inventory', [InventoryController::class, 'searchInventory'])->name('inventory.search');
Route::get('/filter-inventory-category', [InventoryController::class, 'filterByCategory'])->name('inventory.filter-category');
Route::get('/filter-inventory-status', [InventoryController::class, 'filterByStatus'])->name('inventory.filter-status');
Route::get('/paginated-inventory', [InventoryController::class, 'getPaginatedInventory'])->name('inventory.paginated');
Route::get('/inventory-item/{itemId}', [InventoryController::class, 'getInventoryItemDetails'])->name('inventory.item-details');
Route::patch('/inventory-item/{itemId}', [InventoryController::class, 'updateInventoryItem'])->name('inventory.update');
Route::delete('/inventory-item/{itemId}', [InventoryController::class, 'deleteInventoryItem'])->name('inventory.delete');
Route::post('/inventory-item', [InventoryController::class, 'addInventoryItem'])->name('inventory.add');
Route::get('/export-inventory', [InventoryController::class, 'exportInventory'])->name('inventory.export');
```

### 3. **Access the Inventory Panel**
Navigate to: `/admin/inventory`

## Database Integration

### **Required Database Tables**

```sql
-- Products/Inventory Table
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    item_id VARCHAR(50) UNIQUE NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    current_stock INT NOT NULL DEFAULT 0,
    max_capacity INT NOT NULL DEFAULT 100,
    status ENUM('In Stock', 'Out of Stock', 'Low Stock', 'Discontinued') DEFAULT 'In Stock',
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories Table
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Stock History Table
CREATE TABLE stock_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED,
    action ENUM('added', 'removed', 'updated') NOT NULL,
    quantity INT NOT NULL,
    previous_stock INT NOT NULL,
    new_stock INT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

### **Eloquent Models**

```php
// app/Models/Product.php
class Product extends Model
{
    protected $fillable = [
        'item_id', 'product_name', 'category', 'current_stock',
        'max_capacity', 'status', 'price', 'description'
    ];

    public function stockHistory()
    {
        return $this->hasMany(StockHistory::class);
    }
}

// app/Models/Category.php
class Category extends Model
{
    protected $fillable = ['name', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
```

### **Example Database Queries**

```php
// Get inventory statistics
public function getInventoryStatistics(): array
{
    return [
        'total_items' => Product::count(),
        'in_stock' => Product::where('status', 'In Stock')->count(),
        'out_of_stock' => Product::where('status', 'Out of Stock')->count(),
        'low_stock' => Product::where('status', 'Low Stock')->count(),
        'total_categories' => Category::count(),
        'total_value' => Product::sum('price')
    ];
}

// Search inventory
public function searchInventoryData(string $query): array
{
    return Product::where('product_name', 'LIKE', "%{$query}%")
        ->orWhere('item_id', 'LIKE', "%{$query}%")
        ->orWhere('category', 'LIKE', "%{$query}%")
        ->get()
        ->toArray();
}

// Filter by category
public function filterInventoryByCategory(string $category): array
{
    return Product::where('category', $category)->get()->toArray();
}
```

## API Endpoints

### **GET /admin/inventory-data**
Returns comprehensive inventory data including statistics, items list, categories, and statuses.

**Response:**
```json
{
    "statistics": {
        "total_items": 150,
        "in_stock": 120,
        "out_of_stock": 20,
        "low_stock": 10,
        "total_categories": 4,
        "total_value": 25000.00
    },
    "inventory": [...],
    "categories": ["Gowns", "Barong", "Pants", "Other Formal Wear"],
    "statuses": ["In Stock", "Out of Stock", "Low Stock", "Discontinued"]
}
```

### **GET /admin/search-inventory?query={search_term}**
Searches inventory items by product name, ID, or category.

### **GET /admin/filter-inventory-category?category={category_name}**
Filters inventory items by specific category.

### **GET /admin/filter-inventory-status?status={status_name}**
Filters inventory items by stock status.

### **POST /admin/inventory-item**
Adds a new inventory item.

**Request Body:**
```json
{
    "item_id": "#7679",
    "product_name": "New Product",
    "category": "Gowns",
    "current_stock": 50,
    "max_capacity": 100,
    "price": 1500.00,
    "description": "Product description"
}
```

## Customization

### **Adding New Categories**
1. Update the `getCategories()` method in `InventoryController.php`
2. Add new category buttons in the view
3. Update the category filtering logic

### **Modifying Status Types**
1. Update the `getStatuses()` method in `InventoryController.php`
2. Modify the status filter dropdown in the view
3. Update status badge styling in CSS

### **Custom Styling**
Modify `public/css/inventory-panel.css` to customize:
- Color schemes
- Animations and transitions
- Responsive breakpoints
- Component styling

## Performance Considerations

### **Database Optimization**
- Add indexes on frequently searched columns (`product_name`, `category`, `status`)
- Implement pagination for large inventory lists
- Use database caching for frequently accessed data

### **Frontend Optimization**
- Implement lazy loading for large tables
- Use debouncing for search inputs
- Cache API responses when appropriate

## Security Features

### **Access Control**
- Admin-only access through middleware
- CSRF protection on all forms
- Input validation and sanitization

### **Data Validation**
```php
// Example validation rules
$request->validate([
    'item_id' => 'required|unique:products,item_id',
    'product_name' => 'required|string|max:255',
    'category' => 'required|string|max:100',
    'current_stock' => 'required|integer|min:0',
    'max_capacity' => 'required|integer|min:1',
    'price' => 'required|numeric|min:0'
]);
```

## Troubleshooting

### **Common Issues**

1. **Routes Not Working**
   - Verify routes are registered in `web.php`
   - Check if middleware is properly configured
   - Ensure controller exists and is properly namespaced

2. **Styling Not Applied**
   - Verify CSS file path in view
   - Check if Tailwind CSS is loaded
   - Clear browser cache

3. **Database Connection Issues**
   - Verify database configuration in `.env`
   - Check if migrations have been run
   - Ensure database tables exist

### **Debug Mode**
Enable Laravel debug mode in `.env`:
```
APP_DEBUG=true
```

## Future Enhancements

### **Planned Features**
- **Barcode Integration** - Scan products for quick inventory updates
- **Low Stock Alerts** - Email notifications for items running low
- **Inventory Reports** - Detailed analytics and reporting
- **Supplier Management** - Track suppliers and reorder points
- **Multi-location Support** - Manage inventory across multiple stores

### **Integration Possibilities**
- **E-commerce Platforms** - Sync with online stores
- **Accounting Software** - Export data for financial reporting
- **Mobile App** - Inventory management on mobile devices

## Support

For technical support or feature requests, please refer to the main project documentation or contact the development team.

---

**Version:** 1.0.0  
**Last Updated:** January 2024  
**Compatibility:** Laravel 10+, PHP 8.1+
