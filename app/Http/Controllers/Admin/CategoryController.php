<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\Admin\AdminCategoryQueryService;
use App\Services\Admin\AdminCategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private readonly AdminCategoryQueryService $categoryQueryService,
        private readonly AdminCategoryService $categoryService
    ) {
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search')),
            'status' => $request->query('status'),
        ];

        $categories = $this->categoryQueryService->paginated($filters);

        return view('admin.categories.index', [
            'categories' => $categories,
            'search' => $filters['search'],
            'status' => $filters['status'],
        ]);
    }

    public function show(Category $category){
        return view('admin.categories.show',compact('category'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->create($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->categoryService->update(
            category: $category,
            data: $request->validated()
        );

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function toggleStatus(Category $category)
    {
        $category = $this->categoryService->toggleStatus($category);

        return back()->with(
            'success',
            $category->is_active
                ? 'Kategori berhasil diaktifkan.'
                : 'Kategori berhasil dinonaktifkan.'
        );
    }
}