<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cashier\AddCashierCartItemRequest;
use App\Http\Requests\Cashier\PrepareCashierPosCheckoutRequest;
use App\Http\Requests\Cashier\StoreCashierPosOrderRequest;
use App\Http\Requests\Cashier\UpdateCashierCartRequest;
use App\Models\CashierShift;
use App\Services\Cashier\CashierCartService;
use App\Services\Cashier\CashierPosCatalogService;
use App\Services\Cashier\CashierPosCheckoutService;
use App\Services\Cashier\CashierPosOrderService;
use App\Services\OrderPricingService;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function __construct(
        private readonly CashierPosCatalogService $catalogService,
        private readonly CashierCartService $cartService,
        private readonly CashierPosCheckoutService $checkoutService,
        private readonly CashierPosOrderService $orderService
    ) {
    }

    public function index(Request $request)
    {
        $activeShift = $this->activeShift($request);

        $categories = $this->catalogService->getActiveCategoriesWithMenus();
        $menusForJs = $this->catalogService->buildMenusForJs($categories);
        $initialCart = $this->cartService->initialCartForView();

        return view('cashier.pos.index', compact(
            'activeShift',
            'categories',
            'menusForJs',
            'initialCart'
        ));
    }

    public function addToCart(AddCashierCartItemRequest $request)
    {
        $validated = $request->validated();

        $this->cartService->add(
            menuId: (int) $validated['menu_id'],
            quantity: (int) $validated['quantity'],
            note: $validated['note'] ?? null
        );

        return redirect()
            ->route('cashier.pos.index')
            ->with('success', 'Menu berhasil ditambahkan ke cart.');
    }

    public function updateCart(UpdateCashierCartRequest $request)
    {
        $this->cartService->updateQuantities($request->validated('quantities'));

        return redirect()
            ->route('cashier.pos.index')
            ->with('success', 'Cart berhasil diperbarui.');
    }

    public function removeFromCart(string $cartKey)
    {
        $this->cartService->remove($cartKey);

        return redirect()
            ->route('cashier.pos.index')
            ->with('success', 'Item berhasil dihapus dari cart.');
    }

    public function clearCart(Request $request)
    {
        $this->cartService->clear();

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Cart berhasil dikosongkan.',
            ]);
        }

        return redirect()
            ->route('cashier.pos.index')
            ->with('success', 'Cart berhasil dikosongkan.');
    }

    public function prepareCheckout(PrepareCashierPosCheckoutRequest $request)
    {
        $cart = $this->cartService->buildFromJson($request->validated('cart_json'));

        if (count($cart) === 0) {
            return redirect()
                ->route('cashier.pos.index')
                ->with('error', 'Cart tidak valid.');
        }

        $this->cartService->put($cart);

        return redirect()->route('cashier.pos.checkout');
    }

    public function checkout(Request $request, OrderPricingService $pricingService)
    {
        $activeShift = $this->activeShift($request);

        $cart = $this->cartService->all();

        if (count($cart) === 0) {
            return redirect()
                ->route('cashier.pos.index')
                ->with('error', 'Cart masih kosong.')
                ->with('clear_pos_cart', true);
        }

        $pricing = $pricingService->calculate($cart);
        $tables = $this->checkoutService->activeTables();
        $orderSubmitToken = $this->checkoutService->generateSubmitToken();

        return view('cashier.pos.checkout', compact(
            'activeShift',
            'cart',
            'pricing',
            'tables',
            'orderSubmitToken'
        ));
    }

    public function store(StoreCashierPosOrderRequest $request)
    {
        $activeShift = $this->activeShift($request);

        if (! $this->checkoutService->isValidSubmitToken($request->validated('order_submit_token'))) {
            return redirect()
                ->route('cashier.incoming-orders.index')
                ->with('error', 'Order sudah diproses atau request tidak valid.');
        }

        $cart = $this->cartService->all();

        if (count($cart) === 0) {
            return redirect()
                ->route('cashier.pos.index')
                ->with('error', 'Cart masih kosong.')
                ->with('clear_pos_cart', true);
        }

        $order = $this->orderService->createOrder(
            activeShift: $activeShift,
            cashier: $request->user(),
            cart: $cart,
            data: $request->validated()
        );

        $this->cartService->clear();
        $this->checkoutService->forgetSubmitToken();

        return redirect()
            ->route('cashier.incoming-orders.index')
            ->with('success', 'Order ' . $order->order_number . ' berhasil dibuat dan masuk ke antrian proses.')
            ->with('clear_pos_cart', true);
    }

    private function activeShift(Request $request): CashierShift
    {
        $activeShift = $request->attributes->get('activeCashierShift');

        if (! $activeShift instanceof CashierShift) {
            abort(403, 'Kamu harus membuka shift sebelum menggunakan POS.');
        }

        return $activeShift;
    }
}