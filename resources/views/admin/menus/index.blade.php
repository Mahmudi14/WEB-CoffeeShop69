@extends('layouts.master')

@section('title', 'Admin')
@section('header-title', 'Menu')

@section('content')
    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-[2rem] bg-[#171412] p-6 shadow-xl border border-white/10">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)] pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-3 py-1.5 mb-4">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Menu Management
                        </span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Data Menu
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-stone-300">
                        Kelola menu yang tampil di POS kasir dan katalog customer.
                    </p>
                </div>

                <a href="{{ route('admin.menus.create') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                    Tambah Menu
                </a>
            </div>
        </section>

        <section class="rounded-3xl border border-stone-200 bg-white p-4 shadow-sm lg:p-5">
            <form method="GET" action="{{ route('admin.menus.index') }}"
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-6 lg:items-end xl:grid-cols-[minmax(320px,1fr)_190px_150px_170px_max-content]">

                {{-- Search --}}
                <div class="min-w-0 sm:col-span-2 lg:col-span-3 xl:col-span-1">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Cari Menu
                    </label>

                    <input type="text" name="search" value="{{ $search }}" placeholder="Nama atau deskripsi menu"
                        class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                </div>

                {{-- Kategori --}}
                <div class="min-w-0 sm:col-span-1 lg:col-span-3 xl:col-span-1" x-data="filterDropdown(@js((string) $categoryId))"
                    @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Kategori
                    </label>

                    <input type="hidden" name="category_id" x-model="selectedValue">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                <span x-show="selectedValue === ''">Semua</span>

                                @foreach ($categories as $category)
                                    <span x-show="selectedValue === @js((string) $category->id)" x-cloak>
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </span>

                            <svg class="h-4 w-4 shrink-0 text-stone-400 transition"
                                :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" x-cloak x-transition.origin.top @click.outside="close()"
                            class="absolute left-0 right-0 top-full z-50 mt-2 max-h-64 overflow-y-auto rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                            <button type="button" @click="select('')"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                :class="selectedValue === '' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                                <span>Semua</span>

                                <svg x-show="selectedValue === ''" x-cloak class="h-4 w-4 shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>

                            @foreach ($categories as $category)
                                <button type="button" @click="select(@js((string) $category->id))"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                    :class="selectedValue === @js((string) $category->id) ? 'bg-amber-100 text-amber-800' :
                                        'text-stone-700'">
                                    <span class="truncate">
                                        {{ $category->name }}
                                    </span>

                                    <svg x-show="selectedValue === @js((string) $category->id)" x-cloak
                                        class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="min-w-0 sm:col-span-1 lg:col-span-2 xl:col-span-1" x-data="filterDropdown(@js((string) $status))"
                    @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Status
                    </label>

                    <input type="hidden" name="status" x-model="selectedValue">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                <span x-show="selectedValue === ''">Semua</span>
                                <span x-show="selectedValue === 'active'" x-cloak>Aktif</span>
                                <span x-show="selectedValue === 'inactive'" x-cloak>Nonaktif</span>
                            </span>

                            <svg class="h-4 w-4 shrink-0 text-stone-400 transition"
                                :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" x-cloak x-transition.origin.top @click.outside="close()"
                            class="absolute left-0 right-0 top-full z-50 mt-2 overflow-hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                            <button type="button" @click="select('')"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                :class="selectedValue === '' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                                <span>Semua</span>
                            </button>

                            <button type="button" @click="select('active')"
                                class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-emerald-50 hover:text-emerald-700"
                                :class="selectedValue === 'active' ? 'bg-emerald-100 text-emerald-800' : 'text-stone-700'">
                                <span>Aktif</span>
                            </button>

                            <button type="button" @click="select('inactive')"
                                class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-rose-50 hover:text-rose-700"
                                :class="selectedValue === 'inactive' ? 'bg-rose-100 text-rose-800' : 'text-stone-700'">
                                <span>Nonaktif</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Ketersediaan --}}
                <div class="min-w-0 sm:col-span-1 lg:col-span-2 xl:col-span-1" x-data="filterDropdown(@js((string) $availability))"
                    @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Ketersediaan
                    </label>

                    <input type="hidden" name="availability" x-model="selectedValue">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                <span x-show="selectedValue === ''">Semua</span>
                                <span x-show="selectedValue === 'available'" x-cloak>Tersedia</span>
                                <span x-show="selectedValue === 'unavailable'" x-cloak>Habis</span>
                            </span>

                            <svg class="h-4 w-4 shrink-0 text-stone-400 transition"
                                :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" x-cloak x-transition.origin.top @click.outside="close()"
                            class="absolute left-0 right-0 top-full z-50 mt-2 overflow-hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                            <button type="button" @click="select('')"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                :class="selectedValue === '' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                                <span>Semua</span>
                            </button>

                            <button type="button" @click="select('available')"
                                class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-sky-50 hover:text-sky-700"
                                :class="selectedValue === 'available' ? 'bg-sky-100 text-sky-800' : 'text-stone-700'">
                                <span>Tersedia</span>
                            </button>

                            <button type="button" @click="select('unavailable')"
                                class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-orange-50 hover:text-orange-700"
                                :class="selectedValue === 'unavailable' ? 'bg-orange-100 text-orange-800' : 'text-stone-700'">
                                <span>Habis</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 sm:col-span-2 lg:col-span-2 lg:justify-end xl:col-span-1">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center whitespace-nowrap rounded-2xl bg-stone-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-stone-800 active:scale-[0.98] xl:flex-none">
                        Filter
                    </button>

                    <a href="{{ route('admin.menus.index') }}"
                        class="inline-flex flex-1 items-center justify-center whitespace-nowrap rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98] xl:flex-none">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-200 px-6 py-5">
                <h3 class="text-lg font-black text-stone-950">
                    Daftar Menu
                </h3>

                <p class="mt-1 text-sm text-stone-500">
                    Total menu: {{ $menus->total() }}
                </p>
            </div>

            @if ($menus->isEmpty())
                <div class="p-10 text-center">
                    <p class="text-sm font-black text-stone-700">
                        Belum ada menu.
                    </p>
                    <p class="mt-2 text-xs font-medium text-stone-500">
                        Tambahkan menu agar bisa digunakan di POS dan katalog customer.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto rounded-3xl border border-stone-200 bg-white shadow-sm">
                    <table class="w-full table-fixed divide-y divide-stone-200">
                        <thead class="bg-stone-100">
                            <tr>
                                <th
                                    class="w-14 px-3 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    No
                                </th>

                                <th class="px-3 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Nama Menu
                                </th>

                                <th
                                    class="w-32 px-3 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500 lg:w-26">
                                    Kategori
                                </th>

                                <th
                                    class="w-32 px-3 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500 lg:w-26">
                                    Harga
                                </th>

                                <th
                                    class="w-36 px-3 py-4 text-center text-xs font-black uppercase tracking-wider text-stone-500 lg:w-40">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100">
                            @forelse ($menus as $menu)
                                <tr class="transition hover:bg-stone-50">
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-stone-600">
                                        @if (method_exists($menus, 'firstItem'))
                                            {{ $menus->firstItem() + $loop->index }}
                                        @else
                                            {{ $loop->iteration }}
                                        @endif
                                    </td>

                                    <td class="px-3 py-4">
                                        <div class="flex min-w-0 items-center gap-3">
                                            <div
                                                class="h-12 w-12 shrink-0 overflow-hidden rounded-2xl bg-stone-100 lg:h-14 lg:w-14">
                                                @if ($menu->image_path)
                                                    <img src="{{ Storage::url($menu->image_path) }}"
                                                        alt="{{ $menu->name }}" class="h-full w-full object-cover">
                                                @else
                                                    <img src="{{ Storage::url('menus/default.png') }}"
                                                        alt="{{ $menu->name }}" class="h-full w-full object-cover">
                                                @endif
                                            </div>

                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-black text-stone-950">
                                                    {{ $menu->name }}
                                                </p>
                                                <div class="mt-2 flex flex-wrap gap-1.5">
                                                    @if ($menu->is_active)
                                                        <span
                                                            class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-black text-emerald-700">
                                                            Aktif
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-black text-rose-700">
                                                            Nonaktif
                                                        </span>
                                                    @endif

                                                    @if ($menu->is_available)
                                                        <span
                                                            class="inline-flex rounded-full bg-sky-100 px-2.5 py-1 text-[11px] font-black text-sky-700">
                                                            Tersedia
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex rounded-full bg-orange-100 px-2.5 py-1 text-[11px] font-black text-orange-700">
                                                            Habis
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4">
                                        <span class="block truncate text-sm font-bold text-stone-700">
                                            {{ $menu->category?->name ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-black text-stone-950">
                                        Rp{{ number_format($menu->normal_price, 0, ',', '.') }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.menus.edit', $menu) }}"
                                                class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-3 py-2 text-xs font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98] lg:px-4">
                                                Edit
                                            </a>

                                            <a href="{{ route('admin.menus.show', $menu) }}"
                                                class="inline-flex items-center justify-center rounded-2xl bg-stone-950 px-3 py-2 text-xs font-black text-white shadow-sm transition hover:bg-stone-800 active:scale-[0.98] lg:px-4">
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-sm font-bold text-stone-500">
                                        Belum ada data menu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-stone-200 px-6 py-4">
                    {{ $menus->links() }}
                </div>
            @endif
        </section>
    </div>
    <script>
        function filterDropdown(initialValue) {
            return {
                selectedValue: initialValue || '',
                dropdownOpen: false,

                toggle() {
                    this.dropdownOpen = !this.dropdownOpen;
                },

                close() {
                    this.dropdownOpen = false;
                },

                select(value) {
                    this.selectedValue = value;
                    this.close();
                },
            };
        }
    </script>
@endsection
