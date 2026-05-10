@extends('layouts.customer', ['title' => 'Status Pesanan'])

@section('content')
    @php
        $orderStatusLabel = match ($order->order_status) {
            \App\Models\Order::STATUS_PENDING_PAYMENT => 'Menunggu pembayaran di kasir',
            \App\Models\Order::STATUS_PENDING_PAYMENT_VERIFICATION => 'Menunggu verifikasi pembayaran',
            \App\Models\Order::STATUS_PROCESSING => 'Pesanan sedang diproses',
            \App\Models\Order::STATUS_COMPLETED => 'Pesanan selesai',
            \App\Models\Order::STATUS_CANCELLED => 'Pesanan dibatalkan',
            \App\Models\Order::STATUS_REJECTED => 'Pesanan ditolak',
            \App\Models\Order::STATUS_EXPIRED => 'Pesanan kedaluwarsa',
            default => 'Status tidak diketahui',
        };

        $paymentStatusLabel = match ($order->payment_status) {
            \App\Models\Order::PAYMENT_UNPAID => 'Belum dibayar',
            \App\Models\Order::PAYMENT_PENDING_VERIFICATION => 'Menunggu verifikasi',
            \App\Models\Order::PAYMENT_PAID => 'Sudah dibayar',
            \App\Models\Order::PAYMENT_REJECTED => 'Pembayaran ditolak',
            \App\Models\Order::PAYMENT_VOIDED => 'Pembayaran dibatalkan',
            default => 'Status pembayaran tidak diketahui',
        };

        $failedStatuses = [
            \App\Models\Order::STATUS_CANCELLED,
            \App\Models\Order::STATUS_REJECTED,
            \App\Models\Order::STATUS_EXPIRED,
        ];

        $isFailed = in_array($order->order_status, $failedStatuses, true);
        $isCompleted = $order->order_status === \App\Models\Order::STATUS_COMPLETED;
        $isProcessing = $order->order_status === \App\Models\Order::STATUS_PROCESSING;

        $paymentStepTitle =
            $order->payment?->method === \App\Models\Payment::METHOD_CASH
                ? 'Pembayaran di Kasir'
                : 'Verifikasi Pembayaran';

        $paymentStepDescription =
            $order->payment?->method === \App\Models\Payment::METHOD_CASH
                ? 'Customer melakukan pembayaran tunai ke kasir.'
                : 'Kasir memeriksa bukti pembayaran QRIS / Transfer.';

        $paymentIsCurrent = in_array(
            $order->order_status,
            [\App\Models\Order::STATUS_PENDING_PAYMENT, \App\Models\Order::STATUS_PENDING_PAYMENT_VERIFICATION],
            true,
        );

        $paymentIsDone = in_array(
            $order->order_status,
            [\App\Models\Order::STATUS_PROCESSING, \App\Models\Order::STATUS_COMPLETED],
            true,
        );

        $timeline = [
            [
                'title' => 'Pesanan Dibuat',
                'description' => 'Order berhasil masuk ke sistem Cafe 69.',
                'state' => 'done',
            ],
            [
                'title' => $paymentStepTitle,
                'description' => $paymentStepDescription,
                'state' => $isFailed
                    ? 'failed'
                    : ($paymentIsDone
                        ? 'done'
                        : ($paymentIsCurrent
                            ? 'current'
                            : 'pending')),
            ],
            [
                'title' => 'Pesanan Diproses',
                'description' => 'Pesanan sedang disiapkan oleh kasir / kitchen.',
                'state' => $isFailed ? 'pending' : ($isCompleted ? 'done' : ($isProcessing ? 'current' : 'pending')),
            ],
            [
                'title' => 'Pesanan Selesai',
                'description' => 'Pesanan sudah selesai dan siap diterima customer.',
                'state' => $isCompleted ? 'done' : 'pending',
            ],
        ];

        if ($isFailed) {
            $timeline[] = [
                'title' => $orderStatusLabel,
                'description' => 'Pesanan tidak dapat dilanjutkan.',
                'state' => 'failed',
            ];
        }

        $iconClass = fn($state) => match ($state) {
            'done' => 'bg-emerald-500 text-white border-emerald-500',
            'current' => 'bg-amber-500 text-white border-amber-500 shadow-lg shadow-amber-500/20',
            'failed' => 'bg-rose-500 text-white border-rose-500',
            default => 'bg-white text-stone-400 border-stone-200',
        };

        $lineClass = fn($state) => match ($state) {
            'done' => 'bg-emerald-500',
            'failed' => 'bg-rose-300',
            default => 'bg-stone-200',
        };

        $textClass = fn($state) => match ($state) {
            'done' => 'text-stone-950',
            'current' => 'text-stone-950',
            'failed' => 'text-rose-700',
            default => 'text-stone-400',
        };
    @endphp

    <div class="min-h-screen bg-stone-50 px-3 py-4 sm:px-4 sm:py-6">
        <div class="mx-auto max-w-md">
            <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                {{-- Header --}}
                <div class="relative overflow-hidden bg-[#15110f] px-5 pb-5 pt-5 text-white sm:px-6">
                    <div
                        class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_34%),linear-gradient(135deg,rgba(255,255,255,0.07),transparent_48%)]">
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div
                                    class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.07] px-3 py-1.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>

                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-amber-300">
                                        Status Pesanan
                                    </p>
                                </div>

                                <h1 class="mt-3 text-2xl font-black tracking-tight text-white">
                                    Cafe 69
                                </h1>

                                <p class="mt-1 text-xs font-semibold text-stone-400">
                                    Pantau status pesanan kamu secara real-time.
                                </p>
                            </div>

                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-amber-400/25 bg-white/[0.07] text-lg font-black text-amber-300 shadow-lg shadow-black/10">
                                69
                            </div>
                        </div>

                        <div class="mt-5 rounded-[1.35rem] border border-white/10 bg-white/[0.08] p-4 backdrop-blur-sm">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-stone-400">
                                Nomor Order
                            </p>

                            <div class="mt-2 flex items-center justify-between gap-3">
                                <p id="orderNumberText"
                                    class="min-w-0 break-all text-lg font-black leading-tight text-white">
                                    {{ $order->order_number }}
                                </p>

                                <button type="button" onclick="copyOrderNumber()" id="copyOrderButton"
                                    class="shrink-0 rounded-xl bg-amber-500 px-3.5 py-2 text-xs font-black text-stone-950 transition hover:bg-amber-400 active:scale-[0.97]">
                                    Salin
                                </button>
                            </div>

                            <p id="copyOrderFeedback" class="mt-2 hidden text-xs font-bold text-emerald-300">
                                Nomor order berhasil disalin.
                            </p>
                        </div>

                        <div class="mt-4 mb-2 grid grid-cols-2 gap-3">
                            <div class="rounded-2xl border border-white/10 bg-white/[0.07] px-4 py-3">
                                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-stone-500">
                                    Meja
                                </p>

                                <div class="mt-1.5 flex items-center gap-2">
                                    <span
                                        class="h-2 w-2 rounded-full bg-emerald-400 shadow-sm shadow-emerald-400/40"></span>

                                    <p class="truncate text-sm font-black text-white">
                                        {{ $order->table?->name ?? 'Tidak tersedia' }}
                                    </p>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-white/[0.07] px-4 py-3 text-right">
                                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-stone-500">
                                    Waktu Order
                                </p>

                                <p class="mt-1.5 text-sm font-black text-white">
                                    {{ $order->created_at?->format('H:i') }}
                                </p>

                                <p class="mt-0.5 text-[11px] font-semibold text-stone-400">
                                    {{ $order->created_at?->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-5">
                    {{-- Current Status --}}
                    <section class="rounded-[1.5rem] border border-amber-200 bg-amber-50 p-5">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-amber-700">
                            Status Saat Ini
                        </p>

                        <p id="orderStatusLabel" class="mt-2 text-xl font-black leading-tight text-stone-950">
                            {{ $orderStatusLabel }}
                        </p>

                        <p class="mt-2 text-xs font-semibold leading-5 text-amber-800">
                            Halaman ini akan memperbarui status otomatis.
                        </p>
                    </section>

                    {{-- Timeline --}}
                    <section class="mt-5 rounded-[1.5rem] border border-stone-200 bg-white p-5 shadow-sm">
                        <div class="mb-5 flex items-center justify-between">
                            <h2 class="text-sm font-black text-stone-950">
                                Timeline Pesanan
                            </h2>

                            <span class="rounded-full bg-stone-100 px-3 py-1 text-[11px] font-black text-stone-500">
                                Live
                            </span>
                        </div>

                        <div class="space-y-0">
                            @foreach ($timeline as $index => $step)
                                @php
                                    $isLast = $loop->last;
                                @endphp

                                <div class="relative flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="relative z-10 flex h-9 w-9 items-center justify-center rounded-full border-2 text-sm font-black {{ $iconClass($step['state']) }}">
                                            @if ($step['state'] === 'done')
                                                ✓
                                            @elseif ($step['state'] === 'failed')
                                                ×
                                            @elseif ($step['state'] === 'current')
                                                !
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </div>

                                        @unless ($isLast)
                                            <div class="h-12 w-0.5 {{ $lineClass($step['state']) }}"></div>
                                        @endunless
                                    </div>

                                    <div class="{{ $isLast ? 'pb-0' : 'pb-5' }} min-w-0 flex-1">
                                        <p class="text-sm font-black {{ $textClass($step['state']) }}">
                                            {{ $step['title'] }}
                                        </p>

                                        <p class="mt-1 text-xs font-semibold leading-5 text-stone-500">
                                            {{ $step['description'] }}
                                        </p>

                                        @if ($step['state'] === 'current')
                                            <span
                                                class="mt-2 inline-flex rounded-full bg-amber-100 px-3 py-1 text-[11px] font-black text-amber-700">
                                                Sedang berlangsung
                                            </span>
                                        @elseif ($step['state'] === 'done')
                                            <span
                                                class="mt-2 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-black text-emerald-700">
                                                Selesai
                                            </span>
                                        @elseif ($step['state'] === 'failed')
                                            <span
                                                class="mt-2 inline-flex rounded-full bg-rose-100 px-3 py-1 text-[11px] font-black text-rose-700">
                                                Dihentikan
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Payment --}}
                    <section class="mt-4 rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.18em] text-stone-500">
                                    Pembayaran
                                </p>

                                <p id="paymentStatusLabel" class="mt-2 text-base font-black text-stone-950">
                                    {{ $paymentStatusLabel }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-white px-4 py-2 text-right shadow-sm">
                                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-stone-400">
                                    Metode
                                </p>

                                <p class="mt-1 text-sm font-black text-amber-700">
                                    {{ strtoupper($order->payment?->method ?? '-') }}
                                </p>
                            </div>
                        </div>

                        <p class="mt-4 text-xs font-semibold text-stone-400">
                            Terakhir diperbarui:
                            <span id="updatedAt">{{ $order->updated_at?->format('d M Y H:i') }}</span>
                        </p>
                    </section>

                    {{-- Items --}}
                    <section class="mt-5">
                        <div class="mb-3 flex items-center justify-between">
                            <h2 class="text-sm font-black text-stone-950">
                                Detail Pesanan
                            </h2>

                            <span class="text-xs font-bold text-stone-400">
                                {{ $order->items->sum('quantity') }} item
                            </span>
                        </div>

                        <div class="space-y-2.5">
                            @foreach ($order->items as $item)
                                <div class="rounded-2xl border border-stone-100 bg-white p-4 shadow-sm">
                                    <div class="flex justify-between gap-4">
                                        <div class="min-w-0">
                                            <p class="line-clamp-1 text-sm font-black text-stone-950">
                                                {{ $item->menu_name }}
                                            </p>

                                            <p class="mt-1 text-xs font-semibold text-stone-500">
                                                {{ $item->quantity }} x
                                                Rp{{ number_format($item->final_price, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <p class="shrink-0 text-sm font-black text-stone-950">
                                            Rp{{ number_format($item->subtotal_after_discount, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    @if ($item->note)
                                        <p
                                            class="mt-3 rounded-xl bg-stone-50 px-3 py-2 text-xs font-semibold text-stone-500">
                                            Catatan: {{ $item->note }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Total --}}
                    <section class="mt-5 rounded-[1.5rem] bg-[#15110f] p-5 text-white">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.18em] text-stone-400">
                                    Total Bayar
                                </p>

                                <p class="mt-2 text-2xl font-black text-amber-400">
                                    Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                </p>
                            </div>

                            <div
                                class="rounded-2xl border border-white/10 bg-white/[0.06] px-3 py-2 text-xs font-bold text-stone-300">
                                {{ $order->items->count() }} menu
                            </div>
                        </div>
                    </section>

                    {{-- Actions --}}
                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <a href="{{ $order->table ? route('customer.orders.track', ['table' => $order->table->qr_token]) : route('customer.orders.track') }}"
                            class="flex h-11 items-center justify-center rounded-2xl border border-stone-200 bg-white px-4 text-sm font-bold text-stone-700 transition hover:bg-stone-50 active:scale-[0.98]">
                            Tracking
                        </a>

                        @if ($order->table)
                            <a href="{{ route('customer.qr.menu', $order->table->qr_token) }}"
                                class="flex h-11 items-center justify-center rounded-2xl bg-[#1f1a17] px-4 text-sm font-black text-white transition hover:bg-[#2a231f] active:scale-[0.98]">
                                Menu
                            </a>
                        @else
                            <button type="button" disabled
                                class="flex h-11 items-center justify-center rounded-2xl bg-stone-200 px-4 text-sm font-black text-stone-400">
                                Menu
                            </button>
                        @endif
                    </div>

                    @unless ($isCompleted || $isFailed)
                        <p class="mt-4 text-center text-xs font-semibold leading-5 text-stone-400">
                            Jangan tutup halaman ini jika ingin melihat perubahan status secara otomatis.
                        </p>
                    @endunless
                </div>
            </div>
        </div>
    </div>

    <script>
        const statusUrl = "{{ route('customer.orders.status.data', ['order' => $order->order_number]) }}";
        const orderTableToken = @js($order->table?->qr_token);
        const orderNumber = @js($order->order_number);
        const orderStatusUrl = "{{ route('customer.orders.status', ['order' => $order->order_number]) }}";

        const terminalStatuses = [
            @js(\App\Models\Order::STATUS_COMPLETED),
            @js(\App\Models\Order::STATUS_CANCELLED),
            @js(\App\Models\Order::STATUS_REJECTED),
            @js(\App\Models\Order::STATUS_EXPIRED),
        ];

        let currentOrderStatus = @js($order->order_status);
        let currentPaymentStatus = @js($order->payment_status);

        function saveLastOrder() {
            if (!orderTableToken) return;

            if (terminalStatuses.includes(currentOrderStatus)) {
                clearLastOrder();
                return;
            }

            localStorage.setItem(`last_order_number_${orderTableToken}`, orderNumber);
            localStorage.setItem(`last_order_status_url_${orderTableToken}`, orderStatusUrl);
            localStorage.setItem(`last_order_status_data_url_${orderTableToken}`, statusUrl);
        }

        function clearLastOrder() {
            if (!orderTableToken) return;

            localStorage.removeItem(`last_order_number_${orderTableToken}`);
            localStorage.removeItem(`last_order_status_url_${orderTableToken}`);
            localStorage.removeItem(`last_order_status_data_url_${orderTableToken}`);

            const globalNumber = localStorage.getItem('last_order_number');

            if (globalNumber === orderNumber) {
                localStorage.removeItem('last_order_number');
                localStorage.removeItem('last_order_status_url');
                localStorage.removeItem('last_order_status_data_url');
            }
        }

        saveLastOrder();

        @if (session('clear_customer_cart') && $order->table)
            localStorage.removeItem(`customer_cart_${orderTableToken}`);
        @endif

        function copyOrderNumber() {
            const orderNumberText = document.getElementById('orderNumberText')?.textContent.trim();
            const feedback = document.getElementById('copyOrderFeedback');
            const button = document.getElementById('copyOrderButton');

            if (!orderNumberText) return;

            navigator.clipboard.writeText(orderNumberText).then(() => {
                if (button) button.textContent = 'Tersalin';
                if (feedback) feedback.classList.remove('hidden');

                setTimeout(() => {
                    if (button) button.textContent = 'Salin';
                    if (feedback) feedback.classList.add('hidden');
                }, 1800);
            }).catch(() => {
                alert('Gagal menyalin nomor order. Silakan salin manual.');
            });
        }

        async function refreshOrderStatus() {
            try {
                const response = await fetch(statusUrl, {
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) return;

                const data = await response.json();

                if (terminalStatuses.includes(data.order_status)) {
                    clearLastOrder();
                }

                if (
                    data.order_status !== currentOrderStatus ||
                    data.payment_status !== currentPaymentStatus
                ) {
                    window.location.reload();
                    return;
                }

                const orderStatusLabel = document.getElementById('orderStatusLabel');
                const paymentStatusLabel = document.getElementById('paymentStatusLabel');
                const updatedAt = document.getElementById('updatedAt');

                if (orderStatusLabel) {
                    orderStatusLabel.textContent = data.order_status_label;
                }

                if (paymentStatusLabel) {
                    paymentStatusLabel.textContent = data.payment_status_label;
                }

                if (updatedAt) {
                    updatedAt.textContent = data.updated_at;
                }
            } catch (error) {
                console.error('Gagal mengambil status pesanan:', error);
            }
        }

        setInterval(refreshOrderStatus, 5000);
    </script>
@endsection
