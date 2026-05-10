<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();

            $table->string('method'); // cash, qris, transfer
            $table->string('status'); // unpaid, pending_verification, paid, rejected, voided

            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('paid_amount')->default(0);
            $table->unsignedBigInteger('change_amount')->default(0);

            $table->string('proof_path')->nullable();
            $table->dateTime('proof_uploaded_at')->nullable();

            $table->string('reference')->nullable();
            $table->text('note')->nullable();

            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('verified_at')->nullable();

            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index('method');
            $table->index('status');
            $table->index('verified_by');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};