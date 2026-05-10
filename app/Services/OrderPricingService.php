<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Promotion;
use App\Models\TaxSetting;

class OrderPricingService
{
    public function calculate(array $cart): array
    {
        $items = [];

        $subtotalBeforeDiscount = 0;
        $discountTotal = 0;
        $subtotalAfterDiscount = 0;

        foreach ($cart as $cartKey => $cartItem) {
            $menu = Menu::query()
                ->where('is_active', true)
                ->where('is_available', true)
                ->findOrFail($cartItem['menu_id']);

            $quantity = max((int) $cartItem['quantity'], 1);
            $normalPrice = (int) $menu->normal_price;

            $currentPrice = $normalPrice;
            $appliedPromotions = [];

            $promotions = $this->getApplicablePromotions($menu->id);

            $appliedOrder = 1;

            foreach ($promotions as $promotion) {
                $priceBeforeDiscount = $currentPrice;
                $discountAmountPerUnit = $this->calculateDiscountAmount($promotion, $currentPrice);

                if ($discountAmountPerUnit <= 0) {
                    continue;
                }

                $discountAmountPerUnit = min($discountAmountPerUnit, $currentPrice);
                $currentPrice = max($currentPrice - $discountAmountPerUnit, 0);

                $appliedPromotions[] = [
                    'promotion_id' => $promotion->id,
                    'promotion_name' => $promotion->name,
                    'discount_type' => $promotion->discount_type,
                    'discount_value' => $promotion->discount_value,
                    'priority' => $promotion->priority,
                    'price_before_discount' => $priceBeforeDiscount,
                    'discount_amount_per_unit' => $discountAmountPerUnit,
                    'price_after_discount' => $currentPrice,
                    'quantity' => $quantity,
                    'discount_amount_total' => $discountAmountPerUnit * $quantity,
                    'applied_order' => $appliedOrder,
                ];

                $appliedOrder++;
            }

            $finalPrice = $currentPrice;

            $itemSubtotalBeforeDiscount = $normalPrice * $quantity;
            $itemSubtotalAfterDiscount = $finalPrice * $quantity;
            $itemTotalDiscount = $itemSubtotalBeforeDiscount - $itemSubtotalAfterDiscount;

            $subtotalBeforeDiscount += $itemSubtotalBeforeDiscount;
            $discountTotal += $itemTotalDiscount;
            $subtotalAfterDiscount += $itemSubtotalAfterDiscount;

            $items[] = [
                'cart_key' => $cartKey,
                'menu_id' => $menu->id,
                'menu_name' => $menu->name,
                'quantity' => $quantity,
                'normal_price' => $normalPrice,
                'final_price' => $finalPrice,
                'subtotal_before_discount' => $itemSubtotalBeforeDiscount,
                'total_discount' => $itemTotalDiscount,
                'subtotal_after_discount' => $itemSubtotalAfterDiscount,
                'note' => $cartItem['note'] ?? null,
                'promotions' => $appliedPromotions,
            ];
        }

        $taxSetting = TaxSetting::query()
            ->where('is_active', true)
            ->latest()
            ->first();

        $taxRate = $taxSetting ? (float) $taxSetting->rate : 0;
        $taxTotal = 0;

        if ($taxSetting && ! $taxSetting->price_includes_tax) {
            $taxTotal = (int) round($subtotalAfterDiscount * ($taxRate / 100));
        }

        $grandTotal = $subtotalAfterDiscount + $taxTotal;

        return [
            'items' => $items,
            'subtotal_before_discount' => $subtotalBeforeDiscount,
            'discount_total' => $discountTotal,
            'subtotal_after_discount' => $subtotalAfterDiscount,
            'tax_setting_id' => $taxSetting?->id,
            'tax_rate' => $taxRate,
            'tax_total' => $taxTotal,
            'grand_total' => $grandTotal,
        ];
    }

    private function getApplicablePromotions(int $menuId)
    {
        return Promotion::query()
            ->active()
            ->where(function ($query) use ($menuId) {
                $query->where('scope', Promotion::SCOPE_ALL_MENU)
                    ->orWhere(function ($query) use ($menuId) {
                        $query->where('scope', Promotion::SCOPE_SELECTED_MENU)
                            ->whereHas('menus', function ($menuQuery) use ($menuId) {
                                $menuQuery->where('menus.id', $menuId);
                            });
                    });
            })
            ->orderBy('priority')
            ->orderBy('id')
            ->get();
    }

    private function calculateDiscountAmount(Promotion $promotion, int $currentPrice): int
    {
        if ($promotion->discount_type === Promotion::DISCOUNT_PERCENTAGE) {
            return (int) round($currentPrice * ((float) $promotion->discount_value / 100));
        }

        if ($promotion->discount_type === Promotion::DISCOUNT_FIXED) {
            return (int) round((float) $promotion->discount_value);
        }

        return 0;
    }
}