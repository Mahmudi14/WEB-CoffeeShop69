<?php

namespace App\Services\Customer;

use App\Models\PaymentChannel;
use Illuminate\Support\Collection;

class CustomerPaymentChannelService
{
    public function getActiveGroupedByMethod(): Collection
    {
        return PaymentChannel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('method');
    }
}