@extends('layouts.master')

@section('title', 'Admin')
@section('header-title', 'Order')

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
                            Order Monitoring
                        </span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Data Order
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-stone-300 max-w-3xl">
                        Pantau seluruh order, lihat detail pesanan, serta batalkan order
                        aktif jika diperlukan.
                    </p>
                </div>
            </div>
        </section>
        <section class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <form action="{{ route('admin.orders.index') }}" method="GET">
                <div class="grid gap-4 lg:grid-cols-[minmax(220px,1fr)_180px_180px_240px_190px] lg:items-end">
                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                            Cari Order
                        </label>

                        <input type="text" name="search" value="{{ request('search', $search ?? '') }}"
                            placeholder="Nomor / customer"
                            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                            Dari Tanggal
                        </label>

                        <input type="date" name="date_from" value="{{ request('date_from', $dateFrom ?? '') }}"
                            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                            Sampai Tanggal
                        </label>

                        <input type="date" name="date_to" value="{{ request('date_to', $dateTo ?? '') }}"
                            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                    </div>

                    <div x-data="filterDropdown(@js((string) ($orderStatus ?? request('order_status'))))" @keydown.escape.window="close()">
                        <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                            Status Order
                        </label>

                        <input type="hidden" name="order_status" x-model="selectedValue">

                        <div class="relative">
                            <button type="button" @click="toggle()"
                                class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                                <span class="min-w-0 truncate">
                                    <span x-show="selectedValue === ''">Semua</span>
                                    <span x-show="selectedValue === 'pending_payment'" x-cloak>Menunggu Bayar</span>
                                    <span x-show="selectedValue === 'pending_payment_verification'" x-cloak>Menunggu
                                        Verifikasi</span>
                                    <span x-show="selectedValue === 'processing'" x-cloak>Processing</span>
                                    <span x-show="selectedValue === 'completed'" x-cloak>Completed</span>
                                    <span x-show="selectedValue === 'cancelled'" x-cloak>Cancelled</span>
                                    <span x-show="selectedValue === 'rejected'" x-cloak>Rejected</span>
                                    <span x-show="selectedValue === 'expired'" x-cloak>Expired</span>
                                </span>

                                <svg class="h-4 w-4 shrink-0 text-stone-400 transition"
                                    :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="dropdownOpen" x-cloak x-transition.origin.top @click.outside="close()"
                                class="absolute left-0 right-0 top-[54px] z-50 max-h-72 overflow-y-auto rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">

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

                                <button type="button" @click="select('pending_payment')"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                    :class="selectedValue === 'pending_payment' ? 'bg-amber-100 text-amber-800' :
                                        'text-stone-700'">
                                    <span>Menunggu Bayar</span>

                                    <svg x-show="selectedValue === 'pending_payment'" x-cloak class="h-4 w-4 shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>

                                <button type="button" @click="select('pending_payment_verification')"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-sky-50 hover:text-sky-700"
                                    :class="selectedValue === 'pending_payment_verification' ? 'bg-sky-100 text-sky-800' :
                                        'text-stone-700'">
                                    <span>Menunggu Verifikasi</span>

                                    <svg x-show="selectedValue === 'pending_payment_verification'" x-cloak
                                        class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>

                                <button type="button" @click="select('processing')"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-orange-50 hover:text-orange-700"
                                    :class="selectedValue === 'processing' ? 'bg-orange-100 text-orange-800' : 'text-stone-700'">
                                    <span>Processing</span>

                                    <svg x-show="selectedValue === 'processing'" x-cloak class="h-4 w-4 shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>

                                <button type="button" @click="select('completed')"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-emerald-50 hover:text-emerald-700"
                                    :class="selectedValue === 'completed' ? 'bg-emerald-100 text-emerald-800' :
                                        'text-stone-700'">
                                    <span>Completed</span>

                                    <svg x-show="selectedValue === 'completed'" x-cloak class="h-4 w-4 shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>

                                <button type="button" @click="select('cancelled')"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-rose-50 hover:text-rose-700"
                                    :class="selectedValue === 'cancelled' ? 'bg-rose-100 text-rose-800' : 'text-stone-700'">
                                    <span>Cancelled</span>

                                    <svg x-show="selectedValue === 'cancelled'" x-cloak class="h-4 w-4 shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>

                                <button type="button" @click="select('rejected')"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-rose-50 hover:text-rose-700"
                                    :class="selectedValue === 'rejected' ? 'bg-rose-100 text-rose-800' : 'text-stone-700'">
                                    <span>Rejected</span>

                                    <svg x-show="selectedValue === 'rejected'" x-cloak class="h-4 w-4 shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>

                                <button type="button" @click="select('expired')"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-stone-100 hover:text-stone-800"
                                    :class="selectedValue === 'expired' ? 'bg-stone-200 text-stone-900' : 'text-stone-700'">
                                    <span>Expired</span>

                                    <svg x-show="selectedValue === 'expired'" x-cloak class="h-4 w-4 shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div x-data="filterDropdown(@js((string) ($source ?? request('source'))))" @keydown.escape.window="close()">
                        <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                            Sumber
                        </label>

                        <input type="hidden" name="source" x-model="selectedValue">

                        <div class="relative">
                            <button type="button" @click="toggle()"
                                class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                                <span class="min-w-0 truncate">
                                    <span x-show="selectedValue === ''">Semua</span>
                                    <span x-show="selectedValue === 'cashier_pos'" x-cloak>POS Kasir</span>
                                    <span x-show="selectedValue === 'customer_qr'" x-cloak>QR Customer</span>
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
                                    <span>Semua</span>
                                </button>

                                <button type="button" @click="select('cashier_pos')"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                    :class="selectedValue === 'cashier_pos' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                                    <span>POS Kasir</span>
                                </button>

                                <button type="button" @click="select('customer_qr')"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-sky-50 hover:text-sky-700"
                                    :class="selectedValue === 'customer_qr' ? 'bg-sky-100 text-sky-800' : 'text-stone-700'">
                                    <span>QR Customer</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 border-t border-stone-100 pt-5 sm:grid-cols-2">
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-2xl bg-stone-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-stone-800 active:scale-[0.98]">
                        Filter
                    </button>

                    <a href="{{ route('admin.orders.index') }}"
                        class="inline-flex w-full items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98]">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-200 px-6 py-5">
                <h3 class="text-lg font-black text-stone-950">
                    Daftar Order
                </h3>

                <p class="mt-1 text-sm text-stone-500">
                    Total data: {{ $orders->total() }}
                </p>
            </div>

            @if ($orders->isEmpty())
                <div class="p-10 text-center">
                    <p class="text-sm font-black text-stone-700">
                        Belum ada order.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Order</th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Sumber</th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Total</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-stone-50">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <p class="text-sm font-black text-stone-950">
                                            {{ $order->order_number }}
                                        </p>
                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            {{ $order->created_at->format('d M Y H:i') }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-stone-800">
                                            {{ $order->customer_name }}
                                        </p>
                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            {{ $order->table?->name ?? 'Takeaway / Tanpa Meja' }}
                                        </p>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            class="rounded-full bg-stone-100 px-3 py-1 text-xs font-black text-stone-700">
                                            {{ $order->order_source === 'cashier_pos' ? 'POS Kasir' : 'QR Customer' }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <span
                                                class="w-fit rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-700">
                                                {{ str_replace('_', ' ', $order->order_status) }}
                                            </span>

                                            <span
                                                class="w-fit rounded-full bg-sky-100 px-3 py-1 text-xs font-black text-sky-700">
                                                {{ str_replace('_', ' ', $order->payment_status) }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-black text-stone-950">
                                        Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="inline-flex rounded-2xl border border-stone-200 bg-white px-4 py-2 text-xs font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-stone-200 px-6 py-4">
                    {{ $orders->links() }}
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
