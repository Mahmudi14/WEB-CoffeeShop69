@extends('layouts.master')

@section('title', 'Admin')
@section('header-title', 'PPN / Pajak')

@section('content')
    <div class="space-y-6">

        <section class="relative overflow-hidden rounded-[2rem] bg-[#171412] p-6 shadow-xl border border-white/10">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)] pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                <div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-3 py-1.5 mb-4">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Tax Management
                        </span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Pengaturan PPN / Pajak
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-stone-300">
                        Kelola pajak yang digunakan dalam perhitungan checkout, struk, shift, dan laporan.
                    </p>
                </div>

                <a href="{{ route('admin.taxes.create') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                    Tambah Pajak
                </a>
            </div>
        </section>
        <section class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm lg:col-span-2">
                <p class="text-xs font-black uppercase tracking-wider text-emerald-700">
                    Pajak Aktif Saat Ini
                </p>

                @if ($activeTax)
                    <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h3 class="text-2xl font-black text-emerald-900">
                                {{ $activeTax->name }}
                            </h3>

                            <p class="mt-1 text-sm font-semibold text-emerald-700">
                                Rate: {{ rtrim(rtrim(number_format($activeTax->rate, 2, ',', '.'), '0'), ',') }}%
                            </p>

                            <p class="mt-1 text-sm font-semibold text-emerald-700">
                                {{ $activeTax->price_includes_tax ? 'Harga menu sudah termasuk pajak.' : 'Pajak ditambahkan saat checkout.' }}
                            </p>
                        </div>

                        <a href="{{ route('admin.taxes.edit', $activeTax) }}"
                            class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-black text-white transition hover:bg-emerald-700">
                            Edit Pajak Aktif
                        </a>
                    </div>
                @else
                    <div class="mt-3">
                        <h3 class="text-xl font-black text-emerald-900">
                            Tidak ada pajak aktif
                        </h3>

                        <p class="mt-1 text-sm font-semibold text-emerald-700">
                            Checkout akan berjalan tanpa pajak aktif.
                        </p>
                    </div>
                @endif
            </div>

            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                    Catatan
                </p>

                <p class="mt-3 text-sm leading-6 text-stone-600">
                    Hanya satu pajak yang boleh aktif. Saat kamu mengaktifkan pajak baru, pajak aktif sebelumnya otomatis
                    dinonaktifkan.
                </p>
            </div>
        </section>

        <section class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.taxes.index') }}"
                class="grid gap-4 lg:grid-cols-[minmax(280px,1fr)_220px_96px_96px] lg:items-end">

                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Cari Pajak
                    </label>

                    <input type="text" name="search" value="{{ $search }}" placeholder="Nama pajak"
                        class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                </div>

                <div x-data="filterDropdown(@js((string) $status))" @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Status
                    </label>

                    <input type="hidden" name="status" x-model="selectedValue">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                <span x-show="selectedValue === ''">
                                    Semua Status
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
                            class="absolute left-0 right-0 top-[54px] z-50 overflow-hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">

                            <button type="button" @click="select('')"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                :class="selectedValue === '' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                                <span>Semua Status</span>

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

                <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-2xl bg-stone-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-stone-800 active:scale-[0.98]">
                    Filter
                </button>

                <a href="{{ route('admin.taxes.index') }}"
                    class="inline-flex w-full items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98]">
                    Reset
                </a>
            </form>
        </section>

        <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-200 px-6 py-5">
                <h3 class="text-lg font-black text-stone-950">
                    Daftar Pengaturan Pajak
                </h3>

                <p class="mt-1 text-sm text-stone-500">
                    Total pengaturan: {{ $taxes->total() }}
                </p>
            </div>

            @if ($taxes->isEmpty())
                <div class="p-10 text-center">
                    <p class="text-sm font-black text-stone-700">
                        Belum ada pengaturan pajak.
                    </p>

                    <p class="mt-2 text-xs font-medium text-stone-500">
                        Tambahkan pajak jika sistem membutuhkan perhitungan PPN.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Pajak
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Rate
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Skema Harga
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100">
                            @foreach ($taxes as $tax)
                                <tr class="hover:bg-stone-50">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-black text-stone-950">
                                            {{ $tax->name }}
                                        </p>

                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            Dibuat {{ $tax->created_at->format('d M Y H:i') }}
                                        </p>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-black text-stone-950">
                                        {{ rtrim(rtrim(number_format($tax->rate, 2, ',', '.'), '0'), ',') }}%
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($tax->price_includes_tax)
                                            <span
                                                class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-black text-sky-700">
                                                Harga Include Pajak
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-700">
                                                Pajak Ditambahkan
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        @if ($tax->is_active)
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

                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.taxes.edit', $tax) }}"
                                                class="inline-flex rounded-2xl border border-stone-200 bg-white px-4 py-2 text-xs font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('admin.taxes.toggle-status', $tax) }}">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="{{ $tax->is_active
                                                        ? 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100'
                                                        : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} inline-flex rounded-2xl border px-4 py-2 text-xs font-black transition">
                                                    {{ $tax->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-stone-200 px-6 py-4">
                    {{ $taxes->links() }}
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
