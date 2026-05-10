@props([
    'role' => null,
    'panel' => null,
    'href' => null,
])

@php
    $user = auth()->user();

    $resolvedRole =
        $role ??
        match (true) {
            $user?->hasRole('superadmin') => 'superadmin',
            $user?->hasRole('admin') => 'admin',
            $user?->hasRole('cashier') => 'cashier',
            default => 'guest',
        };

    $config = match ($resolvedRole) {
        'superadmin' => [
            'panel' => 'Superadmin Panel',
            'route' => 'superadmin.dashboard',
        ],
        'admin' => [
            'panel' => 'Admin Panel',
            'route' => 'admin.dashboard',
        ],
        'cashier' => [
            'panel' => 'Kasir Panel',
            'route' => 'cashier.dashboard',
        ],
        default => [
            'panel' => 'User Panel',
            'route' => 'dashboard',
        ],
    };

    $routeName = $config['route'];

    $brandHref = $href ?? (\Illuminate\Support\Facades\Route::has($routeName) ? route($routeName) : '#');

    $panelLabel = $panel ?? $config['panel'];
@endphp

<a href="{{ $brandHref }}" aria-label="69 Coffee Shop - {{ $panelLabel }}"
    {{ $attributes->class([
        'group flex items-center gap-3 border-b border-white/10 px-5 py-5 transition hover:bg-white/[0.035]',
    ]) }}>

    <div
        class="relative flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-amber-400/25 bg-[#15110f] shadow-lg shadow-amber-600/15">

        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_32%_24%,rgba(255,226,161,0.24),transparent_35%),linear-gradient(135deg,rgba(245,166,35,0.20),transparent_55%)]">
        </div>

        <div class="absolute inset-[1px] rounded-[15px] border border-white/5"></div>

        <span
            class="relative -mt-0.5 bg-gradient-to-br from-[#FFE2A1] via-[#F5A623] to-[#B86B09] bg-clip-text text-[28px] font-black leading-none tracking-[-0.06em] text-transparent drop-shadow-[0_6px_14px_rgba(245,166,35,0.35)]"
            style="font-family: ui-serif, Georgia, Cambria, 'Times New Roman', serif;">
            69
        </span>

        <span
            class="absolute bottom-2.5 h-[2px] w-7 rounded-full bg-gradient-to-r from-transparent via-amber-300/70 to-transparent">
        </span>
    </div>

    <div class="min-w-0 flex-1">
        <div class="truncate text-[13px] font-black uppercase leading-tight tracking-[0.12em] text-amber-400">
            69 Coffee Shop
        </div>

        <div class="mt-1 truncate text-xs font-semibold leading-tight text-stone-400">
            {{ $panelLabel }}
        </div>
    </div>
</a>
