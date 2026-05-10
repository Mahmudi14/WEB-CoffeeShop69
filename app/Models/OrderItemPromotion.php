<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemPromotion extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'priority' => 'integer',
        'price_before_discount' => 'integer',
        'discount_amount_per_unit' => 'integer',
        'price_after_discount' => 'integer',
        'quantity' => 'integer',
        'discount_amount_total' => 'integer',
        'applied_order' => 'integer',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}