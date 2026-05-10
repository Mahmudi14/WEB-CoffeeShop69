<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashierShift extends Model
{
    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';

    protected $guarded = ['id'];

    protected $casts = [
        'opening_cash' => 'integer',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function expenses()
    {
        return $this->hasMany(ShiftExpense::class);
    }

    public function printJobs()
    {
        return $this->hasMany(PrintJob::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}