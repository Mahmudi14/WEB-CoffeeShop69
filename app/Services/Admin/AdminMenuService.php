<?php

namespace App\Services\Admin;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class AdminMenuService
{
    public function __construct(
        private readonly AdminMenuImageService $imageService
    ) {
    }

    public function create(array $data, User $admin, ?UploadedFile $image = null): Menu
    {
        $imagePath = $this->imageService->store($image);

        return Menu::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $this->generateUniqueSlug($data['name']),
            'description' => $data['description'] ?? null,
            'normal_price' => $data['normal_price'],
            'sort_order' => $data['sort_order'] ?? 0,
            'image_path' => $imagePath,
            'is_active' => (bool) ($data['is_active'] ?? false),
            'is_available' => (bool) ($data['is_available'] ?? false),
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);
    }

    public function update(Menu $menu, array $data, User $admin, ?UploadedFile $image = null): Menu
    {
        $oldImagePath = $menu->image;
        $newImagePath = $oldImagePath;

        if ($image) {
            $newImagePath = $this->imageService->store($image);
        }

        $menu->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $this->generateUniqueSlug($data['name'], $menu->id),
            'description' => $data['description'] ?? null,
            'normal_price' => $data['normal_price'],
            'sort_order' => $data['sort_order'] ?? $menu->sort_order ?? 0,
            'image_path' => $newImagePath,
            'is_active' => (bool) ($data['is_active'] ?? false),
            'is_available' => (bool) ($data['is_available'] ?? false),
            'updated_by' => $admin->id,
        ]);

        if ($image && $oldImagePath) {
            $this->imageService->delete($oldImagePath);
        }

        return $menu->fresh();
    }

    public function toggleStatus(Menu $menu, User $admin): Menu
    {
        $menu->update([
            'is_active' => ! $menu->is_active,
            'updated_by' => $admin->id,
        ]);

        return $menu->fresh();
    }

    public function toggleAvailability(Menu $menu, User $admin): Menu
    {
        $menu->update([
            'is_available' => ! $menu->is_available,
            'updated_by' => $admin->id,
        ]);

        return $menu->fresh();
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Menu::query()
                ->where('slug', $slug)
                ->whereNull('deleted_at')
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