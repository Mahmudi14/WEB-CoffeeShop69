<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class IncomingOrderPaymentService
{
    public function __construct(
        private readonly IncomingOrderGuard $guard,
        private readonly IncomingOrderPrintService $printService
    ) {
    }

    public function acceptCash(
        Order $order,
        CashierShift $activeShift,
        User $cashier,
        int $paidAmount
    ): void {
        $order->loadMissing('payment');

        $this->guard->ensurePendingCashOrder($order);

        DB::transaction(function () use ($order, $activeShift, $cashier, $paidAmount) {
            $order->update([
                'cashier_shift_id' => $activeShift->id,
                'cashier_id' => $cashier->id,
                'order_status' => Order::STATUS_PROCESSING,
                'payment_status' => Order::PAYMENT_PAID,
                'paid_at' => now(),
            ]);

            $order->payment->update([
                'status' => Payment::STATUS_PAID,
                'paid_amount' => $paidAmount,
                'change_amount' => $paidAmount - $order->grand_total,
                'verified_by' => $cashier->id,
                'verified_at' => now(),
            ]);

            $this->printService->createKitchenPrintIfMissing(
                order: $order,
                user: $cashier,
                activeShift: $activeShift
            );
        });
    }

    public function acceptProof(
        Order $order,
        CashierShift $activeShift,
        User $cashier
    ): void {
        $order->loadMissing('payment');

        $this->guard->ensurePendingProofOrder($order);

        DB::transaction(function () use ($order, $activeShift, $cashier) {
            $order->update([
                'cashier_shift_id' => $activeShift->id,
                'cashier_id' => $cashier->id,
                'order_status' => Order::STATUS_PROCESSING,
                'payment_status' => Order::PAYMENT_PAID,
                'paid_at' => now(),
            ]);

            $order->payment->update([
                'status' => Payment::STATUS_PAID,
                'paid_amount' => $order->grand_total,
                'change_amount' => 0,
                'verified_by' => $cashier->id,
                'verified_at' => now(),
            ]);

            $this->printService->createKitchenPrintIfMissing(
                order: $order,
                user: $cashier,
                activeShift: $activeShift
            );
        });
    }

    public function rejectProof(
        Order $order,
        User $cashier,
        string $reason
    ): void {
        $order->loadMissing('payment');

        $this->guard->ensurePendingProofOrder($order);

        DB::transaction(function () use ($order, $cashier, $reason) {
            $order->update([
                'order_status' => Order::STATUS_REJECTED,
                'payment_status' => Order::PAYMENT_REJECTED,
            ]);

            $order->payment->update([
                'status' => Payment::STATUS_REJECTED,
                'rejected_by' => $cashier->id,
                'rejected_at' => now(),
                'rejection_reason' => $reason,
            ]);
        });
    }

    public function complete(
        Order $order,
        CashierShift $activeShift
    ): void {
        $this->guard->ensureProcessingOrder($order, $activeShift);

        if (! $this->printService->hasPrintedCustomerReceipt($order)) {
            abort(422, 'Pesanan belum bisa diselesaikan. Struk customer harus dicetak minimal 1 kali.');
        }

        $order->update([
            'order_status' => Order::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }
}