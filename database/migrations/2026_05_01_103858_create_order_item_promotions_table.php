<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_item_promotions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('promotion_id')->nullable()->constrained('promotions')->nullOnDelete();

            $table->string('promotion_name');
            $table->string('discount_type');
            $table->decimal('discount_value', 12, 2);
            $table->unsignedInteger('priority');

            $table->unsignedBigInteger('price_before_discount');
            $table->unsignedBigInteger('discount_amount_per_unit');
            $table->unsignedBigInteger('price_after_discount');

            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('discount_amount_total');

            $table->unsignedInteger('applied_order');

            $table->timestamps();

            $table->index('order_item_id');
            $table->index('promotion_id');
            $table->index('applied_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_promotions');
    }
};