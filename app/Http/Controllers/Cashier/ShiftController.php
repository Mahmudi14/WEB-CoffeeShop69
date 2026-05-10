<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cashier\CloseCashierShiftRequest;
use App\Http\Requests\Cashier\StartCashierShiftRequest;
use App\Models\CashierShift;
use App\Services\Cashier\CashierShiftService;
use App\Services\Cashier\CashierShiftSummaryService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ShiftController extends Controller
{
    public function __construct(
        private readonly CashierShiftService $shiftService,
        private readonly CashierShiftSummaryService $summaryService
    ) {
    }

    public function start(Request $request)
    {
        $myActiveShift = $this->shiftService->getActiveForUser($request->user());

        if ($myActiveShift) {
            return redirect()
                ->route('cashier.dashboard')
                ->with('success', 'Shift kamu sudah aktif.');
        }

        $activeShift = $this->shiftService->getAnyActiveShift();

        if ($activeShift) {
            return redirect()
                ->route('cashier.dashboard')
                ->with('error', 'Shift belum bisa dimulai. Saat ini shift masih aktif oleh kasir ' . ($activeShift->user?->name ?? 'lain') . '.');
        }

        return view('cashier.shifts.start');
    }

    public function store(StartCashierShiftRequest $request)
    {
        try {
            $this->shiftService->start(
                cashier: $request->user(),
                data: $request->validated()
            );
        } catch (ValidationException $exception) {
            return redirect()
                ->route('cashier.dashboard')
                ->with('error', collect($exception->errors())->flatten()->first());
        }

        return redirect()
            ->route('cashier.dashboard')
            ->with('success', 'Shift berhasil dimulai.');
    }

    public function close(Request $request)
    {
        $activeShift = $this->activeShift($request);

        $unfinishedOrdersCount = $this->summaryService->unfinishedOrdersCount($activeShift);
        $canCloseShift = $unfinishedOrdersCount === 0;

        $summary = $this->summaryService->buildSummary($activeShift);
        $soldItems = $this->summaryService->soldItems($activeShift);
        $expenses = $this->summaryService->expenses($activeShift);

        return view('cashier.shifts.close', compact(
            'activeShift',
            'summary',
            'soldItems',
            'expenses',
            'unfinishedOrdersCount',
            'canCloseShift'
        ));
    }

    public function closeStore(CloseCashierShiftRequest $request)
    {
        $activeShift = $this->activeShift($request);

        try {
            $this->shiftService->close(
                shift: $activeShift,
                cashier: $request->user(),
                data: $request->validated()
            );
        } catch (ValidationException $exception) {
            return redirect()
                ->route('cashier.shifts.close')
                ->with('error', collect($exception->errors())->flatten()->first());
        }

        return redirect()
            ->route('cashier.dashboard')
            ->with('success', 'Shift berhasil ditutup. Rekapan shift masuk ke antrean cetak.');
    }

    private function activeShift(Request $request): CashierShift
    {
        $activeShift = $request->attributes->get('activeCashierShift');

        if (! $activeShift instanceof CashierShift) {
            abort(403, 'Kamu belum membuka shift.');
        }

        return $activeShift;
    }
}