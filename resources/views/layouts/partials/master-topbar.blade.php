@php
    $authUser = auth()->user();

    $initial = strtoupper(mb_substr($authUser?->name ?? 'U', 0, 1));
    $displayName = $authUser?->name ?? 'User';
    $displayEmail = $authUser?->email ?? '-';
@endphp

<header class="sticky top-0 z-30 border-b border-stone-200 bg-stone-50/90 backdrop-blur">
    <div class="flex h-20 items-center justify-between px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3">
            <button type="button"
                class="{{ ($role ?? null) === 'cashier' ? 'inline-flex' : 'inline-flex lg:hidden' }} h-11 w-11 items-center justify-center rounded-2xl border border-stone-200 bg-white text-stone-700 shadow-sm transition hover:bg-stone-100"
                @click.stop="sidebarOpen = true" aria-label="Buka sidebar">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="min-w-0">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                    69 Coffee Shop
                </p>

                <h1 class="truncate text-xl font-black tracking-tight text-stone-950 sm:text-2xl">
                    {{ $pageTitle ?? 'Dashboard' }}
                </h1>
            </div>
        </div>

        <div class="relative" x-data="{ profileOpen: false }">
            <button type="button"
                class="group inline-flex items-center gap-3 rounded-2xl border border-stone-200 bg-white px-2.5 py-2 shadow-sm transition hover:bg-stone-100 sm:px-3"
                @click="profileOpen = !profileOpen" @keydown.escape.window="profileOpen = false" aria-label="Menu akun">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-stone-950 text-sm font-black text-white shadow-sm">
                    {{ $initial }}
                </div>

                <div class="hidden min-w-0 text-left sm:block">
                    <p class="max-w-[150px] truncate text-sm font-black text-stone-900">
                        {{ $displayName }}
                    </p>
                    <p class="text-xs font-bold text-stone-500">
                        {{ $roleLabel ?? 'User' }}
                    </p>
                </div>

                <svg class="hidden h-4 w-4 text-stone-400 transition group-hover:text-stone-700 sm:block"
                    :class="profileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-cloak x-show="profileOpen" x-transition.origin.top.right @click.outside="profileOpen = false"
                class="absolute right-0 mt-3 w-72 overflow-hidden rounded-[1.5rem] border border-stone-200 bg-white shadow-2xl">
                <div class="border-b border-stone-100 bg-stone-50 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-stone-950 text-base font-black text-white">
                            {{ $initial }}
                        </div>

                        <div class="min-w-0">
                            <p class="truncate text-sm font-black text-stone-950">
                                {{ $displayName }}
                            </p>

                            <p class="truncate text-xs font-semibold text-stone-500">
                                {{ $displayEmail }}
                            </p>

                            <span
                                class="mt-2 inline-flex rounded-full bg-amber-100 px-3 py-1 text-[11px] font-black uppercase tracking-wide text-amber-700">
                                {{ $roleLabel ?? 'User' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-2">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-stone-700 transition hover:bg-stone-100">
                        <svg class="h-5 w-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profil Saya
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                            class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-left text-sm font-bold text-rose-600 transition hover:bg-rose-50">
                            <svg class="h-5 w-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H9m4 8H5a2 2 0 01-2-2V6a2 2 0 012-2h8" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
