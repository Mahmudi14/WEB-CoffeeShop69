<?php

namespace App\Services\Customer;

use App\Models\Category;
use Illuminate\Support\Collection;

class CustomerMenuService
{
    public function getActiveCategoriesWithAvailableMenus(): Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->with(['menus' => function ($query) {
                $query->where('is_active', true)
                    ->where('is_available', true)
                    ->orderBy('sort_order')
                    ->orderBy('name');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function buildMenusForJs(Collection $categories): Collection
    {
        return $categories
            ->flatMap(fn ($category) => $category->menus)
            ->map(fn ($menu) => [
                'id' => $menu->id,
                'name' => $menu->name,
                'description' => $menu->description,
                'normal_price' => (int) $menu->normal_price,
                'image_path' => $menu->image_path,
            ])
            ->values();
    }
}