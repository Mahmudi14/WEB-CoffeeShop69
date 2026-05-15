@extends('layouts.master')

@section('title', 'Admin')
@section('header-title', 'Kategori Menu')

@section('content')
    <div x-data="categoryIndexPage()" class="space-y-6">
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
                            Menu Categories
                        </span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Kategori Menu
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-stone-300">
                        Atur kategori menu yang akan digunakan di POS kasir dan katalog customer.
                    </p>
                </div>

                <a href="{{ route('admin.categories.create') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                    Tambah Kategori
                </a>
            </div>
        </section>

        <section class="rounded-3xl border border-stone-200 bg-white p-4 shadow-sm lg:p-5">
            <form method="GET" action="{{ route('admin.categories.index') }}"
                class="grid gap-4 lg:grid-cols-[minmax(260px,1fr)_160px_max-content] lg:items-end xl:grid-cols-[minmax(360px,1fr)_180px_max-content]">

                {{-- Search --}}
                <div class="min-w-0">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Cari Kategori
                    </label>

                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Nama atau deskripsi kategori"
                        class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                </div>

                {{-- Status --}}
                <div class="min-w-0" x-data="filterDropdown(@js((string) $status))" @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Status
                    </label>

                    <input type="hidden" name="status" x-model="selectedValue">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-2 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                <span x-show="selectedValue === ''">
                                    Semua
                                </span>

                                <span x-show="selectedValue === 'active'" x-cloak>
                                    Aktif
                                </span>

                                <span x-show="selectedValue === 'inactive'" x-cloak>
                                    Nonaktif
                                </span>
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

                                <svg x-show="selectedValue === ''" x-cloak class="h-4 w-4 shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>

                            <button type="button" @click="select('active')"
                                class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-emerald-50 hover:text-emerald-700"
                                :class="selectedValue === 'active' ? 'bg-emerald-100 text-emerald-800' : 'text-stone-700'">
                                <span>Aktif</span>

                                <svg x-show="selectedValue === 'active'" x-cloak class="h-4 w-4 shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>

                            <button type="button" @click="select('inactive')"
                                class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-rose-50 hover:text-rose-700"
                                :class="selectedValue === 'inactive' ? 'bg-rose-100 text-rose-800' : 'text-stone-700'">
                                <span>Nonaktif</span>

                                <svg x-show="selectedValue === 'inactive'" x-cloak class="h-4 w-4 shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 lg:justify-end">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center whitespace-nowrap rounded-2xl bg-stone-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-stone-800 active:scale-[0.98] lg:flex-none">
                        Filter
                    </button>

                    <a href="{{ route('admin.categories.index') }}"
                        class="inline-flex flex-1 items-center justify-center whitespace-nowrap rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98] lg:flex-none">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-200 px-6 py-5">
                <h3 class="text-lg font-black text-stone-950">
                    Daftar Kategori
                </h3>

                <p class="mt-1 text-sm text-stone-500">
                    Total kategori: {{ $categories->total() }}
                </p>
            </div>

            @if ($categories->isEmpty())
                <div class="p-10 text-center">
                    <p class="text-sm font-black text-stone-700">
                        Belum ada kategori.
                    </p>

                    <p class="mt-2 text-xs font-medium text-stone-500">
                        Tambahkan kategori sebelum membuat data menu.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] table-fixed divide-y divide-stone-200">
                        <thead class="bg-stone-100">
                            <tr>
                                <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Kategori
                                </th>

                                <th
                                    class="w-28 px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-stone-500">
                                    Urutan
                                </th>

                                <th
                                    class="w-32 px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-stone-500">
                                    Jumlah Menu
                                </th>

                                <th
                                    class="w-32 px-4 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Status
                                </th>

                                <th
                                    class="w-40 px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-stone-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100">
                            @foreach ($categories as $category)
                                <tr class="transition hover:bg-stone-50">
                                    <td class="px-4 py-4">
                                        <p class="truncate text-sm font-black text-stone-950">
                                            {{ $category->name }}
                                        </p>

                                        <p class="mt-1 line-clamp-1 text-xs font-semibold text-stone-500">
                                            {{ $category->description ?: 'Tanpa deskripsi' }}
                                        </p>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4 text-center text-sm font-black text-stone-800">
                                        {{ $category->sort_order }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4 text-center text-sm font-black text-stone-800">
                                        {{ $category->menus_count }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4">
                                        @if ($category->is_active)
                                            <span
                                                class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-black text-rose-700">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.categories.edit', $category) }}"
                                                class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-4 py-2 text-xs font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98]">
                                                Edit
                                            </a>

                                            <a href="{{ route('admin.categories.show', $category) }}"
                                                class="inline-flex items-center justify-center rounded-2xl bg-stone-950 px-4 py-2 text-xs font-black text-white shadow-sm transition hover:bg-stone-800 active:scale-[0.98]">
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-stone-200 px-6 py-4">
                    {{ $categories->links() }}
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
