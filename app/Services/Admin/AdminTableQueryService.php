<?php

namespace App\Services\Admin;

use App\Models\CafeTable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminTableQueryService
{
    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $status = $filters['status'] ?? null;

        return CafeTable::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();
    }
}