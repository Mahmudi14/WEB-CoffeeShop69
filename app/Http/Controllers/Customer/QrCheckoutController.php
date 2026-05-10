<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\PrepareQrCheckoutRequest;
use App\Http\Requests\Customer\StoreQrOrderRequest;
use App\Models\Order;
use App\Services\Customer\CustomerCartService;
use App\Services\Customer\CustomerOrderService;
use App\Services\Customer\CustomerPaymentChannelService;
use App\Services\Customer\OrderingAvailabilityService;
use App\Services\Customer\QrTableService;
use App\Services\OrderPricingService;

class QrCheckoutController extends Controller
{
    public function __construct(
        private readonly QrTableService $qrTableService,
        private readonly CustomerCartService $cartService,
        private readonly CustomerOrderService $orderService,
        private readonly CustomerPaymentChannelService $paymentChannelService,
        private readonly OrderingAvailabilityService $orderingAvailabilityService
    ) {
    }

    public function prepareCheckout(PrepareQrCheckoutRequest $request, string $qrToken)
    {
        $table = $this->qrTableService->findActiveByToken($qrToken);

        if (! $this->orderingAvailabilityService->isOpen()) {
            return redirect()
                ->route('customer.qr.menu', $table->qr_token)
                ->with('error', 'Pemesanan sedang ditutup karena belum ada kasir yang membuka shift.');
        }

        $cart = $this->cartService->buildFromJson($request->validated('cart_json'));

        if (count($cart) === 0) {
            return redirect()
                ->route('customer.qr.menu', $table->qr_token)
                ->with('error', 'Cart tidak valid.');
        }

        $this->cartService->putToSession($table, $cart);

        return redirect()->route('customer.qr.checkout', $table->qr_token);
    }

    public function checkout(string $qrToken, OrderPricingService $pricingService)
    {
        $table = $this->qrTableService->findActiveByToken($qrToken);

        if (! $this->orderingAvailabilityService->isOpen()) {
            return redirect()
                ->route('customer.qr.menu', $table->qr_token)
                ->with('error', 'Pemesanan sedang ditutup karena belum ada kasir yang membuka shift.');
        }

        $cart = $this->cartService->getFromSession($table);

        if (count($cart) === 0) {
            return redirect()
                ->route('customer.qr.menu', $table->qr_token)
                ->with('error', 'Cart masih kosong.');
        }

        $pricing = $pricingService->calculate($cart);
        $paymentChannels = $this->paymentChannelService->getActiveGroupedByMethod();

        return view('customer.checkout', compact(
            'table',
            'cart',
            'pricing',
            'paymentChannels'
        ));
    }

    public function store(StoreQrOrderRequest $request, string $qrToken)
    {
        $table = $this->qrTableService->findActiveByToken($qrToken);

        if (! $this->orderingAvailabilityService->isOpen()) {
            return redirect()
                ->route('customer.qr.menu', $table->qr_token)
                ->with('error', 'Pemesanan sedang ditutup karena belum ada kasir yang membuka shift.');
        }

        $cart = $this->cartService->getFromSession($table);

        if (count($cart) === 0) {
            return redirect()
                ->route('customer.qr.menu', $table->qr_token)
                ->with('error', 'Cart masih kosong.');
        }

        $order = $this->orderService->createFromQrOrder(
            table: $table,
            cart: $cart,
            data: $request->validated(),
            proof: $request->file('proof')
        );

        $this->cartService->forget($table);

        return redirect()
            ->route('customer.orders.status', ['order' => $order->order_number])
            ->with('clear_customer_cart', true);
    }

    public function success(string $qrToken, Order $order)
    {
        $table = $this->qrTableService->findActiveByToken($qrToken);

        if ((int) $order->table_id !== (int) $table->id) {
            abort(404);
        }

        $order->load(['payment', 'table']);

        return view('customer.success', compact('table', 'order'));
    }
}