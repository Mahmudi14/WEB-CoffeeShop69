<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Services\Superadmin\SuperadminAuditLogQueryService;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct(
        private readonly SuperadminAuditLogQueryService $auditLogQueryService
    ) {
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search')),
            'module' => $request->query('module'),
        ];

        $logs = $this->auditLogQueryService->paginated($filters);
        $modules = $this->auditLogQueryService->modules();

        return view('superadmin.audit-logs.index', [
            'logs' => $logs,
            'modules' => $modules,
            'search' => $filters['search'],
            'module' => $filters['module'],
        ]);
    }
}