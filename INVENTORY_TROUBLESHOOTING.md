# Inventory System Troubleshooting Guide

## Issue: "Failed to Add Product" Error

### Step 1: Check Database Structure
1. **Click the "Debug DB" button** (yellow button) in the inventory panel
2. **Check the browser console** for the test results
3. **Look for any error messages** in the console

### Step 2: Database Fields Check
The system needs these new fields in the `products` table:
- `is_new_arrival` (BOOLEAN)
- `is_new_design` (BOOLEAN) 
- `product_type` (ENUM: 'gown', 'barong', 'accessory', 'other')

### Step 3: Manual Database Update
If the debug shows missing fields, run this SQL in phpMyAdmin:

```sql
ALTER TABLE products 
ADD COLUMN is_new_arrival BOOLEAN DEFAULT FALSE,
ADD COLUMN is_new_design BOOLEAN DEFAULT FALSE,
ADD COLUMN product_type ENUM('gown', 'barong', 'accessory', 'other') DEFAULT 'gown';

-- Add index for performance
CREATE INDEX idx_products_status_indicators ON products (is_new_arrival, is_new_design);

-- Update existing products
UPDATE products SET product_type = 'gown' WHERE product_type IS NULL;
```

### Step 4: Test Again
1. **Refresh the page**
2. **Click "Debug DB" again** to verify the fix
3. **Try adding a product** using the "New Item +" button

### Step 5: Check Console for Errors
1. **Open browser developer tools** (F12)
2. **Go to Console tab**
3. **Try to add a product**
4. **Look for any error messages** in red

### Common Issues & Solutions

#### Issue: "Column not found" error
**Solution**: Run the SQL script above to add missing columns

#### Issue: "Table doesn't exist" error
**Solution**: Check if the `products` table exists in your database

#### Issue: "Permission denied" error
**Solution**: Ensure your database user has ALTER and CREATE permissions

#### Issue: CSRF token error
**Solution**: Check if the CSRF meta tag is present in the page head

### Debug Information
The debug button will show:
- ✅ **Success**: Database structure is correct
- ❌ **Failed**: Shows specific error details
- **Field Status**: Lists which new fields exist/missing

### Still Having Issues?
1. **Check Laravel logs**: `storage/logs/laravel.log`
2. **Verify database connection** in `.env` file
3. **Clear Laravel cache**: `php artisan config:clear`
4. **Check browser console** for JavaScript errors

### Contact Support
If the issue persists, provide:
- Debug button results
- Browser console errors
- Laravel log entries
- Database structure screenshot
