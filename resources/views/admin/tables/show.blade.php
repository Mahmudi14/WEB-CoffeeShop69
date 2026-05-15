@extends('layouts.master')

@section('title', 'Detail Meja')
@section('header-title', 'Detail Mejalayouts.master')

@section('title', 'Detail Meja')
@section('header-title', 'Detail Meja')

@section('content')
    @php
        use Illuminate\Support\Str;

        $customerUrl = route('customer.qr.menu', ['qrToken' => $table->qr_token]);

        $svgPreview = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(220)
            ->margin(1)
            ->generate($customerUrl);

        $svgLarge = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(900)
            ->margin(2)
            ->generate($customerUrl);

        $base64Svg = base64_encode((string) $svgLarge);
        $downloadName = 'QR_' . Str::slug($table->name ?: $table->code);
    @endphp

    <div x-data="tableShowPage()" @keydown.escape.window="closeRegenerateModal()" class="w-full space-y-8">
        {{-- Header --}}
        <section class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-[#171412] p-6 shadow-xl">
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)]">
            </div>

            <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0">
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5">
                        <span class="h-2 w-2 rounded-full {{ $table->is_active ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>

                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Table Detail
                        </span>
                    </div>

                    <h2 class="truncate text-2xl font-black tracking-tight text-white sm:text-3xl">
                        {{ $table->name }}
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-300">
                        Detail informasi meja, status, kode meja, QR Code customer, dan tindakan lanjutan.
                    </p>
                </div>

                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
                    <a href="{{ route('admin.tables.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-5 py-3 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                        Kembali
                    </a>

                    <a href="{{ route('admin.tables.edit', $table) }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                        Edit Meja
                    </a>
                </div>
            </div>
        </section>

        {{-- Content --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
            {{-- Informasi Meja --}}
            <section class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                <div class="border-b border-stone-100 px-6 py-5">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                        Informasi Meja
                    </p>

                    <h3 class="mt-2 text-xl font-black tracking-tight text-stone-950">
                        Data Meja
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-stone-500">
                        Informasi utama meja yang tersimpan di sistem.
                    </p>
                </div>

                <div class="divide-y divide-stone-100">
                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Nama Meja
                        </p>

                        <p class="min-w-0 break-words text-sm font-bold text-stone-900 sm:col-span-2">
                            {{ $table->name }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Kode Meja
                        </p>

                        <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                            <span class="inline-flex rounded-full bg-stone-100 px-3 py-1 text-xs font-black text-stone-700">
                                {{ $table->code }}
                            </span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Status
                        </p>

                        <div class="sm:col-span-2">
                            @if ($table->is_active)
                                <span
                                    class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                    Aktif
                                </span>
                            @else
                                <span
                                    class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-black text-rose-700">
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            QR Token
                        </p>

                        <p class="min-w-0 break-all font-mono text-xs font-semibold text-stone-600 sm:col-span-2">
                            {{ $table->qr_token ?? '-' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Link Customer
                        </p>

                        <div class="min-w-0 sm:col-span-2">
                            <input type="text" readonly value="{{ $customerUrl }}" onclick="this.select()"
                                class="w-full rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-xs font-semibold text-stone-600 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Dibuat Pada
                        </p>

                        <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                            {{ $table->created_at->format('d M Y H:i') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Terakhir Diubah
                        </p>

                        <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                            {{ $table->updated_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- QR Preview --}}
            <aside class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                <div class="border-b border-stone-100 px-6 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                                QR Meja
                            </p>

                            <h3 class="mt-2 text-xl font-black tracking-tight text-stone-950">
                                Preview QR
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-stone-500">
                                Scan untuk order dari {{ $table->name }}.
                            </p>
                        </div>

                        @if ($table->is_active)
                            <span
                                class="shrink-0 rounded-full bg-emerald-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-emerald-700">
                                Aktif
                            </span>
                        @else
                            <span
                                class="shrink-0 rounded-full bg-rose-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-rose-700">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex justify-center">
                        <div class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
                            {!! $svgPreview !!}
                        </div>
                    </div>

                    <div class="mt-5 grid gap-3">
                        <button type="button"
                            @click="downloadQRPng(@js($base64Svg), @js($downloadName))"
                            class="inline-flex items-center justify-center rounded-2xl bg-stone-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-stone-800 active:scale-[0.98]">
                            Unduh QR PNG
                        </button>

                        <button type="button" @click="copyCustomerUrl(@js($customerUrl))"
                            class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98]">
                            Salin Link
                        </button>
                    </div>
                </div>
            </aside>
        </div>

        {{-- Status Action --}}
        <section
            class="{{ $table->is_active ? 'border-rose-200 bg-rose-50' : 'border-emerald-200 bg-emerald-50' }} rounded-[2rem] border p-5 shadow-sm">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <p
                        class="{{ $table->is_active ? 'text-rose-500' : 'text-emerald-600' }} text-xs font-black uppercase tracking-[0.22em]">
                        Status Action
                    </p>

                    <h3 class="{{ $table->is_active ? 'text-rose-800' : 'text-emerald-800' }} mt-2 text-lg font-black">
                        {{ $table->is_active ? 'Nonaktifkan Meja' : 'Aktifkan Meja' }}
                    </h3>

                    <p
                        class="{{ $table->is_active ? 'text-rose-700' : 'text-emerald-700' }} mt-2 text-sm font-semibold leading-6">
                        @if ($table->is_active)
                            Meja tidak akan dihapus, tetapi QR meja tidak disarankan digunakan untuk customer.
                        @else
                            Meja ini sedang nonaktif. Aktifkan kembali jika QR meja ingin digunakan lagi.
                        @endif
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.tables.toggle-status', $table) }}" class="shrink-0">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                        class="{{ $table->is_active
                            ? 'border-rose-300 bg-rose-600 text-white hover:bg-rose-700'
                            : 'border-emerald-300 bg-emerald-600 text-white hover:bg-emerald-700' }} inline-flex w-full items-center justify-center rounded-2xl border px-5 py-3 text-sm font-black transition active:scale-[0.98] lg:w-auto">
                        {{ $table->is_active ? 'Nonaktifkan Meja' : 'Aktifkan Meja' }}
                    </button>
                </form>
            </div>
        </section>

        {{-- Regenerate QR --}}
        <section class="rounded-[2rem] border border-rose-200 bg-rose-50 p-5 shadow-sm">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-rose-500">
                        QR Token Action
                    </p>

                    <h3 class="mt-2 text-lg font-black text-rose-800">
                        Regenerate QR Token
                    </h3>

                    <p class="mt-2 text-sm font-semibold leading-6 text-rose-700">
                        Gunakan hanya jika QR lama bocor, rusak, atau perlu diganti. Setelah regenerate, link QR lama tidak
                        dapat dipakai lagi.
                    </p>
                </div>

                <button type="button" @click="openRegenerateModal()"
                    class="inline-flex w-full shrink-0 items-center justify-center rounded-2xl border border-rose-300 bg-rose-600 px-5 py-3 text-sm font-black text-white transition hover:bg-rose-700 active:scale-[0.98] lg:w-auto">
                    Regenerate QR
                </button>
            </div>
        </section>

        {{-- Modal Regenerate QR --}}
        <div x-cloak x-show="regenerateModalOpen" x-transition.opacity
            class="fixed inset-0 z-[80] flex items-center justify-center bg-stone-950/70 px-4 backdrop-blur-sm">
            <div x-show="regenerateModalOpen" x-transition.scale.origin.center @click.outside="closeRegenerateModal()"
                class="w-full max-w-md overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-2xl">
                <div class="border-b border-stone-100 px-6 py-5">
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-rose-500">
                                Konfirmasi QR
                            </p>

                            <h3 class="mt-1 text-xl font-black text-stone-900">
                                Regenerate QR token?
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-stone-500">
                                QR lama untuk <span class="font-black text-stone-900">{{ $table->name }}</span> tidak
                                akan bisa dipakai lagi.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 px-6 py-5 sm:flex-row sm:justify-end">
                    <button type="button" @click="closeRegenerateModal()"
                        class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-black text-stone-700 transition hover:bg-stone-50">
                        Batal
                    </button>

                    <form method="POST" action="{{ route('admin.tables.regenerate-qr-token', $table) }}">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-2xl bg-rose-600 px-4 py-2.5 text-sm font-black text-white shadow-lg shadow-rose-600/20 transition hover:bg-rose-700 active:scale-[0.98] sm:w-auto">
                            Ya, Regenerate
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Toast --}}
        <div x-cloak x-show="toastOpen" x-transition.opacity
            class="fixed bottom-6 right-6 z-[9999] max-w-sm rounded-2xl border px-5 py-4 shadow-2xl"
            :class="toastType === 'success'
                ?
                'border-emerald-200 bg-emerald-50' :
                'border-rose-200 bg-rose-50'">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl"
                    :class="toastType === 'success' ? 'bg-emerald-100' : 'bg-rose-100'">
                    <svg x-show="toastType === 'success'" x-cloak class="h-5 w-5 text-emerald-700" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>

                    <svg x-show="toastType === 'error'" x-cloak class="h-5 w-5 text-rose-700" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-black" :class="toastType === 'success' ? 'text-emerald-800' : 'text-rose-800'"
                        x-text="toastTitle">
                    </p>

                    <p class="mt-1 text-sm font-semibold"
                        :class="toastType === 'success' ? 'text-emerald-700' : 'text-rose-700'" x-text="toastMessage">
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function tableShowPage() {
            return {
                regenerateModalOpen: false,
                toastOpen: false,
                toastTitle: '',
                toastMessage: '',
                toastType: 'success',
                toastTimer: null,

                openRegenerateModal() {
                    this.regenerateModalOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                closeRegenerateModal() {
                    this.regenerateModalOpen = false;
                    document.body.style.overflow = '';
                },

                downloadQRPng(base64Svg, filename) {
                    const image = new Image();

                    image.onload = () => {
                        const scale = 2;
                        const canvas = document.createElement('canvas');

                        canvas.width = image.width * scale;
                        canvas.height = image.height * scale;

                        const context = canvas.getContext('2d');

                        context.imageSmoothingEnabled = false;
                        context.fillStyle = '#ffffff';
                        context.fillRect(0, 0, canvas.width, canvas.height);
                        context.drawImage(image, 0, 0, canvas.width, canvas.height);

                        const link = document.createElement('a');

                        link.download = `${filename}.png`;
                        link.href = canvas.toDataURL('image/png');
                        link.click();

                        this.showToast('Berhasil', 'QR Code berhasil diunduh.', 'success');
                    };

                    image.onerror = () => {
                        this.showToast('Gagal', 'QR Code gagal diunduh.', 'error');
                    };

                    image.src = `data:image/svg+xml;base64,${base64Svg}`;
                },

                async copyCustomerUrl(url) {
                    try {
                        if (navigator.clipboard && window.isSecureContext) {
                            await navigator.clipboard.writeText(url);
                        } else {
                            this.fallbackCopy(url);
                        }

                        this.showToast('Berhasil', 'Link QR berhasil disalin.', 'success');
                    } catch (error) {
                        this.showToast('Gagal', 'Link QR gagal disalin.', 'error');
                    }
                },

                fallbackCopy(text) {
                    const input = document.createElement('input');

                    input.value = text;
                    input.setAttribute('readonly', '');
                    input.style.position = 'fixed';
                    input.style.opacity = '0';
                    input.style.pointerEvents = 'none';

                    document.body.appendChild(input);
                    input.select();
                    document.execCommand('copy');
                    document.body.removeChild(input);
                },

                showToast(title, message, type = 'success') {
                    this.toastTitle = title;
                    this.toastMessage = message;
                    this.toastType = type;
                    this.toastOpen = true;

                    clearTimeout(this.toastTimer);

                    this.toastTimer = setTimeout(() => {
                        this.toastOpen = false;
                    }, 2200);
                },
            };
        }
    </script>
@endsection
