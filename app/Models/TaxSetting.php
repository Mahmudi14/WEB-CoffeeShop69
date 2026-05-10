<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxSetting extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
        'price_includes_tax' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}