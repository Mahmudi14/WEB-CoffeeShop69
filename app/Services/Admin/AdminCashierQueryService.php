<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminCashierQueryService
{
    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $status = $filters['status'] ?? null;

        return User::query()
            ->role('cashier')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
}