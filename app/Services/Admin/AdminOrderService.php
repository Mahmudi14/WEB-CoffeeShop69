<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminOrderService
{
    public function loadDetail(Order $order): Order
    {
        return $order->load([
            'items.promotions.promotion',
            'payment',
            'table',
            'cashier',
            'shift.user',
            'taxSetting',
            'cancelledBy',
        ]);
    }

    public function canCancel(Order $order): bool
    {
        return ! in_array($order->order_status, $this->uncancellableStatuses(), true);
    }

    public function cancel(Order $order, User $admin, string $reason): Order
    {
        if (! $this->canCancel($order)) {
            throw ValidationException::withMessages([
                'order' => 'Order ini tidak bisa dibatalkan.',
            ]);
        }

        return DB::transaction(function () use ($order, $admin, $reason) {
            $order->loadMissing('payment');

            $order->update([
                'order_status' => Order::STATUS_CANCELLED,
                'payment_status' => Order::PAYMENT_VOIDED,
                'cancelled_by' => $admin->id,
                'cancelled_at' => now(),
                'cancel_reason' => $reason,
            ]);

            if ($order->payment) {
                $order->payment->update([
                    'status' => Payment::STATUS_VOIDED,
                    'note' => trim(($order->payment->note ? $order->payment->note . "\n" : '') . 'Dibatalkan admin: ' . $reason),
                ]);
            }

            return $order->fresh();
        });
    }

    private function uncancellableStatuses(): array
    {
        return [
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
            Order::STATUS_REJECTED,
            Order::STATUS_EXPIRED,
        ];
    }
}