<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion_menu', function (Blueprint $table) {
            $table->id();

            $table->foreignId('promotion_id')->constrained('promotions')->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['promotion_id', 'menu_id']);
            $table->index('menu_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_menu');
    }
};