<?php

namespace App\Services\Admin;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminMenuImageService
{
    private string $disk = 'public';

    public function store(?UploadedFile $image): ?string
    {
        if (! $image) {
            return null;
        }

        return $image->store('menus', $this->disk);
    }

    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
        }
    }
}