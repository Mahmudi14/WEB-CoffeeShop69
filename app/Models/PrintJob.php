<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintJob extends Model
{
    public const TYPE_KITCHEN_ORDER = 'kitchen_order';
    public const TYPE_CUSTOMER_RECEIPT = 'customer_receipt';
    public const TYPE_SHIFT_CLOSING = 'shift_closing';

    public const STATUS_PENDING = 'pending';
    public const STATUS_PRINTING = 'printing';
    public const STATUS_PRINTED = 'printed';
    public const STATUS_FAILED = 'failed';

    protected $guarded = ['id'];

    protected $casts = [
        'payload' => 'array',
        'attempts' => 'integer',
        'printed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shift()
    {
        return $this->belongsTo(CashierShift::class, 'cashier_shift_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}