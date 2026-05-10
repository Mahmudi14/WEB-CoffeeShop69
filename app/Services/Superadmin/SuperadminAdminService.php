<?php

namespace App\Services\Superadmin;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperadminAdminService
{
    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {
    }

    public function create(array $data, User $superadmin): User
    {
        return DB::transaction(function () use ($data, $superadmin) {
            $admin = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);

            $admin->syncRoles(['admin']);

            $this->logAdminActivity(
                actor: $superadmin,
                action: 'create',
                description: 'Membuat akun admin: ' . $admin->name,
                admin: $admin
            );

            return $admin;
        });
    }

    public function update(User $admin, array $data, User $superadmin): User
    {
        $this->ensureAdmin($admin);

        return DB::transaction(function () use ($admin, $data, $superadmin) {
            $payload = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'is_active' => (bool) ($data['is_active'] ?? false),
            ];

            if (! empty($data['password'])) {
                $payload['password'] = Hash::make($data['password']);
            }

            $admin->update($payload);
            $admin->syncRoles(['admin']);

            $admin = $admin->fresh();

            $this->logAdminActivity(
                actor: $superadmin,
                action: 'update',
                description: 'Memperbarui akun admin: ' . $admin->name,
                admin: $admin
            );

            return $admin;
        });
    }

    public function toggleStatus(User $admin, User $superadmin): User
    {
        $this->ensureAdmin($admin);

        return DB::transaction(function () use ($admin, $superadmin) {
            $admin->update([
                'is_active' => ! $admin->is_active,
            ]);

            $admin = $admin->fresh();

            $this->logAdminActivity(
                actor: $superadmin,
                action: $admin->is_active ? 'activate' : 'deactivate',
                description: ($admin->is_active ? 'Mengaktifkan' : 'Menonaktifkan') . ' akun admin: ' . $admin->name,
                admin: $admin
            );

            return $admin;
        });
    }

    public function ensureAdmin(User $user): void
    {
        abort_unless($user->hasRole('admin'), 404);
    }

    private function logAdminActivity(
        User $actor,
        string $action,
        string $description,
        User $admin
    ): void {
        $this->activityLogService->log(
            actor: $actor,
            module: 'admin_account',
            action: $action,
            description: $description,
            subject: $admin,
            properties: [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => 'admin',
            ]
        );
    }
}