<?php

namespace App\Http\Middleware;

use App\Services\Cashier\ActiveCashierShiftService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCashierShiftIsOpen
{
    public function __construct(
        private readonly ActiveCashierShiftService $activeCashierShiftService
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $activeShift = $this->activeCashierShiftService->getForUser($request->user());

        if (! $activeShift) {
            if (
                $request->expectsJson() ||
                $request->routeIs(
                    'cashier.incoming-orders.poll',
                    'cashier.incoming-orders.customer-receipt-status',
                    'cashier.incoming-orders.kitchen-order-status'
                )
            ) {
                return response()->json([
                    'success' => false,
                    'signature' => null,
                    'count' => 0,
                    'latest_order' => null,
                    'message' => 'Shift belum aktif.',
                ], 403);
            }

            return redirect()
                ->route('cashier.shifts.start')
                ->with('error', 'Kamu harus membuka shift sebelum memproses order.');
        }

        $request->attributes->set('activeCashierShift', $activeShift);

        return $next($request);
    }
}