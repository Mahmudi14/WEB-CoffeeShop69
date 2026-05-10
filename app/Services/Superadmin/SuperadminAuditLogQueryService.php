<?php

namespace App\Services\Superadmin;

use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SuperadminAuditLogQueryService
{
    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $module = $filters['module'] ?? null;

        return ActivityLog::query()
            ->with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('description', 'like', "%{$search}%")
                        ->orWhere('action', 'like', "%{$search}%")
                        ->orWhere('module', 'like', "%{$search}%");
                });
            })
            ->when($module, function ($query) use ($module) {
                $query->where('module', $module);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();
    }

    public function modules(): Collection
    {
        return ActivityLog::query()
            ->select('module')
            ->whereNotNull('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');
    }
}