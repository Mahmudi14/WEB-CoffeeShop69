@extends('layouts.master')

@section('title', 'Dashboard Admin')
@section('header-title', 'Dashboard Admin')

@php
    use Illuminate\Support\Str;

    $safeRoute = function (string $name, string $fallback = '#') {
        return \Illuminate\Support\Facades\Route::has($name) ? route($name) : $fallback;
    };

    $formatStatus = function (?string $value) {
        if (!$value) {
            return '-';
        }

        return Str::headline(str_replace('_', ' ', $value));
    };
@endphp

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
                        class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-3 py-1.5 mb-4">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Admin Overview
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Dashboard Admin
                    </h1>

                    <p class="mt-2 text-sm text-stone-300">
                        Ringkasan operasional hari ini.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:flex sm:flex-wrap">
                    <a href="{{ $safeRoute('admin.orders.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm font-black text-white transition hover:bg-white/15">
                        Order
                    </a>

                    <a href="{{ $safeRoute('admin.menus.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm font-black text-white transition hover:bg-white/15">
                        Menu
                    </a>

                    <a href="{{ $safeRoute('admin.cashiers.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm font-black text-white transition hover:bg-white/15">
                        Kasir
                    </a>

                    <a href="{{ $safeRoute('admin.reports.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-4 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                        Laporan
                    </a>
                </div>
            </div>
        </section>

        {{-- Summary Cards --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[2rem] border border-stone-100 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-stone-500">
                            Penjualan Hari Ini
                        </p>
                        <p class="mt-3 text-3xl font-black tracking-tight text-stone-950">
                            Rp{{ number_format($summary['sales_today'], 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M12 8c-2.21 0-4 .895-4 2s1.79 2 4 2 4 .895 4 2-1.79 2-4 2m0-10V6m0 12v-2m8-4a8 8 0 11-16 0 8 8 0 0116 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-sky-100 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-stone-500">
                            Order Hari Ini
                        </p>
                        <p class="mt-3 text-3xl font-black tracking-tight text-stone-950">
                            {{ $summary['orders_today'] }}
                        </p>
                        <p class="mt-2 text-xs font-bold text-sky-600">
                            Completed: {{ $summary['completed_orders_today'] }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-sky-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M9 17h6m-6-4h6m-7 8h8a2 2 0 002-2V7.414A2 2 0 0017.414 6L14 2.586A2 2 0 0012.586 2H8a2 2 0 00-2 2v16a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-orange-100 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-stone-500">
                            Order Pending
                        </p>
                        <p class="mt-3 text-3xl font-black tracking-tight text-stone-950">
                            {{ $summary['pending_orders'] }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100 text-orange-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-emerald-100 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-stone-500">
                            Status Shift
                        </p>

                        <p
                            class="mt-3 text-3xl font-black tracking-tight {{ $summary['active_cashier_shifts'] > 0 ? 'text-emerald-700' : 'text-stone-950' }}">
                            {{ $summary['active_cashier_shifts'] > 0 ? 'Berjalan' : 'Kosong' }}
                        </p>

                        <p class="mt-2 text-xs font-semibold text-stone-500">
                            {{ $summary['active_cashier_shifts'] > 0 ? 'Ada kasir sedang bertugas' : 'Belum ada shift aktif' }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm6 0a3 3 0 100-6 3 3 0 000 6z" />
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        {{-- Secondary Stats --}}
        <section class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[2rem] border border-stone-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-stone-500">
                            Cash
                        </p>
                        <p class="mt-3 text-2xl font-black text-stone-950">
                            Rp{{ number_format($summary['cash_sales_today'], 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-stone-100 text-stone-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M17 9V7a5 5 0 00-10 0v2M5 9h14l-1 10H6L5 9z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-stone-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-stone-500">
                            Non Tunai
                        </p>
                        <p class="mt-3 text-2xl font-black text-stone-950">
                            Rp{{ number_format($summary['non_cash_sales_today'], 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-stone-100 text-stone-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M3 7h18M5 11h14M7 15h4m-6 6h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-stone-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-stone-500">
                            Diproses
                        </p>
                        <p class="mt-3 text-2xl font-black text-stone-950">
                            {{ $summary['processing_orders'] }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-stone-100 text-stone-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        {{-- Main Content --}}
        <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
            {{-- Latest Orders --}}
            <div class="overflow-hidden rounded-[2rem] border border-stone-100 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-stone-100 px-6 py-5">
                    <h3 class="text-lg font-black text-stone-950">
                        Order Terbaru
                    </h3>

                    <a href="{{ $safeRoute('admin.orders.index') }}"
                        class="text-sm font-black text-amber-600 transition hover:text-amber-700">
                        Lihat semua
                    </a>
                </div>

                @if ($latestOrders->isEmpty())
                    <div class="px-6 py-14 text-center">
                        <div
                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-stone-100 text-stone-300">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                    d="M9 12h6m-6 4h6M7 4h10l1 16H6L7 4z" />
                            </svg>
                        </div>

                        <p class="mt-4 text-sm font-bold text-stone-500">
                            Belum ada order.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left">
                            <thead class="bg-stone-50">
                                <tr class="text-[11px] font-black uppercase tracking-[0.18em] text-stone-500">
                                    <th class="px-6 py-4">Order</th>
                                    <th class="px-6 py-4">Customer</th>
                                    <th class="px-6 py-4">Pembayaran</th>
                                    <th class="px-6 py-4 text-right">Total</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-stone-100 text-sm text-stone-700">
                                @foreach ($latestOrders as $order)
                                    <tr class="transition hover:bg-stone-50/80">
                                        <td class="px-6 py-4 align-top">
                                            <p class="font-black text-stone-950">
                                                {{ $order->order_number }}
                                            </p>
                                            <p class="mt-1 text-xs font-semibold text-stone-500">
                                                {{ $order->created_at->format('d M Y, H:i') }}
                                            </p>
                                            <div class="mt-2">
                                                <span
                                                    class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-black
                                                    @if ($order->order_status === 'completed') bg-emerald-50 text-emerald-700
                                                    @elseif(in_array($order->order_status, ['processing'])) bg-sky-50 text-sky-700
                                                    @elseif(in_array($order->order_status, ['pending_payment', 'pending_payment_verification'])) bg-amber-50 text-amber-700
                                                    @else bg-rose-50 text-rose-700 @endif">
                                                    {{ $formatStatus($order->order_status) }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 align-top">
                                            <p class="font-bold text-stone-900">
                                                {{ $order->customer_name }}
                                            </p>
                                            <p class="mt-1 text-xs font-semibold text-stone-500">
                                                {{ $order->table?->name ?? 'Takeaway / Tanpa meja' }}
                                            </p>
                                        </td>

                                        <td class="px-6 py-4 align-top">
                                            <p class="font-black text-stone-900">
                                                {{ strtoupper($order->payment?->method ?? '-') }}
                                            </p>
                                            <p class="mt-1 text-xs font-semibold text-stone-500">
                                                {{ $formatStatus($order->payment_status) }}
                                            </p>
                                        </td>

                                        <td class="px-6 py-4 text-right align-top font-black text-stone-950">
                                            Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Right Column --}}
            <div class="space-y-6">
                <div class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-lg font-black text-stone-950">
                            Menu Terlaris
                        </h3>

                        <span
                            class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-[11px] font-black uppercase tracking-wide text-amber-700">
                            Hari ini
                        </span>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse ($topMenusToday as $menu)
                            <div class="flex items-center justify-between gap-4 rounded-2xl bg-stone-50 px-4 py-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-black text-stone-900">
                                        {{ $menu->menu_name }}
                                    </p>
                                    <p class="mt-1 text-xs font-semibold text-stone-500">
                                        {{ $menu->total_quantity }} terjual
                                    </p>
                                </div>

                                <p class="whitespace-nowrap text-sm font-black text-stone-950">
                                    Rp{{ number_format($menu->total_sales, 0, ',', '.') }}
                                </p>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-stone-300 bg-stone-50 p-6 text-center">
                                <p class="text-sm font-bold text-stone-500">
                                    Belum ada data.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-lg font-black text-stone-950">
                            Shift Aktif
                        </h3>

                        <span
                            class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-black uppercase tracking-wide text-emerald-700">
                            Open
                        </span>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse ($activeCashierShifts as $shift)
                            <div class="rounded-2xl bg-emerald-50 px-4 py-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-black text-emerald-900">
                                            {{ $shift->user?->name ?? 'Kasir' }}
                                        </p>
                                        <p class="mt-1 text-xs font-semibold text-emerald-700">
                                            {{ $shift->opened_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-[11px] font-bold uppercase tracking-wide text-emerald-700">
                                            Kas Awal
                                        </p>
                                        <p class="mt-1 text-sm font-black text-emerald-900">
                                            Rp{{ number_format($shift->opening_cash, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-stone-300 bg-stone-50 p-6 text-center">
                                <p class="text-sm font-bold text-stone-500">
                                    Tidak ada shift aktif.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-black text-stone-950">
                        Akses Cepat
                    </h3>

                    <div class="mt-5 grid gap-3">
                        <a href="{{ $safeRoute('admin.menus.index') }}"
                            class="flex items-center justify-between rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-black text-stone-700 transition hover:border-amber-300 hover:bg-amber-50/40 hover:text-amber-700">
                            <span>Kelola Menu</span>
                            <span>→</span>
                        </a>

                        <a href="{{ $safeRoute('admin.promotions.index') }}"
                            class="flex items-center justify-between rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-black text-stone-700 transition hover:border-amber-300 hover:bg-amber-50/40 hover:text-amber-700">
                            <span>Kelola Promo</span>
                            <span>→</span>
                        </a>

                        <a href="{{ $safeRoute('admin.cashiers.index') }}"
                            class="flex items-center justify-between rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-black text-stone-700 transition hover:border-amber-300 hover:bg-amber-50/40 hover:text-amber-700">
                            <span>Kelola Kasir</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
