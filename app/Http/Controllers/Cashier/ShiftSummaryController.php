<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use App\Services\Cashier\CashierShiftSummaryService;
use Illuminate\Http\Request;

class ShiftSummaryController extends Controller
{
    public function __construct(
        private readonly CashierShiftSummaryService $summaryService
    ) {
    }

    public function index(Request $request)
    {
        $activeShift = $this->activeShift($request);

        $data = $this->summaryService->summaryPageData($activeShift);

        return view('cashier.shift-summary.index', [
            'activeShift' => $activeShift,
            'summary' => $data['summary'],
            'soldItems' => $data['soldItems'],
            'orders' => $data['orders'],
            'expenses' => $data['expenses'],
        ]);
    }

    private function activeShift(Request $request): CashierShift
    {
        $activeShift = $request->attributes->get('activeCashierShift');

        if (! $activeShift instanceof CashierShift) {
            abort(403, 'Kamu harus membuka shift untuk melihat ringkasan shift.');
        }

        return $activeShift;
    }
}