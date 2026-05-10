<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_settings', function (Blueprint $table) {
            $table->id();

            $table->string('name')->default('PPN');
            $table->decimal('rate', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('price_includes_tax')->default(false);

            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_settings');
    }
};