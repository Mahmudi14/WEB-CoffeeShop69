<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminCategoryQueryService
{
    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $status = $filters['status'] ?? null;

        return Category::query()
            ->withCount('menus')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();
    }
}