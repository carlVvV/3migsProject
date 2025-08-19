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
        Schema::create('price_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('rule_type', ['percentage', 'fixed_amount', 'seasonal', 'bulk_discount', 'custom']);
            $table->json('conditions'); // product category, date range, quantity, etc.
            $table->decimal('adjustment_value', 10, 2); // percentage or fixed amount
            $table->enum('adjustment_type', ['increase', 'decrease']);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // higher priority rules apply first
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['rule_type', 'is_active']);
            $table->index(['start_date', 'end_date']);
            $table->index(['priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_rules');
    }
};
