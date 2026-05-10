<?php

namespace App\Services\Customer;

use App\Models\CafeTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemPromotion;
use App\Models\Payment;
use App\Services\OrderNumberService;
use App\Services\OrderPricingService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CustomerOrderService
{
    public function __construct(
        private readonly OrderPricingService $pricingService,
        private readonly OrderNumberService $orderNumberService
    ) {
    }

    public function createFromQrOrder(
        CafeTable $table,
        array $cart,
        array $data,
        ?UploadedFile $proof = null
    ): Order {
        $pricing = $this->pricingService->calculate($cart);

        $proofPath = $proof
            ? $proof->store('payment-proofs', 'public')
            : null;

        return DB::transaction(function () use ($table, $data, $pricing, $proofPath) {
            $isCash = $data['payment_method'] === Payment::METHOD_CASH;

            $order = Order::create([
                'order_number' => $this->orderNumberService->generate(),
                'cashier_shift_id' => null,
                'cashier_id' => null,
                'table_id' => $table->id,
                'customer_name' => $data['customer_name'],
                'order_source' => Order::SOURCE_CUSTOMER_QR,
                'order_type' => Order::TYPE_DINE_IN,
                'order_status' => $isCash
                    ? Order::STATUS_PENDING_PAYMENT
                    : Order::STATUS_PENDING_PAYMENT_VERIFICATION,
                'payment_status' => $isCash
                    ? Order::PAYMENT_UNPAID
                    : Order::PAYMENT_PENDING_VERIFICATION,
                'subtotal_before_discount' => $pricing['subtotal_before_discount'],
                'discount_total' => $pricing['discount_total'],
                'subtotal_after_discount' => $pricing['subtotal_after_discount'],
                'tax_setting_id' => $pricing['tax_setting_id'],
                'tax_rate' => $pricing['tax_rate'],
                'tax_total' => $pricing['tax_total'],
                'grand_total' => $pricing['grand_total'],
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
                    'note' => $pricedItem['note'],
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

            Payment::create([
                'order_id' => $order->id,
                'method' => $data['payment_method'],
                'status' => $isCash
                    ? Payment::STATUS_UNPAID
                    : Payment::STATUS_PENDING_VERIFICATION,
                'amount' => $pricing['grand_total'],
                'paid_amount' => 0,
                'change_amount' => 0,
                'proof_path' => $proofPath,
                'proof_uploaded_at' => $proofPath ? now() : null,
                'note' => $data['note'] ?? null,
                'created_by' => null,
            ]);

            return $order;
        });
    }
}