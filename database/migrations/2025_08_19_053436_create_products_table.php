<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->decimal('base_price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->enum('status', ['available', 'rented', 'out_of_stock'])->default('available');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_customizable')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_new_design')->default(false);
            $table->enum('product_type', ['gown', 'barong', 'accessory', 'other'])->default('gown');
            $table->json('customization_options')->nullable(); // fabric, color, size, embroidery
            $table->json('measurements')->nullable(); // custom measurement fields
            $table->string('main_image');
            $table->json('gallery_images')->nullable();
            $table->integer('view_count')->default(0);
            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->index(['status', 'category_id', 'is_featured']);
            $table->index(['slug']);
            $table->index(['is_new_arrival', 'is_new_design']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
