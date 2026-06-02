<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SavePromotionRequest;
use App\Models\Promotion;
use App\Services\Admin\AdminPromotionQueryService;
use App\Services\Admin\AdminPromotionService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function __construct(
        private readonly AdminPromotionQueryService $promotionQueryService,
        private readonly AdminPromotionService $promotionService
    ) {
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search')),
            'scope' => $request->query('scope'),
            'status' => $request->query('status'),
        ];

        $promotions = $this->promotionQueryService->paginated($filters);

        return view('admin.promotions.index', [
            'promotions' => $promotions,
            'search' => $filters['search'],
            'scope' => $filters['scope'],
            'status' => $filters['status'],
        ]);
    }


    public function create()
    {
        $menus = $this->promotionQueryService->menuOptions();

        return view('admin.promotions.create', compact('menus'));
    }

    public function store(SavePromotionRequest $request)
    {
        $this->promotionService->create(
            data: $request->validatedData(),
            admin: $request->user()
        );

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Promo berhasil ditambahkan.');
    }

    public function show(Promotion $promotion){
        return view('admin.promotions.show',compact('promotion'));
    }

    public function edit(Promotion $promotion)
    {
        $promotion->load('menus');

        $menus = $this->promotionQueryService->menuOptions();

        return view('admin.promotions.edit', compact('promotion', 'menus'));
    }

    public function update(SavePromotionRequest $request, Promotion $promotion)
    {
        $this->promotionService->update(
            promotion: $promotion,
            data: $request->validatedData(),
            admin: $request->user()
        );

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, Promotion $promotion)
    {
        $promotion = $this->promotionService->toggleStatus(
            promotion: $promotion,
            admin: $request->user()
        );

        return back()->with(
            'success',
            $promotion->is_active
                ? 'Promo berhasil diaktifkan.'
                : 'Promo berhasil dinonaktifkan.'
        );
    }
    public function destroy(Promotion $promotion)
    {
    $promotion->delete();

    return redirect()
        ->route('admin.promotions.index')
        ->with('success', 'Promo berhasil dihapus.');
    }
}