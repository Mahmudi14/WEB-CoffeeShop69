<?php

namespace App\Services;

use App\Models\CashierShift;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShiftExpense;
use Illuminate\Support\Facades\DB;

class PrintPayloadService
{
    public function buildKitchenOrderPayload(Order $order): array
{
    $order->loadMissing(['items', 'payment', 'cashier', 'table']);

    $notes = collect();

    if (filled($order->customer_note)) {
        $notes->push('Customer: ' . $order->customer_note);
    }

    $order->items
        ->filter(fn ($item) => filled($item->note))
        ->each(function ($item) use ($notes) {
            $notes->push($item->menu_name . ': ' . $item->note);
        });

    return [
        'transaction_id' => $order->order_number,
        'created_at' => $order->created_at?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s'),
        'customer_name' => $order->customer_name,
        'table_name' => $order->table?->name ?? 'Takeaway',
        'cashier_name' => $order->cashier?->name ?? auth()->user()?->name ?? '-',
        'payment_method' => $order->payment?->method,
        'note' => $notes->implode("\n"),
        'total' => (int) $order->grand_total,

        'items' => $order->items
            ->map(fn ($item) => [
                'name' => $item->menu_name,
                'qty' => (int) $item->quantity,
                'price' => (int) $item->final_price,
                'subtotal' => (int) $item->subtotal_after_discount,
            ])
            ->values()
            ->all(),
    ];
}

    public function buildCustomerReceiptPayload(Order $order): array
{
    $order->loadMissing(['items', 'payment', 'cashier', 'table']);

    return [
        'transaction_id' => $order->order_number,
        'created_at' => $order->created_at?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s'),
        'customer_name' => $order->customer_name,
        'table_name' => $order->table?->name ?? 'Takeaway',
        'cashier_name' => $order->cashier?->name ?? auth()->user()?->name ?? '-',
        'payment_method' => $order->payment?->method,
        'note' => $order->customer_note ?? '',
        'total' => (int) $order->grand_total,

        'items' => $order->items
            ->map(fn ($item) => [
                'name' => $item->menu_name,
                'qty' => (int) $item->quantity,
                'price' => (int) $item->final_price,
                'subtotal' => (int) $item->subtotal_after_discount,
            ])
            ->values()
            ->all(),
    ];
}

    public function buildShiftClosingPayload(CashierShift $shift): array
    {
        $shift->loadMissing('user');

        $excludedStatuses = [
            Order::STATUS_CANCELLED,
            Order::STATUS_REJECTED,
            Order::STATUS_EXPIRED,
        ];

        $validOrderQuery = Order::query()
            ->where('cashier_shift_id', $shift->id)
            ->where('payment_status', Order::PAYMENT_PAID)
            ->whereNotIn('order_status', $excludedStatuses);

        $cashSales = $this->sumPaidPaymentByMethod($shift, Payment::METHOD_CASH, $excludedStatuses);
        $qrisSales = $this->sumPaidPaymentByMethod($shift, Payment::METHOD_QRIS, $excludedStatuses);
        $transferSales = $this->sumPaidPaymentByMethod($shift, Payment::METHOD_TRANSFER, $excludedStatuses);

        $expenseTotal = ShiftExpense::query()
            ->where('cashier_shift_id', $shift->id)
            ->sum('amount');

        $soldItems = OrderItem::query()
            ->select([
                'order_items.menu_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal_after_discount) as total_amount'),
            ])
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.cashier_shift_id', $shift->id)
            ->where('orders.payment_status', Order::PAYMENT_PAID)
            ->whereNotIn('orders.order_status', $excludedStatuses)
            ->groupBy('order_items.menu_name')
            ->orderByDesc('total_quantity')
            ->get();

        $expenses = ShiftExpense::query()
            ->where('cashier_shift_id', $shift->id)
            ->oldest()
            ->get();

        $totalPayment = (int) ($cashSales + $qrisSales + $transferSales);
        $endingCash = (int) ($shift->opening_cash + $cashSales - $expenseTotal);
        $netRevenue = (int) ($totalPayment - $expenseTotal);

        return [
            'cashier_name' => $shift->user?->name ?? '-',
            'shift_start' => $shift->opened_at?->format('d/m/Y H:i') ?? '-',
            'shift_end' => $shift->closed_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i'),
            'duration' => $shift->opened_at
                ? $shift->opened_at->diffForHumans($shift->closed_at ?? now(), true)
                : '-',
            'total_transactions' => (int) (clone $validOrderQuery)->count(),
            'sold_items_count' => (int) $soldItems->sum('total_quantity'),
            'printed_at' => now()->format('d/m/Y H:i'),

            'cash_management' => [
                'starting_cash' => (int) $shift->opening_cash,
                'cash_sales' => (int) $cashSales,
                'cash_expense' => (int) $expenseTotal,
                'ending_cash' => $endingCash,
            ],

            'payment_summary' => [
                'cash' => (int) $cashSales,
                'qris' => (int) $qrisSales,
                'transfer' => (int) $transferSales,
                'total_payment' => $totalPayment,
            ],

            'expenses' => $expenses
                ->map(fn ($expense) => [
                    'title' => $expense->category,
                    'amount' => (int) $expense->amount,
                    'note' => $expense->note,
                ])
                ->values()
                ->all(),

            'sold_items' => $soldItems
                ->map(fn ($item) => [
                    'name' => $item->menu_name,
                    'quantity' => (int) $item->total_quantity,
                    'total_amount' => (int) $item->total_amount,
                ])
                ->values()
                ->all(),

            'summary' => [
                'gross_revenue' => $totalPayment,
                'total_expense' => (int) $expenseTotal,
                'net_revenue' => $netRevenue,
            ],
        ];
    }

    private function sumPaidPaymentByMethod(CashierShift $shift, string $method, array $excludedStatuses): int
    {
        return (int) Payment::query()
            ->where('method', $method)
            ->where('status', Payment::STATUS_PAID)
            ->whereHas('order', function ($query) use ($shift, $excludedStatuses) {
                $query
                    ->where('cashier_shift_id', $shift->id)
                    ->whereNotIn('order_status', $excludedStatuses);
            })
            ->sum('amount');
    }
}