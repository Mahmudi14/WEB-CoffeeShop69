<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public const METHOD_CASH = 'cash';
    public const METHOD_QRIS = 'qris';
    public const METHOD_TRANSFER = 'transfer';

    public const STATUS_UNPAID = 'unpaid';
    public const STATUS_PENDING_VERIFICATION = 'pending_verification';
    public const STATUS_PAID = 'paid';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_VOIDED = 'voided';

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'integer',
        'paid_amount' => 'integer',
        'change_amount' => 'integer',
        'proof_uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}