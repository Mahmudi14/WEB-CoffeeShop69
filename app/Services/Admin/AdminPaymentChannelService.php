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
        if ($qrImage) {
            $data['qr_image_path'] = $this->imageService->store($qrImage);
        }

        return PaymentChannel::create($data);
    }

    public function update(
        PaymentChannel $paymentChannel,
        array $data,
        ?UploadedFile $qrImage = null,
        bool $removeQrImage = false
    ): PaymentChannel {
        $oldQrImagePath = $paymentChannel->qr_image_path;

        if ($removeQrImage) {
            $this->imageService->delete($oldQrImagePath);
            $data['qr_image_path'] = null;
        }

        if ($qrImage) {
            $newQrImagePath = $this->imageService->store($qrImage);

            $this->imageService->delete($oldQrImagePath);

            $data['qr_image_path'] = $newQrImagePath;
        }

        $paymentChannel->update($data);

        return $paymentChannel->fresh();
    }

    public function delete(PaymentChannel $paymentChannel): void
    {
        $this->imageService->delete($paymentChannel->qr_image_path);

        $paymentChannel->delete();
    }
}