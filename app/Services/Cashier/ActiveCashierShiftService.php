<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;
use App\Models\User;

class ActiveCashierShiftService
{
    public function getForUser(User|int|null $user): ?CashierShift
    {
        if (! $user) {
            return null;
        }

        $userId = $user instanceof User ? $user->id : $user;

        return CashierShift::query()
            ->where('user_id', $userId)
            ->where('status', CashierShift::STATUS_OPEN)
            ->first();
    }
}