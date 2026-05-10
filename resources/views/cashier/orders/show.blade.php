@extends('layouts.master')

@section('title', 'Detail Order')
@section('header-title', 'Detail Order')

@section('content')
    @php
        $customerReceiptPending = $order->printJobs
            ->where('type', \App\Models\PrintJob::TYPE_CUSTOMER_RECEIPT)
            ->whereIn('status', [\App\Models\PrintJob::STATUS_PENDING, \App\Models\PrintJob::STATUS_PRINTING])
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
                            Order Detail
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        {{ $order->order_number }}
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-stone-300">
                        Detail transaksi, pembayaran, item, dan status cetak.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:w-auto">
                    <a href="{{ route('cashier.orders.index') }}"
                        class="inline-flex h-[52px] items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-5 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                        Kembali
                    </a>
                    <form method="POST" action="{{ route('cashier.orders.print-customer-receipt', $order) }}">
                        @csrf

                        <button type="submit" @disabled($customerReceiptPending)
                            class="{{ $customerReceiptPending
                                ? 'cursor-not-allowed bg-stone-300 text-stone-500'
                                : 'bg-amber-500 text-stone-950 shadow-lg shadow-amber-500/25 hover:bg-amber-400' }} inline-flex h-[52px] w-full items-center justify-center rounded-2xl px-5 text-sm font-black transition active:scale-[0.98]">
                            {{ $customerReceiptPending ? 'Dalam Antrean' : 'Cetak Struk' }}
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-stone-500">Customer</p>
                <p class="mt-3 text-xl font-black text-stone-950">
                    {{ $order->customer_name }}
                </p>
                <p class="mt-1 text-xs text-stone-500">
                    {{ $order->table?->name ?? 'Takeaway / Tanpa meja' }}
                </p>
            </div>

            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-stone-500">Status Order</p>
                <p class="mt-3 inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-black text-sky-700">
                    {{ $order->order_status }}
                </p>
            </div>

            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-stone-500">Status Payment</p>
                <p class="mt-3 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                    {{ $order->payment_status }}
                </p>
            </div>

            <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-amber-700">Total Bayar</p>
                <p class="mt-3 text-2xl font-black text-amber-800">
                    Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                </p>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <div class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm xl:col-span-2">
                <div class="border-b border-stone-200 px-6 py-5">
                    <h3 class="text-lg font-black text-stone-950">
                        Item Order
                    </h3>
                    <p class="mt-1 text-sm text-stone-500">
                        Harga dan promo disimpan sebagai snapshot transaksi.
                    </p>
                </div>

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
                                <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Harga Normal
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Diskon
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-black text-stone-950">
                                            {{ $item->menu_name }}
                                        </p>

                                        @if ($item->note)
                                            <p class="mt-1 text-xs text-stone-500">
                                                Catatan: {{ $item->note }}
                                            </p>
                                        @endif

                                        @if ($item->promotions->isNotEmpty())
                                            <div class="mt-2 space-y-1">
                                                @foreach ($item->promotions->sortBy('applied_order') as $promotion)
                                                    <p class="text-xs font-semibold text-emerald-700">
                                                        {{ $promotion->promotion_name }}
                                                        -
                                                        Rp{{ number_format($promotion->discount_amount_total, 0, ',', '.') }}
                                                    </p>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-center text-sm font-black text-stone-800">
                                        {{ $item->quantity }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-bold text-stone-700">
                                        Rp{{ number_format($item->normal_price, 0, ',', '.') }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-black text-emerald-700">
                                        -Rp{{ number_format($item->total_discount, 0, ',', '.') }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-black text-stone-950">
                                        Rp{{ number_format($item->subtotal_after_discount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-black text-stone-950">
                        Ringkasan Pembayaran
                    </h3>

                    <div class="mt-5 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="font-bold text-stone-500">Metode</span>
                            <span class="font-black text-stone-950">
                                {{ strtoupper($order->payment?->method ?? '-') }}
                            </span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="font-bold text-stone-500">Subtotal Normal</span>
                            <span class="font-black text-stone-950">
                                Rp{{ number_format($order->subtotal_before_discount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="font-bold text-stone-500">Diskon Promo</span>
                            <span class="font-black text-emerald-700">
                                -Rp{{ number_format($order->discount_total, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="font-bold text-stone-500">Subtotal Bersih</span>
                            <span class="font-black text-stone-950">
                                Rp{{ number_format($order->subtotal_after_discount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="font-bold text-stone-500">PPN {{ $order->tax_rate }}%</span>
                            <span class="font-black text-stone-950">
                                Rp{{ number_format($order->tax_total, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between rounded-2xl bg-amber-50 px-4 py-4">
                            <span class="text-sm font-black text-amber-800">Grand Total</span>
                            <span class="text-xl font-black text-amber-800">
                                Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                            </span>
                        </div>

                        @if ($order->payment?->method === \App\Models\Payment::METHOD_CASH)
                            <div class="flex justify-between text-sm">
                                <span class="font-bold text-stone-500">Uang Diterima</span>
                                <span class="font-black text-stone-950">
                                    Rp{{ number_format($order->payment->paid_amount, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="font-bold text-stone-500">Kembalian</span>
                                <span class="font-black text-stone-950">
                                    Rp{{ number_format($order->payment->change_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </section>

                <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-black text-stone-950">
                        Status Print
                    </h3>

                    <div class="mt-5 space-y-3">
                        <div class="rounded-2xl bg-stone-50 p-4">
                            <p class="text-sm font-black text-stone-800">
                                Kitchen Order
                            </p>
                            <p class="mt-1 text-xs font-semibold text-stone-500">
                                {{ $kitchenPrint?->status ?? 'Belum ada print job' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-stone-50 p-4">
                            <p class="text-sm font-black text-stone-800">
                                Struk Customer
                            </p>

                            @if ($customerReceiptPending)
                                <p class="mt-1 text-xs font-semibold text-amber-700">
                                    Masih dalam antrean cetak.
                                </p>
                            @elseif ($customerReceiptPrintedCount > 0)
                                <p class="mt-1 text-xs font-semibold text-emerald-700">
                                    Pernah dicetak {{ $customerReceiptPrintedCount }} kali.
                                </p>
                            @else
                                <p class="mt-1 text-xs font-semibold text-stone-500">
                                    Belum dicetak.
                                </p>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </div>
@endsection
