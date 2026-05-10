@extends('layouts.master')

@section('title', 'Admin')
@section('header-title', 'Manajemen Kasir')

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
                            Cashier Management
                        </span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Data Kasir
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-stone-300">
                        Admin dapat menambah kasir, mengubah data kasir, dan mengaktifkan atau menonaktifkan akun kasir.
                    </p>
                </div>

                <a href="{{ route('admin.cashiers.create') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                    Tambah Kasir
                </a>
            </div>
        </section>

        <section class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.cashiers.index') }}"
                class="grid gap-4 lg:grid-cols-12 lg:items-end">

                <div class="lg:col-span-7">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Cari Kasir
                    </label>

                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Nama, email, atau nomor HP"
                        class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                </div>

                <div class="lg:col-span-3" x-data="statusDropdown(@js((string) $status))" @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Status
                    </label>

                    <input type="hidden" name="status" x-model="selectedStatus">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                <span x-show="selectedStatus === ''">
                                    Semua Status
                                </span>

                                <span x-show="selectedStatus === 'active'" x-cloak>
                                    Aktif
                                </span>

                                <span x-show="selectedStatus === 'inactive'" x-cloak>
                                    Nonaktif
                                </span>
                            </span>

                            <svg class="h-4 w-4 shrink-0 text-stone-400 transition" :class="statusOpen ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="statusOpen" x-cloak x-transition.origin.top @click.outside="close()"
                            class="absolute left-0 right-0 top-[54px] z-50 overflow-hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                            <button type="button" @click="select('')"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                :class="selectedStatus === '' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                                <span>Semua Status</span>

                                <svg x-show="selectedStatus === ''" x-cloak class="h-4 w-4 shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>

                            <button type="button" @click="select('active')"
                                class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-emerald-50 hover:text-emerald-700"
                                :class="selectedStatus === 'active' ? 'bg-emerald-100 text-emerald-800' : 'text-stone-700'">
                                <span>Aktif</span>

                                <svg x-show="selectedStatus === 'active'" x-cloak class="h-4 w-4 shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>

                            <button type="button" @click="select('inactive')"
                                class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-rose-50 hover:text-rose-700"
                                :class="selectedStatus === 'inactive' ? 'bg-rose-100 text-rose-800' : 'text-stone-700'">
                                <span>Nonaktif</span>

                                <svg x-show="selectedStatus === 'inactive'" x-cloak class="h-4 w-4 shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 lg:col-span-2 lg:justify-end">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-2xl bg-stone-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-stone-800 active:scale-[0.98] lg:flex-none">
                        Filter
                    </button>

                    <a href="{{ route('admin.cashiers.index') }}"
                        class="inline-flex flex-1 items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98] lg:flex-none">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-200 px-6 py-5">
                <h3 class="text-lg font-black text-stone-950">
                    Daftar Kasir
                </h3>

                <p class="mt-1 text-sm text-stone-500">
                    Total kasir terdaftar: {{ $cashiers->total() }}
                </p>
            </div>

            @if ($cashiers->isEmpty())
                <div class="p-10 text-center">
                    <p class="text-sm font-black text-stone-700">
                        Belum ada kasir.
                    </p>
                    <p class="mt-2 text-xs font-medium text-stone-500">
                        Tambahkan kasir agar operasional POS bisa digunakan.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Kasir
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Kontak
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
                            @foreach ($cashiers as $cashier)
                                <tr class="hover:bg-stone-50">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-black text-stone-950">
                                            {{ $cashier->name }}
                                        </p>

                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            Dibuat {{ $cashier->created_at->format('d M Y H:i') }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-stone-800">
                                            {{ $cashier->email }}
                                        </p>

                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            {{ $cashier->phone ?: '-' }}
                                        </p>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        @if ($cashier->is_active)
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
                                            <a href="{{ route('admin.cashiers.edit', $cashier) }}"
                                                class="inline-flex rounded-2xl border border-stone-200 bg-white px-4 py-2 text-xs font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                action="{{ route('admin.cashiers.toggle-status', $cashier) }}">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="{{ $cashier->is_active
                                                        ? 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100'
                                                        : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} inline-flex rounded-2xl border px-4 py-2 text-xs font-black transition">
                                                    {{ $cashier->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
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
                    {{ $cashiers->links() }}
                </div>
            @endif
        </section>
    </div>
    <script>
        function statusDropdown(initialStatus) {
            return {
                selectedStatus: initialStatus || '',
                statusOpen: false,

                toggle() {
                    this.statusOpen = !this.statusOpen;
                },

                close() {
                    this.statusOpen = false;
                },

                select(status) {
                    this.selectedStatus = status;
                    this.close();
                },
            };
        }
    </script>
@endsection
