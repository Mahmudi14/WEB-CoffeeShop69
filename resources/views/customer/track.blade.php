@extends('layouts.customer', ['title' => 'Tracking Pesanan'])

@section('content')
    @php
        $tableToken = request('table');
    @endphp
    <div class="mx-auto flex min-h-screen max-w-md items-center px-4 py-8">
        <div class="w-full overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
            <div class="bg-[#15110f] px-6 py-6 text-white">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.06] px-3 py-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                    <p class="text-[10px] font-black uppercase tracking-[0.22em] text-amber-300">
                        Cafe 69
                    </p>
                </div>

                <h1 class="mt-4 text-2xl font-black tracking-tight">
                    Tracking Pesanan
                </h1>

                <p class="mt-2 text-sm leading-6 text-stone-300">
                    Masukkan nomor order untuk melihat status pesanan kamu.
                </p>
            </div>

            <div class="p-5">
                @if (session('error'))
                    <div
                        class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-700">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.orders.track.find') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="order_number" class="mb-2 block text-sm font-bold text-stone-700">
                            Nomor Order
                        </label>

                        <input id="order_number" name="order_number" type="text" value="{{ old('order_number') }}"
                            required placeholder="Contoh: ORD-20260504-0001" autocomplete="off"
                            class="h-12 w-full rounded-2xl border border-stone-200 bg-stone-50 px-4 text-center text-sm font-black uppercase tracking-[0.06em] text-stone-950 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">

                        @error('order_number')
                            <p class="mt-2 text-sm font-bold text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="flex h-12 w-full items-center justify-center rounded-2xl bg-[#1f1a17] px-5 text-sm font-black text-white shadow-sm transition hover:bg-[#2a231f] active:scale-[0.98]">
                        Cek Status
                    </button>
                </form>

                <div class="my-5 flex items-center gap-3">
                    <div class="h-px flex-1 bg-stone-200"></div>
                    <span class="text-[11px] font-bold uppercase tracking-[0.18em] text-stone-400">
                        atau
                    </span>
                    <div class="h-px flex-1 bg-stone-200"></div>
                </div>

                <div class="mt-3 grid gap-3 {{ $tableToken ? 'grid-cols-2' : 'grid-cols-1' }}">
                    @if ($tableToken)
                        <a href="{{ route('customer.qr.menu', ['qrToken' => $tableToken]) }}"
                            class="flex h-11 items-center justify-center rounded-2xl border border-stone-200 bg-white px-4 text-sm font-bold text-stone-700 transition hover:bg-stone-50 active:scale-[0.98]">
                            Kembali ke Menu
                        </a>
                    @endif

                    <button type="button" onclick="openLastOrder()" id="openLastOrderButton"
                        class="flex h-11 items-center justify-center rounded-2xl bg-[#1f1a17] px-4 text-sm font-black text-white shadow-sm transition hover:bg-[#2a231f] active:scale-[0.98]">
                        {{ $tableToken ? 'Pesanan Terakhir' : 'Buka Pesanan Terakhir' }}
                    </button>
                </div>

                <p class="mt-4 text-center text-xs font-semibold leading-5 text-stone-400">
                    Nomor order bisa dilihat pada halaman status setelah pesanan dibuat.
                </p>
                <div id="trackingNotice" class="mt-5 hidden rounded-2xl border px-4 py-3">
                    <div class="flex items-start gap-3">
                        <div id="trackingNoticeIcon"
                            class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl text-sm font-black">
                            !
                        </div>

                        <div class="min-w-0 flex-1">
                            <p id="trackingNoticeTitle" class="text-sm font-black">
                                Informasi
                            </p>

                            <p id="trackingNoticeMessage" class="mt-1 text-xs font-semibold leading-5">
                                Pesan notifikasi.
                            </p>
                        </div>

                        <button type="button" onclick="hideTrackingNotice()"
                            class="shrink-0 rounded-lg px-2 text-sm font-black opacity-60 transition hover:opacity-100">
                            ×
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const currentTableToken = @js($tableToken);

        const terminalStatuses = [
            @js(\App\Models\Order::STATUS_COMPLETED),
            @js(\App\Models\Order::STATUS_CANCELLED),
            @js(\App\Models\Order::STATUS_REJECTED),
            @js(\App\Models\Order::STATUS_EXPIRED),
        ];

        let noticeTimeout = null;

        function showTrackingNotice(type, title, message) {
            const notice = document.getElementById('trackingNotice');
            const icon = document.getElementById('trackingNoticeIcon');
            const titleEl = document.getElementById('trackingNoticeTitle');
            const messageEl = document.getElementById('trackingNoticeMessage');

            if (!notice || !icon || !titleEl || !messageEl) return;

            const styles = {
                error: {
                    box: 'mt-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700',
                    icon: 'mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-sm font-black text-rose-700',
                    symbol: '!',
                },
                info: {
                    box: 'mt-5 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800',
                    icon: 'mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-sm font-black text-amber-800',
                    symbol: 'i',
                },
                success: {
                    box: 'mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700',
                    icon: 'mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-sm font-black text-emerald-700',
                    symbol: '✓',
                },
            };

            const selected = styles[type] || styles.info;

            notice.className = selected.box;
            icon.className = selected.icon;
            icon.textContent = selected.symbol;
            titleEl.textContent = title;
            messageEl.textContent = message;

            notice.classList.remove('hidden');

            if (noticeTimeout) {
                clearTimeout(noticeTimeout);
            }

            noticeTimeout = setTimeout(() => {
                hideTrackingNotice();
            }, 3500);
        }

        function hideTrackingNotice() {
            const notice = document.getElementById('trackingNotice');

            if (notice) {
                notice.classList.add('hidden');
            }
        }

        function clearStoredLastOrder(tableToken) {
            if (!tableToken) return;

            localStorage.removeItem(`last_order_number_${tableToken}`);
            localStorage.removeItem(`last_order_status_url_${tableToken}`);
            localStorage.removeItem(`last_order_status_data_url_${tableToken}`);
        }

        async function openLastOrder() {
            const button = document.getElementById('openLastOrderButton');

            hideTrackingNotice();

            if (!currentTableToken) {
                showTrackingNotice(
                    'info',
                    'Scan QR Meja Dulu',
                    'Untuk membuka pesanan terakhir meja ini, silakan akses halaman tracking dari QR meja.'
                );
                return;
            }

            const statusUrl = localStorage.getItem(`last_order_status_url_${currentTableToken}`);
            const dataUrl = localStorage.getItem(`last_order_status_data_url_${currentTableToken}`);

            if (!statusUrl || !dataUrl) {
                showTrackingNotice(
                    'info',
                    'Tidak Ada Pesanan Aktif',
                    'Belum ada pesanan terakhir yang aktif untuk meja ini.'
                );
                return;
            }

            try {
                if (button) {
                    button.disabled = true;
                    button.textContent = 'Mengecek...';
                    button.classList.add('opacity-70');
                }

                const response = await fetch(dataUrl, {
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) {
                    clearStoredLastOrder(currentTableToken);

                    showTrackingNotice(
                        'error',
                        'Pesanan Tidak Ditemukan',
                        'Data pesanan terakhir sudah tidak tersedia. Masukkan nomor order secara manual jika diperlukan.'
                    );
                    return;
                }

                const data = await response.json();

                if (terminalStatuses.includes(data.order_status)) {
                    clearStoredLastOrder(currentTableToken);

                    showTrackingNotice(
                        'success',
                        'Pesanan Sudah Selesai',
                        'Tidak ada pesanan aktif terakhir untuk meja ini.'
                    );
                    return;
                }

                window.location.href = statusUrl;
            } catch (error) {
                showTrackingNotice(
                    'error',
                    'Gagal Mengecek Pesanan',
                    'Koneksi bermasalah. Coba lagi atau masukkan nomor order secara manual.'
                );
            } finally {
                if (button) {
                    button.disabled = false;
                    button.textContent = @js($tableToken ? 'Pesanan Terakhir' : 'Buka Pesanan Terakhir');
                    button.classList.remove('opacity-70');
                }
            }
        }
    </script>
@endsection
