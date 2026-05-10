<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cashier\AcceptCashPaymentRequest;
use App\Http\Requests\Cashier\RejectPaymentProofRequest;
use App\Models\CashierShift;
use App\Models\Order;
use App\Services\Cashier\IncomingOrderGuard;
use App\Services\Cashier\IncomingOrderPaymentService;
use App\Services\Cashier\IncomingOrderPollService;
use App\Services\Cashier\IncomingOrderPrintService;
use App\Services\Cashier\IncomingOrderQueryService;
use Illuminate\Http\Request;

class IncomingOrderController extends Controller
{
    public function __construct(
        private readonly IncomingOrderQueryService $queryService,
        private readonly IncomingOrderPollService $pollService,
        private readonly IncomingOrderPaymentService $paymentService,
        private readonly IncomingOrderPrintService $printService,
        private readonly IncomingOrderGuard $guard
    ) {
    }

    public function index(Request $request)
    {
        $activeShift = $this->activeShift($request);

        $pendingCashOrders = $this->queryService
            ->pendingCashQuery()
            ->with(['table', 'payment', 'items'])
            ->oldest()
            ->get();

        $pendingVerificationOrders = $this->queryService
            ->pendingVerificationQuery()
            ->with(['table', 'payment', 'items'])
            ->oldest()
            ->get();

        $processingOrders = $this->queryService
            ->processingQuery($activeShift)
            ->with(['table', 'payment', 'items', 'printJobs'])
            ->oldest()
            ->get();

        $pollSignature = $this->pollService->buildSignature($activeShift);

        return view('cashier.incoming-orders.index', compact(
            'activeShift',
            'pendingCashOrders',
            'pendingVerificationOrders',
            'processingOrders',
            'pollSignature'
        ));
    }

    public function poll(Request $request)
    {
        return response()->json(
            $this->pollService->pollData($this->activeShift($request))
        );
    }

    public function acceptCash(AcceptCashPaymentRequest $request, Order $order)
    {
        $this->paymentService->acceptCash(
            order: $order,
            activeShift: $this->activeShift($request),
            cashier: $request->user(),
            paidAmount: $request->integer('paid_amount')
        );

        return redirect()
            ->route('cashier.incoming-orders.index')
            ->with('success', 'Pembayaran tunai berhasil diverifikasi. Order masuk ke antrian proses.')
            ->with('watch_kitchen_order_id', $order->id);
    }

    public function acceptProof(Request $request, Order $order)
    {
        $this->paymentService->acceptProof(
            order: $order,
            activeShift: $this->activeShift($request),
            cashier: $request->user()
        );

        return redirect()
            ->route('cashier.incoming-orders.index')
            ->with('success', 'Bukti pembayaran diterima. Order masuk ke antrian proses.')
            ->with('watch_kitchen_order_id', $order->id);
    }

    public function rejectProof(RejectPaymentProofRequest $request, Order $order)
    {
        $this->paymentService->rejectProof(
            order: $order,
            cashier: $request->user(),
            reason: $request->validated('rejection_reason')
        );

        return redirect()
            ->route('cashier.incoming-orders.index')
            ->with('success', 'Bukti pembayaran ditolak. Order keluar dari antrian aktif.');
    }

    public function printCustomerReceipt(Request $request, Order $order)
    {
        $activeShift = $this->activeShift($request);

        $this->guard->ensureProcessingOrder($order, $activeShift);

        $created = $this->printService->createCustomerReceiptPrint(
            order: $order,
            user: $request->user(),
            activeShift: $activeShift
        );

        if (! $created) {
            return back()->with('error', 'Struk customer masih ada di antrean cetak.');
        }

        return back()
            ->with('success', 'Permintaan cetak struk pelanggan dikirim ke printer.')
            ->with('watch_print_order_id', $order->id);
    }

    public function complete(Request $request, Order $order)
    {
        $this->paymentService->complete(
            order: $order,
            activeShift: $this->activeShift($request)
        );

        return redirect()
            ->route('cashier.incoming-orders.index')
            ->with('success', 'Pesanan berhasil diselesaikan.');
    }

    public function customerReceiptStatus(Order $order)
    {
        return response()->json(
            $this->printService->customerReceiptStatus($order)
        );
    }

    public function kitchenOrderStatus(Order $order)
    {
        return response()->json(
            $this->printService->kitchenOrderStatus($order)
        );
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