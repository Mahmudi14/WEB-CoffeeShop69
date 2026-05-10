<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CashierOrderQueryService
{
    public function paginatedByShift(CashierShift $shift, array $filters = []): LengthAwarePaginator
    {
        $status = $filters['status'] ?? null;
        $paymentMethod = $filters['payment_method'] ?? null;
        $search = trim((string) ($filters['search'] ?? ''));

        return Order::query()
            ->with(['payment', 'table', 'items'])
            ->where('cashier_shift_id', $shift->id)
            ->when($status, function ($query) use ($status) {
                $query->where('order_status', $status);
            })
            ->when($paymentMethod, function ($query) use ($paymentMethod) {
                $query->whereHas('payment', function ($paymentQuery) use ($paymentMethod) {
                    $paymentQuery->where('method', $paymentMethod);
                });
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery
                        ->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhereHas('table', function ($tableQuery) use ($search) {
                            $tableQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();
    }

    public function summaryByShift(CashierShift $shift): array
    {
        return [
            'total_orders' => Order::query()
                ->where('cashier_shift_id', $shift->id)
                ->count(),

            'completed_orders' => Order::query()
                ->where('cashier_shift_id', $shift->id)
                ->where('order_status', Order::STATUS_COMPLETED)
                ->count(),

            'processing_orders' => Order::query()
                ->where('cashier_shift_id', $shift->id)
                ->where('order_status', Order::STATUS_PROCESSING)
                ->count(),

            'cancelled_or_rejected_orders' => Order::query()
                ->where('cashier_shift_id', $shift->id)
                ->whereIn('order_status', [
                    Order::STATUS_CANCELLED,
                    Order::STATUS_REJECTED,
                ])
                ->count(),

            'total_sales' => (int) Order::query()
                ->where('cashier_shift_id', $shift->id)
                ->where('payment_status', Order::PAYMENT_PAID)
                ->whereNotIn('order_status', [
                    Order::STATUS_CANCELLED,
                    Order::STATUS_REJECTED,
                    Order::STATUS_EXPIRED,
                ])
                ->sum('grand_total'),
        ];
    }

    public function statusOptions(): array
    {
        return [
            Order::STATUS_PROCESSING => 'Processing',
            Order::STATUS_COMPLETED => 'Completed',
            Order::STATUS_CANCELLED => 'Cancelled',
            Order::STATUS_REJECTED => 'Rejected',
            Order::STATUS_EXPIRED => 'Expired',
        ];
    }

    public function paymentMethodOptions(): array
    {
        return [
            Payment::METHOD_CASH => 'Cash',
            Payment::METHOD_QRIS => 'QRIS',
            Payment::METHOD_TRANSFER => 'Transfer',
        ];
    }
}