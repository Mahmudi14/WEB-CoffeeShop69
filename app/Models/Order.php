<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const SOURCE_CASHIER_POS = 'cashier_pos';
    public const SOURCE_CUSTOMER_QR = 'customer_qr';

    public const TYPE_DINE_IN = 'dine_in';
    public const TYPE_TAKEAWAY = 'takeaway';

    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PENDING_PAYMENT_VERIFICATION = 'pending_payment_verification';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_EXPIRED = 'expired';

    public const PAYMENT_UNPAID = 'unpaid';
    public const PAYMENT_PENDING_VERIFICATION = 'pending_verification';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_REJECTED = 'rejected';
    public const PAYMENT_VOIDED = 'voided';

    protected $guarded = ['id'];

    protected $casts = [
        'subtotal_before_discount' => 'integer',
        'discount_total' => 'integer',
        'subtotal_after_discount' => 'integer',
        'tax_rate' => 'decimal:2',
        'tax_total' => 'integer',
        'grand_total' => 'integer',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function shift()
    {
        return $this->belongsTo(CashierShift::class, 'cashier_shift_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function table()
    {
        return $this->belongsTo(CafeTable::class, 'table_id');
    }

    public function taxSetting()
    {
        return $this->belongsTo(TaxSetting::class);
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function printJobs()
    {
        return $this->hasMany(PrintJob::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function isCancelled(): bool
    {
        return $this->order_status === self::STATUS_CANCELLED;
    }
}