<?php

namespace App\Services\Admin;

use App\Models\CashierShift;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminDashboardService
{
    private const EXCLUDED_ORDER_STATUSES = [
        Order::STATUS_CANCELLED,
        Order::STATUS_REJECTED,
        Order::STATUS_EXPIRED,
    ];

    public function dashboardData(): array
    {
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        $activeCashierShifts = $this->activeCashierShifts();

        $cashSalesToday = $this->salesByPaymentMethod(
            method: Payment::METHOD_CASH,
            startDate: $todayStart,
            endDate: $todayEnd
        );

        $qrisSalesToday = $this->salesByPaymentMethod(
            method: Payment::METHOD_QRIS,
            startDate: $todayStart,
            endDate: $todayEnd
        );

        $transferSalesToday = $this->salesByPaymentMethod(
            method: Payment::METHOD_TRANSFER,
            startDate: $todayStart,
            endDate: $todayEnd
        );

        return [
            'summary' => [
                'sales_today' => $this->validPaidOrdersQuery($todayStart, $todayEnd)
                    ->sum('grand_total'),

                'orders_today' => $this->ordersTodayCount($todayStart, $todayEnd),

                'completed_orders_today' => $this->ordersByStatusCount(
                    status: Order::STATUS_COMPLETED,
                    startDate: $todayStart,
                    endDate: $todayEnd
                ),

                'pending_orders' => $this->pendingOrdersCount(),

                'processing_orders' => $this->processingOrdersCount(),

                'active_cashier_shifts' => $activeCashierShifts->count(),

                'cash_sales_today' => $cashSalesToday,
                'non_cash_sales_today' => $qrisSalesToday + $transferSalesToday,
                'qris_sales_today' => $qrisSalesToday,
                'transfer_sales_today' => $transferSalesToday,
            ],

            'topMenusToday' => $this->topMenusToday($todayStart, $todayEnd),

            'latestOrders' => $this->latestOrders(),

            'activeCashierShifts' => $activeCashierShifts,
        ];
    }

    private function validPaidOrdersQuery(Carbon $startDate, Carbon $endDate): Builder
    {
        return Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', Order::PAYMENT_PAID)
            ->whereNotIn('order_status', self::EXCLUDED_ORDER_STATUSES);
    }

    private function ordersTodayCount(Carbon $startDate, Carbon $endDate): int
    {
        return Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    private function ordersByStatusCount(string $status, Carbon $startDate, Carbon $endDate): int
    {
        return Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', $status)
            ->count();
    }

    private function pendingOrdersCount(): int
    {
        return Order::query()
            ->whereIn('order_status', [
                Order::STATUS_PENDING_PAYMENT,
                Order::STATUS_PENDING_PAYMENT_VERIFICATION,
            ])
            ->count();
    }

    private function processingOrdersCount(): int
    {
        return Order::query()
            ->where('order_status', Order::STATUS_PROCESSING)
            ->count();
    }

    private function activeCashierShifts(): Collection
    {
        return CashierShift::query()
            ->with('user')
            ->where('status', CashierShift::STATUS_OPEN)
            ->latest('opened_at')
            ->get();
    }

    private function salesByPaymentMethod(string $method, Carbon $startDate, Carbon $endDate): int
    {
        return (int) Payment::query()
            ->where('method', $method)
            ->where('status', Payment::STATUS_PAID)
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->whereNotIn('order_status', self::EXCLUDED_ORDER_STATUSES);
            })
            ->sum('amount');
    }

    private function topMenusToday(Carbon $startDate, Carbon $endDate): Collection
    {
        return OrderItem::query()
            ->select([
                'order_items.menu_id',
                'order_items.menu_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal_after_discount) as total_sales'),
            ])
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.payment_status', Order::PAYMENT_PAID)
            ->whereNotIn('orders.order_status', self::EXCLUDED_ORDER_STATUSES)
            ->groupBy('order_items.menu_id', 'order_items.menu_name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
    }

    private function latestOrders(): Collection
    {
        return Order::query()
            ->with(['payment', 'table', 'cashier'])
            ->latest()
            ->limit(8)
            ->get();
    }
}