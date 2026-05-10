<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('description')->nullable();

            $table->string('scope'); // all_menu, selected_menu
            $table->string('discount_type'); // percentage, fixed
            $table->decimal('discount_value', 12, 2);

            $table->unsignedInteger('priority')->default(1);

            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();

            $table->boolean('is_active')->default(true);

            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('scope');
            $table->index('discount_type');
            $table->index('priority');
            $table->index(['is_active', 'starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};