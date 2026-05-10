<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cashier_shifts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();

            $table->unsignedBigInteger('opening_cash')->default(0);

            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();

            $table->string('status')->default('open'); // open, closed

            $table->text('opening_note')->nullable();
            $table->text('closing_note')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('opened_at');
            $table->index('closed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashier_shifts');
    }
};