<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;

class IncomingOrderPollService
{
    public function __construct(
        private readonly IncomingOrderQueryService $queryService
    ) {
    }

    public function pollData(CashierShift $activeShift): array
    {
        $latestOrder = $this->queryService
            ->incomingOrdersBadgeQuery()
            ->with(['table'])
            ->latest('created_at')
            ->first();

        return [
            'success' => true,
            'signature' => $this->buildSignature($activeShift),
            'count' => $this->queryService->incomingOrdersBadgeCount(),
            'latest_order' => $latestOrder ? [
                'id' => $latestOrder->id,
                'order_number' => $latestOrder->order_number,
                'customer_name' => $latestOrder->customer_name,
                'table_name' => $latestOrder->table?->name,
                'created_at' => $latestOrder->created_at?->format('d M Y H:i'),
            ] : null,
        ];
    }

    public function buildSignature(CashierShift $activeShift): string
    {
        $orders = collect()
            ->merge(
                $this->queryService
                    ->pendingCashQuery()
                    ->with([
                        'payment:id,order_id,status,method,updated_at',
                        'printJobs:id,order_id,type,status,updated_at,created_at',
                    ])
                    ->get()
            )
            ->merge(
                $this->queryService
                    ->pendingVerificationQuery()
                    ->with([
                        'payment:id,order_id,status,method,updated_at',
                        'printJobs:id,order_id,type,status,updated_at,created_at',
                    ])
                    ->get()
            )
            ->merge(
                $this->queryService
                    ->processingQuery($activeShift)
                    ->with([
                        'payment:id,order_id,status,method,updated_at',
                        'printJobs:id,order_id,type,status,updated_at,created_at',
                    ])
                    ->get()
            )
            ->unique('id')
            ->sortBy('id')
            ->values();

        $signature = $orders
            ->map(function ($order) {
                $paymentSignature = implode('|', [
                    $order->payment?->id,
                    $order->payment?->method,
                    $order->payment?->status,
                    optional($order->payment?->updated_at)->timestamp,
                ]);

                $printJobsSignature = $order->printJobs
                    ->sortBy('id')
                    ->map(function ($printJob) {
                        return implode('|', [
                            $printJob->id,
                            $printJob->type,
                            $printJob->status,
                            optional($printJob->created_at)->timestamp,
                            optional($printJob->updated_at)->timestamp,
                        ]);
                    })
                    ->implode('::print::');

                return implode('|', [
                    $order->id,
                    $order->order_number,
                    $order->cashier_shift_id,
                    $order->cashier_id,
                    $order->order_status,
                    $order->payment_status,
                    optional($order->paid_at)->timestamp,
                    optional($order->completed_at)->timestamp,
                    optional($order->updated_at)->timestamp,
                    $paymentSignature,
                    $printJobsSignature,
                ]);
            })
            ->implode('::order::');

        return md5($signature);
    }
}