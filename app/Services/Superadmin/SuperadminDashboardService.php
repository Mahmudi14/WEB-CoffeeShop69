<?php

namespace App\Services\Superadmin;

use App\Models\ActivityLog;
use App\Models\CashierShift;
use App\Models\User;

class SuperadminDashboardService
{
    public function dashboardData(): array
    {
        return [
            'adminCount' => $this->userCountByRole('admin'),
            'activeAdminCount' => $this->activeUserCountByRole('admin'),

            'cashierCount' => $this->userCountByRole('cashier'),
            'activeCashierCount' => $this->activeUserCountByRole('cashier'),

            'activeShiftCount' => $this->activeShiftCount(),

            'latestActivities' => $this->latestActivities(),
        ];
    }

    private function userCountByRole(string $role): int
    {
        return User::role($role)->count();
    }

    private function activeUserCountByRole(string $role): int
    {
        return User::role($role)
            ->where('is_active', true)
            ->count();
    }

    private function activeShiftCount(): int
    {
        return CashierShift::query()
            ->where('status', CashierShift::STATUS_OPEN)
            ->count();
    }

    private function latestActivities()
    {
        return ActivityLog::query()
            ->with('user')
            ->latest()
            ->limit(8)
            ->get();
    }
}