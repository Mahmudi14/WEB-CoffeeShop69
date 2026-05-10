<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('menu_id')->nullable()->constrained('menus')->nullOnDelete();

            $table->string('menu_name');

            $table->unsignedInteger('quantity');

            $table->unsignedBigInteger('normal_price');
            $table->unsignedBigInteger('final_price');

            $table->unsignedBigInteger('subtotal_before_discount');
            $table->unsignedBigInteger('total_discount')->default(0);
            $table->unsignedBigInteger('subtotal_after_discount');

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('menu_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};