@php
    $safeRoute = function (string $name, string $fallback = '#') {
        return \Illuminate\Support\Facades\Route::has($name) ? route($name) : $fallback;
    };

    $navItems = [
        [
            'label' => 'Dashboard',
            'href' => $safeRoute('cashier.dashboard'),
            'active' => request()->routeIs('cashier.dashboard'),
            'icon' => 'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z',
        ],
        [
            'label' => 'POS',
            'href' => $safeRoute('cashier.pos.index'),
            'active' => request()->routeIs('cashier.pos.*'),
            'icon' => 'M3 6h18M7 6V4h10v2m-9 4h8m-9 4h10m-11 4h12',
        ],
        [
            'label' => 'Order Masuk',
            'href' => $safeRoute('cashier.incoming-orders.index'),
            'active' => request()->routeIs('cashier.incoming-orders.*'),
            'icon' => 'M4 4h16v12H5.5L4 18V4zm4 5h8m-8 4h5',
        ],
        [
            'label' => 'Riwayat Order',
            'href' => $safeRoute('cashier.orders.index'),
            'active' => request()->routeIs('cashier.orders.*'),
            'icon' => 'M6 2h12l1 20H5L6 2zm3 5h6m-6 4h6m-6 4h3',
        ],
        [
            'label' => 'Pengeluaran',
            'href' => $safeRoute('cashier.expenses.index'),
            'active' => request()->routeIs('cashier.expenses.*'),
            'icon' => 'M12 8c-2.5 0-4 1.2-4 3s1.5 3 4 3 4 1.2 4 3-1.5 3-4 3m0-18v18',
        ],
        [
            'label' => 'Ringkasan Shift',
            'href' => $safeRoute('cashier.shift-summary.index'),
            'active' => request()->routeIs('cashier.shift-summary.*'),
            'icon' => 'M3 3v18h18M7 15l3-3 3 2 4-6',
        ],
        [
            'label' => 'Tutup Shift',
            'href' => $safeRoute('cashier.shifts.close'),
            'active' => request()->routeIs('cashier.shifts.close'),
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
    ];
@endphp

<aside
    class="fixed inset-y-0 left-0 z-50 w-72 -translate-x-full border-r border-stone-800 bg-stone-950 transition-transform duration-300 xl:translate-x-0"
    :class="{ 'translate-x-0': sidebarOpen }">
    <div class="flex h-full flex-col">
        <x-sidebar-brand role="cashier" />
        <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-2">
            @foreach ($navItems as $item)
                <a href="{{ $item['href'] }}"
                    class="{{ $item['active']
                        ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/20'
                        : 'text-stone-300 hover:bg-white/10 hover:text-white' }} group flex min-h-[58px] items-center gap-3 rounded-2xl px-4 py-4 text-sm font-bold transition active:scale-[0.98]">
                    <svg class="h-5 w-5 flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $item['icon'] }}" />
                    </svg>
                    <span class="flex min-w-0 flex-1 items-center justify-between gap-3">
                        <span class="truncate">{{ $item['label'] }}</span>

                        @if (($item['label'] ?? '') === 'Order Masuk')
                            <span data-cashier-incoming-order-badge
                                class="{{ ($cashierIncomingOrderCount ?? 0) > 0 ? 'inline-flex' : 'hidden' }} min-w-[22px] items-center justify-center rounded-full bg-rose-500 px-2 py-0.5 text-[11px] font-black text-white">
                                <span data-cashier-incoming-order-count>
                                    {{ $cashierIncomingOrderCount ?? 0 }}
                                </span>
                            </span>
                        @endif
                    </span>
                </a>
            @endforeach
        </nav>

        <div class="border-t border-white/10 p-4">
            <div class="rounded-2xl bg-white/5 p-4">
                <p class="text-sm font-bold text-white">
                    {{ auth()->user()->name }}
                </p>

                <p class="mt-1 truncate text-xs text-stone-400">
                    {{ auth()->user()->email }}
                </p>

                <div
                    class="mt-3 inline-flex rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-black uppercase tracking-wide text-emerald-300">
                    Kasir
                </div>
            </div>
        </div>
    </div>
</aside>
