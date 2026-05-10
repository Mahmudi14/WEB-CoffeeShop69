<?php

namespace App\Services\Admin;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class AdminOrderQueryService
{
    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;
        $orderStatus = $filters['order_status'] ?? null;
        $paymentStatus = $filters['payment_status'] ?? null;
        $source = $filters['source'] ?? null;

        return Order::query()
            ->with(['payment', 'table', 'cashier', 'shift'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%");
                });
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->where('created_at', '>=', Carbon::parse($dateFrom)->startOfDay());
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->where('created_at', '<=', Carbon::parse($dateTo)->endOfDay());
            })
            ->when($orderStatus, function ($query) use ($orderStatus) {
                $query->where('order_status', $orderStatus);
            })
            ->when($paymentStatus, function ($query) use ($paymentStatus) {
                $query->where('payment_status', $paymentStatus);
            })
            ->when($source, function ($query) use ($source) {
                $query->where('order_source', $source);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }
}