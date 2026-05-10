<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_number')->unique();

            $table->foreignId('cashier_shift_id')->nullable()->constrained('cashier_shifts')->nullOnDelete();
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('table_id')->nullable()->constrained('cafe_tables')->nullOnDelete();

            $table->string('customer_name');

            $table->string('order_source'); // cashier_pos, customer_qr
            $table->string('order_type'); // dine_in, takeaway

            $table->string('order_status'); // pending_payment, processing, cancelled, etc
            $table->string('payment_status'); // unpaid, paid, pending_verification, etc

            $table->unsignedBigInteger('subtotal_before_discount')->default(0);
            $table->unsignedBigInteger('discount_total')->default(0);
            $table->unsignedBigInteger('subtotal_after_discount')->default(0);

            $table->foreignId('tax_setting_id')->nullable()->constrained('tax_settings')->nullOnDelete();
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->unsignedBigInteger('tax_total')->default(0);

            $table->unsignedBigInteger('grand_total')->default(0);

            $table->dateTime('paid_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();

            $table->timestamps();

            $table->index('cashier_shift_id');
            $table->index('cashier_id');
            $table->index('table_id');
            $table->index('order_source');
            $table->index('order_type');
            $table->index('order_status');
            $table->index('payment_status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};