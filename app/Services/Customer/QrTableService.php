<?php

namespace App\Services\Customer;

use App\Models\CafeTable;

class QrTableService
{
    public function findActiveByToken(string $qrToken): CafeTable
    {
        return CafeTable::query()
            ->where('qr_token', $qrToken)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function cartSessionKey(CafeTable $table): string
    {
        return 'customer_cart_table_' . $table->id;
    }
}