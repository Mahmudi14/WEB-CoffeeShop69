<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemPromotion;
use App\Models\Payment;
use App\Models\PrintJob;
use App\Models\User;
use App\Services\OrderNumberService;
use App\Services\OrderPricingService;
use App\Services\PrintPayloadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CashierPosOrderService
{
    public function __construct(
        private readonly OrderPricingService $pricingService,
        private readonly OrderNumberService $orderNumberService,
        private readonly PrintPayloadService $printPayloadService
    ) {
    }

    public function createOrder(
        CashierShift $activeShift,
        User $cashier,
        array $cart,
        array $data
    ): Order {
        $pricing = $this->pricingService->calculate($cart);

        $this->validatePaymentAmount($pricing, $data);

        if ($data['order_type'] === Order::TYPE_TAKEAWAY) {
            $data['table_id'] = null;
        }

        return DB::transaction(function () use ($activeShift, $cashier, $pricing, $data) {
            $order = Order::create([
                'order_number' => $this->orderNumberService->generate(),
                'cashier_shift_id' => $activeShift->id,
                'cashier_id' => $cashier->id,
                'table_id' => $data['table_id'] ?? null,
                'customer_name' => $data['customer_name'],
                'customer_note' => $data['customer_note'] ?? null,
                'order_source' => Order::SOURCE_CASHIER_POS,
                'order_type' => $data['order_type'],
                'order_status' => Order::STATUS_PROCESSING,
                'payment_status' => Order::PAYMENT_PAID,
                'subtotal_before_discount' => $pricing['subtotal_before_discount'],
                'discount_total' => $pricing['discount_total'],
                'subtotal_after_discount' => $pricing['subtotal_after_discount'],
                'tax_setting_id' => $pricing['tax_setting_id'],
                'tax_rate' => $pricing['tax_rate'],
                'tax_total' => $pricing['tax_total'],
                'grand_total' => $pricing['grand_total'],
                'paid_at' => now(),
            ]);

            foreach ($pricing['items'] as $pricedItem) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $pricedItem['menu_id'],
                    'menu_name' => $pricedItem['menu_name'],
                    'quantity' => $pricedItem['quantity'],
                    'normal_price' => $pricedItem['normal_price'],
                    'final_price' => $pricedItem['final_price'],
                    'subtotal_before_discount' => $pricedItem['subtotal_before_discount'],
                    'total_discount' => $pricedItem['total_discount'],
                    'subtotal_after_discount' => $pricedItem['subtotal_after_discount'],
                ]);

                foreach ($pricedItem['promotions'] as $promotion) {
                    OrderItemPromotion::create([
                        'order_item_id' => $orderItem->id,
                        'promotion_id' => $promotion['promotion_id'],
                        'promotion_name' => $promotion['promotion_name'],
                        'discount_type' => $promotion['discount_type'],
                        'discount_value' => $promotion['discount_value'],
                        'priority' => $promotion['priority'],
                        'price_before_discount' => $promotion['price_before_discount'],
                        'discount_amount_per_unit' => $promotion['discount_amount_per_unit'],
                        'price_after_discount' => $promotion['price_after_discount'],
                        'quantity' => $promotion['quantity'],
                        'discount_amount_total' => $promotion['discount_amount_total'],
                        'applied_order' => $promotion['applied_order'],
                    ]);
                }
            }

            $paidAmount = $data['payment_method'] === Payment::METHOD_CASH
                ? (int) $data['paid_amount']
                : $pricing['grand_total'];

            Payment::create([
                'order_id' => $order->id,
                'method' => $data['payment_method'],
                'status' => Payment::STATUS_PAID,
                'amount' => $pricing['grand_total'],
                'paid_amount' => $paidAmount,
                'change_amount' => max($paidAmount - $pricing['grand_total'], 0),
                'verified_by' => $cashier->id,
                'verified_at' => now(),
                'created_by' => $cashier->id,
            ]);

            $order->load([
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
                'created_by' => $cashier->id,
            ]);

            return $order;
        });
    }

    private function validatePaymentAmount(array $pricing, array $data): void
    {
        if ($data['payment_method'] !== Payment::METHOD_CASH) {
            return;
        }

        $paidAmount = (int) ($data['paid_amount'] ?? 0);

        if ($paidAmount < $pricing['grand_total']) {
            throw ValidationException::withMessages([
                'paid_amount' => 'Uang diterima tidak boleh kurang dari total bayar.',
            ]);
        }
    }
}