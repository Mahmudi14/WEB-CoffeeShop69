<?php

namespace App\Services\Admin;

use App\Models\PaymentChannel;
use Illuminate\Http\UploadedFile;

class AdminPaymentChannelService
{
    public function __construct(
        private readonly AdminPaymentChannelImageService $imageService
    ) {
    }

    public function create(array $data, ?UploadedFile $qrImage = null): PaymentChannel
    {
        $qrImagePath = null;

        if ($qrImage) {
            $qrImagePath = $this->imageService->store($qrImage);
        }

        return PaymentChannel::create([
            'method' => $data['method'],
            'name' => $data['name'],
            'account_name' => $data['account_name'] ?? null,
            'account_number' => $data['account_number'] ?? null,
            'qr_image_path' => $qrImagePath,

            // Tidak dikirim dari form, disetel otomatis
            'note' => null,
            'sort_order' => 0,
            'is_active' => true,
        ]);
    }

    public function update(
        PaymentChannel $paymentChannel,
        array $data,
        ?UploadedFile $qrImage = null,
        bool $removeQrImage = false
    ): PaymentChannel {
        $oldQrImagePath = $paymentChannel->qr_image_path;
        $qrImagePath = $oldQrImagePath;

        if ($removeQrImage) {
            $this->imageService->delete($oldQrImagePath);
            $qrImagePath = null;
        }

        if ($qrImage) {
            $newQrImagePath = $this->imageService->store($qrImage);

            if ($oldQrImagePath) {
                $this->imageService->delete($oldQrImagePath);
            }

            $qrImagePath = $newQrImagePath;
        }

        $paymentChannel->update([
            'method' => $data['method'],
            'name' => $data['name'],
            'account_name' => $data['account_name'] ?? null,
            'account_number' => $data['account_number'] ?? null,
            'qr_image_path' => $qrImagePath,

            // Tidak dipakai lagi dari form
            'note' => null,
            'sort_order' => 0,

            // Saat edit, status boleh diubah
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return $paymentChannel->fresh();
    }

    public function delete(PaymentChannel $paymentChannel): void
    {
        $this->imageService->delete($paymentChannel->qr_image_path);

        $paymentChannel->delete();
    }
}