@php
    $routeExists = function (string $name): bool {
        return \Illuminate\Support\Facades\Route::has($name);
    };

    $makeNav = function (string $label, string $route, string $activePattern, string $icon) use ($routeExists) {
        $exists = $routeExists($route);

        return [
            'label' => $label,
            'route' => $route,
            'href' => $exists ? route($route) : '#',
            'active' => $exists && request()->routeIs($activePattern),
            'enabled' => $exists,
            'icon' => $icon,
        ];
    };

    $navItems = [
        $makeNav(
            'Dashboard',
            'admin.dashboard',
            'admin.dashboard',
            'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z',
        ),

        $makeNav(
            'Kasir',
            'admin.cashiers.index',
            'admin.cashiers.*',
            'M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm6 0a3 3 0 100-6 3 3 0 000 6z',
        ),

        $makeNav('Menu', 'admin.menus.index', 'admin.menus.*', 'M4 6h16M4 12h16M4 18h16'),

        $makeNav(
            'Kategori',
            'admin.categories.index',
            'admin.categories.*',
            'M4 5a2 2 0 012-2h3l2 2h7a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V5z',
        ),

        $makeNav('Meja & QR', 'admin.tables.index', 'admin.tables.*', 'M4 6h16v12H4V6zm4 0v12m8-12v12'),

        $makeNav(
            'Promo',
            'admin.promotions.index',
            'admin.promotions.*',
            'M9 14l6-6m-5.5.5h.01m5.99 5.99h.01M19 21l-7-7-7 7V5a2 2 0 012-2h10a2 2 0 012 2v16z',
        ),

        $makeNav(
            'Pembayaran',
            'admin.payment-channels.index',
            'admin.payment-channels.*',
            'M3 10h18M7 15h.01M11 15h2m-8 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        ),

        $makeNav(
            'PPN',
            'admin.taxes.index',
            'admin.taxes.*',
            'M9 7h6m-6 4h6m-6 4h3m-7 6h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z',
        ),

        $makeNav('Order', 'admin.orders.index', 'admin.orders.*', 'M6 2h12l1 20H5L6 2zm3 5h6m-6 4h6m-6 4h3'),

        $makeNav('Laporan', 'admin.reports.index', 'admin.reports.*', 'M11 3v18M6 8v13M16 13v8M21 5v16'),
    ];
@endphp


<aside class="fixed inset-y-0 left-0 z-50 w-72 border-r border-stone-200 bg-white transition-transform duration-300"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="flex h-full flex-col">
        <x-sidebar-brand role="admin" />

        <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-5">
            @foreach ($navItems as $item)
                <a href="{{ $item['href'] }}"
                    @if (!$item['enabled']) aria-disabled="true" onclick="return false;" @endif
                    class="{{ $item['active']
                        ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/20'
                        : ($item['enabled']
                            ? 'text-stone-600 hover:bg-stone-100 hover:text-stone-950'
                            : 'cursor-not-allowed text-stone-300') }}
                        group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition">

                    <svg class="h-5 w-5 flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $item['icon'] }}" />
                    </svg>

                    <span class="flex-1 truncate">
                        {{ $item['label'] }}
                    </span>

                    @if (!$item['enabled'])
                        <span
                            class="rounded-full bg-stone-100 px-2 py-0.5 text-[10px] font-black uppercase tracking-wide text-stone-400">
                            Soon
                        </span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="border-t border-stone-200 p-4">
            <div class="rounded-2xl bg-stone-100 p-4">
                <p class="truncate text-sm font-bold text-stone-950">
                    {{ auth()->user()->name }}
                </p>

                <p class="mt-1 truncate text-xs text-stone-500">
                    {{ auth()->user()->email }}
                </p>

                <div
                    class="mt-3 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-amber-700">
                    Admin
                </div>
            </div>
        </div>
    </div>
</aside>
