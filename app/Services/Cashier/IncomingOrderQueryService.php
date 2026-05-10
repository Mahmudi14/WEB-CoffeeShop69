<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;

class IncomingOrderQueryService
{
    public function pendingCashQuery(): Builder
    {
        return Order::query()
            ->where('order_source', Order::SOURCE_CUSTOMER_QR)
            ->where('order_status', Order::STATUS_PENDING_PAYMENT)
            ->where('payment_status', Order::PAYMENT_UNPAID)
            ->whereHas('payment', function ($query) {
                $query->where('method', Payment::METHOD_CASH);
            });
    }

    public function pendingVerificationQuery(): Builder
    {
        return Order::query()
            ->where('order_source', Order::SOURCE_CUSTOMER_QR)
            ->where('order_status', Order::STATUS_PENDING_PAYMENT_VERIFICATION)
            ->where('payment_status', Order::PAYMENT_PENDING_VERIFICATION)
            ->whereHas('payment', function ($query) {
                $query->whereIn('method', [
                    Payment::METHOD_QRIS,
                    Payment::METHOD_TRANSFER,
                ]);
            });
    }

    public function processingQuery(CashierShift $activeShift): Builder
    {
        return Order::query()
            ->whereIn('order_source', [
                Order::SOURCE_CUSTOMER_QR,
                Order::SOURCE_CASHIER_POS,
            ])
            ->where('cashier_shift_id', $activeShift->id)
            ->where('order_status', Order::STATUS_PROCESSING)
            ->where('payment_status', Order::PAYMENT_PAID);
    }

    public function incomingOrdersBadgeQuery(): Builder
    {
        return Order::query()
            ->where(function ($query) {
                $query
                    ->where(function ($cashQuery) {
                        $cashQuery
                            ->where('order_source', Order::SOURCE_CUSTOMER_QR)
                            ->where('order_status', Order::STATUS_PENDING_PAYMENT)
                            ->where('payment_status', Order::PAYMENT_UNPAID)
                            ->whereHas('payment', function ($paymentQuery) {
                                $paymentQuery->where('method', Payment::METHOD_CASH);
                            });
                    })
                    ->orWhere(function ($proofQuery) {
                        $proofQuery
                            ->where('order_source', Order::SOURCE_CUSTOMER_QR)
                            ->where('order_status', Order::STATUS_PENDING_PAYMENT_VERIFICATION)
                            ->where('payment_status', Order::PAYMENT_PENDING_VERIFICATION)
                            ->whereHas('payment', function ($paymentQuery) {
                                $paymentQuery->whereIn('method', [
                                    Payment::METHOD_QRIS,
                                    Payment::METHOD_TRANSFER,
                                ]);
                            });
                    });
            });
    }

    public function incomingOrdersBadgeCount(): int
    {
        return $this->incomingOrdersBadgeQuery()->count();
    }
}