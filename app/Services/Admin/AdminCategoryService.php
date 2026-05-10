<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Str;

class AdminCategoryService
{
    public function create(array $data): Category
    {
        return Category::create([
            'name' => $data['name'],
            'slug' => $this->generateUniqueSlug($data['name']),
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update([
            'name' => $data['name'],
            'slug' => $this->generateUniqueSlug($data['name'], $category->id),
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return $category->fresh();
    }

    public function toggleStatus(Category $category): Category
    {
        $category->update([
            'is_active' => ! $category->is_active,
        ]);

        return $category->fresh();
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