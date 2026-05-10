@extends('layouts.master')

@section('title', 'Edit Meja')
@section('header-title', 'Edit Meja')

@section('content')
    @php
        use Illuminate\Support\Facades\Route;
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

    <div class="space-y-6">

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                    Table Configuration
                </p>

                <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">
                    Edit Meja
                </h2>

                <p class="mt-2 max-w-3xl text-sm leading-6 text-stone-600">
                    Ubah data meja, atur status, regenerate QR token, dan unduh QR untuk dicetak.
                </p>
            </div>

            <a href="{{ route('admin.tables.index') }}"
                class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                Kembali
            </a>
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_420px] xl:items-start">
            {{-- Panel kiri --}}
            <div class="space-y-5">
                <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                    <div class="mb-6">
                        <h3 class="text-xl font-black text-stone-950">
                            Informasi Meja
                        </h3>

                        <p class="mt-1 text-sm text-stone-500">
                            Perubahan nama dan kode tidak akan mengganti QR token.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.tables.update', $table) }}">
                        @csrf
                        @method('PUT')

                        @include('admin.tables._form', ['table' => $table])

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <a href="{{ route('admin.tables.index') }}"
                                class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                                Batal
                            </a>

                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-2xl bg-amber-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700 active:scale-[0.98]">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-black text-stone-950">
                                Status Meja
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-stone-500">
                                @if ($table->is_active)
                                    Meja aktif. QR dapat digunakan customer untuk melakukan pemesanan.
                                @else
                                    Meja nonaktif. QR sebaiknya tidak digunakan customer.
                                @endif
                            </p>
                        </div>

                        <form method="POST" action="{{ route('admin.tables.toggle-status', $table) }}">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                class="{{ $table->is_active ? 'bg-emerald-600' : 'bg-stone-300' }} relative inline-flex h-9 w-16 shrink-0 items-center rounded-full transition active:scale-95"
                                title="{{ $table->is_active ? 'Nonaktifkan meja' : 'Aktifkan meja' }}">
                                <span
                                    class="{{ $table->is_active ? 'translate-x-8' : 'translate-x-1' }} inline-block h-7 w-7 transform rounded-full bg-white shadow transition">
                                </span>
                            </button>
                        </form>
                    </div>

                    <div class="mt-4">
                        @if ($table->is_active)
                            <span
                                class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-emerald-700">
                                Aktif
                            </span>
                        @else
                            <span
                                class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-rose-700">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                </div>

                <div class="rounded-[2rem] border border-rose-200 bg-rose-50 p-6 shadow-sm">
                    <h3 class="text-lg font-black text-rose-800">
                        Regenerate QR Token
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-rose-700">
                        Gunakan hanya jika QR lama bocor, rusak, atau perlu diganti. Setelah regenerate, link QR lama tidak
                        dapat dipakai lagi.
                    </p>

                    <button type="button" onclick="openRegenerateModal()"
                        class="mt-5 w-full rounded-2xl bg-rose-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-rose-600/20 transition hover:bg-rose-700 active:scale-[0.98]">
                        Regenerate QR Token
                    </button>
                </div>
            </div>

            {{-- Panel kanan --}}
            <aside class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-black text-stone-950">
                            QR Meja
                        </h3>

                        <p class="mt-1 text-sm text-stone-500">
                            Scan untuk order dari {{ $table->name }}.
                        </p>
                    </div>

                    @if ($table->is_active)
                        <span
                            class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-emerald-700">
                            Aktif
                        </span>
                    @else
                        <span
                            class="rounded-full bg-rose-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-rose-700">
                            Nonaktif
                        </span>
                    @endif
                </div>

                <div class="mt-6 flex justify-center">
                    <div class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
                        {!! $svgPreview !!}
                    </div>
                </div>

                <div class="mt-5 rounded-2xl border border-stone-100 bg-stone-50 p-4">
                    <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                        Link Customer
                    </p>

                    <input type="text" readonly value="{{ $customerUrl }}" onclick="this.select()"
                        class="mt-2 w-full rounded-xl border border-stone-200 bg-white px-3 py-2 text-xs font-semibold text-stone-600">

                    <p class="mt-3 break-all font-mono text-[11px] font-semibold text-stone-400">
                        Token: {{ $table->qr_token }}
                    </p>
                </div>

                <div class="mt-5 grid gap-3">
                    <button type="button" onclick="downloadQRPng('{{ $base64Svg }}', '{{ $downloadName }}')"
                        class="inline-flex items-center justify-center rounded-2xl bg-stone-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-stone-800 active:scale-[0.98]">
                        Unduh QR PNG
                    </button>

                    <button type="button" onclick="copyCustomerUrl('{{ $customerUrl }}')"
                        class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                        Salin Link
                    </button>
                </div>
            </aside>
        </div>
    </div>

    {{-- Modal konfirmasi regenerate QR --}}
    <div id="regenerateQrModal"
        class="fixed inset-0 z-[80] hidden items-center justify-center bg-stone-950/70 px-4 backdrop-blur-sm">
        <div class="w-full max-w-md overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-2xl">
            <div class="border-b border-stone-100 px-6 py-5">
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600">
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
                            QR lama untuk <span class="font-black text-stone-900">{{ $table->name }}</span> tidak akan
                            bisa dipakai lagi.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 px-6 py-5 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeRegenerateModal()"
                    class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-black text-stone-700 transition hover:bg-stone-50">
                    Batal
                </button>

                <form method="POST" action="{{ route('admin.tables.regenerate-qr-token', $table) }}">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-2xl bg-rose-600 px-4 py-2.5 text-sm font-black text-white shadow-lg shadow-rose-600/20 transition hover:bg-rose-700 sm:w-auto">
                        Ya, Regenerate
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function downloadQRPng(base64Svg, filename) {
            const img = new Image();
            img.src = 'data:image/svg+xml;base64,' + base64Svg;

            img.onload = function() {
                const scale = 2;
                const canvas = document.createElement('canvas');

                canvas.width = img.width * scale;
                canvas.height = img.height * scale;

                const ctx = canvas.getContext('2d');
                ctx.imageSmoothingEnabled = false;
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                const link = document.createElement('a');
                link.download = filename + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            };
        }

        function copyCustomerUrl(url) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url);
                alert('Link QR berhasil disalin.');
                return;
            }

            const input = document.createElement('input');
            input.value = url;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            input.remove();

            alert('Link QR berhasil disalin.');
        }

        function openRegenerateModal() {
            const modal = document.getElementById('regenerateQrModal');
            if (!modal) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeRegenerateModal() {
            const modal = document.getElementById('regenerateQrModal');
            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeRegenerateModal();
            }
        });
    </script>
@endsection
