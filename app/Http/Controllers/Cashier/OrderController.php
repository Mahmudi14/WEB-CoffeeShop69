<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use App\Models\Order;
use App\Services\Cashier\CashierOrderGuard;
use App\Services\Cashier\CashierOrderPrintService;
use App\Services\Cashier\CashierOrderQueryService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly CashierOrderQueryService $orderQueryService,
        private readonly CashierOrderGuard $orderGuard,
        private readonly CashierOrderPrintService $orderPrintService
    ) {
    }

    public function index(Request $request)
    {
        $activeShift = $this->activeShift($request);

        $filters = [
            'status' => $request->query('status'),
            'payment_method' => $request->query('payment_method'),
            'search' => trim((string) $request->query('search')),
        ];

        $orders = $this->orderQueryService->paginatedByShift($activeShift, $filters);
        $summary = $this->orderQueryService->summaryByShift($activeShift);

        $statusOptions = $this->orderQueryService->statusOptions();
        $paymentMethodOptions = $this->orderQueryService->paymentMethodOptions();

        $status = $filters['status'];
        $paymentMethod = $filters['payment_method'];
        $search = $filters['search'];

        return view('cashier.orders.index', compact(
            'activeShift',
            'orders',
            'summary',
            'statusOptions',
            'paymentMethodOptions',
            'status',
            'paymentMethod',
            'search'
        ));
    }

    public function show(Request $request, Order $order)
    {
        $this->orderGuard->ensureBelongsToCashier($order, $request->user());

        $order->load([
            'shift',
            'cashier',
            'table',
            'items.promotions',
            'payment',
            'printJobs',
        ]);

        return view('cashier.orders.show', compact('order'));
    }

    public function printCustomerReceipt(Request $request, Order $order)
    {
        $created = $this->orderPrintService->createCustomerReceiptPrint(
            order: $order,
            cashier: $request->user(),
            activeShift: $this->activeShift($request)
        );

        if (! $created) {
            return back()->with('error', 'Struk customer masih ada di antrean print.');
        }

        return back()->with('success', 'Struk customer masuk antrean cetak.');
    }

    private function activeShift(Request $request): CashierShift
    {
        $activeShift = $request->attributes->get('activeCashierShift');

        if (! $activeShift instanceof CashierShift) {
            abort(403, 'Shift kasir belum dibuka.');
        }

        return $activeShift;
    }
}