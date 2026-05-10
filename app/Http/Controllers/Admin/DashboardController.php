<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminDashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AdminDashboardService $dashboardService
    ) {
    }

    public function index()
    {
        $data = $this->dashboardService->dashboardData();

        return view('admin.dashboard', [
            'summary' => $data['summary'],
            'topMenusToday' => $data['topMenusToday'],
            'latestOrders' => $data['latestOrders'],
            'activeCashierShifts' => $data['activeCashierShifts'],
        ]);
    }
}