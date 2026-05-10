<?php

namespace App\Services\Customer;

use App\Models\CashierShift;

class OrderingAvailabilityService
{
    public function isOpen(): bool
    {
        return CashierShift::query()
            ->where('status', CashierShift::STATUS_OPEN)
            ->exists();
    }
}