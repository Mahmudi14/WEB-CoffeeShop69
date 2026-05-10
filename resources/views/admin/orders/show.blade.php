@extends('layouts.master')

@section('title', 'Detail Order')
@section('header-title', 'Detail Order')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                    Order Detail
                </p>

                <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">
                    {{ $order->order_number }}
                </h2>

                <p class="mt-2 text-sm leading-6 text-stone-600">
                    Dibuat pada {{ $order->created_at->format('d M Y H:i') }}
                </p>
            </div>

            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                Kembali
            </a>
        </div>

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
                    <section class="rounded-3xl border border-rose-200 bg-rose-50 p-6 shadow-sm">
                        <h3 class="text-lg font-black text-rose-800">
                            Batalkan Order
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-rose-700">
                            Gunakan hanya untuk kesalahan operasional. Order yang dibatalkan tidak dihapus, hanya diubah
                            statusnya.
                        </p>

                        <form method="POST" action="{{ route('admin.orders.cancel', $order) }}" class="mt-5 space-y-3">
                            @csrf
                            @method('PATCH')

                            <textarea name="cancel_reason" rows="3" required
                                class="w-full rounded-2xl border border-rose-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
                                placeholder="Tulis alasan pembatalan...">{{ old('cancel_reason') }}</textarea>

                            <button type="submit" onclick="return confirm('Yakin ingin membatalkan order ini?')"
                                class="w-full rounded-2xl bg-rose-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-rose-600/20 transition hover:bg-rose-700">
                                Batalkan Order
                            </button>
                        </form>
                    </section>
                @endif
            </aside>
        </div>
    </div>
@endsection
