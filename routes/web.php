<?php

use App\Http\Controllers\Admin\CashierController as AdminCashierController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentChannelController as AdminPaymentChannelController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\TableController as AdminTableController;
use App\Http\Controllers\Admin\TaxController as AdminTaxController;
use App\Http\Controllers\Cashier\DashboardController as CashierDashboardController;
use App\Http\Controllers\Cashier\ExpenseController as CashierExpenseController;
use App\Http\Controllers\Cashier\IncomingOrderController as CashierIncomingOrderController;
use App\Http\Controllers\Cashier\OrderController as CashierOrderController;
use App\Http\Controllers\Cashier\PosController as CashierPosController;
use App\Http\Controllers\Cashier\ShiftController as CashierShiftController;
use App\Http\Controllers\Cashier\ShiftSummaryController as CashierShiftSummaryController;
use App\Http\Controllers\Customer\OrderTrackingController;
use App\Http\Controllers\Customer\QrCheckoutController;
use App\Http\Controllers\Customer\QrMenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Superadmin\AdminController as SuperadminAdminController;
use App\Http\Controllers\Superadmin\AuditLogController as SuperadminAuditLogController;
use App\Http\Controllers\Superadmin\CashierController as SuperadminCashierController;
use App\Http\Controllers\Superadmin\DashboardController as SuperadminDashboardController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureCashierShiftIsOpen;

Route::get('/', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    /** @var User $user */
    $user = Auth::user();

    if ($user->hasRole('superadmin')) {
        return redirect()->route('superadmin.dashboard');
    }

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('cashier')) {
        return redirect()->route('cashier.dashboard');
    }

    Auth::logout();

    return redirect()
        ->route('login')
        ->with('error', 'Akun belum memiliki role yang valid.');
})->middleware('no-cache');


/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::controller(ProfileController::class)
        ->prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
        });
});


