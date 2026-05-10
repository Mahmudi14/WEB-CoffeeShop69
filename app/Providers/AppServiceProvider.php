<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $incomingOrderCount = 0;

            if (Auth::check() && Auth::user()->hasRole('cashier')) {
                $incomingOrderCount = Order::query()
                    ->where('order_source', Order::SOURCE_CUSTOMER_QR)
                    ->whereIn('payment_status', [
                        Order::PAYMENT_UNPAID,
                        Order::PAYMENT_PENDING_VERIFICATION,
                    ])
                    ->whereIn('order_status', [
                        Order::STATUS_PENDING_PAYMENT,
                        Order::STATUS_PENDING_PAYMENT_VERIFICATION,
                    ])
                    ->count();
            }

            $view->with('cashierIncomingOrderCount', $incomingOrderCount);
        });
    }
}