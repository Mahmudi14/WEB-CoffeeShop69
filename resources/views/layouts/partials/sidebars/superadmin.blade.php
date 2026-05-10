@php
    $safeRoute = function (string $name, string $fallback = '#') {
        return \Illuminate\Support\Facades\Route::has($name) ? route($name) : $fallback;
    };

    $navItems = [
        [
            'label' => 'Dashboard',
            'href' => $safeRoute('superadmin.dashboard'),
            'active' => request()->routeIs('superadmin.dashboard'),
            'icon' => 'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z',
        ],
        [
            'label' => 'Kelola Admin',
            'href' => $safeRoute('superadmin.admins.index'),
            'active' => request()->routeIs('superadmin.admins.*'),
            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        ],
        [
            'label' => 'Kelola Kasir',
            'href' => $safeRoute('superadmin.cashiers.index'),
            'active' => request()->routeIs('superadmin.cashiers.*'),
            'icon' =>
                'M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm6 0a3 3 0 100-6 3 3 0 000 6z',
        ],
        [
            'label' => 'Audit Aktivitas',
            'href' => $safeRoute('superadmin.audit-logs.index'),
            'active' => request()->routeIs('superadmin.audit-logs.*'),
            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z',
        ],
    ];
@endphp

<aside
    class="fixed inset-y-0 left-0 z-50 w-72 border-r border-stone-800 bg-stone-950 transition-transform duration-300 lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="flex h-full flex-col">
        <x-sidebar-brand role="superadmin" />

        <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-5">
            @foreach ($navItems as $item)
                <a href="{{ $item['href'] }}"
                    class="{{ $item['active']
                        ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/20'
                        : 'text-stone-300 hover:bg-white/10 hover:text-white' }} group flex min-h-[52px] items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition">
                    <svg class="h-5 w-5 flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $item['icon'] }}" />
                    </svg>

                    <span>{{ $item['label'] }}</span>
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
                    class="mt-3 inline-flex rounded-full bg-amber-500/10 px-3 py-1 text-xs font-black uppercase tracking-wide text-amber-300">
                    Superadmin
                </div>
            </div>
        </div>
    </div>
</aside>
