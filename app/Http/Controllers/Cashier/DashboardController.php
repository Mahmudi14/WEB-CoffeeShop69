<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Services\Cashier\CashierDashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private readonly CashierDashboardService $dashboardService
    ) {
    }

    public function index(Request $request)
    {
        $data = $this->dashboardService->dashboardData($request->user());

        return view('cashier.dashboard', [
            'summary' => $data['summary'],
            'activeShift' => $data['activeShift'],
            'incomingOrderCount' => $data['incomingOrderCount'],
        ]);
    }
}