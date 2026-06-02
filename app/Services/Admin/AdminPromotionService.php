<?php

namespace App\Services\Admin;

use App\Models\Promotion;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminPromotionService
{
    public function create(array $data, User $admin): Promotion
    {
        return DB::transaction(function () use ($data, $admin) {
            $promotion = Promotion::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'scope' => $data['scope'],
                'discount_type' => $data['discount_type'],
                'discount_value' => $data['discount_value'],

                // Promo baru otomatis priority 0
                'priority' => 0,

                'starts_at' => $data['starts_at'] ?? null,
                'ends_at' => $data['ends_at'] ?? null,
                'is_active' => (bool) ($data['is_active'] ?? false),
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);

            $this->syncMenus($promotion, $data);

            return $promotion;
        });
    }

    public function update(Promotion $promotion, array $data, User $admin): Promotion
    {
        return DB::transaction(function () use ($promotion, $data, $admin) {
            $promotion->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'scope' => $data['scope'],
                'discount_type' => $data['discount_type'],
                'discount_value' => $data['discount_value'],

                // Priority tidak diubah saat edit

                'starts_at' => $data['starts_at'] ?? null,
                'ends_at' => $data['ends_at'] ?? null,
                'is_active' => (bool) ($data['is_active'] ?? false),
                'updated_by' => $admin->id,
            ]);

            $this->syncMenus($promotion, $data);

            return $promotion->fresh();
        });
    }

    public function toggleStatus(Promotion $promotion, User $admin): Promotion
    {
        $promotion->update([
            'is_active' => ! $promotion->is_active,
            'updated_by' => $admin->id,
        ]);

        return $promotion->fresh();
    }

    private function syncMenus(Promotion $promotion, array $data): void
    {
        if ($data['scope'] === Promotion::SCOPE_SELECTED_MENU) {
            $promotion->menus()->sync($data['menu_ids'] ?? []);
            return;
        }

        $promotion->menus()->detach();
    }
}