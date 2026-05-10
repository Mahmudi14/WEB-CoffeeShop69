@extends('layouts.master')
@section('title', 'Tutup Shift')
@section('header-title', 'Tutup Shift')

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
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-3 py-1.5">
                        <span class="{{ $canCloseShift ? 'bg-emerald-400' : 'bg-rose-400' }} h-2 w-2 rounded-full"></span>

                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Cashier Shift
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Tutup Shift
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-stone-300">
                        Audit sesi kasir sebelum shift ditutup.
                    </p>
                </div>
            </div>
        </section>

        {{-- Status Shift --}}
        <section class="rounded-[2rem] border border-stone-100 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-6 flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-stone-400">
                        Sesi Aktif
                    </p>

                    <h2 class="mt-2 text-2xl font-black text-stone-950">
                        {{ auth()->user()->name }}
                    </h2>

                    <p class="mt-1 text-sm font-semibold text-stone-500">
                        Dibuka {{ $activeShift->opened_at->format('d M Y, H:i') }}
                    </p>
                </div>

                <span
                    class="{{ $canCloseShift
                        ? 'border-emerald-100 bg-emerald-50 text-emerald-700'
                        : 'border-rose-100 bg-rose-50 text-rose-700' }} inline-flex w-fit items-center gap-2 rounded-full border px-4 py-2 text-xs font-black uppercase tracking-wider">
                    <span class="{{ $canCloseShift ? 'bg-emerald-500' : 'bg-rose-500' }} h-2 w-2 rounded-full"></span>
                    {{ $canCloseShift ? 'Shift Aman' : 'Ada Pesanan Aktif' }}
                </span>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-[2rem] border border-stone-100 bg-stone-50 p-5">
                    <p class="text-xs font-black uppercase tracking-widest text-stone-400">
                        Jam Buka
                    </p>

                    <p class="mt-2 text-3xl font-black text-stone-950">
                        {{ $activeShift->opened_at->format('H:i') }}
                    </p>

                    <p class="mt-1 text-xs font-semibold text-stone-500">
                        {{ $activeShift->opened_at->translatedFormat('d F Y') }}
                    </p>
                </div>

                <div class="rounded-[2rem] border border-stone-100 bg-stone-50 p-5">
                    <p class="text-xs font-black uppercase tracking-widest text-stone-400">
                        Durasi Shift
                    </p>

                    <p class="mt-2 text-2xl font-black text-stone-950">
                        {{ $activeShift->opened_at->diffForHumans(now(), true) }}
                    </p>

                    <p class="mt-1 text-xs font-semibold text-stone-500">
                        Sampai {{ now()->format('H:i') }}
                    </p>
                </div>

                <div
                    class="{{ $canCloseShift ? 'border-emerald-100 bg-emerald-50' : 'border-rose-100 bg-rose-50' }} rounded-[2rem] border p-5">
                    <p
                        class="{{ $canCloseShift ? 'text-emerald-700' : 'text-rose-700' }} text-xs font-black uppercase tracking-widest">
                        Validasi Tutup Shift
                    </p>

                    <p class="{{ $canCloseShift ? 'text-emerald-800' : 'text-rose-800' }} mt-2 text-xl font-black">
                        {{ $canCloseShift ? 'Bisa Ditutup' : 'Belum Bisa Ditutup' }}
                    </p>

                    <p
                        class="{{ $canCloseShift ? 'text-emerald-700/80' : 'text-rose-700/80' }} mt-1 text-xs font-semibold leading-5">
                        @if ($canCloseShift)
                            Tidak ada pesanan aktif.
                        @else
                            Masih ada {{ $unfinishedOrdersCount }} pesanan aktif.
                        @endif
                    </p>
                </div>
            </div>
        </section>

        {{-- Aksi --}}
        <section class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
            @if (!$canCloseShift)
                <div class="rounded-[2rem] border border-rose-100 bg-rose-50 p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-black text-rose-800">
                                Shift belum bisa ditutup
                            </h3>

                            <p class="mt-1 text-sm font-semibold text-rose-700/80">
                                Selesaikan {{ $unfinishedOrdersCount }} pesanan aktif terlebih dahulu.
                            </p>
                        </div>

                        <a href="{{ route('cashier.incoming-orders.index') }}"
                            class="inline-flex h-[52px] items-center justify-center rounded-2xl bg-rose-600 px-5 text-sm font-black text-white shadow-lg shadow-rose-600/20 transition hover:bg-rose-700 active:scale-[0.98]">
                            Lihat Pesanan Aktif
                        </a>
                    </div>
                </div>
            @else
                <div class="grid gap-3 sm:grid-cols-2">
                    <a href="{{ route('cashier.shift-summary.index') }}"
                        class="flex h-[52px] w-full items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98]">
                        Lihat Ringkasan
                    </a>

                    <button type="button" onclick="openEndShiftModal()"
                        class="flex h-[52px] w-full items-center justify-center rounded-2xl bg-rose-600 px-5 text-sm font-black text-white shadow-lg shadow-rose-600/20 transition hover:bg-rose-700 active:scale-[0.98]">
                        Tutup Shift
                    </button>
                </div>
            @endif
        </section>
    </div>

    {{-- Modal Detail Tutup Shift --}}
    <div id="endShiftModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-stone-950/60 px-4 py-6 backdrop-blur-sm">
        <div
            class="flex max-h-[92vh] w-full max-w-5xl flex-col overflow-hidden rounded-[2rem] border border-white/20 bg-white shadow-2xl">
            <div class="flex items-start justify-between gap-4 border-b border-stone-100 p-6">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                        Final Shift Report
                    </p>

                    <h3 class="mt-2 text-2xl font-black text-stone-900">
                        Detail Penutupan Shift
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-stone-500">
                        Periksa data akhir sebelum menutup shift.
                    </p>
                </div>

                <button type="button" onclick="closeEndShiftModal()"
                    class="flex h-10 w-10 items-center justify-center rounded-2xl bg-stone-100 text-xl font-black text-stone-500 transition hover:bg-rose-100 hover:text-rose-600">
                    ×
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                            Nama Kasir
                        </p>

                        <p class="mt-2 text-sm font-black text-stone-950">
                            {{ auth()->user()->name }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                            Tanggal
                        </p>

                        <p class="mt-2 text-sm font-black text-stone-950">
                            {{ now()->translatedFormat('d F Y') }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                            Jam Buka
                        </p>

                        <p class="mt-2 text-sm font-black text-stone-950">
                            {{ $activeShift->opened_at->format('H:i') }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                            Jam Tutup
                        </p>

                        <p class="mt-2 text-sm font-black text-stone-950">
                            {{ now()->format('H:i') }}
                        </p>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl bg-emerald-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wider text-emerald-700">
                            Pemasukan Tunai
                        </p>

                        <p class="mt-2 text-xl font-black text-emerald-800">
                            Rp{{ number_format($summary['cash_sales'], 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-sky-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wider text-sky-700">
                            Pemasukan Non Tunai
                        </p>

                        <p class="mt-2 text-xl font-black text-sky-800">
                            Rp{{ number_format($summary['qris_sales'] + $summary['transfer_sales'], 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-rose-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wider text-rose-700">
                            Pengeluaran
                        </p>

                        <p class="mt-2 text-xl font-black text-rose-800">
                            Rp{{ number_format($summary['expense_total'], 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-amber-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wider text-amber-700">
                            Estimasi Kas Laci
                        </p>

                        <p class="mt-2 text-xl font-black text-amber-800">
                            Rp{{ number_format($summary['estimated_cash'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 lg:grid-cols-2">
                    <div class="overflow-hidden rounded-3xl border border-stone-200">
                        <div class="border-b border-stone-200 bg-stone-50 px-5 py-4">
                            <h4 class="text-base font-black text-stone-950">
                                Detail Item Terjual
                            </h4>
                        </div>

                        @if ($soldItems->isEmpty())
                            <div class="p-5 text-center text-sm font-bold text-stone-500">
                                Belum ada item terjual.
                            </div>
                        @else
                            <div class="max-h-80 overflow-y-auto">
                                <table class="min-w-full divide-y divide-stone-200">
                                    <thead class="bg-white">
                                        <tr>
                                            <th
                                                class="px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-stone-400">
                                                Item
                                            </th>
                                            <th
                                                class="px-5 py-3 text-center text-xs font-black uppercase tracking-wider text-stone-400">
                                                Qty
                                            </th>
                                            <th
                                                class="px-5 py-3 text-right text-xs font-black uppercase tracking-wider text-stone-400">
                                                Harga
                                            </th>
                                            <th
                                                class="px-5 py-3 text-right text-xs font-black uppercase tracking-wider text-stone-400">
                                                Total
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-stone-100">
                                        @foreach ($soldItems as $item)
                                            <tr>
                                                <td class="px-5 py-3 text-sm font-bold text-stone-800">
                                                    {{ $item->menu_name }}
                                                </td>

                                                <td class="px-5 py-3 text-center text-sm font-black text-stone-800">
                                                    {{ $item->total_quantity }}
                                                </td>

                                                <td class="px-5 py-3 text-right text-sm font-bold text-stone-600">
                                                    Rp{{ number_format($item->average_price, 0, ',', '.') }}
                                                </td>

                                                <td class="px-5 py-3 text-right text-sm font-black text-stone-950">
                                                    Rp{{ number_format($item->total_after_discount, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div class="overflow-hidden rounded-3xl border border-stone-200">
                        <div class="border-b border-stone-200 bg-stone-50 px-5 py-4">
                            <h4 class="text-base font-black text-stone-950">
                                Detail Pengeluaran
                            </h4>
                        </div>

                        @if ($expenses->isEmpty())
                            <div class="p-5 text-center text-sm font-bold text-stone-500">
                                Tidak ada pengeluaran.
                            </div>
                        @else
                            <div class="max-h-80 overflow-y-auto">
                                <table class="min-w-full divide-y divide-stone-200">
                                    <thead class="bg-white">
                                        <tr>
                                            <th
                                                class="px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-stone-400">
                                                Pengeluaran
                                            </th>
                                            <th
                                                class="px-5 py-3 text-right text-xs font-black uppercase tracking-wider text-stone-400">
                                                Nominal
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-stone-100">
                                        @foreach ($expenses as $expense)
                                            <tr>
                                                <td class="px-5 py-3">
                                                    <p class="text-sm font-black text-stone-800">
                                                        {{ $expense->category }}
                                                    </p>

                                                    <p class="mt-1 text-xs font-semibold text-stone-500">
                                                        {{ $expense->created_at->format('H:i') }}
                                                        @if ($expense->note)
                                                            • {{ $expense->note }}
                                                        @endif
                                                    </p>
                                                </td>

                                                <td class="px-5 py-3 text-right text-sm font-black text-rose-600">
                                                    Rp{{ number_format($expense->amount, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <form id="closeShiftForm" action="{{ route('cashier.shifts.close.store') }}" method="POST"
                    class="mt-6">
                    @csrf

                    <label for="closing_note" class="mb-2 block text-sm font-bold text-stone-700">
                        Catatan Penutupan Shift
                    </label>

                    <textarea id="closing_note" name="closing_note" rows="3"
                        class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                        placeholder="Opsional. Contoh: shift berjalan normal, stok aman, tidak ada kendala.">{{ old('closing_note') }}</textarea>
                </form>
            </div>

            <div class="flex flex-col gap-3 border-t border-stone-100 bg-white p-6 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeEndShiftModal()"
                    class="rounded-2xl border border-stone-200 px-5 py-3 text-sm font-black text-stone-600 transition hover:bg-stone-50">
                    Batal
                </button>

                <button type="button" onclick="openFinalCloseShiftModal()"
                    class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700 active:scale-[0.98]">
                    Konfirmasi Tutup Shift
                </button>
            </div>
        </div>
    </div>
    <div id="finalCloseShiftModal"
        class="fixed inset-0 z-[80] hidden items-center justify-center bg-stone-950/70 px-4 backdrop-blur-sm">
        <div class="w-full max-w-lg rounded-[2rem] bg-white shadow-2xl border border-stone-200 overflow-hidden">
            <div class="border-b border-stone-100 px-6 py-5">
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>

                    <div class="flex-1">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-rose-500">
                            Konfirmasi Akhir
                        </p>
                        <h3 class="mt-1 text-xl font-black text-stone-900">
                            Tutup shift sekarang?
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-stone-500">
                            Setelah dikonfirmasi, shift akan ditutup dan kasir harus mulai shift baru untuk kembali
                            beroperasi.
                        </p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-5">
                <div class="rounded-2xl bg-stone-50 border border-stone-100 p-4 space-y-3">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm font-semibold text-stone-500">Nama Kasir</span>
                        <span class="text-sm font-black text-stone-900">
                            {{ $activeShift->user->name ?? auth()->user()->name }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm font-semibold text-stone-500">Jam Buka</span>
                        <span class="text-sm font-black text-stone-900">
                            {{ optional($activeShift->opened_at)->format('d M Y, H:i') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm font-semibold text-stone-500">Jam Tutup</span>
                        <span class="text-sm font-black text-stone-900">
                            {{ now()->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-stone-100 px-6 py-5 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeFinalCloseShiftModal()"
                    class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-black text-stone-700 transition hover:bg-stone-50">
                    Kembali
                </button>

                <button type="button" onclick="submitCloseShiftForm()"
                    class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-2.5 text-sm font-black text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700">
                    Ya, Tutup Shift
                </button>
            </div>
        </div>
    </div>

    <script>
        function openEndShiftModal() {
            const modal = document.getElementById('endShiftModal');

            if (!modal) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeEndShiftModal() {
            const modal = document.getElementById('endShiftModal');

            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            const finalModal = document.getElementById('finalCloseShiftModal');

            if (!finalModal || finalModal.classList.contains('hidden')) {
                document.body.style.overflow = '';
            }
        }

        function openFinalCloseShiftModal() {
            const modal = document.getElementById('finalCloseShiftModal');

            if (!modal) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeFinalCloseShiftModal() {
            const modal = document.getElementById('finalCloseShiftModal');

            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            const detailModal = document.getElementById('endShiftModal');

            if (!detailModal || detailModal.classList.contains('hidden')) {
                document.body.style.overflow = '';
            }
        }

        function submitCloseShiftForm(button) {
            const form = document.getElementById('closeShiftForm');

            if (!form) return;

            if (button) {
                button.disabled = true;
                button.innerText = 'Menutup Shift...';
                button.classList.add('opacity-70', 'cursor-not-allowed');
            }

            form.submit();
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeFinalCloseShiftModal();
                closeEndShiftModal();
            }
        });
    </script>
@endsection
