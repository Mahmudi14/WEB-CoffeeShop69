<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminOrderIndexRequest;
use App\Http\Requests\Admin\CancelAdminOrderRequest;
use App\Models\Order;
use App\Services\Admin\AdminOrderQueryService;
use App\Services\Admin\AdminOrderService;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function __construct(
        private readonly AdminOrderQueryService $orderQueryService,
        private readonly AdminOrderService $orderService
    ) {
    }

    public function index(AdminOrderIndexRequest $request)
    {
        $filters = $request->filters();

        $orders = $this->orderQueryService->paginated($filters);

        return view('admin.orders.index', [
            'orders' => $orders,
            'search' => $filters['search'],
            'dateFrom' => $filters['date_from'],
            'dateTo' => $filters['date_to'],
            'orderStatus' => $filters['order_status'],
            'paymentStatus' => $filters['payment_status'],
            'source' => $filters['source'],
        ]);
    }

    public function show(Order $order)
    {
        $order = $this->orderService->loadDetail($order);
        $canCancel = $this->orderService->canCancel($order);

        return view('admin.orders.show', compact('order', 'canCancel'));
    }

    public function cancel(CancelAdminOrderRequest $request, Order $order)
    {
        try {
            $this->orderService->cancel(
                order: $order,
                admin: $request->user(),
                reason: $request->validated('cancel_reason')
            );
        } catch (ValidationException $exception) {
            return back()->with(
                'error',
                collect($exception->errors())->flatten()->first()
            );
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order berhasil dibatalkan.');
    }
}