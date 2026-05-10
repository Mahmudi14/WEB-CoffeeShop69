<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')->constrained('categories')->cascadeOnUpdate()->restrictOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->unsignedBigInteger('normal_price');
            $table->string('image_path')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index(['is_active', 'is_available']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};