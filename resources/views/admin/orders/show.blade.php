@extends('layouts.master')

@section('title', 'Detail Order')
@section('header-title', 'Detail Order')

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
                            Order Detail
                        </span>
                    </div>

                    <h2 class="truncate text-2xl font-black tracking-tight text-white sm:text-3xl">
                        {{ $order->order_number }}
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-300">
                        Detail transaksi order, item pesanan, pembayaran, ringkasan total, dan informasi operasional order.
                    </p>

                    <p class="mt-1 text-xs font-bold text-stone-400">
                        Dibuat pada {{ $order->created_at->format('d M Y H:i') }}
                    </p>
                </div>

                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
                    <a href="{{ route('admin.orders.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-5 py-3 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                        Kembali
                    </a>
                </div>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1fr_380px]">
            <div class="space-y-6">
                <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-black text-stone-950">
                        Item Order
                    </h3>

                    <div class="mt-5 overflow-x-auto">
                        <table class="min-w-full divide-y divide-stone-200">
                            <thead class="bg-stone-100">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                        Item</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-black uppercase tracking-wider text-stone-500">
                                        Qty</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                        Harga</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                        Diskon</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                        Total</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-stone-100">
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <p class="text-sm font-black text-stone-900">
                                                {{ $item->menu_name }}
                                            </p>

                                            @if ($item->note)
                                                <p class="mt-1 text-xs font-semibold text-stone-500">
                                                    Catatan: {{ $item->note }}
                                                </p>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3 text-center text-sm font-black text-stone-900">
                                            {{ $item->quantity }}
                                        </td>

                                        <td class="px-4 py-3 text-right text-sm font-bold text-stone-700">
                                            Rp{{ number_format($item->normal_price, 0, ',', '.') }}
                                        </td>

                                        <td class="px-4 py-3 text-right text-sm font-bold text-rose-600">
                                            Rp{{ number_format($item->total_discount, 0, ',', '.') }}
                                        </td>

                                        <td class="px-4 py-3 text-right text-sm font-black text-stone-950">
                                            Rp{{ number_format($item->subtotal_after_discount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-black text-stone-950">
                        Pembayaran
                    </h3>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl bg-stone-50 p-4">
                            <p class="text-xs font-black uppercase tracking-wider text-stone-400">Metode</p>
                            <p class="mt-2 text-sm font-black text-stone-900">
                                {{ strtoupper($order->payment?->method ?? '-') }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-stone-50 p-4">
                            <p class="text-xs font-black uppercase tracking-wider text-stone-400">Status</p>
                            <p class="mt-2 text-sm font-black text-stone-900">
                                {{ str_replace('_', ' ', $order->payment?->status ?? $order->payment_status) }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-stone-50 p-4">
                            <p class="text-xs font-black uppercase tracking-wider text-stone-400">Dibayar</p>
                            <p class="mt-2 text-sm font-black text-stone-900">
                                Rp{{ number_format($order->payment?->paid_amount ?? 0, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-stone-50 p-4">
                            <p class="text-xs font-black uppercase tracking-wider text-stone-400">Kembalian</p>
                            <p class="mt-2 text-sm font-black text-stone-900">
                                Rp{{ number_format($order->payment?->change_amount ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-black text-stone-950">
                        Ringkasan
                    </h3>

                    <div class="mt-5 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="font-semibold text-stone-500">Subtotal</span>
                            <span
                                class="font-black text-stone-900">Rp{{ number_format($order->subtotal_before_discount, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="font-semibold text-stone-500">Diskon</span>
                            <span
                                class="font-black text-rose-600">-Rp{{ number_format($order->discount_total, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="font-semibold text-stone-500">Pajak {{ $order->tax_rate }}%</span>
                            <span
                                class="font-black text-stone-900">Rp{{ number_format($order->tax_total, 0, ',', '.') }}</span>
                        </div>

                        <div class="border-t border-stone-200 pt-3">
                            <div class="flex justify-between">
                                <span class="text-base font-black text-stone-950">Grand Total</span>
                                <span
                                    class="text-base font-black text-stone-950">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-black text-stone-950">
                        Informasi Order
                    </h3>

                    <div class="mt-5 space-y-3 text-sm">
                        <div>
                            <p class="text-xs font-black uppercase tracking-wider text-stone-400">Customer</p>
                            <p class="mt-1 font-black text-stone-900">{{ $order->customer_name }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-black uppercase tracking-wider text-stone-400">Meja</p>
                            <p class="mt-1 font-black text-stone-900">{{ $order->table?->name ?? 'Takeaway / Tanpa meja' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-black uppercase tracking-wider text-stone-400">Kasir</p>
                            <p class="mt-1 font-black text-stone-900">{{ $order->cashier?->name ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-black uppercase tracking-wider text-stone-400">Status Order</p>
                            <p class="mt-1 font-black text-stone-900">{{ str_replace('_', ' ', $order->order_status) }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-black uppercase tracking-wider text-stone-400">Status Pembayaran</p>
                            <p class="mt-1 font-black text-stone-900">{{ str_replace('_', ' ', $order->payment_status) }}
                            </p>
                        </div>
                    </div>
                </section>

                @if ($order->order_status === \App\Models\Order::STATUS_CANCELLED)
                    <section class="rounded-3xl border border-rose-200 bg-rose-50 p-6 shadow-sm">
                        <h3 class="text-lg font-black text-rose-800">
                            Order Dibatalkan
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-rose-700">
                            {{ $order->cancel_reason ?? 'Tidak ada alasan.' }}
                        </p>

                        <p class="mt-3 text-xs font-semibold text-rose-700">
                            Oleh: {{ $order->cancelledBy?->name ?? '-' }}
                            <br>
                            Pada: {{ $order->cancelled_at?->format('d M Y H:i') ?? '-' }}
                        </p>
                    </section>
                @elseif ($canCancel)
                    <section x-data="{ confirmOpen: @js($errors->has('cancel_reason')) }" class="rounded-3xl border border-rose-200 bg-rose-50 p-6 shadow-sm">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                            <div class="max-w-2xl">
                                <h3 class="text-lg font-black text-rose-800">
                                    Batalkan Order
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-rose-700">
                                    Gunakan hanya untuk kesalahan operasional. Order yang dibatalkan tidak dihapus, hanya
                                    diubah
                                    statusnya.
                                </p>
                            </div>

                            <button type="button" @click="confirmOpen = true"
                                class="inline-flex w-full items-center justify-center rounded-2xl border border-rose-300 bg-rose-600 px-5 py-3 text-sm font-black text-white transition hover:bg-rose-700 active:scale-[0.98] lg:w-auto">
                                Batalkan Order
                            </button>
                        </div>

                        {{-- Modal Confirmation --}}
                        <div x-cloak x-show="confirmOpen" x-transition.opacity @keydown.escape.window="confirmOpen = false"
                            class="fixed inset-0 z-[9999] flex items-center justify-center bg-stone-950/60 px-4">

                            <div x-show="confirmOpen" x-transition.scale.origin.center @click.outside="confirmOpen = false"
                                class="w-full max-w-md rounded-[2rem] border border-stone-200 bg-white p-6 shadow-2xl">

                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-100">
                                    <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                            d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                    </svg>
                                </div>

                                <h3 class="mt-5 text-lg font-black text-stone-950">
                                    Batalkan Order?
                                </h3>

                                <p class="mt-2 text-sm font-semibold leading-6 text-stone-500">
                                    Order <span class="font-black text-stone-900">{{ $order->order_number }}</span> akan
                                    dibatalkan.
                                    Status order akan berubah menjadi batal, tetapi data order tetap tersimpan di sistem.
                                </p>

                                <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                                    class="mt-5 space-y-4">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <label class="mb-2 block text-sm font-bold text-stone-700">
                                            Alasan Pembatalan
                                        </label>

                                        <textarea name="cancel_reason" rows="4" required
                                            class="w-full rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-100"
                                            placeholder="Tulis alasan pembatalan...">{{ old('cancel_reason') }}</textarea>

                                        @error('cancel_reason')
                                            <p class="mt-2 text-xs font-bold text-rose-600">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <p
                                        class="rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-xs font-bold leading-5 text-rose-700">
                                        Pastikan alasan pembatalan sudah benar karena informasi ini akan tersimpan pada
                                        riwayat order.
                                    </p>

                                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                        <button type="button" @click="confirmOpen = false"
                                            class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 transition hover:bg-stone-50">
                                            Batal
                                        </button>

                                        <button type="submit"
                                            class="inline-flex w-full items-center justify-center rounded-2xl bg-rose-600 px-5 py-3 text-sm font-black text-white transition hover:bg-rose-700 active:scale-[0.98] sm:w-auto">
                                            Ya, Batalkan Order
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                @endif
            </aside>
        </div>
    </div>
@endsection
