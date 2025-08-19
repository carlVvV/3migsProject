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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_variant_id');
            $table->enum('movement_type', ['in', 'out', 'adjustment', 'return', 'damage', 'transfer']);
            $table->integer('quantity');
            $table->integer('quantity_before');
            $table->integer('quantity_after');
            $table->string('reference_type')->nullable(); // order, return, adjustment, etc.
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of the reference
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // who made the change
            $table->timestamps();
            
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['product_variant_id', 'movement_type']);
            $table->index(['reference_type', 'reference_id']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
