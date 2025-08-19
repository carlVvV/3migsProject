<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $weddingGownsCategory = Category::where('slug', 'wedding-gowns')->first();
        $barongCategory = Category::where('slug', 'barong-tagalog')->first();
        $eveningGownsCategory = Category::where('slug', 'evening-gowns')->first();

        // Wedding Gowns
        if ($weddingGownsCategory) {
            Product::create([
                'name' => 'Classic A-Line Wedding Gown',
                'slug' => 'classic-a-line-wedding-gown',
                'description' => 'A timeless A-line wedding gown with delicate lace detailing and a flowing train. Perfect for the traditional bride.',
                'short_description' => 'Timeless A-line design with lace detailing',
                'category_id' => $weddingGownsCategory->id,
                'base_price' => 25000.00,
                'status' => 'available',
                'is_featured' => true,
                'is_customizable' => true,
                'customization_options' => json_encode([
                    'fabrics' => ['Silk', 'Satin', 'Lace', 'Tulle'],
                    'colors' => ['White', 'Ivory', 'Champagne'],
                    'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                    'embroidery' => ['Simple', 'Detailed', 'Custom Design']
                ]),
                'measurements' => json_encode([
                    'bust', 'waist', 'hips', 'length', 'shoulder_width'
                ]),
                'main_image' => 'wedding-gown-1.jpg',
                'gallery_images' => json_encode([
                    'wedding-gown-1-front.jpg',
                    'wedding-gown-1-back.jpg',
                    'wedding-gown-1-detail.jpg'
                ]),
            ]);

            Product::create([
                'name' => 'Mermaid Wedding Gown',
                'slug' => 'mermaid-wedding-gown',
                'description' => 'A stunning mermaid silhouette wedding gown with crystal embellishments and a dramatic train.',
                'short_description' => 'Stunning mermaid silhouette with crystals',
                'category_id' => $weddingGownsCategory->id,
                'base_price' => 30000.00,
                'status' => 'available',
                'is_featured' => true,
                'is_customizable' => true,
                'customization_options' => json_encode([
                    'fabrics' => ['Silk', 'Satin', 'Lace', 'Tulle'],
                    'colors' => ['White', 'Ivory', 'Champagne'],
                    'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                    'embroidery' => ['Simple', 'Detailed', 'Custom Design']
                ]),
                'measurements' => json_encode([
                    'bust', 'waist', 'hips', 'length', 'shoulder_width'
                ]),
                'main_image' => 'wedding-gown-2.jpg',
                'gallery_images' => json_encode([
                    'wedding-gown-2-front.jpg',
                    'wedding-gown-2-back.jpg',
                    'wedding-gown-2-detail.jpg'
                ]),
            ]);
        }

        // Barong Tagalog
        if ($barongCategory) {
            Product::create([
                'name' => 'Traditional Barong Tagalog',
                'slug' => 'traditional-barong-tagalog',
                'description' => 'A classic Barong Tagalog made from fine piña fabric with traditional embroidery patterns.',
                'short_description' => 'Classic piña fabric with traditional embroidery',
                'category_id' => $barongCategory->id,
                'base_price' => 15000.00,
                'status' => 'available',
                'is_featured' => true,
                'is_customizable' => true,
                'customization_options' => json_encode([
                    'fabrics' => ['Piña', 'Jusi', 'Silk', 'Cotton'],
                    'colors' => ['White', 'Cream', 'Ivory'],
                    'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                    'embroidery' => ['Traditional', 'Modern', 'Custom Design']
                ]),
                'measurements' => json_encode([
                    'chest', 'waist', 'length', 'shoulder_width', 'sleeve_length'
                ]),
                'main_image' => 'barong-1.jpg',
                'gallery_images' => json_encode([
                    'barong-1-front.jpg',
                    'barong-1-back.jpg',
                    'barong-1-detail.jpg'
                ]),
            ]);
        }

        // Evening Gowns
        if ($eveningGownsCategory) {
            Product::create([
                'name' => 'Elegant Evening Gown',
                'slug' => 'elegant-evening-gown',
                'description' => 'A sophisticated evening gown perfect for formal events and special occasions.',
                'short_description' => 'Sophisticated design for formal events',
                'category_id' => $eveningGownsCategory->id,
                'base_price' => 18000.00,
                'status' => 'available',
                'is_featured' => false,
                'is_customizable' => true,
                'customization_options' => json_encode([
                    'fabrics' => ['Silk', 'Satin', 'Velvet', 'Chiffon'],
                    'colors' => ['Black', 'Red', 'Blue', 'Green', 'Custom'],
                    'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                    'embroidery' => ['Simple', 'Detailed', 'Custom Design']
                ]),
                'measurements' => json_encode([
                    'bust', 'waist', 'hips', 'length', 'shoulder_width'
                ]),
                'main_image' => 'evening-gown-1.jpg',
                'gallery_images' => json_encode([
                    'evening-gown-1-front.jpg',
                    'evening-gown-1-back.jpg',
                    'evening-gown-1-detail.jpg'
                ]),
            ]);
        }
    }
}
