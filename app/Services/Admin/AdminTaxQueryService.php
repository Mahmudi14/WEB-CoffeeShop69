<?php

namespace App\Services\Admin;

use App\Models\TaxSetting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminTaxQueryService
{
    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $status = $filters['status'] ?? null;

        return TaxSetting::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function activeTax(): ?TaxSetting
    {
        return TaxSetting::query()
            ->where('is_active', true)
            ->latest()
            ->first();
    }
}