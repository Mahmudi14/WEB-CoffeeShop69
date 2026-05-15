@extends('layouts.master')

@section('title', 'Admin')
@section('header-title', 'Manajemen Meja')

@section('content')
    @php
        use Illuminate\Support\Str;

        $customerMenuUrl = function ($table) {
            return route('customer.qr.menu', ['qrToken' => $table->qr_token]);
        };
    @endphp

    <div x-data="tableIndexPage()" class="space-y-6">
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
                            Table & QR Order
                        </span>
                    </div>

                    <h1 class="text-2xl font-black tracking-tight text-white sm:text-3xl">
                        Manajemen Meja
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-stone-300">
                        Kelola meja dan QR Code self-ordering Cafe 69. QR dapat diunduh sebagai PNG untuk dicetak.
                    </p>
                </div>

                <a href="{{ route('admin.tables.create') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 4v16m8-8H4" />
                    </svg>

                    Tambah Meja
                </a>
            </div>
        </section>

        {{-- Filter --}}
        <section class="rounded-3xl border border-stone-200 bg-white p-4 shadow-sm lg:p-5">
            <form action="{{ route('admin.tables.index') }}" method="GET"
                class="grid gap-4 lg:grid-cols-[minmax(260px,1fr)_160px_max-content] lg:items-end xl:grid-cols-[minmax(360px,1fr)_180px_max-content]">

                {{-- Search --}}
                <div class="min-w-0">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Cari Meja
                    </label>

                    <input type="search" name="search" value="{{ $search ?? request('search') }}"
                        placeholder="Cari nama atau kode meja..." enterkeyhint="search" autocomplete="off"
                        class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                </div>

                {{-- Status --}}
                <div class="min-w-0" x-data="filterDropdown(@js((string) ($status ?? request('status'))))" @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Status
                    </label>

                    <input type="hidden" name="status" x-model="selectedValue">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-2 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                <span x-show="selectedValue === ''">
                                    Semua
                                </span>

                                <span x-show="selectedValue === 'active'" x-cloak>
                                    Aktif
                                </span>

                                <span x-show="selectedValue === 'inactive'" x-cloak>
                                    Nonaktif
                                </span>
                            </span>

                            <svg class="h-4 w-4 shrink-0 text-stone-400 transition"
                                :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" x-cloak x-transition.origin.top @click.outside="close()"
                            class="absolute left-0 right-0 top-full z-50 mt-2 overflow-hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                            <button type="button" @click="select('')"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                :class="selectedValue === '' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                                <span>Semua</span>

                                <svg x-show="selectedValue === ''" x-cloak class="h-4 w-4 shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>

                            <button type="button" @click="select('active')"
                                class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-emerald-50 hover:text-emerald-700"
                                :class="selectedValue === 'active' ? 'bg-emerald-100 text-emerald-800' : 'text-stone-700'">
                                <span>Aktif</span>

                                <svg x-show="selectedValue === 'active'" x-cloak class="h-4 w-4 shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>

                            <button type="button" @click="select('inactive')"
                                class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-rose-50 hover:text-rose-700"
                                :class="selectedValue === 'inactive' ? 'bg-rose-100 text-rose-800' : 'text-stone-700'">
                                <span>Nonaktif</span>

                                <svg x-show="selectedValue === 'inactive'" x-cloak class="h-4 w-4 shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 lg:justify-end">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center whitespace-nowrap rounded-2xl bg-stone-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-stone-800 active:scale-[0.98] lg:flex-none">
                        Filter
                    </button>

                    <a href="{{ route('admin.tables.index') }}"
                        class="inline-flex flex-1 items-center justify-center whitespace-nowrap rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98] lg:flex-none">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        {{-- Table --}}
        <section class="overflow-visible rounded-3xl border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-200 px-6 py-5">
                <h3 class="text-lg font-black text-stone-950">
                    Daftar Meja
                </h3>

                <p class="mt-1 text-sm text-stone-500">
                    Total meja terdaftar: {{ $tables->total() }}
                </p>
            </div>

            <div id="table-container">
                <div class="overflow-x-auto lg:overflow-visible">
                    <table class="w-full min-w-[720px] table-fixed divide-y divide-stone-200 lg:min-w-0">
                        <thead class="bg-stone-100">
                            <tr>
                                <th
                                    class="w-12 px-3 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    No
                                </th>

                                <th
                                    class="w-48 px-3 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    QR
                                </th>

                                <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Meja
                                </th>

                                <th
                                    class="w-24 px-3 py-4 text-center text-xs font-black uppercase tracking-wider text-stone-500">
                                    Kode
                                </th>

                                <th
                                    class="w-24 px-3 py-4 text-center text-xs font-black uppercase tracking-wider text-stone-500">
                                    Status
                                </th>

                                <th
                                    class="w-36 px-3 py-4 text-center text-xs font-black uppercase tracking-wider text-stone-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100">
                            @forelse ($tables as $table)
                                @php
                                    $qrUrl = $customerMenuUrl($table);

                                    $svgPreview = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                        ->size(64)
                                        ->margin(1)
                                        ->generate($qrUrl);

                                    $svgLarge = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                        ->size(900)
                                        ->margin(2)
                                        ->generate($qrUrl);

                                    $base64Svg = base64_encode((string) $svgLarge);
                                    $downloadName = 'QR_' . Str::slug($table->name ?: $table->code);
                                @endphp

                                <tr class="transition hover:bg-stone-50">
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-stone-500">
                                        {{ $tables->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-3 py-4">
                                        @if ($table->qr_token)
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="shrink-0 rounded-2xl border border-stone-200 bg-white p-2 shadow-sm [&_svg]:h-14 [&_svg]:w-14">
                                                    {!! $svgPreview !!}
                                                </div>

                                                <div class="flex min-w-0 flex-col gap-1.5">
                                                    <button type="button"
                                                        @click="downloadQRPng(@js($base64Svg), @js($downloadName))"
                                                        class="inline-flex items-center justify-center whitespace-nowrap rounded-xl bg-amber-100 px-3 py-1.5 text-[11px] font-black text-amber-700 transition hover:bg-amber-200 active:scale-[0.98]">
                                                        Unduh
                                                    </button>

                                                    <button type="button"
                                                        @click="copyCustomerUrl(@js($qrUrl))"
                                                        class="inline-flex items-center justify-center whitespace-nowrap rounded-xl border border-stone-200 bg-white px-3 py-1.5 text-[11px] font-black text-stone-700 transition hover:bg-stone-50 active:scale-[0.98]">
                                                        Salin
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-rose-50 px-2.5 py-1 text-[11px] font-black text-rose-600">
                                                Kosong
                                            </span>
                                        @endif
                                    </td>

                                    <td class="min-w-0 px-4 py-4">
                                        <p class="truncate text-sm font-black text-stone-950">
                                            {{ $table->name }}
                                        </p>

                                        <p
                                            class="mt-1 hidden truncate font-mono text-[11px] font-semibold text-stone-400 xl:block">
                                            Token: {{ $table->qr_token ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-center">
                                        <span
                                            class="inline-flex max-w-full items-center rounded-full bg-stone-100 px-2.5 py-1 text-[11px] font-black text-stone-700">
                                            {{ $table->code }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-center">
                                        @if ($table->is_active)
                                            <span
                                                class="inline-flex rounded-full border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-[11px] font-black text-emerald-600">
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full border border-rose-100 bg-rose-50 px-2.5 py-1 text-[11px] font-black text-rose-600">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.tables.edit', $table) }}"
                                                class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-3 py-2 text-xs font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98]">
                                                Edit
                                            </a>

                                            <a href="{{ route('admin.tables.show', $table) }}"
                                                class="inline-flex items-center justify-center rounded-2xl bg-stone-950 px-3 py-2 text-xs font-black text-white shadow-sm transition hover:bg-stone-800 active:scale-[0.98]">
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-stone-100 text-stone-300">
                                                <svg class="h-10 w-10" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 12h16M12 4v16" />
                                                </svg>
                                            </div>

                                            <h4 class="mb-1 text-lg font-black text-stone-800">
                                                Belum Ada Meja
                                            </h4>

                                            <p class="text-sm text-stone-500">
                                                Tambahkan meja baru untuk mengaktifkan QR ordering.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($tables->hasPages())
                    <div class="border-t border-stone-100 bg-stone-50/30 px-6 py-4">
                        {{ $tables->links() }}
                    </div>
                @endif
            </div>
        </section>

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
                    <p class="text-sm font-black"
                        :class="toastType === 'success' ? 'text-emerald-800' : 'text-rose-800'">
                        <span x-text="toastTitle"></span>
                    </p>

                    <p class="mt-1 text-sm font-semibold"
                        :class="toastType === 'success' ? 'text-emerald-700' : 'text-rose-700'" x-text="toastMessage">
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterDropdown(initialValue) {
            return {
                selectedValue: initialValue || '',
                dropdownOpen: false,

                toggle() {
                    this.dropdownOpen = !this.dropdownOpen;
                },

                close() {
                    this.dropdownOpen = false;
                },

                select(value) {
                    this.selectedValue = value;
                    this.close();
                },
            };
        }

        function tableIndexPage() {
            return {
                toastOpen: false,
                toastTitle: '',
                toastMessage: '',
                toastType: 'success',
                toastTimer: null,

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
