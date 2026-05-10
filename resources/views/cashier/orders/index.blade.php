@extends('layouts.master')

@section('title', 'Riwayat Order')
@section('header-title', 'Riwayat Order')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <section class="relative overflow-hidden rounded-[2rem] bg-[#171412] p-6 shadow-xl border border-white/10">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)] pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                <div>
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-3 py-1.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>

                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Order History
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Riwayat Order
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-stone-300">
                        Riwayat transaksi pada shift aktif.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-3 xl:w-auto">
                    <div class="rounded-2xl border border-white/10 bg-white/10 px-5 py-4 backdrop-blur-md">
                        <p class="text-[11px] font-black uppercase tracking-widest text-stone-400">
                            Total Order
                        </p>

                        <p class="mt-1 text-base font-black text-amber-300">
                            {{ $summary['total_orders'] }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Filter --}}
        <section x-data="{
            statusFilter: @js($status ?? ''),
            paymentMethodFilter: @js($paymentMethod ?? ''),
            statusOpen: false,
            paymentOpen: false
        }" @keydown.escape.window="statusOpen = false; paymentOpen = false"
            class="rounded-[2rem] border border-stone-100 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('cashier.orders.index') }}"
                class="grid gap-3 xl:grid-cols-[minmax(0,1fr)_220px_220px_110px_110px] xl:items-end">
                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Cari Order
                    </label>

                    <div class="relative">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>

                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Cari nomor order, customer, atau meja..."
                            class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 pl-12 pr-4 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Status
                    </label>

                    <input type="hidden" name="status" x-model="statusFilter">

                    <div class="relative">
                        <button type="button" @click="statusOpen = !statusOpen; paymentOpen = false"
                            class="flex h-[52px] w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 text-left text-sm font-bold text-stone-700 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                <span x-show="statusFilter === ''">Semua Status</span>

                                @foreach ($statusOptions as $value => $label)
                                    <span x-show="statusFilter === '{{ $value }}'" x-cloak>
                                        {{ $label }}
                                    </span>
                                @endforeach
                            </span>

                            <svg class="h-4 w-4 shrink-0 text-stone-400 transition" :class="statusOpen ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="statusOpen" x-cloak x-transition.origin.top @click.outside="statusOpen = false"
                            class="absolute left-0 right-0 top-[60px] z-50 max-h-64 overflow-y-auto rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                            <button type="button" @click="statusFilter = ''; statusOpen = false"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                :class="statusFilter === '' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                                <span>Semua Status</span>

                                <svg x-show="statusFilter === ''" x-cloak class="h-4 w-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>

                            @foreach ($statusOptions as $value => $label)
                                <button type="button" @click="statusFilter = '{{ $value }}'; statusOpen = false"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                    :class="statusFilter === '{{ $value }}' ? 'bg-amber-100 text-amber-800' :
                                        'text-stone-700'">
                                    <span class="truncate">{{ $label }}</span>

                                    <svg x-show="statusFilter === '{{ $value }}'" x-cloak class="h-4 w-4 shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Pembayaran --}}
                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Pembayaran
                    </label>

                    <input type="hidden" name="payment_method" x-model="paymentMethodFilter">

                    <div class="relative">
                        <button type="button" @click="paymentOpen = !paymentOpen; statusOpen = false"
                            class="flex h-[52px] w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 text-left text-sm font-bold text-stone-700 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                <span x-show="paymentMethodFilter === ''">Semua Metode</span>

                                @foreach ($paymentMethodOptions as $value => $label)
                                    <span x-show="paymentMethodFilter === '{{ $value }}'" x-cloak>
                                        {{ $label }}
                                    </span>
                                @endforeach
                            </span>

                            <svg class="h-4 w-4 shrink-0 text-stone-400 transition" :class="paymentOpen ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="paymentOpen" x-cloak x-transition.origin.top @click.outside="paymentOpen = false"
                            class="absolute left-0 right-0 top-[60px] z-50 max-h-64 overflow-y-auto rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                            <button type="button" @click="paymentMethodFilter = ''; paymentOpen = false"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                :class="paymentMethodFilter === '' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                                <span>Semua Metode</span>

                                <svg x-show="paymentMethodFilter === ''" x-cloak class="h-4 w-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>

                            @foreach ($paymentMethodOptions as $value => $label)
                                <button type="button"
                                    @click="paymentMethodFilter = '{{ $value }}'; paymentOpen = false"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                    :class="paymentMethodFilter === '{{ $value }}' ? 'bg-amber-100 text-amber-800' :
                                        'text-stone-700'">
                                    <span class="truncate">{{ $label }}</span>

                                    <svg x-show="paymentMethodFilter === '{{ $value }}'" x-cloak
                                        class="h-4 w-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="flex h-[52px] w-full items-center justify-center rounded-2xl bg-[#171412] px-5 text-sm font-black text-white shadow-sm transition hover:bg-[#2a231f] active:scale-[0.98]">
                    Filter
                </button>

                <a href="{{ route('cashier.orders.index') }}"
                    class="flex h-[52px] w-full items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98]">
                    Reset
                </a>
            </form>
        </section>

        {{-- Order List --}}
        <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-200 px-6 py-5">
                <h3 class="text-lg font-black text-stone-950">
                    Transaksi Shift Ini
                </h3>

                <p class="mt-1 text-sm text-stone-500">
                    Shift dibuka pada {{ $activeShift->opened_at->format('d M Y H:i') }}.
                </p>
            </div>

            @if ($orders->isEmpty())
                <div class="p-10 text-center">
                    <p class="text-sm font-black text-stone-700">
                        Tidak ada order yang cocok.
                    </p>

                    <p class="mt-2 text-xs font-medium text-stone-500">
                        Coba ubah filter pencarian.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Order
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Customer
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Pembayaran
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Total
                                </th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100 bg-white">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-stone-50">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <p class="text-sm font-black text-stone-950">
                                            {{ $order->order_number }}
                                        </p>

                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            {{ $order->created_at->format('d M Y H:i') }}
                                        </p>

                                        <p class="mt-1 text-xs font-semibold text-stone-400">
                                            {{ $order->items->sum('quantity') }} item
                                        </p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-stone-800">
                                            {{ $order->customer_name }}
                                        </p>

                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            {{ $order->table?->name ?? 'Takeaway / Tanpa meja' }}
                                        </p>

                                        <p class="mt-1 text-xs font-semibold text-stone-400">
                                            {{ str_replace('_', ' ', strtoupper($order->order_source)) }}
                                        </p>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        <p class="text-sm font-black text-stone-800">
                                            {{ strtoupper($order->payment?->method ?? '-') }}
                                        </p>

                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            {{ $order->payment_status }}
                                        </p>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        @php
                                            $statusClass = match ($order->order_status) {
                                                \App\Models\Order::STATUS_COMPLETED
                                                    => 'bg-emerald-100 text-emerald-700',
                                                \App\Models\Order::STATUS_PROCESSING => 'bg-sky-100 text-sky-700',
                                                \App\Models\Order::STATUS_CANCELLED,
                                                \App\Models\Order::STATUS_REJECTED,
                                                \App\Models\Order::STATUS_EXPIRED
                                                    => 'bg-rose-100 text-rose-700',
                                                default => 'bg-stone-100 text-stone-700',
                                            };
                                        @endphp

                                        <span
                                            class="{{ $statusClass }} inline-flex rounded-full px-3 py-1 text-xs font-black">
                                            {{ $order->order_status }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-black text-stone-950">
                                        Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <a href="{{ route('cashier.orders.show', $order) }}"
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
@endsection
