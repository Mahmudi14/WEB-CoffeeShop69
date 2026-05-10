<?php

namespace App\Services\Cashier;

use App\Models\Category;
use Illuminate\Support\Collection;

class CashierPosCatalogService
{
    public function getActiveCategoriesWithMenus(): Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->with(['menus' => function ($query) {
                $query->where('is_active', true)
                    ->where('is_available', true)
                    ->orderBy('name');
            }])
            ->orderBy('name')
            ->get();
    }

    public function buildMenusForJs(Collection $categories): Collection
    {
        return $categories
            ->flatMap(function ($category) {
                return $category->menus->map(function ($menu) use ($category) {
                    return [
                        'id' => $menu->id,
                        'category_id' => $category->id,
                        'name' => $menu->name,
                        'description' => $menu->description,
                        'normal_price' => (int) $menu->normal_price,
                    ];
                });
            })
            ->values();
    }
}