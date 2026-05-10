<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ShiftExpense;
use App\Models\User;

class CashierDashboardService
{
    private const EXCLUDED_ORDER_STATUSES = [
        Order::STATUS_CANCELLED,
        Order::STATUS_REJECTED,
        Order::STATUS_EXPIRED,
    ];

    public function dashboardData(User $cashier): array
    {
        $activeShift = $this->activeShiftForCashier($cashier);

        $pendingCashOrders = $this->pendingCashOrdersCount();
        $pendingVerificationOrders = $this->pendingVerificationOrdersCount();

        $summary = $this->defaultSummary(
            activeShift: $activeShift,
            pendingCashOrders: $pendingCashOrders,
            pendingVerificationOrders: $pendingVerificationOrders
        );

        if ($activeShift) {
            $summary = array_merge(
                $summary,
                $this->activeShiftSummary($activeShift)
            );
        }

        return [
            'activeShift' => $activeShift,
            'summary' => $summary,
            'incomingOrderCount' => $pendingCashOrders + $pendingVerificationOrders,
        ];
    }

    private function activeShiftForCashier(User $cashier): ?CashierShift
    {
        return CashierShift::query()
            ->where('user_id', $cashier->id)
            ->where('status', CashierShift::STATUS_OPEN)
            ->latest('opened_at')
            ->first();
    }

    private function defaultSummary(
        ?CashierShift $activeShift,
        int $pendingCashOrders,
        int $pendingVerificationOrders
    ): array {
        return [
            'has_active_shift' => (bool) $activeShift,
            'opening_cash' => (int) ($activeShift?->opening_cash ?? 0),

            'cash_sales' => 0,
            'qris_sales' => 0,
            'transfer_sales' => 0,
            'expense_total' => 0,
            'estimated_cash' => 0,

            'paid_orders' => 0,
            'incoming_orders' => $pendingCashOrders + $pendingVerificationOrders,
            'pending_cash_orders' => $pendingCashOrders,
            'pending_verification_orders' => $pendingVerificationOrders,

            'completed_orders' => 0,
            'cancelled_orders' => 0,
            'rejected_orders' => 0,
            'cancelled_or_rejected_orders' => 0,
        ];
    }

    private function activeShiftSummary(CashierShift $shift): array
    {
        $cashSales = $this->salesByPaymentMethod($shift, Payment::METHOD_CASH);
        $qrisSales = $this->salesByPaymentMethod($shift, Payment::METHOD_QRIS);
        $transferSales = $this->salesByPaymentMethod($shift, Payment::METHOD_TRANSFER);

        $expenseTotal = $this->expenseTotal($shift);

        $completedOrders = $this->ordersCountByStatus($shift, Order::STATUS_COMPLETED);
        $cancelledOrders = $this->ordersCountByStatus($shift, Order::STATUS_CANCELLED);
        $rejectedOrders = $this->rejectedOrdersCount($shift);

        return [
            'cash_sales' => $cashSales,
            'qris_sales' => $qrisSales,
            'transfer_sales' => $transferSales,
            'expense_total' => $expenseTotal,

            'estimated_cash' => (int) $shift->opening_cash + $cashSales - $expenseTotal,

            'paid_orders' => $this->paidOrdersCount($shift),
            'completed_orders' => $completedOrders,
            'cancelled_orders' => $cancelledOrders,
            'rejected_orders' => $rejectedOrders,
            'cancelled_or_rejected_orders' => $cancelledOrders + $rejectedOrders,
        ];
    }

    private function salesByPaymentMethod(CashierShift $shift, string $method): int
    {
        return (int) Payment::query()
            ->where('method', $method)
            ->where('status', Payment::STATUS_PAID)
            ->whereHas('order', function ($query) use ($shift) {
                $query
                    ->where('cashier_shift_id', $shift->id)
                    ->where('payment_status', Order::PAYMENT_PAID)
                    ->whereNotIn('order_status', self::EXCLUDED_ORDER_STATUSES);
            })
            ->sum('amount');
    }

    private function expenseTotal(CashierShift $shift): int
    {
        return (int) ShiftExpense::query()
            ->where('cashier_shift_id', $shift->id)
            ->sum('amount');
    }

    private function paidOrdersCount(CashierShift $shift): int
    {
        return Order::query()
            ->where('cashier_shift_id', $shift->id)
            ->where('payment_status', Order::PAYMENT_PAID)
            ->whereNotIn('order_status', self::EXCLUDED_ORDER_STATUSES)
            ->count();
    }

    private function ordersCountByStatus(CashierShift $shift, string $status): int
    {
        return Order::query()
            ->where('cashier_shift_id', $shift->id)
            ->where('order_status', $status)
            ->count();
    }

    private function rejectedOrdersCount(CashierShift $shift): int
    {
        return Order::query()
            ->where('order_status', Order::STATUS_REJECTED)
            ->where(function ($query) use ($shift) {
                $query
                    ->where('cashier_shift_id', $shift->id)
                    ->orWhereHas('payment', function ($paymentQuery) use ($shift) {
                        $paymentQuery
                            ->where('rejected_by', $shift->user_id)
                            ->where('rejected_at', '>=', $shift->opened_at);
                    });
            })
            ->count();
    }

    private function pendingCashOrdersCount(): int
    {
        return Order::query()
            ->where('order_source', Order::SOURCE_CUSTOMER_QR)
            ->where('order_status', Order::STATUS_PENDING_PAYMENT)
            ->where('payment_status', Order::PAYMENT_UNPAID)
            ->whereHas('payment', function ($query) {
                $query->where('method', Payment::METHOD_CASH);
            })
            ->count();
    }

    private function pendingVerificationOrdersCount(): int
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
            })
            ->count();
    }
}