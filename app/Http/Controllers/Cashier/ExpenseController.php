<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cashier\StoreShiftExpenseRequest;
use App\Models\CashierShift;
use App\Services\Cashier\ShiftExpenseService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct(
        private readonly ShiftExpenseService $shiftExpenseService
    ) {
    }

    public function index(Request $request)
    {
        $activeShift = $this->activeShift($request);

        $expenses = $this->shiftExpenseService->getByShift($activeShift);
        $totalExpense = $this->shiftExpenseService->getTotalByShift($activeShift);

        return view('cashier.expenses.index', compact(
            'activeShift',
            'expenses',
            'totalExpense'
        ));
    }

    public function create(Request $request)
    {
        $activeShift = $this->activeShift($request);

        return view('cashier.expenses.create', compact('activeShift'));
    }

    public function store(StoreShiftExpenseRequest $request)
    {
        $this->shiftExpenseService->create(
            shift: $this->activeShift($request),
            cashier: $request->user(),
            data: $request->validated()
        );

        return redirect()
            ->route('cashier.expenses.index')
            ->with('success', 'Pengeluaran shift berhasil dicatat.');
    }

    private function activeShift(Request $request): CashierShift
    {
        $activeShift = $request->attributes->get('activeCashierShift');

        if (! $activeShift instanceof CashierShift) {
            abort(403, 'Shift kasir belum dibuka.');
        }

        return $activeShift;
    }
}