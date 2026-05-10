<?php

namespace App\Services\Superadmin;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SuperadminCashierService
{
    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {
    }

    public function create(array $data, User $superadmin): User
    {
        return DB::transaction(function () use ($data, $superadmin) {
            $cashier = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);

            $cashier->syncRoles(['cashier']);

            $this->logCashierActivity(
                actor: $superadmin,
                action: 'create',
                description: 'Membuat akun kasir: ' . $cashier->name,
                cashier: $cashier
            );

            return $cashier;
        });
    }

    public function update(User $cashier, array $data, User $superadmin): User
    {
        $this->ensureCashier($cashier);

        return DB::transaction(function () use ($cashier, $data, $superadmin) {
            $payload = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'is_active' => (bool) ($data['is_active'] ?? false),
            ];

            if (! empty($data['password'])) {
                $payload['password'] = Hash::make($data['password']);
            }

            $cashier->update($payload);
            $cashier->syncRoles(['cashier']);

            $cashier = $cashier->fresh();

            $this->logCashierActivity(
                actor: $superadmin,
                action: 'update',
                description: 'Memperbarui akun kasir: ' . $cashier->name,
                cashier: $cashier
            );

            return $cashier;
        });
    }

    public function toggleStatus(User $cashier, User $superadmin): User
    {
        $this->ensureCashier($cashier);

        if ($cashier->is_active && $cashier->activeCashierShift()->exists()) {
            throw ValidationException::withMessages([
                'cashier' => 'Kasir tidak bisa dinonaktifkan karena masih memiliki shift aktif.',
            ]);
        }

        return DB::transaction(function () use ($cashier, $superadmin) {
            $cashier->update([
                'is_active' => ! $cashier->is_active,
            ]);

            $cashier = $cashier->fresh();

            $this->logCashierActivity(
                actor: $superadmin,
                action: $cashier->is_active ? 'activate' : 'deactivate',
                description: ($cashier->is_active ? 'Mengaktifkan' : 'Menonaktifkan') . ' akun kasir: ' . $cashier->name,
                cashier: $cashier
            );

            return $cashier;
        });
    }

    public function ensureCashier(User $user): void
    {
        abort_unless($user->hasRole('cashier'), 404);
    }

    private function logCashierActivity(
        User $actor,
        string $action,
        string $description,
        User $cashier
    ): void {
        $this->activityLogService->log(
            actor: $actor,
            module: 'cashier_account',
            action: $action,
            description: $description,
            subject: $cashier,
            properties: [
                'name' => $cashier->name,
                'email' => $cashier->email,
                'role' => 'cashier',
            ]
        );
    }
}