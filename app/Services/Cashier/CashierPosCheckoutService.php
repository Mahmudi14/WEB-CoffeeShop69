<?php

namespace App\Services\Cashier;

use App\Models\CafeTable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CashierPosCheckoutService
{
    private const SUBMIT_TOKEN_KEY = 'cashier_pos_order_submit_token';

    public function activeTables(): Collection
    {
        return CafeTable::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function generateSubmitToken(): string
    {
        $token = (string) Str::uuid();

        session([
            self::SUBMIT_TOKEN_KEY => $token,
        ]);

        return $token;
    }

    public function isValidSubmitToken(?string $submittedToken): bool
    {
        $submittedToken = (string) $submittedToken;
        $sessionToken = (string) session(self::SUBMIT_TOKEN_KEY);

        return $submittedToken !== ''
            && $sessionToken !== ''
            && hash_equals($sessionToken, $submittedToken);
    }

    public function forgetSubmitToken(): void
    {
        session()->forget(self::SUBMIT_TOKEN_KEY);
    }
}