<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTableRequest;
use App\Http\Requests\Admin\UpdateTableRequest;
use App\Models\CafeTable;
use App\Services\Admin\AdminTableQueryService;
use App\Services\Admin\AdminTableService;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function __construct(
        private readonly AdminTableQueryService $tableQueryService,
        private readonly AdminTableService $tableService
    ) {
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search')),
            'status' => $request->query('status'),
        ];

        $tables = $this->tableQueryService->paginated($filters);

        return view('admin.tables.index', [
            'tables' => $tables,
            'search' => $filters['search'],
            'status' => $filters['status'],
        ]);
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(StoreTableRequest $request)
    {
        $this->tableService->create(
            data: $request->validatedData(),
            admin: $request->user()
        );

        return redirect()
            ->route('admin.tables.index')
            ->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit(CafeTable $table)
    {
        return view('admin.tables.edit', compact('table'));
    }

    public function update(UpdateTableRequest $request, CafeTable $table)
    {
        $this->tableService->update(
            table: $table,
            data: $request->validatedData(),
            admin: $request->user()
        );

        return redirect()
            ->route('admin.tables.index')
            ->with('success', 'Data meja berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, CafeTable $table)
    {
        $table = $this->tableService->toggleStatus(
            table: $table,
            admin: $request->user()
        );

        return back()->with(
            'success',
            $table->is_active
                ? 'Meja berhasil diaktifkan.'
                : 'Meja berhasil dinonaktifkan.'
        );
    }

    public function regenerateQrToken(Request $request, CafeTable $table)
    {
        $this->tableService->regenerateQrToken(
            table: $table,
            admin: $request->user()
        );

        return back()->with('success', 'QR token meja berhasil dibuat ulang.');
    }
}