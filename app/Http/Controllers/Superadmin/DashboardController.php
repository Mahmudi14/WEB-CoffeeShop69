<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Services\Superadmin\SuperadminDashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private readonly SuperadminDashboardService $dashboardService
    ) {
    }

    public function index()
    {
        return view(
            'superadmin.dashboard',
            $this->dashboardService->dashboardData()
        );
    }
}