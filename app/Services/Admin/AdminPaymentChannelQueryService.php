<?php

namespace App\Services\Admin;

use App\Models\PaymentChannel;
use Illuminate\Support\Collection;

class AdminPaymentChannelQueryService
{
    public function allOrdered(): Collection
    {
        return PaymentChannel::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function methodLabels(): array
    {
        return PaymentChannel::methodLabels();
    }
}