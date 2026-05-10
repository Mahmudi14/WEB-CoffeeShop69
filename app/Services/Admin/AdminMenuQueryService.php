<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminMenuQueryService
{
    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $categoryId = $filters['category_id'] ?? null;
        $status = $filters['status'] ?? null;
        $availability = $filters['availability'] ?? null;

        return Menu::query()
            ->with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->when($availability === 'available', function ($query) {
                $query->where('is_available', true);
            })
            ->when($availability === 'unavailable', function ($query) {
                $query->where('is_available', false);
            })
            ->orderBy(
                Category::query()
                    ->select('sort_order')
                    ->whereColumn('categories.id', 'menus.category_id')
                    ->limit(1)
            )
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();
    }

    public function activeCategories(): Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }
}