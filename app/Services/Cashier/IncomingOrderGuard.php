<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;
use App\Models\Order;
use App\Models\Payment;

class IncomingOrderGuard
{
    public function ensurePendingCashOrder(Order $order): void
    {
        $order->loadMissing('payment');

        if (
            $order->order_source !== Order::SOURCE_CUSTOMER_QR ||
            $order->order_status !== Order::STATUS_PENDING_PAYMENT ||
            $order->payment_status !== Order::PAYMENT_UNPAID ||
            ! $order->payment ||
            $order->payment->method !== Payment::METHOD_CASH
        ) {
            abort(422, 'Order ini bukan order tunai yang menunggu pembayaran.');
        }
    }

    public function ensurePendingProofOrder(Order $order): void
    {
        $order->loadMissing('payment');

        if (
            $order->order_source !== Order::SOURCE_CUSTOMER_QR ||
            $order->order_status !== Order::STATUS_PENDING_PAYMENT_VERIFICATION ||
            $order->payment_status !== Order::PAYMENT_PENDING_VERIFICATION ||
            ! $order->payment ||
            ! in_array($order->payment->method, [
                Payment::METHOD_QRIS,
                Payment::METHOD_TRANSFER,
            ], true)
        ) {
            abort(422, 'Order ini bukan order yang menunggu verifikasi bukti.');
        }
    }

    public function ensureProcessingOrder(Order $order, CashierShift $activeShift): void
    {
        if (
            ! in_array($order->order_source, [
                Order::SOURCE_CUSTOMER_QR,
                Order::SOURCE_CASHIER_POS,
            ], true) ||
            (int) $order->cashier_shift_id !== (int) $activeShift->id ||
            $order->order_status !== Order::STATUS_PROCESSING ||
            $order->payment_status !== Order::PAYMENT_PAID
        ) {
            abort(422, 'Order ini bukan order aktif yang sedang diproses pada shift kamu.');
        }
    }
}