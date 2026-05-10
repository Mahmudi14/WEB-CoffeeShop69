<?php

namespace App\Services\Customer;

use App\Models\Order;

class CustomerOrderStatusPresenter
{
    public function orderStatusLabel(Order $order): string
    {
        return match ($order->order_status) {
            Order::STATUS_PENDING_PAYMENT => 'Menunggu pembayaran di kasir',
            Order::STATUS_PENDING_PAYMENT_VERIFICATION => 'Menunggu verifikasi pembayaran',
            Order::STATUS_PROCESSING => 'Pesanan sedang diproses',
            Order::STATUS_COMPLETED => 'Pesanan selesai',
            Order::STATUS_CANCELLED => 'Pesanan dibatalkan',
            Order::STATUS_REJECTED => 'Pesanan ditolak',
            Order::STATUS_EXPIRED => 'Pesanan kedaluwarsa',
            default => 'Status tidak diketahui',
        };
    }

    public function paymentStatusLabel(Order $order): string
    {
        return match ($order->payment_status) {
            Order::PAYMENT_UNPAID => 'Belum dibayar',
            Order::PAYMENT_PENDING_VERIFICATION => 'Menunggu verifikasi',
            Order::PAYMENT_PAID => 'Sudah dibayar',
            Order::PAYMENT_REJECTED => 'Pembayaran ditolak',
            Order::PAYMENT_VOIDED => 'Pembayaran dibatalkan',
            default => 'Status pembayaran tidak diketahui',
        };
    }
}