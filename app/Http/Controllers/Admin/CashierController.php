<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCashierRequest;
use App\Http\Requests\Admin\UpdateCashierRequest;
use App\Models\User;
use App\Services\Admin\AdminCashierQueryService;
use App\Services\Admin\AdminCashierService;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function __construct(
        private readonly AdminCashierQueryService $cashierQueryService,
        private readonly AdminCashierService $cashierService
    ) {
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search')),
            'status' => $request->query('status'),
        ];

        $cashiers = $this->cashierQueryService->paginated($filters);

        return view('admin.cashiers.index', [
            'cashiers' => $cashiers,
            'search' => $filters['search'],
            'status' => $filters['status'],
        ]);
    }

    public function create()
    {
        return view('admin.cashiers.create');
    }

    public function store(StoreCashierRequest $request)
    {
        $this->cashierService->create($request->validated());

        return redirect()
            ->route('admin.cashiers.index')
            ->with('success', 'Kasir berhasil ditambahkan.');
    }

    public function show(User $cashier)
{
    return view('admin.cashiers.show', compact('cashier'));
}

public function destroy(User $cashier)
{
    $cashier->delete();

    return redirect()
        ->route('admin.cashiers.index')
        ->with('success', 'Kasir berhasil dihapus.');
}

    public function edit(User $cashier)
    {
        $this->cashierService->ensureCashier($cashier);

        return view('admin.cashiers.edit', compact('cashier'));
    }

    public function update(UpdateCashierRequest $request, User $cashier)
    {
        $this->cashierService->update(
            cashier: $cashier,
            data: $request->validated()
        );

        return redirect()
            ->route('admin.cashiers.index')
            ->with('success', 'Data kasir berhasil diperbarui.');
    }

    public function toggleStatus(User $cashier)
    {
        $cashier = $this->cashierService->toggleStatus($cashier);

        return back()->with(
            'success',
            $cashier->is_active
                ? 'Kasir berhasil diaktifkan.'
                : 'Kasir berhasil dinonaktifkan.'
        );
    }
}