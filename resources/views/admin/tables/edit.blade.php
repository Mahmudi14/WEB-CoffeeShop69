@extends('layouts.master')

@section('title', 'Edit Meja')
@section('header-title', 'Edit Meja')

@section('content')
    @php
        use Illuminate\Support\Str;

        $customerUrl = route('customer.qr.menu', ['qrToken' => $table->qr_token]);

        $svgPreview = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(190)
            ->margin(1)
            ->generate($customerUrl);

        $svgLarge = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(900)
            ->margin(2)
            ->generate($customerUrl);

        $base64Svg = base64_encode((string) $svgLarge);
        $downloadName = 'QR_' . Str::slug($table->name ?: $table->code);
    @endphp

    <div x-data="tableEditPage()" @keydown.escape.window="closeRegenerateModal()" class="w-full space-y-8">
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
                            Table Configuration
                        </span>
                    </div>

                    <h2 class="truncate text-2xl font-black tracking-tight text-white sm:text-3xl">
                        Edit Meja
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-300">
                        Ubah data meja, atur status, regenerate QR token, dan unduh QR untuk dicetak.
                    </p>
                </div>

                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
                    <a href="{{ route('admin.tables.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-5 py-3 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                        Kembali
                    </a>

                    <a href="{{ route('admin.tables.show', $table) }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                        Detail Meja
                    </a>
                </div>
            </div>
        </section>

        {{-- Content --}}
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px] xl:items-start">
            {{-- Left Column --}}
            <div class="space-y-6">
                {{-- Form Section --}}
                <section class="rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                    <div class="border-b border-stone-100 px-6 py-5">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                            Form Meja
                        </p>

                        <h3 class="mt-2 text-xl font-black tracking-tight text-stone-950">
                            Informasi Meja
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-stone-500">
                            Perubahan nama dan kode tidak akan mengganti QR token.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.tables.update', $table) }}" class="p-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">
                            @include('admin.tables._form', ['table' => $table])
                        </div>

                        <div class="mt-8 flex flex-col gap-3 border-t border-stone-100 pt-6 sm:flex-row sm:justify-end">
                            <a href="{{ route('admin.tables.index', $table) }}"
                                class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98]">
                                Batal
                            </a>

                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-2xl bg-amber-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700 active:scale-[0.98]">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </section>


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
        function tableEditPage() {
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
