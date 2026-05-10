<?php

namespace App\Services\Cashier;

use App\Models\Order;
use App\Models\User;

class CashierOrderGuard
{
    public function ensureBelongsToCashier(Order $order, User $cashier): void
    {
        $order->loadMissing('shift');

        if (! $order->shift || (int) $order->shift->user_id !== (int) $cashier->id) {
            abort(403, 'Kamu tidak punya akses ke order ini.');
        }
    }

    public function ensureCanPrintCustomerReceipt(Order $order): void
    {
        if ($order->payment_status !== Order::PAYMENT_PAID) {
            abort(422, 'Struk customer hanya bisa dicetak untuk order yang sudah paid.');
        }

        if (in_array($order->order_status, [
            Order::STATUS_CANCELLED,
            Order::STATUS_REJECTED,
            Order::STATUS_EXPIRED,
        ], true)) {
            abort(422, 'Order yang sudah batal/rejected/expired tidak bisa dicetak struk customer.');
        }
    }
}