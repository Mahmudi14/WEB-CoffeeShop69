<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShiftExpense;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CashierShiftSummaryService
{
    private const EXCLUDED_ORDER_STATUSES = [
        Order::STATUS_CANCELLED,
        Order::STATUS_REJECTED,
        Order::STATUS_EXPIRED,
    ];

    public function summaryPageData(CashierShift $shift): array
    {
        return [
            'summary' => $this->buildSummary($shift),
            'soldItems' => $this->soldItems($shift),
            'orders' => $this->auditOrders($shift),
            'expenses' => $this->expenses($shift),
        ];
    }

    public function buildSummary(CashierShift $shift): array
    {
        $shiftOrderQuery = $this->shiftOrdersQuery($shift);
        $validOrderQuery = $this->validPaidOrdersQuery($shift);

        $cashSales = $this->sumPaidPaymentByMethod($shift, Payment::METHOD_CASH);
        $qrisSales = $this->sumPaidPaymentByMethod($shift, Payment::METHOD_QRIS);
        $transferSales = $this->sumPaidPaymentByMethod($shift, Payment::METHOD_TRANSFER);

        $expenseTotal = $this->expenseTotal($shift);

        $pendingCashOrders = $this->pendingCashOrdersQuery()->count();
        $pendingVerificationOrders = $this->pendingVerificationOrdersQuery()->count();

        $processingOrders = (clone $shiftOrderQuery)
            ->where('order_status', Order::STATUS_PROCESSING)
            ->count();

        $completedOrders = (clone $shiftOrderQuery)
            ->where('order_status', Order::STATUS_COMPLETED)
            ->count();

        $cancelledOrders = (clone $shiftOrderQuery)
            ->where('order_status', Order::STATUS_CANCELLED)
            ->count();

        $rejectedOrders = $this->rejectedOrdersQuery($shift)->count();

        $activeOrders = $pendingCashOrders + $pendingVerificationOrders + $processingOrders;

        $shiftTotalOrders = (clone $shiftOrderQuery)->count();

        $totalOrders = $shiftTotalOrders + $pendingCashOrders + $pendingVerificationOrders;

        $totalMenuSoldQty = $this->totalMenuSoldQuantity($shift);

        return [
            'opening_cash' => (int) $shift->opening_cash,

            'cash_sales' => $cashSales,
            'qris_sales' => $qrisSales,
            'transfer_sales' => $transferSales,
            'non_cash_sales' => $qrisSales + $transferSales,
            'total_sales' => $cashSales + $qrisSales + $transferSales,

            'expense_total' => $expenseTotal,
            'estimated_cash' => (int) $shift->opening_cash + $cashSales - $expenseTotal,

            'total_orders' => $totalOrders,
            'shift_total_orders' => $shiftTotalOrders,
            'total_menu_sold_qty' => $totalMenuSoldQty,
            'rejected_orders' => $rejectedOrders,

            'active_orders' => $activeOrders,
            'pending_cash_orders' => $pendingCashOrders,
            'pending_verification_orders' => $pendingVerificationOrders,
            'processing_orders' => $processingOrders,

            'paid_orders' => (clone $validOrderQuery)->count(),
            'completed_orders' => $completedOrders,
            'cancelled_orders' => $cancelledOrders,
        ];
    }

    public function soldItems(CashierShift $shift): Collection
    {
        return OrderItem::query()
            ->select([
                'order_items.menu_id',
                'order_items.menu_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal_before_discount) as subtotal_before_discount'),
                DB::raw('SUM(order_items.total_discount) as total_discount'),
                DB::raw('SUM(order_items.subtotal_after_discount) as subtotal_after_discount'),
            ])
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.cashier_shift_id', $shift->id)
            ->where('orders.payment_status', Order::PAYMENT_PAID)
            ->whereNotIn('orders.order_status', self::EXCLUDED_ORDER_STATUSES)
            ->groupBy('order_items.menu_id', 'order_items.menu_name')
            ->orderByDesc('total_quantity')
            ->get();
    }

    public function auditOrders(CashierShift $shift): LengthAwarePaginator
    {
        return $this->auditOrdersQuery($shift)
            ->with(['payment', 'table', 'items'])
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();
    }

    public function expenses(CashierShift $shift): Collection
    {
        return ShiftExpense::query()
            ->where('cashier_shift_id', $shift->id)
            ->latest()
            ->get();
    }

    public function unfinishedOrdersCount(CashierShift $shift): int
    {
        return Order::query()
            ->where(function ($query) use ($shift) {
                $query
                    ->where(function ($query) use ($shift) {
                        $query->where('cashier_shift_id', $shift->id)
                            ->whereIn('order_status', [
                                Order::STATUS_PENDING_PAYMENT,
                                Order::STATUS_PENDING_PAYMENT_VERIFICATION,
                                Order::STATUS_PROCESSING,
                            ]);
                    })
                    ->orWhere(function ($query) use ($shift) {
                        $query->where('order_source', Order::SOURCE_CUSTOMER_QR)
                            ->whereNull('cashier_shift_id')
                            ->where('created_at', '>=', $shift->opened_at)
                            ->whereIn('order_status', [
                                Order::STATUS_PENDING_PAYMENT,
                                Order::STATUS_PENDING_PAYMENT_VERIFICATION,
                            ]);
                    });
            })
            ->count();
    }

    private function shiftOrdersQuery(CashierShift $shift): Builder
    {
        return Order::query()
            ->where('cashier_shift_id', $shift->id);
    }

    private function validPaidOrdersQuery(CashierShift $shift): Builder
    {
        return Order::query()
            ->where('cashier_shift_id', $shift->id)
            ->where('payment_status', Order::PAYMENT_PAID)
            ->whereNotIn('order_status', self::EXCLUDED_ORDER_STATUSES);
    }

    private function sumPaidPaymentByMethod(CashierShift $shift, string $method): int
    {
        return (int) Payment::query()
            ->where('method', $method)
            ->where('status', Payment::STATUS_PAID)
            ->whereHas('order', function ($query) use ($shift) {
                $query
                    ->where('cashier_shift_id', $shift->id)
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

    private function totalMenuSoldQuantity(CashierShift $shift): int
    {
        return (int) OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.cashier_shift_id', $shift->id)
            ->where('orders.payment_status', Order::PAYMENT_PAID)
            ->whereNotIn('orders.order_status', self::EXCLUDED_ORDER_STATUSES)
            ->sum('order_items.quantity');
    }

    private function pendingCashOrdersQuery(): Builder
    {
        return Order::query()
            ->where('order_source', Order::SOURCE_CUSTOMER_QR)
            ->where('order_status', Order::STATUS_PENDING_PAYMENT)
            ->where('payment_status', Order::PAYMENT_UNPAID)
            ->whereHas('payment', function ($query) {
                $query->where('method', Payment::METHOD_CASH);
            });
    }

    private function pendingVerificationOrdersQuery(): Builder
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

    private function rejectedOrdersQuery(CashierShift $shift): Builder
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
            });
    }

    private function auditOrdersQuery(CashierShift $shift): Builder
    {
        return Order::query()
            ->where(function ($query) use ($shift) {
                $query
                    ->where('cashier_shift_id', $shift->id)
                    ->orWhereHas('payment', function ($paymentQuery) use ($shift) {
                        $paymentQuery
                            ->where('rejected_by', $shift->user_id)
                            ->where('rejected_at', '>=', $shift->opened_at);
                    });
            });
    }
}