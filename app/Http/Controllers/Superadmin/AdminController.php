<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\StoreAdminAccountRequest;
use App\Http\Requests\Superadmin\UpdateAdminAccountRequest;
use App\Models\User;
use App\Services\Superadmin\SuperadminAdminQueryService;
use App\Services\Superadmin\SuperadminAdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        private readonly SuperadminAdminQueryService $adminQueryService,
        private readonly SuperadminAdminService $adminService
    ) {
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search')),
            'status' => $request->query('status'),
        ];

        $admins = $this->adminQueryService->paginated($filters);

        return view('superadmin.admins.index', [
            'admins' => $admins,
            'search' => $filters['search'],
            'status' => $filters['status'],
        ]);
    }

    public function create()
    {
        return view('superadmin.admins.create');
    }

    public function store(StoreAdminAccountRequest $request)
    {
        $this->adminService->create(
            data: $request->validatedData(),
            superadmin: $request->user()
        );

        return redirect()
            ->route('superadmin.admins.index')
            ->with('success', 'Akun admin berhasil dibuat.');
    }

    public function edit(User $admin)
    {
        $this->adminService->ensureAdmin($admin);

        return view('superadmin.admins.edit', compact('admin'));
    }

    public function update(UpdateAdminAccountRequest $request, User $admin)
    {
        $this->adminService->update(
            admin: $admin,
            data: $request->validatedData(),
            superadmin: $request->user()
        );

        return redirect()
            ->route('superadmin.admins.index')
            ->with('success', 'Akun admin berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, User $admin)
    {
        $admin = $this->adminService->toggleStatus(
            admin: $admin,
            superadmin: $request->user()
        );

        return back()->with(
            'success',
            $admin->is_active
                ? 'Admin berhasil diaktifkan.'
                : 'Admin berhasil dinonaktifkan.'
        );
    }
}