-- Add new status indicator fields to products table
-- Run this SQL script if the migration doesn't work

ALTER TABLE products 
ADD COLUMN is_new_arrival BOOLEAN DEFAULT FALSE,
ADD COLUMN is_new_design BOOLEAN DEFAULT FALSE,
ADD COLUMN product_type ENUM('gown', 'barong', 'accessory', 'other') DEFAULT 'gown';

-- Add index for better performance
CREATE INDEX idx_products_status_indicators ON products (is_new_arrival, is_new_design);

-- Update existing products to have a default product type
UPDATE products SET product_type = 'gown' WHERE product_type IS NULL;
