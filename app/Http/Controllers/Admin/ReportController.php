<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReportIndexRequest;
use App\Services\Admin\AdminReportService;

class ReportController extends Controller
{
    public function __construct(
        private readonly AdminReportService $reportService
    ) {
    }

    public function index(ReportIndexRequest $request)
    {
        return view(
            'admin.reports.index',
            $this->reportService->reportData($request->filters())
        );
    }
}