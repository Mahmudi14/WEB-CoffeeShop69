<?php

namespace App\Services\Admin;

use App\Models\Menu;
use App\Models\Promotion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminPromotionQueryService
{
    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $scope = $filters['scope'] ?? null;
        $status = $filters['status'] ?? null;

        return Promotion::query()
            ->withCount('menus')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(in_array($scope, [
                Promotion::SCOPE_ALL_MENU,
                Promotion::SCOPE_SELECTED_MENU,
            ], true), function ($query) use ($scope) {
                $query->where('scope', $scope);
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->orderBy('priority')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();
    }

    public function menuOptions(): Collection
    {
        return Menu::query()
            ->with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}