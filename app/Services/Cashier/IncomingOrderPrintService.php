<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;
use App\Models\Order;
use App\Models\PrintJob;
use App\Models\User;
use App\Services\PrintPayloadService;

class IncomingOrderPrintService
{
    public function __construct(
        private readonly PrintPayloadService $printPayloadService
    ) {
    }

    public function createKitchenPrintIfMissing(
        Order $order,
        User $user,
        CashierShift $activeShift
    ): void {
        $exists = PrintJob::query()
            ->where('order_id', $order->id)
            ->where('type', PrintJob::TYPE_KITCHEN_ORDER)
            ->whereIn('status', [
                PrintJob::STATUS_PENDING,
                PrintJob::STATUS_PRINTING,
                PrintJob::STATUS_PRINTED,
            ])
            ->exists();

        if ($exists) {
            return;
        }

        $order->loadMissing([
            'items',
            'payment',
            'cashier',
            'table',
        ]);

        PrintJob::create([
            'order_id' => $order->id,
            'cashier_shift_id' => $activeShift->id,
            'type' => PrintJob::TYPE_KITCHEN_ORDER,
            'status' => PrintJob::STATUS_PENDING,
            'payload' => $this->printPayloadService->buildKitchenOrderPayload($order),
            'created_by' => $user->id,
        ]);
    }

    public function createCustomerReceiptPrint(
        Order $order,
        User $user,
        CashierShift $activeShift
    ): bool {
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
            'payment',
            'cashier',
            'table',
        ]);

        PrintJob::create([
            'order_id' => $order->id,
            'cashier_shift_id' => $activeShift->id,
            'type' => PrintJob::TYPE_CUSTOMER_RECEIPT,
            'status' => PrintJob::STATUS_PENDING,
            'payload' => $this->printPayloadService->buildCustomerReceiptPayload($order),
            'created_by' => $user->id,
        ]);

        return true;
    }

    public function hasPrintedCustomerReceipt(Order $order): bool
    {
        return PrintJob::query()
            ->where('order_id', $order->id)
            ->where('type', PrintJob::TYPE_CUSTOMER_RECEIPT)
            ->whereIn('status', [
                PrintJob::STATUS_PRINTED,
                'completed',
            ])
            ->exists();
    }

    public function customerReceiptStatus(Order $order): array
    {
        $printedCount = $order->printJobs()
            ->where('type', PrintJob::TYPE_CUSTOMER_RECEIPT)
            ->whereIn('status', [
                PrintJob::STATUS_PRINTED,
                'completed',
            ])
            ->count();

        $latestJob = $order->printJobs()
            ->where('type', PrintJob::TYPE_CUSTOMER_RECEIPT)
            ->latest()
            ->first();

        return [
            'printed_count' => $printedCount,
            'can_complete' => $printedCount > 0,
            'latest_status' => $latestJob?->status,
            'latest_error' => $latestJob?->error_message,
        ];
    }

    public function kitchenOrderStatus(Order $order): array
    {
        $latestJob = $order->printJobs()
            ->where('type', PrintJob::TYPE_KITCHEN_ORDER)
            ->latest()
            ->first();

        $printedCount = $order->printJobs()
            ->where('type', PrintJob::TYPE_KITCHEN_ORDER)
            ->whereIn('status', [
                PrintJob::STATUS_PRINTED,
                'completed',
            ])
            ->count();

        return [
            'printed_count' => $printedCount,
            'is_printed' => $printedCount > 0,
            'latest_status' => $latestJob?->status,
            'latest_error' => $latestJob?->error_message,
        ];
    }
}