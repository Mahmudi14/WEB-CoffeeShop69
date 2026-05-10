<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftExpense extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function shift()
    {
        return $this->belongsTo(CashierShift::class, 'cashier_shift_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}