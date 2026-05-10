<?php

namespace App\Services\Admin;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminPaymentChannelImageService
{
    private string $disk = 'public';

    public function store(?UploadedFile $image): ?string
    {
        if (! $image) {
            return null;
        }

        return $image->store('payment-channels', $this->disk);
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