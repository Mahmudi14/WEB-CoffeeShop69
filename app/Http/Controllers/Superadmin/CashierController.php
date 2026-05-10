<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\StoreCashierAccountRequest;
use App\Http\Requests\Superadmin\UpdateCashierAccountRequest;
use App\Models\User;
use App\Services\Superadmin\SuperadminCashierQueryService;
use App\Services\Superadmin\SuperadminCashierService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CashierController extends Controller
{
    public function __construct(
        private readonly SuperadminCashierQueryService $cashierQueryService,
        private readonly SuperadminCashierService $cashierService
    ) {
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search')),
            'status' => $request->query('status'),
        ];

        $cashiers = $this->cashierQueryService->paginated($filters);

        return view('superadmin.cashiers.index', [
            'cashiers' => $cashiers,
            'search' => $filters['search'],
            'status' => $filters['status'],
        ]);
    }

    public function create()
    {
        return view('superadmin.cashiers.create');
    }

    public function store(StoreCashierAccountRequest $request)
    {
        $this->cashierService->create(
            data: $request->validatedData(),
            superadmin: $request->user()
        );

        return redirect()
            ->route('superadmin.cashiers.index')
            ->with('success', 'Akun kasir berhasil dibuat.');
    }

    public function edit(User $cashier)
    {
        $this->cashierService->ensureCashier($cashier);

        return view('superadmin.cashiers.edit', compact('cashier'));
    }

    public function update(UpdateCashierAccountRequest $request, User $cashier)
    {
        $this->cashierService->update(
            cashier: $cashier,
            data: $request->validatedData(),
            superadmin: $request->user()
        );

        return redirect()
            ->route('superadmin.cashiers.index')
            ->with('success', 'Akun kasir berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, User $cashier)
    {
        try {
            $cashier = $this->cashierService->toggleStatus(
                cashier: $cashier,
                superadmin: $request->user()
            );
        } catch (ValidationException $exception) {
            return back()->with(
                'error',
                collect($exception->errors())->flatten()->first()
            );
        }

        return back()->with(
            'success',
            $cashier->is_active
                ? 'Kasir berhasil diaktifkan.'
                : 'Kasir berhasil dinonaktifkan.'
        );
    }
}