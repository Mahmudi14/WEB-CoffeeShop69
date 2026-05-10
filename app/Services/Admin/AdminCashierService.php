<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminCashierService
{
    public function create(array $data): User
    {
        $cashier = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        $cashier->assignRole('cashier');

        return $cashier;
    }

    public function update(User $cashier, array $data): User
    {
        $this->ensureCashier($cashier);

        $cashier->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        if (! empty($data['password'])) {
            $cashier->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        return $cashier->fresh();
    }

    public function toggleStatus(User $cashier): User
    {
        $this->ensureCashier($cashier);

        $cashier->update([
            'is_active' => ! $cashier->is_active,
        ]);

        return $cashier->fresh();
    }

    public function ensureCashier(User $user): void
    {
        abort_unless($user->hasRole('cashier'), 404);
    }
}