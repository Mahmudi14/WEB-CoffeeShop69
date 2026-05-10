<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;
use App\Models\Order;
use App\Models\PrintJob;
use App\Models\User;
use App\Services\PrintPayloadService;

class CashierOrderPrintService
{
    public function __construct(
        private readonly CashierOrderGuard $guard,
        private readonly PrintPayloadService $printPayloadService
    ) {
    }

    public function createCustomerReceiptPrint(
        Order $order,
        User $cashier,
        ?CashierShift $activeShift = null
    ): bool {
        $this->guard->ensureBelongsToCashier($order, $cashier);
        $this->guard->ensureCanPrintCustomerReceipt($order);

        $existingPendingPrint = PrintJob::query()
            ->where('order_id', $order->id)
            ->where('type', PrintJob::TYPE_CUSTOMER_RECEIPT)
            ->whereIn('status', [
                PrintJob::STATUS_PENDING,
                PrintJob::STATUS_PRINTING,
            ])
            ->exists();

        if ($existingPendingPrint) {
            return false;
        }

        $order->loadMissing([
            'items',
            'items.promotions',
            'payment',
            'cashier',
            'table',
        ]);

        PrintJob::create([
            'order_id' => $order->id,
            'cashier_shift_id' => $activeShift?->id ?? $order->cashier_shift_id,
            'type' => PrintJob::TYPE_CUSTOMER_RECEIPT,
            'status' => PrintJob::STATUS_PENDING,
            'payload' => $this->printPayloadService->buildCustomerReceiptPayload($order),
            'created_by' => $cashier->id,
        ]);

        return true;
    }
}