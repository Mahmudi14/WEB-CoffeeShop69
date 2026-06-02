<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminCategoryService
{
    public function create(array $data): Category
    {
        return DB::transaction(function () use ($data) {
            $targetSortOrder = $this->normalizeSortOrder($data['sort_order'] ?? null);

            Category::query()
                ->where('sort_order', '>=', $targetSortOrder)
                ->increment('sort_order');

            return Category::create([
                'name' => $data['name'],
                'slug' => $this->generateUniqueSlug($data['name']),
                'description' => $data['description'] ?? null,
                'sort_order' => $targetSortOrder,
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);
        });
    }

    public function update(Category $category, array $data): Category
    {
        return DB::transaction(function () use ($category, $data) {
            $oldSortOrder = (int) $category->sort_order;
            $newSortOrder = $this->normalizeSortOrder($data['sort_order'] ?? $oldSortOrder);

            if ($newSortOrder < $oldSortOrder) {
                Category::query()
                    ->where('id', '!=', $category->id)
                    ->whereBetween('sort_order', [$newSortOrder, $oldSortOrder - 1])
                    ->increment('sort_order');
            }

            if ($newSortOrder > $oldSortOrder) {
                Category::query()
                    ->where('id', '!=', $category->id)
                    ->whereBetween('sort_order', [$oldSortOrder + 1, $newSortOrder])
                    ->decrement('sort_order');
            }

            $category->update([
                'name' => $data['name'],
                'slug' => $this->generateUniqueSlug($data['name'], $category->id),
                'description' => $data['description'] ?? null,
                'sort_order' => $newSortOrder,
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);

            return $category->fresh();
        });
    }

    public function toggleStatus(Category $category): Category
    {
        $category->update([
            'is_active' => ! $category->is_active,
        ]);

        return $category->fresh();
    }

    private function normalizeSortOrder(mixed $sortOrder): int
    {
        $sortOrder = (int) $sortOrder;

        return $sortOrder < 1 ? 1 : $sortOrder;
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Category::query()
                ->where('slug', $slug)
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}