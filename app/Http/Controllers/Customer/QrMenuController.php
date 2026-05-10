<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerMenuService;
use App\Services\Customer\OrderingAvailabilityService;
use App\Services\Customer\QrTableService;

class QrMenuController extends Controller
{
    public function __construct(
        private readonly QrTableService $qrTableService,
        private readonly CustomerMenuService $customerMenuService,
        private readonly OrderingAvailabilityService $orderingAvailabilityService
    ) {
    }

    public function menu(string $qrToken)
    {
        $table = $this->qrTableService->findActiveByToken($qrToken);

        $categories = $this->customerMenuService->getActiveCategoriesWithAvailableMenus();
        $menusForJs = $this->customerMenuService->buildMenusForJs($categories);
        $orderingOpen = $this->orderingAvailabilityService->isOpen();

        return view('customer.menu', compact(
            'table',
            'categories',
            'menusForJs',
            'orderingOpen'
        ));
    }
}