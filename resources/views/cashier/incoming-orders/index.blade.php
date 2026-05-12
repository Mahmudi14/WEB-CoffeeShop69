@extends('layouts.master')

@section('title', 'Order Masuk')
@section('header-title', 'Order Masuk')

@section('content')
    @php
        use Illuminate\Support\Str;
    @endphp

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
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"></span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                        </span>

                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Incoming Orders
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Order Masuk
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-stone-300">
                        Verifikasi pembayaran dan selesaikan order QR customer.
                    </p>
                </div>

                {{-- Search --}}
                <div class="w-full xl:max-w-2xl">
                    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_110px]">
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>

                            <input id="incoming-order-search" type="search"
                                class="h-[52px] w-full rounded-2xl border border-white/10 bg-white px-12 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-500/20"
                                placeholder="Cari order, customer, atau meja...">
                        </div>

                        <button id="incoming-order-search-reset" type="button"
                            class="h-[52px] rounded-2xl border border-white/10 bg-white/10 px-5 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                            Reset
                        </button>
                    </div>

                    <p id="incoming-order-search-info" class="mt-3 hidden text-xs font-bold text-stone-300"></p>
                </div>
            </div>
        </section>

        {{-- Summary --}}
        <section class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[2rem] border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-amber-700">
                    Menunggu Tunai
                </p>

                <p class="mt-3 text-4xl font-black text-amber-800">
                    {{ $pendingCashOrders->count() }}
                </p>
            </div>

            <div class="rounded-[2rem] border border-orange-200 bg-orange-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-orange-700">
                    Verifikasi Bukti
                </p>

                <p class="mt-3 text-4xl font-black text-orange-800">
                    {{ $pendingVerificationOrders->count() }}
                </p>
            </div>

            <div class="rounded-[2rem] border border-sky-200 bg-sky-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-sky-700">
                    Sedang Diproses
                </p>

                <p class="mt-3 text-4xl font-black text-sky-800">
                    {{ $processingOrders->count() }}
                </p>
            </div>
        </section>

        {{-- Order Columns --}}
        <section class="grid gap-6 xl:grid-cols-3">
            {{-- Menunggu Cash --}}
            <div class="rounded-[2rem] border border-stone-100 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-stone-100 px-6 py-5">
                    <h2 class="text-lg font-black text-stone-950">
                        Menunggu Bayar Tunai
                    </h2>
                </div>

                <div id="pending-cash-scroll" data-preserve-scroll
                    class="max-h-[calc(100vh-18rem)] space-y-4 overflow-y-auto p-5">
                    @forelse ($pendingCashOrders as $order)
                        <article data-order-card
                            data-order-search="{{ Str::lower($order->order_number . ' ' . $order->customer_name . ' ' . ($order->table?->name ?? 'tanpa meja')) }}"
                            class="rounded-[2rem] border border-amber-200 bg-amber-50/70 p-5">
                            <div>
                                <p class="text-lg font-black text-stone-950">
                                    {{ $order->order_number }}
                                </p>

                                <p class="mt-1 text-sm font-semibold text-stone-600">
                                    {{ $order->table?->name ?? 'Tanpa meja' }} • {{ $order->customer_name }}
                                </p>

                                <p class="mt-1 text-xs font-semibold text-stone-500">
                                    {{ $order->created_at->format('d M Y H:i') }} • {{ $order->items->sum('quantity') }}
                                    item
                                </p>
                            </div>

                            <div class="mt-4 rounded-2xl bg-white p-4">
                                <p class="mb-3 text-xs font-black uppercase tracking-wider text-stone-400">
                                    Item
                                </p>

                                <div class="space-y-2">
                                    @foreach ($order->items->take(3) as $item)
                                        <div class="flex justify-between gap-3 text-sm">
                                            <span class="font-bold text-stone-700">
                                                {{ $item->quantity }}x {{ $item->menu_name }}
                                            </span>

                                            <span class="font-black text-stone-900">
                                                Rp{{ number_format($item->subtotal_after_discount, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach

                                    @if ($order->items->count() > 3)
                                        <p class="text-xs font-bold text-stone-500">
                                            +{{ $order->items->count() - 3 }} item lainnya
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 rounded-2xl bg-white p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-stone-500">
                                        Total
                                    </span>

                                    <span class="text-2xl font-black text-stone-950">
                                        Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('cashier.incoming-orders.accept-cash', $order) }}"
                                x-data="cashPaymentModal({
                                    grandTotal: {{ (int) $order->grand_total }}
                                })"
                                @submit="
        if (parseMoney(paidAmount) < grandTotal) {
            $event.preventDefault();
            return;
        }

        submitting = true;
    "
                                class="mt-4">
                                @csrf

                                <button type="button" @click="openPaymentModal()"
                                    class="w-full rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700 active:scale-[0.98]">
                                    Verifikasi Pembayaran
                                </button>

                                {{-- Payment Modal --}}
                                <div x-show="showPaymentModal" x-cloak x-transition.opacity
                                    @keydown.escape.window="closePaymentModal()"
                                    class="fixed inset-0 z-[999] flex items-center justify-center bg-stone-950/60 px-4">
                                    <div x-show="showPaymentModal" x-transition.scale.origin.center
                                        @click.outside="closePaymentModal()"
                                        class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-xs font-black uppercase tracking-[0.22em] text-emerald-600">
                                                    Konfirmasi Pembayaran
                                                </p>

                                                <h3 class="mt-2 text-2xl font-black tracking-tight text-stone-950">
                                                    Masukkan Nominal
                                                </h3>

                                                <p class="mt-2 text-sm leading-6 text-stone-500">
                                                    Pastikan uang diterima dan kembalian sudah sesuai sebelum pembayaran
                                                    diverifikasi.
                                                </p>
                                            </div>

                                            <button type="button" @click="closePaymentModal()" :disabled="submitting"
                                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-stone-100 text-stone-500 transition hover:bg-stone-200 disabled:cursor-not-allowed disabled:opacity-60">
                                                ✕
                                            </button>
                                        </div>

                                        <div class="mt-5 rounded-2xl bg-emerald-50 p-4">
                                            <div class="flex items-center justify-between gap-4">
                                                <span class="text-sm font-black text-emerald-800">
                                                    Total Bayar
                                                </span>

                                                <span class="text-xl font-black text-emerald-800">
                                                    Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-5">
                                            <label
                                                class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-500">
                                                Uang Diterima
                                            </label>

                                            <div class="relative">
                                                <span
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-black text-stone-500">
                                                    Rp
                                                </span>

                                                <input type="text" name="paid_amount" inputmode="numeric"
                                                    x-ref="paidAmountInput" x-model="paidAmount" @input="formatPaidAmount()"
                                                    class="w-full rounded-2xl border border-stone-200 bg-white py-4 pl-11 pr-4 text-lg font-black text-stone-950 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                                                    placeholder="0">
                                            </div>

                                            <p class="mt-2 text-xs font-semibold text-stone-500">
                                                Minimal: Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <div class="mt-4 rounded-2xl border border-stone-200 bg-stone-50 p-4">
                                            <div class="flex items-center justify-between gap-4">
                                                <span class="text-sm font-black text-stone-600">
                                                    Kembalian
                                                </span>

                                                <span class="text-xl font-black text-stone-950">
                                                    Rp<span x-text="formatMoney(changeAmount())"></span>
                                                </span>
                                            </div>

                                            <template x-if="remainingAmount() > 0">
                                                <p class="mt-2 text-xs font-bold text-rose-600">
                                                    Uang diterima masih kurang Rp<span
                                                        x-text="formatMoney(remainingAmount())"></span>
                                                </p>
                                            </template>

                                            <template
                                                x-if="remainingAmount() === 0 && parseMoney(paidAmount) >= grandTotal">
                                                <p class="mt-2 text-xs font-bold text-emerald-700">
                                                    Nominal pembayaran sudah cukup.
                                                </p>
                                            </template>
                                        </div>

                                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                            <button type="button" @click="closePaymentModal()" :disabled="submitting"
                                                class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 disabled:cursor-not-allowed disabled:opacity-60">
                                                Batal
                                            </button>

                                            <button type="submit"
                                                :disabled="submitting || parseMoney(paidAmount) < grandTotal"
                                                class="inline-flex min-w-[180px] items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60">
                                                <span x-show="!submitting">
                                                    Konfirmasi Pembayaran
                                                </span>

                                                <span x-show="submitting" x-cloak>
                                                    Memproses...
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </article>
                    @empty
                        <div class="rounded-[2rem] border border-dashed border-stone-300 bg-stone-50 p-8 text-center">
                            <p class="text-sm font-black text-stone-700">
                                Tidak ada order tunai.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Verifikasi Bukti --}}
            <div class="rounded-[2rem] border border-stone-100 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-stone-100 px-6 py-5">
                    <h2 class="text-lg font-black text-stone-950">
                        Verifikasi Bukti
                    </h2>
                </div>

                <div id="pending-verification-scroll" data-preserve-scroll
                    class="max-h-[calc(100vh-18rem)] space-y-4 overflow-y-auto p-5">
                    @forelse ($pendingVerificationOrders as $order)
                        <article data-order-card
                            data-order-search="{{ Str::lower($order->order_number . ' ' . $order->customer_name . ' ' . ($order->table?->name ?? 'tanpa meja')) }}"
                            class="rounded-[2rem] border border-orange-200 bg-orange-50/70 p-5">
                            <div>
                                <p class="text-lg font-black text-stone-950">
                                    {{ $order->order_number }}
                                </p>

                                <p class="mt-1 text-sm font-semibold text-stone-600">
                                    {{ $order->table?->name ?? 'Tanpa meja' }} • {{ $order->customer_name }}
                                </p>

                                <p class="mt-1 text-xs font-semibold text-stone-500">
                                    {{ strtoupper($order->payment?->method ?? '-') }} •
                                    {{ $order->created_at->format('d M Y H:i') }}
                                </p>
                            </div>

                            <div class="mt-4 rounded-2xl bg-white p-4">
                                <p class="mb-3 text-xs font-black uppercase tracking-wider text-stone-400">
                                    Item
                                </p>

                                <div class="space-y-2">
                                    @foreach ($order->items->take(4) as $item)
                                        <div class="flex justify-between gap-3 text-sm">
                                            <span class="font-bold text-stone-700">
                                                {{ $item->quantity }}x {{ $item->menu_name }}
                                            </span>

                                            <span class="font-black text-stone-900">
                                                Rp{{ number_format($item->subtotal_after_discount, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach

                                    @if ($order->items->count() > 4)
                                        <p class="text-xs font-bold text-stone-500">
                                            +{{ $order->items->count() - 4 }} item lainnya
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3 rounded-2xl bg-white p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-stone-500">
                                        Total
                                    </span>

                                    <span class="text-2xl font-black text-orange-800">
                                        Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            @if ($order->payment?->proof_path)
                                <button type="button" data-proof-preview
                                    data-proof-src="{{ asset('storage/' . $order->payment->proof_path) }}"
                                    data-proof-title="Bukti Pembayaran {{ $order->order_number }}"
                                    class="mt-4 inline-flex w-full items-center justify-center rounded-2xl border border-orange-200 bg-white px-4 py-3 text-sm font-black text-orange-700 transition hover:bg-orange-100">
                                    Lihat Bukti Pembayaran
                                </button>
                            @else
                                <div class="mt-4 rounded-2xl bg-rose-100 px-4 py-3 text-sm font-black text-rose-700">
                                    Bukti pembayaran tidak ditemukan.
                                </div>
                            @endif

                            <form method="POST" action="{{ route('cashier.incoming-orders.accept-proof', $order) }}"
                                class="mt-4">
                                @csrf

                                <button type="submit"
                                    class="w-full rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700 active:scale-[0.98]">
                                    Terima Bukti
                                </button>
                            </form>

                            <details class="mt-3 rounded-2xl border border-rose-200 bg-white">
                                <summary class="cursor-pointer px-4 py-3 text-sm font-black text-rose-600">
                                    Tolak Bukti
                                </summary>

                                <form method="POST" action="{{ route('cashier.incoming-orders.reject-proof', $order) }}"
                                    class="space-y-3 border-t border-rose-100 p-4">
                                    @csrf

                                    <input type="text" name="rejection_reason" required placeholder="Alasan penolakan"
                                        class="w-full rounded-2xl border border-rose-200 bg-white px-4 py-3 text-sm font-semibold outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-100">

                                    <button type="submit"
                                        class="w-full rounded-2xl bg-rose-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-rose-600/20 transition hover:bg-rose-700 active:scale-[0.98]">
                                        Konfirmasi Tolak
                                    </button>
                                </form>
                            </details>
                        </article>
                    @empty
                        <div class="rounded-[2rem] border border-dashed border-stone-300 bg-stone-50 p-8 text-center">
                            <p class="text-sm font-black text-stone-700">
                                Tidak ada bukti yang perlu diverifikasi.
                            </p>
                        </div>
                    @endforelse
                    <div id="paymentProofModal" class="fixed inset-0 z-50 hidden">
                        <div data-proof-backdrop class="absolute inset-0 bg-black/70"></div>

                        <div class="relative z-10 flex min-h-screen items-center justify-center p-4">
                            <div class="w-full max-w-3xl overflow-hidden rounded-[2rem] bg-white shadow-2xl">
                                <div class="flex items-center justify-between border-b border-stone-100 px-5 py-4">
                                    <div>
                                        <h3 id="paymentProofTitle" class="text-base font-black text-stone-950">
                                            Bukti Pembayaran
                                        </h3>

                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            Gunakan tombol zoom untuk memperbesar atau memperkecil bukti.
                                        </p>
                                    </div>

                                    <button type="button" data-proof-close
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-stone-100 text-2xl font-black text-stone-700 transition hover:bg-stone-200">
                                        &times;
                                    </button>
                                </div>

                                <div class="flex items-center justify-center gap-2 border-b border-stone-100 px-5 py-3">
                                    <button type="button" data-proof-zoom-out
                                        class="rounded-xl bg-stone-100 px-4 py-2 text-sm font-black text-stone-700 transition hover:bg-stone-200">
                                        -
                                    </button>

                                    <button type="button" data-proof-reset
                                        class="rounded-xl bg-stone-100 px-4 py-2 text-sm font-black text-stone-700 transition hover:bg-stone-200">
                                        Reset
                                    </button>

                                    <button type="button" data-proof-zoom-in
                                        class="rounded-xl bg-stone-100 px-4 py-2 text-sm font-black text-stone-700 transition hover:bg-stone-200">
                                        +
                                    </button>
                                </div>

                                <div class="max-h-[60vh] overflow-auto bg-stone-950 p-4">
                                    <img id="paymentProofImage" src="" alt="Bukti pembayaran"
                                        class="mx-auto h-auto max-w-none rounded-2xl bg-white object-contain">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sedang Diproses --}}
            <div class="rounded-[2rem] border border-stone-100 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-stone-100 px-6 py-5">
                    <h2 class="text-lg font-black text-stone-950">
                        Sedang Diproses
                    </h2>
                </div>

                <div id="processing-scroll" data-preserve-scroll
                    class="max-h-[calc(100vh-18rem)] space-y-4 overflow-y-auto p-5">
                    @forelse ($processingOrders as $order)
                        @php
                            $customerReceiptPending = $order->printJobs
                                ->where('type', \App\Models\PrintJob::TYPE_CUSTOMER_RECEIPT)
                                ->whereIn('status', [
                                    \App\Models\PrintJob::STATUS_PENDING,
                                    \App\Models\PrintJob::STATUS_PRINTING,
                                ])
                                ->isNotEmpty();

                            $customerReceiptPrintedCount = $order->printJobs
                                ->where('type', \App\Models\PrintJob::TYPE_CUSTOMER_RECEIPT)
                                ->where('status', \App\Models\PrintJob::STATUS_PRINTED)
                                ->count();

                            $kitchenPrint = $order->printJobs
                                ->where('type', \App\Models\PrintJob::TYPE_KITCHEN_ORDER)
                                ->sortByDesc('created_at')
                                ->first();
                        @endphp

                        <article data-order-card
                            data-order-search="{{ Str::lower($order->order_number . ' ' . $order->customer_name . ' ' . ($order->table?->name ?? 'tanpa meja')) }}"
                            class="rounded-[2rem] border border-sky-200 bg-sky-50/70 p-5">
                            <div>
                                <p class="text-lg font-black text-stone-950">
                                    {{ $order->order_number }}
                                </p>

                                <p class="mt-1 text-sm font-semibold text-stone-600">
                                    {{ $order->table?->name ?? 'Tanpa meja' }} • {{ $order->customer_name }}
                                </p>

                                <p class="mt-1 text-xs font-semibold text-stone-500">
                                    {{ strtoupper($order->payment?->method ?? '-') }} • Paid
                                </p>
                            </div>

                            <div class="mt-4 rounded-2xl bg-white p-4">
                                <p class="mb-3 text-xs font-black uppercase tracking-wider text-stone-400">
                                    Item
                                </p>

                                <div class="space-y-2">
                                    @foreach ($order->items->take(4) as $item)
                                        <div class="flex justify-between gap-3 text-sm">
                                            <span class="font-bold text-stone-700">
                                                {{ $item->quantity }}x {{ $item->menu_name }}
                                            </span>

                                            <span class="font-black text-stone-900">
                                                Rp{{ number_format($item->subtotal_after_discount, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach

                                    @if ($order->items->count() > 4)
                                        <p class="text-xs font-bold text-stone-500">
                                            +{{ $order->items->count() - 4 }} item lainnya
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 grid gap-3 rounded-2xl bg-white p-4 text-sm">
                                <div class="flex justify-between gap-4">
                                    <span class="font-bold text-stone-500">
                                        Kitchen Print
                                    </span>

                                    <span class="font-black text-stone-950">
                                        {{ $kitchenPrint?->status ?? 'belum ada' }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4">
                                    <span class="font-bold text-stone-500">
                                        Struk Customer
                                    </span>

                                    @if ($customerReceiptPending)
                                        <span class="font-black text-amber-700">
                                            antrean cetak
                                        </span>
                                    @elseif ($customerReceiptPrintedCount > 0)
                                        <span class="font-black text-emerald-700">
                                            sudah dicetak {{ $customerReceiptPrintedCount }}x
                                        </span>
                                    @else
                                        <span class="font-black text-rose-600">
                                            belum dicetak
                                        </span>
                                    @endif
                                </div>

                                <div class="flex justify-between gap-4">
                                    <span class="font-bold text-stone-500">
                                        Total
                                    </span>

                                    <span class="font-black text-stone-950">
                                        Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 grid gap-3">
                                @if ($customerReceiptPending)
                                    <button type="button" disabled
                                        class="w-full cursor-not-allowed rounded-2xl bg-stone-300 px-5 py-3 text-sm font-black text-stone-500">
                                        Struk Dalam Antrean
                                    </button>
                                @elseif ($customerReceiptPrintedCount < 1)
                                    <form method="POST"
                                        action="{{ route('cashier.incoming-orders.print-customer-receipt', $order) }}">
                                        @csrf

                                        <button type="submit"
                                            class="w-full rounded-2xl bg-amber-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700 active:scale-[0.98]">
                                            Cetak Struk
                                        </button>
                                    </form>
                                @else
                                    <div class="grid gap-3">
                                        <form method="POST"
                                            action="{{ route('cashier.incoming-orders.print-customer-receipt', $order) }}">
                                            @csrf

                                            <button type="submit"
                                                class="w-full rounded-2xl bg-amber-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700 active:scale-[0.98]">
                                                Cetak Ulang Struk
                                            </button>
                                        </form>

                                        <form method="POST"
                                            action="{{ route('cashier.incoming-orders.complete', $order) }}">
                                            @csrf

                                            <button type="submit"
                                                class="w-full rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700 active:scale-[0.98]">
                                                Selesaikan Pesanan
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="rounded-[2rem] border border-dashed border-stone-300 bg-stone-50 p-8 text-center">
                            <p class="text-sm font-black text-stone-700">
                                Tidak ada order yang sedang diproses.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>

    @if (session('watch_print_order_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const orderId = '{{ session('watch_print_order_id') }}';

                const statusUrl = "{{ route('cashier.incoming-orders.customer-receipt-status', ':order') }}"
                    .replace(':order', orderId);

                let attempts = 0;
                const maxAttempts = 30;

                const interval = setInterval(async function() {
                    attempts++;

                    try {
                        const response = await fetch(statusUrl, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            cache: 'no-store',
                        });

                        if (!response.ok) {
                            throw new Error('HTTP ' + response.status);
                        }

                        const data = await response.json();

                        if (data.can_complete) {
                            clearInterval(interval);
                            saveIncomingOrderPageState();
                            window.location.reload();
                            return;
                        }

                        if (attempts >= maxAttempts) {
                            clearInterval(interval);

                            if (data.latest_status === 'failed') {
                                alert('Struk gagal dicetak. Silakan cek printer atau aplikasi bridge.');
                            }
                        }
                    } catch (error) {
                        console.error('Print status polling error:', error);

                        if (attempts >= maxAttempts) {
                            clearInterval(interval);
                        }
                    }
                }, 2000);
            });
        </script>
    @endif
    @if (session('watch_kitchen_order_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const orderId = '{{ session('watch_kitchen_order_id') }}';

                const statusUrl = "{{ route('cashier.incoming-orders.kitchen-order-status', ':order') }}"
                    .replace(':order', orderId);

                let attempts = 0;
                const maxAttempts = 30;

                const interval = setInterval(async function() {
                    attempts++;

                    try {
                        const response = await fetch(statusUrl, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            cache: 'no-store',
                        });

                        if (!response.ok) {
                            throw new Error('HTTP ' + response.status);
                        }

                        const data = await response.json();

                        if (data.is_printed) {
                            clearInterval(interval);

                            if (typeof saveIncomingOrderPageState === 'function') {
                                saveIncomingOrderPageState();
                            }

                            window.location.reload();
                            return;
                        }

                        if (attempts >= maxAttempts) {
                            clearInterval(interval);

                            if (data.latest_status === 'failed') {
                                alert(
                                    'Kitchen order gagal dicetak. Silakan cek printer atau aplikasi bridge.'
                                );
                            }
                        }
                    } catch (error) {
                        console.error('Kitchen print status polling error:', error);

                        if (attempts >= maxAttempts) {
                            clearInterval(interval);
                        }
                    }
                }, 2000);
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('paymentProofModal');
            const image = document.getElementById('paymentProofImage');
            const title = document.getElementById('paymentProofTitle');

            const previewButtons = document.querySelectorAll('[data-proof-preview]');
            const closeButtons = document.querySelectorAll('[data-proof-close]');
            const backdrop = document.querySelector('[data-proof-backdrop]');
            const zoomInButton = document.querySelector('[data-proof-zoom-in]');
            const zoomOutButton = document.querySelector('[data-proof-zoom-out]');
            const resetButton = document.querySelector('[data-proof-reset]');

            let zoom = 1;

            function applyZoom() {
                image.style.width = `${zoom * 100}%`;
            }

            function openModal(src, modalTitle) {
                zoom = 1;
                image.src = src;
                title.textContent = modalTitle || 'Bukti Pembayaran';
                applyZoom();

                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
                image.src = '';
                document.body.classList.remove('overflow-hidden');
            }

            previewButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    openModal(button.dataset.proofSrc, button.dataset.proofTitle);
                });
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', closeModal);
            });

            backdrop.addEventListener('click', closeModal);

            zoomInButton.addEventListener('click', () => {
                zoom = Math.min(zoom + 0.25, 4);
                applyZoom();
            });

            zoomOutButton.addEventListener('click', () => {
                zoom = Math.max(zoom - 0.25, 0.5);
                applyZoom();
            });

            resetButton.addEventListener('click', () => {
                zoom = 1;
                applyZoom();
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
        const incomingOrderStateKey = 'cashier_incoming_orders_state';

        function getIncomingOrderPageState() {
            try {
                return JSON.parse(sessionStorage.getItem(incomingOrderStateKey)) || {};
            } catch (error) {
                return {};
            }
        }

        function saveIncomingOrderPageState() {
            const searchInput = document.getElementById('incoming-order-search');
            const scrollContainers = {};

            document.querySelectorAll('[data-preserve-scroll]').forEach((container) => {
                if (container.id) {
                    scrollContainers[container.id] = container.scrollTop;
                }
            });

            sessionStorage.setItem(incomingOrderStateKey, JSON.stringify({
                windowScrollY: window.scrollY,
                searchQuery: searchInput ? searchInput.value : '',
                scrollContainers: scrollContainers,
                savedAt: Date.now(),
            }));
        }

        document.addEventListener('DOMContentLoaded', () => {
            let currentSignature = @js($pollSignature);
            let isReloading = false;

            const searchInput = document.getElementById('incoming-order-search');
            const resetSearchButton = document.getElementById('incoming-order-search-reset');
            const searchInfo = document.getElementById('incoming-order-search-info');

            function normalizeText(value) {
                return String(value || '').toLowerCase().trim();
            }

            function filterOrderCards(keyword) {
                const query = normalizeText(keyword);
                const cards = document.querySelectorAll('[data-order-card]');
                let visibleCount = 0;

                cards.forEach((card) => {
                    const text = normalizeText(card.dataset.orderSearch);

                    if (query === '' || text.includes(query)) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                if (searchInfo) {
                    if (query === '') {
                        searchInfo.classList.add('hidden');
                        searchInfo.textContent = '';
                    } else {
                        searchInfo.classList.remove('hidden');
                        searchInfo.textContent = `${visibleCount} order cocok dengan pencarian.`;
                    }
                }
            }

            function restoreIncomingOrderPageState() {
                const state = getIncomingOrderPageState();

                if (searchInput && state.searchQuery) {
                    searchInput.value = state.searchQuery;
                    filterOrderCards(state.searchQuery);
                }

                if (state.scrollContainers) {
                    Object.entries(state.scrollContainers).forEach(([id, scrollTop]) => {
                        const container = document.getElementById(id);

                        if (container) {
                            container.scrollTop = Number(scrollTop) || 0;
                        }
                    });
                }

                if (typeof state.windowScrollY !== 'undefined') {
                    setTimeout(() => {
                        window.scrollTo(0, Number(state.windowScrollY) || 0);
                    }, 80);
                }
            }

            function isUserTypingSensitiveInput() {
                const active = document.activeElement;

                if (!active) {
                    return false;
                }

                if (['TEXTAREA', 'SELECT'].includes(active.tagName)) {
                    return true;
                }

                if (active.tagName === 'INPUT' && active.id !== 'incoming-order-search') {
                    return true;
                }

                return document.querySelector('details[open]') !== null;
            }

            async function checkIncomingOrders() {
                if (isReloading || isUserTypingSensitiveInput()) {
                    return;
                }

                try {
                    const response = await fetch('{{ route('cashier.incoming-orders.poll') }}', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        cache: 'no-store',
                    });

                    if (!response.ok) {
                        return;
                    }

                    const data = await response.json();

                    if (data.signature && data.signature !== currentSignature) {
                        isReloading = true;
                        saveIncomingOrderPageState();
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Gagal cek order masuk:', error);
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    filterOrderCards(searchInput.value);
                    saveIncomingOrderPageState();
                });
            }

            if (resetSearchButton) {
                resetSearchButton.addEventListener('click', () => {
                    if (!searchInput) return;

                    searchInput.value = '';
                    filterOrderCards('');
                    saveIncomingOrderPageState();
                });
            }

            document.querySelectorAll('[data-preserve-scroll]').forEach((container) => {
                container.addEventListener('scroll', saveIncomingOrderPageState, {
                    passive: true
                });
            });

            window.addEventListener('beforeunload', saveIncomingOrderPageState);

            restoreIncomingOrderPageState();

            document.querySelectorAll('[data-money-input]').forEach((input) => {
                input.addEventListener('input', () => {
                    let rawValue = input.value.replace(/\D/g, '');

                    if (rawValue === '') {
                        input.value = '';
                        return;
                    }

                    input.value = new Intl.NumberFormat('id-ID').format(Number(rawValue));
                });
            });

            setInterval(checkIncomingOrders, 5000);
        });
        document.addEventListener('alpine:init', () => {
            Alpine.data('cashPaymentModal', (config) => ({
                showPaymentModal: false,
                submitting: false,

                grandTotal: Number(config.grandTotal || 0),
                paidAmount: '',

                init() {
                    this.paidAmount = this.formatMoney(this.grandTotal);
                },

                openPaymentModal() {
                    this.paidAmount = this.formatMoney(this.grandTotal);
                    this.showPaymentModal = true;

                    this.$nextTick(() => {
                        this.$refs.paidAmountInput?.focus();
                    });
                },

                closePaymentModal() {
                    if (this.submitting) return;

                    this.showPaymentModal = false;
                },

                parseMoney(value) {
                    return Number(String(value || '').replace(/[^\d]/g, '')) || 0;
                },

                formatMoney(value) {
                    return new Intl.NumberFormat('id-ID').format(Number(value || 0));
                },

                formatPaidAmount() {
                    this.paidAmount = this.formatMoney(this.parseMoney(this.paidAmount));
                },

                changeAmount() {
                    return Math.max(this.parseMoney(this.paidAmount) - this.grandTotal, 0);
                },

                remainingAmount() {
                    return Math.max(this.grandTotal - this.parseMoney(this.paidAmount), 0);
                },
            }));
        });
    </script>
@endsection
