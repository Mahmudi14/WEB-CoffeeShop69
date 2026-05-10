<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveTaxSettingRequest;
use App\Models\TaxSetting;
use App\Services\Admin\AdminTaxQueryService;
use App\Services\Admin\AdminTaxService;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function __construct(
        private readonly AdminTaxQueryService $taxQueryService,
        private readonly AdminTaxService $taxService
    ) {
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search')),
            'status' => $request->query('status'),
        ];

        $taxes = $this->taxQueryService->paginated($filters);
        $activeTax = $this->taxQueryService->activeTax();

        return view('admin.taxes.index', [
            'taxes' => $taxes,
            'activeTax' => $activeTax,
            'search' => $filters['search'],
            'status' => $filters['status'],
        ]);
    }

    public function create()
    {
        return view('admin.taxes.create');
    }

    public function store(SaveTaxSettingRequest $request)
    {
        $this->taxService->create(
            data: $request->validatedData(),
            admin: $request->user()
        );

        return redirect()
            ->route('admin.taxes.index')
            ->with('success', 'Pengaturan pajak berhasil ditambahkan.');
    }

    public function edit(TaxSetting $tax)
    {
        return view('admin.taxes.edit', compact('tax'));
    }

    public function update(SaveTaxSettingRequest $request, TaxSetting $tax)
    {
        $this->taxService->update(
            tax: $tax,
            data: $request->validatedData(),
            admin: $request->user()
        );

        return redirect()
            ->route('admin.taxes.index')
            ->with('success', 'Pengaturan pajak berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, TaxSetting $tax)
    {
        $tax = $this->taxService->toggleStatus(
            tax: $tax,
            admin: $request->user()
        );

        return back()->with(
            'success',
            $tax->is_active
                ? 'Pajak berhasil diaktifkan.'
                : 'Pajak berhasil dinonaktifkan.'
        );
    }
}