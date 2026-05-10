<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentChannel extends Model
{
    public const METHOD_QRIS = 'qris';
    public const METHOD_TRANSFER = 'transfer';
    public const METHOD_EWALLET = 'ewallet';

    protected $fillable = [
        'method',
        'name',
        'account_name',
        'account_number',
        'qr_image_path',
        'note',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function methodLabels(): array
    {
        return [
            self::METHOD_QRIS => 'QRIS',
            self::METHOD_TRANSFER => 'Transfer Bank',
            self::METHOD_EWALLET => 'E-Wallet',
        ];
    }

    public function getMethodLabelAttribute(): string
    {
        return self::methodLabels()[$this->method] ?? $this->method;
    }
}
