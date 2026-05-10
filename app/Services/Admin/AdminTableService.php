<?php

namespace App\Services\Admin;

use App\Models\CafeTable;
use App\Models\User;
use Illuminate\Support\Str;

class AdminTableService
{
    public function create(array $data, User $admin): CafeTable
    {
        return CafeTable::create([
            'name' => $data['name'],
            'code' => $data['code'],
            'qr_token' => $this->generateUniqueQrToken(),
            'is_active' => (bool) ($data['is_active'] ?? false),
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);
    }

    public function update(CafeTable $table, array $data, User $admin): CafeTable
    {
        $table->update([
            'name' => $data['name'],
            'code' => $data['code'],
            'is_active' => (bool) ($data['is_active'] ?? false),
            'updated_by' => $admin->id,
        ]);

        return $table->fresh();
    }

    public function toggleStatus(CafeTable $table, User $admin): CafeTable
    {
        $table->update([
            'is_active' => ! $table->is_active,
            'updated_by' => $admin->id,
        ]);

        return $table->fresh();
    }

    public function regenerateQrToken(CafeTable $table, User $admin): CafeTable
    {
        $table->update([
            'qr_token' => $this->generateUniqueQrToken(),
            'updated_by' => $admin->id,
        ]);

        return $table->fresh();
    }

    private function generateUniqueQrToken(): string
    {
        do {
            $token = Str::random(40);
        } while (
            CafeTable::query()
                ->where('qr_token', $token)
                ->exists()
        );

        return $token;
    }
}