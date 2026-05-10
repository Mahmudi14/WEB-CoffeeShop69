<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cashier_shift_id')->constrained('cashier_shifts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();

            $table->string('category');
            $table->unsignedBigInteger('amount');
            $table->text('note')->nullable();

            $table->timestamps();

            $table->index('cashier_shift_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_expenses');
    }
};