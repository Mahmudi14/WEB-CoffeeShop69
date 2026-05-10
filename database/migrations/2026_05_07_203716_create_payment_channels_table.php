<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_channels', function (Blueprint $table) {
            $table->id();

            $table->string('method'); 
            // qris, transfer, ewallet

            $table->string('name');
            // Contoh: QRIS 69 Coffee, BCA, DANA, GoPay

            $table->string('account_name')->nullable();
            // Nama pemilik rekening / e-wallet

            $table->string('account_number')->nullable();
            // Nomor rekening / nomor e-wallet

            $table->string('qr_image_path')->nullable();
            // Untuk gambar QRIS

            $table->text('note')->nullable();
            // Instruksi tambahan

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index('method');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_channels');
    }
};
