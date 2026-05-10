<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_jobs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            $table->foreignId('cashier_shift_id')->nullable()->constrained('cashier_shifts')->cascadeOnDelete();

            $table->string('type'); // kitchen_order, customer_receipt, shift_closing
            $table->string('status')->default('pending'); // pending, printing, printed, failed

            $table->json('payload')->nullable();

            $table->text('error_message')->nullable();
            $table->unsignedInteger('attempts')->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->dateTime('printed_at')->nullable();
            $table->dateTime('failed_at')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('cashier_shift_id');
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_jobs');
    }
};