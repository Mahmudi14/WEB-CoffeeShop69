<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'quantity' => 'integer',
        'normal_price' => 'integer',
        'final_price' => 'integer',
        'subtotal_before_discount' => 'integer',
        'total_discount' => 'integer',
        'subtotal_after_discount' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function promotions()
    {
        return $this->hasMany(OrderItemPromotion::class);
    }
}