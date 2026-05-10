<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\FindOrderTrackingRequest;
use App\Models\Order;
use App\Services\Customer\CustomerOrderStatusPresenter;

class OrderTrackingController extends Controller
{
    public function __construct(
        private readonly CustomerOrderStatusPresenter $statusPresenter
    ) {
    }

    public function status(Order $order)
    {
        $this->ensureCustomerQrOrder($order);

        $order->load(['payment', 'table', 'items']);

        return view('customer.status', compact('order'));
    }

    public function statusData(Order $order)
    {
        $this->ensureCustomerQrOrder($order);

        return response()->json([
            'order_number' => $order->order_number,
            'order_status' => $order->order_status,
            'payment_status' => $order->payment_status,
            'order_status_label' => $this->statusPresenter->orderStatusLabel($order),
            'payment_status_label' => $this->statusPresenter->paymentStatusLabel($order),
            'updated_at' => $order->updated_at?->format('d M Y H:i'),
        ]);
    }

    public function track()
    {
        return view('customer.track');
    }

    public function findTracking(FindOrderTrackingRequest $request)
    {
        $orderNumber = strtoupper(trim($request->validated('order_number')));

        $order = Order::query()
            ->where('order_number', $orderNumber)
            ->where('order_source', Order::SOURCE_CUSTOMER_QR)
            ->first();

        if (! $order) {
            return back()
                ->withInput()
                ->with('error', 'Nomor order tidak ditemukan.');
        }

        return redirect()->route('customer.orders.status', [
            'order' => $order->order_number,
        ]);
    }

    private function ensureCustomerQrOrder(Order $order): void
    {
        if ($order->order_source !== Order::SOURCE_CUSTOMER_QR) {
            abort(404);
        }
    }
}