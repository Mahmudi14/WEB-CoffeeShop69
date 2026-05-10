<?php

namespace App\Services\Admin;

use App\Models\TaxSetting;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminTaxService
{
    public function create(array $data, User $admin): TaxSetting
    {
        return DB::transaction(function () use ($data, $admin) {
            if ((bool) $data['is_active']) {
                $this->deactivateOtherTaxes($admin);
            }

            return TaxSetting::create([
                'name' => $data['name'],
                'rate' => $data['rate'],
                'is_active' => (bool) $data['is_active'],
                'price_includes_tax' => (bool) $data['price_includes_tax'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        });
    }

    public function update(TaxSetting $tax, array $data, User $admin): TaxSetting
    {
        return DB::transaction(function () use ($tax, $data, $admin) {
            if ((bool) $data['is_active']) {
                $this->deactivateOtherTaxes($admin, $tax->id);
            }

            $tax->update([
                'name' => $data['name'],
                'rate' => $data['rate'],
                'is_active' => (bool) $data['is_active'],
                'price_includes_tax' => (bool) $data['price_includes_tax'],
                'updated_by' => $admin->id,
            ]);

            return $tax->fresh();
        });
    }

    public function toggleStatus(TaxSetting $tax, User $admin): TaxSetting
    {
        return DB::transaction(function () use ($tax, $admin) {
            if (! $tax->is_active) {
                $this->deactivateOtherTaxes($admin, $tax->id);

                $tax->update([
                    'is_active' => true,
                    'updated_by' => $admin->id,
                ]);

                return $tax->fresh();
            }

            $tax->update([
                'is_active' => false,
                'updated_by' => $admin->id,
            ]);

            return $tax->fresh();
        });
    }

    private function deactivateOtherTaxes(User $admin, ?int $exceptId = null): void
    {
        TaxSetting::query()
            ->when($exceptId, function ($query) use ($exceptId) {
                $query->where('id', '!=', $exceptId);
            })
            ->update([
                'is_active' => false,
                'updated_by' => $admin->id,
            ]);
    }
}