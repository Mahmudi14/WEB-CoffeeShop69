<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use SoftDeletes;

    public const SCOPE_ALL_MENU = 'all_menu';
    public const SCOPE_SELECTED_MENU = 'selected_menu';

    public const DISCOUNT_PERCENTAGE = 'percentage';
    public const DISCOUNT_FIXED = 'fixed';

    protected $guarded = ['id'];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'priority' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'promotion_menu');
    }

    public function orderItemPromotions()
    {
        return $this->hasMany(OrderItemPromotion::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            });
    }
}