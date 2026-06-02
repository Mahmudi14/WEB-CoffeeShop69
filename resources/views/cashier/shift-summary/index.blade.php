@extends('layouts.master')

@section('title', 'Ringkasan Shift')
@section('header-title', 'Ringkasan Shift')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <section class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-[#171412] p-6 shadow-xl">
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)]">
            </div>

            <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0">
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>

                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Shift Summary
                        </span>
                    </div>

                    <h1 class="text-2xl font-black tracking-tight text-white sm:text-3xl">
                        Ringkasan Shift
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-stone-300">
                        Ringkasan kas, transaksi, pengeluaran, dan menu terjual.
                    </p>
                </div>

                <div class="flex shrink-0 flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="rounded-2xl border border-white/10 bg-white/10 px-5 py-4 backdrop-blur-md sm:text-right">
                        <p class="text-[11px] font-black uppercase tracking-widest text-stone-400">
                            Shift Dibuka
                        </p>

                        <p class="mt-1 text-base font-black text-amber-300">
                            {{ $activeShift->opened_at->format('d M Y H:i') }}
                        </p>
                    </div>

                    @if ($summary['active_orders'] > 0)
                        <a href="{{ route('cashier.incoming-orders.index') }}"
                            class="inline-flex h-[52px] items-center justify-center rounded-2xl bg-sky-500 px-6 text-sm font-black text-white shadow-lg shadow-sky-500/25 transition hover:bg-sky-600 active:scale-[0.98]">
                            Selesaikan Order
                        </a>
                    @else
                        <a href="{{ route('cashier.shifts.close') }}"
                            class="inline-flex h-[52px] items-center justify-center rounded-2xl bg-amber-500 px-6 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                            Tutup Shift
                        </a>
                    @endif
                </div>
            </div>
        </section>

        {{-- Audit Kas --}}
        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5 xl:grid-cols-5">
            <div class="rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-stone-500">
                    Kas Awal
                </p>

                <p class="mt-3 text-2xl font-black text-stone-950">
                    Rp{{ number_format($summary['opening_cash'], 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-[2rem] border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-emerald-700">
                    Cash Masuk
                </p>

                <p class="mt-3 text-2xl font-black text-emerald-800">
                    Rp{{ number_format($summary['cash_sales'], 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-[2rem] border border-sky-200 bg-sky-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-sky-700">
                    Non Tunai
                </p>

                <p class="mt-3 text-2xl font-black text-sky-800">
                    Rp{{ number_format($summary['non_cash_sales'], 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-[2rem] border border-rose-200 bg-rose-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-rose-700">
                    Pengeluaran
                </p>

                <p class="mt-3 text-2xl font-black text-rose-800">
                    Rp{{ number_format($summary['expense_total'], 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-[2rem] border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-amber-700">
                    Estimasi Kas Laci
                </p>

                <p class="mt-3 text-2xl font-black text-amber-800">
                    Rp{{ number_format($summary['estimated_cash'], 0, ',', '.') }}
                </p>
            </div>
        </section>

        {{-- Audit Order --}}
        <section class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-stone-500">
                    Total Orderan
                </p>

                <p class="mt-3 text-3xl font-black text-stone-950">
                    {{ $summary['total_orders'] }}
                </p>

                <p class="mt-2 text-xs font-semibold text-stone-500">
                    Termasuk order QR yang belum diverifikasi
                </p>
            </div>

            <div class="rounded-[2rem] border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-emerald-700">
                    Menu Terjual
                </p>

                <p class="mt-3 text-3xl font-black text-emerald-800">
                    {{ $summary['total_menu_sold_qty'] }}
                </p>

                <p class="mt-2 text-xs font-semibold text-emerald-700">
                    Total item makanan dan minuman paid
                </p>
            </div>

            <div class="rounded-[2rem] border border-rose-200 bg-rose-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-rose-700">
                    Order Ditolak
                </p>

                <p class="mt-3 text-3xl font-black text-rose-800">
                    {{ $summary['rejected_orders'] }}
                </p>

                <p class="mt-2 text-xs font-semibold text-rose-700">
                    Bukti pembayaran ditolak
                </p>
            </div>
        </section>

        {{-- Payment Breakdown --}}
        <section class="grid items-stretch gap-6 lg:grid-cols-3">
            <div class="h-full rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex h-full min-h-[360px] flex-col">
                    {{-- Header --}}
                    <div>
                        <h3 class="text-xl font-black text-stone-950">
                            Breakdown Pembayaran
                        </h3>
                    </div>

                    {{-- Rincian pemasukan - dempet atas --}}
                    <div class="mt-5 space-y-3">
                        <div class="flex justify-between gap-4 rounded-2xl bg-stone-50 px-4 py-3">
                            <span class="text-sm font-bold text-stone-600">
                                Cash
                            </span>

                            <span class="text-sm font-black text-stone-950">
                                Rp{{ number_format($summary['cash_sales'], 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4 rounded-2xl bg-stone-50 px-4 py-3">
                            <span class="text-sm font-bold text-stone-600">
                                QRIS
                            </span>

                            <span class="text-sm font-black text-stone-950">
                                Rp{{ number_format($summary['qris_sales'], 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4 rounded-2xl bg-stone-50 px-4 py-3">
                            <span class="text-sm font-bold text-stone-600">
                                Transfer
                            </span>

                            <span class="text-sm font-black text-stone-950">
                                Rp{{ number_format($summary['transfer_sales'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Total penjualan - selalu dempet bawah --}}
                    <div class="mt-auto pt-6">
                        <div class="flex justify-between gap-4 rounded-2xl bg-amber-50 px-4 py-4">
                            <span class="text-sm font-black text-amber-800">
                                Total Penjualan
                            </span>

                            <span class="text-sm font-black text-amber-800">
                                Rp{{ number_format($summary['total_sales'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Expenses --}}
            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-black text-stone-950">
                            Pengeluaran
                        </h3>
                        <p class="mt-1 text-sm text-stone-500">
                            Pengeluaran pada shift aktif.
                        </p>
                    </div>

                    <a href="{{ route('cashier.expenses.index') }}"
                        class="rounded-2xl border border-stone-200 bg-white px-4 py-2 text-xs font-black text-stone-700 shadow-sm hover:bg-stone-50">
                        Detail
                    </a>
                </div>

                <div class="mt-5 max-h-72 space-y-3 overflow-y-auto">
                    @forelse ($expenses as $expense)
                        <div class="rounded-2xl bg-stone-50 px-4 py-3">
                            <div class="flex justify-between gap-3">
                                <div>
                                    <p class="text-sm font-black text-stone-800">
                                        {{ $expense->category }}
                                    </p>
                                    <p class="mt-1 text-xs font-semibold text-stone-500">
                                        {{ $expense->created_at->format('d M H:i') }}
                                    </p>
                                </div>

                                <p class="text-sm font-black text-rose-600">
                                    Rp{{ number_format($expense->amount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-stone-300 bg-stone-50 p-6 text-center">
                            <p class="text-sm font-bold text-stone-600">
                                Belum ada pengeluaran.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Audit Tutup Shift --}}
            <div class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-stone-400">
                            Audit Tutup Shift
                        </p>

                        <h3 class="mt-2 text-lg font-black text-stone-950">
                            Status Penutupan
                        </h3>
                    </div>

                    @if ($summary['active_orders'] > 0)
                        <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-black text-sky-700">
                            Belum Siap
                        </span>
                    @else
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                            Siap Ditutup
                        </span>
                    @endif
                </div>

                <div class="mt-5 space-y-3">
                    @if ($summary['active_orders'] > 0)
                        <div class="rounded-2xl border border-sky-200 bg-sky-50 p-4">
                            <p class="text-sm font-black text-sky-800">
                                Masih ada {{ $summary['active_orders'] }} order aktif
                            </p>

                            <p class="mt-1 text-xs font-semibold leading-5 text-sky-700">
                                Selesaikan order aktif sebelum menutup shift.
                            </p>
                        </div>

                        <a href="{{ route('cashier.incoming-orders.index') }}"
                            class="flex h-[52px] w-full items-center justify-center rounded-2xl bg-sky-600 px-5 text-sm font-black text-white shadow-sm transition hover:bg-sky-700 active:scale-[0.98]">
                            Buka Order Masuk
                        </a>
                    @else
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                            <p class="text-sm font-black text-emerald-800">
                                Tidak ada order aktif
                            </p>

                            <p class="mt-1 text-xs font-semibold leading-5 text-emerald-700">
                                Data shift siap diaudit sebelum penutupan.
                            </p>
                        </div>

                        <a href="{{ route('cashier.shifts.close') }}"
                            class="flex h-[52px] w-full items-center justify-center rounded-2xl bg-[#171412] px-5 text-sm font-black text-white shadow-sm transition hover:bg-[#2a231f] active:scale-[0.98]">
                            Lanjut Tutup Shift
                        </a>
                    @endif

                    <a href="{{ route('cashier.orders.index') }}"
                        class="flex h-[52px] w-full items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98]">
                        Lihat Riwayat Order
                    </a>
                </div>
            </div>
        </section>

        {{-- Sold Items --}}
        <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-200 px-6 py-5">
                <h3 class="text-lg font-black text-stone-950">
                    Menu Terjual
                </h3>

                <p class="mt-1 text-sm text-stone-500">
                    Rekap menu yang terjual selama shift aktif.
                </p>
            </div>

            @if ($soldItems->isEmpty())
                <div class="p-8 text-center">
                    <p class="text-sm font-bold text-stone-600">
                        Belum ada menu terjual.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Menu
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-black uppercase tracking-wider text-stone-500">
                                    Qty
                                </th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Subtotal Normal
                                </th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Diskon
                                </th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Total Bersih
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100">
                            @foreach ($soldItems as $item)
                                <tr class="hover:bg-stone-50">
                                    <td class="px-6 py-4 text-sm font-black text-stone-950">
                                        {{ $item->menu_name }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-sm font-black text-stone-800">
                                        {{ $item->total_quantity }}
                                    </td>

                                    <td class="px-6 py-4 text-right text-sm font-bold text-stone-700">
                                        Rp{{ number_format($item->subtotal_before_discount, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 text-right text-sm font-black text-emerald-700">
                                        -Rp{{ number_format($item->total_discount, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 text-right text-sm font-black text-stone-950">
                                        Rp{{ number_format($item->subtotal_after_discount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Transactions --}}
        <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-200 px-6 py-5">
                <h3 class="text-lg font-black text-stone-950">
                    Transaksi Shift
                </h3>

                <p class="mt-1 text-sm text-stone-500">
                    Daftar order pada shift aktif.
                </p>
            </div>

            @if ($orders->isEmpty())
                <div class="p-8 text-center">
                    <p class="text-sm font-bold text-stone-600">
                        Belum ada transaksi.
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
                                    Payment
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
                                            {{ $order->table?->name ?? 'Takeaway / Tanpa meja' }}
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
                                        <span
                                            class="inline-flex rounded-full bg-stone-100 px-3 py-1 text-xs font-black text-stone-700">
                                            {{ $order->order_status }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-black text-stone-950">
                                        Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <a href="{{ route('cashier.orders.show', $order) }}"
                                            class="inline-flex rounded-2xl border border-stone-200 bg-white px-4 py-2 text-xs font-black text-stone-700 shadow-sm hover:bg-stone-50">
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
