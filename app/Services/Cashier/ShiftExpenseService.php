<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;
use App\Models\ShiftExpense;
use App\Models\User;
use Illuminate\Support\Collection;

class ShiftExpenseService
{
    public function getByShift(CashierShift $shift): Collection
    {
        return ShiftExpense::query()
            ->where('cashier_shift_id', $shift->id)
            ->latest()
            ->get();
    }

    public function getTotalByShift(CashierShift $shift): int
    {
        return (int) ShiftExpense::query()
            ->where('cashier_shift_id', $shift->id)
            ->sum('amount');
    }

    public function create(CashierShift $shift, User $cashier, array $data): ShiftExpense
    {
        return ShiftExpense::create([
            'cashier_shift_id' => $shift->id,
            'user_id' => $cashier->id,
            'category' => $data['category'],
            'amount' => $data['amount'],
            'note' => $data['note'] ?? null,
        ]);
    }
}