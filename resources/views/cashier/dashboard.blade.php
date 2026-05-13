@extends('layouts.master')

@section('title', 'Dashboard Kasir')
@section('header-title', 'Dashboard Kasir')

@section('content')
    @php
        $user = auth()->user();

        $shiftIsOpen = $summary['has_active_shift'];
        $nonCashTotal = $summary['qris_sales'] + $summary['transfer_sales'];

        $shiftBadgeClass = $shiftIsOpen
            ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
            : 'bg-amber-100 text-amber-700 border border-amber-200';

        $shiftCardClass = $shiftIsOpen ? 'border-emerald-200 bg-emerald-50' : 'border-amber-200 bg-amber-50';

        $shiftLabel = $shiftIsOpen ? 'Shift Aktif' : 'Belum Ada Shift';
        $shiftActionLabel = $shiftIsOpen ? 'Tutup Shift' : 'Mulai Shift';
        $shiftActionRoute = $shiftIsOpen ? route('cashier.shifts.close') : route('cashier.shifts.start');
        $shiftActionClass = $shiftIsOpen
            ? 'bg-stone-950 text-white hover:bg-stone-800'
            : 'bg-amber-600 text-white hover:bg-amber-700';
    @endphp

    <div class="space-y-6">
        {{-- Header --}}
        <section class="relative overflow-hidden rounded-[2rem] bg-[#171412] p-6 shadow-xl border border-white/10">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)] pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                <div class="min-w-0">
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-3 py-1.5">
                        <span class="h-2 w-2 rounded-full {{ $shiftIsOpen ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Cashier Panel
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Dashboard Kasir
                    </h1>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <span
                            class="inline-flex rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-bold text-stone-300">
                            {{ now()->translatedFormat('d F Y') }}
                        </span>
                    </div>
                </div>

                <div class="grid w-full grid-cols-1 gap-3 sm:grid-cols-3 xl:w-auto">
                    <a href="{{ route('cashier.pos.index') }}"
                        class="relative inline-flex h-[48px] items-center justify-center rounded-2xl bg-amber-500 px-5 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/20 transition hover:bg-amber-400 active:scale-[0.98]">
                        <span>POS</span>
                    </a>

                    <a href="{{ route('cashier.incoming-orders.index') }}"
                        class="relative inline-flex h-[48px] items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-5 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                        <span>Order Masuk</span>

                        <span id="incoming-order-badge-inbox"
                            class="{{ ($incomingOrderCount ?? 0) > 0 ? 'flex' : 'hidden' }} absolute -right-2 -top-2 min-w-[24px] h-6 items-center justify-center rounded-full bg-rose-500 px-2 text-[11px] font-black text-white shadow-lg shadow-rose-500/30">
                            {{ $summary['incoming_orders'] ?? 0 }}
                        </span>
                    </a>

                    <a href="{{ $shiftActionRoute }}"
                        class="inline-flex h-[48px] items-center justify-center rounded-2xl border border-white/10 px-5 text-sm font-black shadow-sm transition active:scale-[0.98]
        {{ $shiftIsOpen ? 'bg-white text-stone-950 hover:bg-stone-100' : 'bg-amber-500 text-stone-950 hover:bg-amber-400' }}">
                        {{ $shiftActionLabel }}
                    </a>
                </div>
            </div>
        </section>

        {{-- Status Shift --}}
        <section class="rounded-[2rem] border p-5 shadow-sm {{ $shiftCardClass }}">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-stone-500">
                        Status Shift
                    </p>

                    <h3 class="mt-2 text-xl font-black text-stone-950">
                        {{ $shiftIsOpen ? 'Shift sedang berjalan' : 'Shift belum dibuka' }}
                    </h3>

                    @if ($shiftIsOpen && isset($activeShift))
                        <p class="mt-1 text-sm font-semibold text-stone-600">
                            Dibuka {{ $activeShift->opened_at->format('d M Y, H:i') }}
                        </p>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white/80 px-5 py-4">
                        <p class="text-[11px] font-black uppercase tracking-wide text-stone-400">
                            Kas Awal
                        </p>

                        <p class="mt-1 text-lg font-black text-stone-900">
                            Rp{{ number_format($summary['opening_cash'], 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-white/80 px-5 py-4">
                        <p class="text-[11px] font-black uppercase tracking-wide text-stone-400">
                            Estimasi Kas
                        </p>

                        <p class="mt-1 text-lg font-black text-emerald-700">
                            Rp{{ number_format($summary['estimated_cash'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Ringkasan Order Operasional --}}
        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5 xl:grid-cols-5">
            <div
                class="grid min-h-[170px] grid-rows-[auto_1fr_auto] rounded-[2rem] border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-amber-700">
                    Orderan Masuk
                </p>

                <div class="flex items-center justify-center">
                    <p class="text-center text-4xl font-black tracking-tight text-amber-800">
                        {{ $summary['incoming_orders'] ?? 0 }}
                    </p>
                </div>

                <p class="text-xs font-semibold text-amber-700">
                    Menunggu persetujuan
                </p>
            </div>

            <div
                class="grid min-h-[170px] grid-rows-[auto_1fr_auto] rounded-[2rem] border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-emerald-700">
                    Selesai
                </p>

                <div class="flex items-center justify-center">
                    <p class="text-center text-4xl font-black tracking-tight text-emerald-800">
                        {{ $summary['completed_orders'] ?? 0 }}
                    </p>
                </div>

                <p class="text-xs font-semibold text-emerald-700">
                    Order completed
                </p>
            </div>

            <div
                class="grid min-h-[170px] grid-rows-[auto_1fr_auto] rounded-[2rem] border border-yellow-200 bg-yellow-50 p-5 shadow-sm">
                <p class="text-[10px] font-bold text-yellow-700">
                    Menunggu Pembayaran
                </p>

                <div class="flex items-center justify-center">
                    <p class="text-center text-4xl font-black tracking-tight text-yellow-800">
                        {{ $summary['pending_cash_orders'] ?? 0 }}
                    </p>
                </div>

                <p class="text-xs font-semibold text-yellow-700">
                    Bayar tunai
                </p>
            </div>

            <div
                class="grid min-h-[170px] grid-rows-[auto_1fr_auto] rounded-[2rem] border border-orange-200 bg-orange-50 p-5 shadow-sm">
                <p class="text-[10px] font-bold text-orange-700">
                    Menunggu Verifikasi
                </p>

                <div class="flex items-center justify-center">
                    <p class="text-center text-4xl font-black tracking-tight text-orange-800">
                        {{ $summary['pending_verification_orders'] ?? 0 }}
                    </p>
                </div>

                <p class="text-xs font-semibold text-orange-700">
                    QRIS / Transfer
                </p>
            </div>

            <div
                class="grid min-h-[170px] grid-rows-[auto_1fr_auto] rounded-[2rem] border border-orange-200 bg-orange-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-rose-700">
                    Batal / Ditolak
                </p>

                <div class="flex items-center justify-center">
                    <p class="text-center text-4xl font-black tracking-tight  text-rose-800">
                        {{ $summary['cancelled_or_rejected_orders'] ?? 0 }}
                    </p>
                </div>

                <p class="text-xs font-semibold text-rose-700">
                    Batal: {{ $summary['cancelled_orders'] ?? 0 }} • Ditolak: {{ $summary['rejected_orders'] ?? 0 }}
                </p>
            </div>
        </section>

        {{-- Akses cepat --}}
        <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-lg font-black text-stone-950">
                    Akses Cepat
                </h3>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('cashier.pos.index') }}"
                    class="group rounded-[1.75rem] border border-stone-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-stone-300 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base font-black text-stone-950">POS</h4>
                        <span class="text-stone-400 transition group-hover:text-stone-700">→</span>
                    </div>
                    <p class="mt-2 text-sm text-stone-500">
                        Buat transaksi baru
                    </p>
                </a>

                <a href="{{ route('cashier.incoming-orders.index') }}"
                    class="group rounded-[1.75rem] border border-stone-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-stone-300 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base font-black text-stone-950">Order Masuk</h4>
                        <span class="text-stone-400 transition group-hover:text-stone-700">→</span>
                    </div>
                    <p class="mt-2 text-sm text-stone-500">
                        Cek order QR
                    </p>
                </a>

                <a href="{{ route('cashier.expenses.index') }}"
                    class="group rounded-[1.75rem] border border-stone-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-stone-300 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base font-black text-stone-950">Pengeluaran</h4>
                        <span class="text-stone-400 transition group-hover:text-stone-700">→</span>
                    </div>
                    <p class="mt-2 text-sm text-stone-500">
                        Catat pengeluaran shift
                    </p>
                </a>

                <a href="{{ $shiftActionRoute }}"
                    class="group rounded-[1.75rem] border border-stone-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-stone-300 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base font-black text-stone-950">{{ $shiftActionLabel }}</h4>
                        <span class="text-stone-400 transition group-hover:text-stone-700">→</span>
                    </div>
                    <p class="mt-2 text-sm text-stone-500">
                        {{ $shiftIsOpen ? 'Akhiri shift aktif' : 'Buka shift baru' }}
                    </p>
                </a>
            </div>
        </section>
    </div>
@endsection