/*
|--------------------------------------------------------------------------
| Superadmin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [SuperadminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('admins', SuperadminAdminController::class)->except(['show', 'destroy']);
        Route::patch('/admins/{admin}/toggle-status', [SuperadminAdminController::class, 'toggleStatus'])->name('admins.toggle-status');
        Route::resource('cashiers', SuperadminCashierController::class)->except(['show', 'destroy']);
        Route::patch('/cashiers/{cashier}/toggle-status', [SuperadminCashierController::class, 'toggleStatus'])->name('cashiers.toggle-status');
        Route::get('/audit-logs', [SuperadminAuditLogController::class, 'index'])->name('audit-logs.index');
    });


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin', 'no-cache'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('cashiers', AdminCashierController::class)->except(['show', 'destroy']);
        Route::patch('/cashiers/{cashier}/toggle-status', [AdminCashierController::class, 'toggleStatus'])->name('cashiers.toggle-status');
        Route::resource('categories', AdminCategoryController::class)->except(['show', 'destroy']);
        Route::patch('/categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        Route::resource('menus', AdminMenuController::class)->except(['show', 'destroy']);
        Route::patch('/menus/{menu}/toggle-status', [AdminMenuController::class, 'toggleStatus'])->name('menus.toggle-status');
        Route::patch('/menus/{menu}/toggle-availability', [AdminMenuController::class, 'toggleAvailability'])->name('menus.toggle-availability');
        Route::resource('tables', AdminTableController::class)->except(['show', 'destroy']);
        Route::patch('/tables/{table}/toggle-status', [AdminTableController::class, 'toggleStatus'])->name('tables.toggle-status');
        Route::patch('/tables/{table}/regenerate-qr-token', [AdminTableController::class, 'regenerateQrToken'])->name('tables.regenerate-qr-token');
        Route::resource('promotions', AdminPromotionController::class)->except(['show', 'destroy']);
        Route::patch('/promotions/{promotion}/toggle-status', [AdminPromotionController::class, 'toggleStatus'])->name('promotions.toggle-status');
        Route::resource('taxes', AdminTaxController::class)->except(['show', 'destroy']);
        Route::patch('/taxes/{tax}/toggle-status', [AdminTaxController::class, 'toggleStatus'])->name('taxes.toggle-status');
        Route::controller(AdminOrderController::class)->prefix('orders')->name('orders.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{order}', 'show')->name('show');
                Route::patch('/{order}/cancel', 'cancel')->name('cancel');
            });
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::resource('payment-channels', AdminPaymentChannelController::class)->except(['show']);
    });


/*
|--------------------------------------------------------------------------
| Cashier Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:cashier', 'no-cache'])
    ->prefix('cashier')
    ->name('cashier.')
    ->group(function () {
        Route::get('/dashboard', [CashierDashboardController::class, 'index'])
            ->name('dashboard');

        Route::controller(CashierShiftController::class)
            ->prefix('shifts')
            ->name('shifts.')
            ->group(function () {
                Route::get('/start', 'start')->name('start');
                Route::post('/start', 'store')->name('store');
                
                Route::middleware(EnsureCashierShiftIsOpen::class)->group(function () {
                    Route::get('/close', 'close')->name('close');
                    Route::post('/close', 'closeStore')->name('close.store');
        });
            });

        Route::middleware(EnsureCashierShiftIsOpen::class)
            ->controller(CashierExpenseController::class)
            ->prefix('expenses')
            ->name('expenses.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });

        Route::middleware(EnsureCashierShiftIsOpen::class)
            ->controller(CashierPosController::class)
            ->prefix('pos')
            ->name('pos.')
            ->group(function () {
                Route::get('/', 'index')->name('index');

                Route::post('/cart', 'addToCart')->name('cart.add');
                Route::patch('/cart', 'updateCart')->name('cart.update');

                Route::delete('/cart/{cartKey}', 'removeFromCart')
                    ->where('cartKey', '[a-f0-9]{32}')
                    ->name('cart.remove');

                Route::delete('/cart', 'clearCart')->name('cart.clear');

                Route::get('/checkout', 'checkout')->name('checkout');
                Route::post('/checkout', 'store')->name('store');
                Route::post('/prepare-checkout', 'prepareCheckout')->name('prepare-checkout');
            });

        Route::middleware(EnsureCashierShiftIsOpen::class)
            ->controller(CashierOrderController::class)
            ->prefix('orders')
            ->name('orders.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{order}', 'show')->name('show');

                Route::post('/{order}/print-customer-receipt', 'printCustomerReceipt')
                    ->name('print-customer-receipt');
            });

        Route::middleware(EnsureCashierShiftIsOpen::class)
            ->controller(CashierIncomingOrderController::class)
            ->prefix('incoming-orders')
            ->name('incoming-orders.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/poll', 'poll')->name('poll');
                Route::get('/{order}/customer-receipt-status', 'customerReceiptStatus')
                    ->name('customer-receipt-status');

                Route::get('/{order}/kitchen-order-status', 'kitchenOrderStatus')
                    ->name('kitchen-order-status');

                Route::post('/{order}/accept-cash', 'acceptCash')->name('accept-cash');
                Route::post('/{order}/accept-proof', 'acceptProof')->name('accept-proof');
                Route::post('/{order}/reject-proof', 'rejectProof')->name('reject-proof');

                Route::post('/{order}/print-customer-receipt', 'printCustomerReceipt')
                    ->name('print-customer-receipt');

                Route::post('/{order}/complete', 'complete')->name('complete');
            });

        Route::middleware(EnsureCashierShiftIsOpen::class)
            ->get('/shift-summary', [CashierShiftSummaryController::class, 'index'])
            ->name('shift-summary.index');
    });


/*
|--------------------------------------------------------------------------
| Customer QR Order Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qr/{qrToken}')
    ->name('customer.qr.')
    ->group(function () {
        Route::get('/menu', [QrMenuController::class, 'menu'])
            ->name('menu');

        Route::controller(QrCheckoutController::class)->group(function () {
            Route::post('/prepare-checkout', 'prepareCheckout')
                ->name('prepare-checkout');

            Route::get('/checkout', 'checkout')
                ->name('checkout');

            Route::post('/checkout', 'store')
                ->name('store');

            Route::get('/success/{order}', 'success')
                ->name('success');
        });
    });


/*
|--------------------------------------------------------------------------
| Customer Order Tracking Routes
|--------------------------------------------------------------------------
*/

Route::controller(OrderTrackingController::class)
    ->prefix('order-status')
    ->name('customer.orders.status')
    ->group(function () {
        Route::get('/{order:order_number}', 'status');
        Route::get('/{order:order_number}/data', 'statusData')->name('.data');
    });

Route::controller(OrderTrackingController::class)
    ->prefix('track-order')
    ->name('customer.orders.track')
    ->group(function () {
        Route::get('/', 'track');
        Route::post('/', 'findTracking')->name('.find');
    });


require __DIR__ . '/auth.php';