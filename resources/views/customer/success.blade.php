@extends('layouts.customer', ['title' => 'Order Berhasil'])

@section('content')
    <div class="mx-auto flex min-h-screen max-w-md items-center px-4 py-8">
        <div class="w-full rounded-3xl border border-stone-200 bg-white p-6 text-center shadow-sm">
            <div
                class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-emerald-100 text-2xl font-black text-emerald-700">
                ✓
            </div>

            <h1 class="mt-5 text-3xl font-black text-stone-950">
                Order Berhasil Dibuat
            </h1>

            <p class="mt-2 text-sm text-stone-500">
                Nomor order kamu:
            </p>

            <p class="mt-3 rounded-2xl bg-stone-100 px-4 py-3 text-xl font-black text-stone-950">
                {{ $order->order_number }}
            </p>

            <div class="mt-6 rounded-2xl bg-amber-50 p-4 text-left">
                @if ($order->payment?->method === \App\Models\Payment::METHOD_CASH)
                    <p class="text-sm font-black text-amber-800">
                        Silakan datang ke kasir untuk membayar.
                    </p>
                    <p class="mt-1 text-xs font-semibold text-amber-700">
                        Sebutkan nomor order di atas agar pesanan bisa diproses.
                    </p>
                @else
                    <p class="text-sm font-black text-amber-800">
                        Bukti pembayaran berhasil dikirim.
                    </p>
                    <p class="mt-1 text-xs font-semibold text-amber-700">
                        Pesanan akan diproses setelah kasir memverifikasi pembayaran.
                    </p>
                @endif
            </div>

            <div class="mt-6 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="font-bold text-stone-500">Meja</span>
                    <span class="font-black text-stone-950">{{ $table->name }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="font-bold text-stone-500">Metode</span>
                    <span class="font-black text-stone-950">{{ strtoupper($order->payment?->method) }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="font-bold text-stone-500">Total</span>
                    <span class="font-black text-stone-950">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ route('customer.qr.menu', $table->qr_token) }}"
                class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-amber-600 px-5 py-3 text-sm font-black text-white">
                Kembali ke Menu
            </a>
        </div>
    </div>
@endsection
