<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMenuRequest;
use App\Http\Requests\Admin\UpdateMenuRequest;
use App\Models\Menu;
use App\Services\Admin\AdminMenuQueryService;
use App\Services\Admin\AdminMenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(
        private readonly AdminMenuQueryService $menuQueryService,
        private readonly AdminMenuService $menuService
    ) {
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search')),
            'category_id' => $request->query('category_id'),
            'status' => $request->query('status'),
            'availability' => $request->query('availability'),
        ];

        $menus = $this->menuQueryService->paginated($filters);
        $categories = $this->menuQueryService->activeCategories();

        return view('admin.menus.index', [
            'menus' => $menus,
            'categories' => $categories,
            'search' => $filters['search'],
            'categoryId' => $filters['category_id'],
            'status' => $filters['status'],
            'availability' => $filters['availability'],
        ]);
    }

    public function create()
    {
        $categories = $this->menuQueryService->activeCategories();

        return view('admin.menus.create', compact('categories'));
    }

    public function show (Menu $menu){
        return view('admin.menus.show', compact('menu'));
    }

    public function store(StoreMenuRequest $request)
    {
        $this->menuService->create(
            data: $request->validated(),
            admin: $request->user(),
            image: $request->file('image')
        );

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $categories = $this->menuQueryService->activeCategories();

        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $this->menuService->update(
            menu: $menu,
            data: $request->validated(),
            admin: $request->user(),
            image: $request->file('image')
        );

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, Menu $menu)
    {
        $menu = $this->menuService->toggleStatus(
            menu: $menu,
            admin: $request->user()
        );

        return back()->with(
            'success',
            $menu->is_active
                ? 'Menu berhasil diaktifkan.'
                : 'Menu berhasil dinonaktifkan.'
        );
    }

    public function toggleAvailability(Request $request, Menu $menu)
    {
        $menu = $this->menuService->toggleAvailability(
            menu: $menu,
            admin: $request->user()
        );

        return back()->with(
            'success',
            $menu->is_available
                ? 'Menu ditandai tersedia.'
                : 'Menu ditandai habis/tidak tersedia.'
        );
    }
}