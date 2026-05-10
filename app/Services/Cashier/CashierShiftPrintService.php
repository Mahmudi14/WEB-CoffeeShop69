<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;
use App\Models\PrintJob;
use App\Models\User;
use App\Services\PrintPayloadService;

class CashierShiftPrintService
{
    public function __construct(
        private readonly PrintPayloadService $printPayloadService
    ) {
    }

    public function queueClosingPrintIfMissing(CashierShift $shift, User $cashier): void
    {
        $alreadyQueued = PrintJob::query()
            ->where('cashier_shift_id', $shift->id)
            ->where('type', PrintJob::TYPE_SHIFT_CLOSING)
            ->whereIn('status', [
                PrintJob::STATUS_PENDING,
                PrintJob::STATUS_PRINTING,
                PrintJob::STATUS_PRINTED,
            ])
            ->exists();

        if ($alreadyQueued) {
            return;
        }

        $shift->refresh()->load('user');

        PrintJob::create([
            'order_id' => null,
            'cashier_shift_id' => $shift->id,
            'type' => PrintJob::TYPE_SHIFT_CLOSING,
            'status' => PrintJob::STATUS_PENDING,
            'payload' => $this->printPayloadService->buildShiftClosingPayload($shift),
            'created_by' => $cashier->id,
        ]);
    }
}