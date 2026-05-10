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

    <div class="space-y-6">
        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-[#171412] p-6 shadow-xl">
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)]">
            </div>

            <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
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
        </div>

        <section class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <form action="{{ route('admin.tables.index') }}" method="GET" class="grid gap-4 lg:grid-cols-12 lg:items-end">

                <div class="lg:col-span-7">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Cari Meja
                    </label>

                    <input type="search" name="search" value="{{ $search ?? request('search') }}"
                        placeholder="Cari nama atau kode meja..." enterkeyhint="search" autocomplete="off"
                        class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                </div>

                <div class="lg:col-span-3" x-data="filterDropdown(@js((string) ($status ?? request('status'))))" @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Status
                    </label>

                    <input type="hidden" name="status" x-model="selectedValue">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                <span x-show="selectedValue === ''">
                                    Semua Status
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
                            class="absolute left-0 right-0 top-[54px] z-50 overflow-hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">

                            <button type="button" @click="select('')"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                :class="selectedValue === '' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                                <span>Semua Status</span>

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

                <div class="flex gap-3 lg:col-span-2 lg:justify-end">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-2xl bg-stone-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-stone-800 active:scale-[0.98] lg:flex-none">
                        Filter
                    </button>

                    <a href="{{ route('admin.tables.index') }}"
                        class="inline-flex flex-1 items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98] lg:flex-none">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-200 px-6 py-5">
                <h3 class="text-lg font-black text-stone-950">
                    Daftar Meja
                </h3>

                <p class="mt-1 text-sm text-stone-500">
                    Total meja terdaftar: {{ $tables->total() }}
                </p>
            </div>

            <div id="table-container">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[980px] border-collapse text-left">
                        <thead>
                            <tr
                                class="border-b border-stone-100 bg-stone-50 text-[11px] font-black uppercase tracking-widest text-stone-500">
                                <th class="w-16 px-6 py-4">No</th>
                                <th class="px-6 py-4">QR Code</th>
                                <th class="px-6 py-4">Nama Meja</th>
                                <th class="px-6 py-4">Kode</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100 text-sm text-stone-700">
                            @forelse ($tables as $table)
                                @php
                                    $qrUrl = $customerMenuUrl($table);

                                    $svgPreview = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                        ->size(92)
                                        ->margin(1)
                                        ->generate($qrUrl);

                                    $svgLarge = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                        ->size(900)
                                        ->margin(2)
                                        ->generate($qrUrl);

                                    $base64Svg = base64_encode((string) $svgLarge);
                                    $downloadName = 'QR_' . Str::slug($table->name ?: $table->code);
                                @endphp

                                <tr class="transition-colors hover:bg-stone-50/80">
                                    <td class="px-6 py-4 font-bold text-stone-500">
                                        {{ $tables->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($table->qr_token)
                                            <div class="inline-flex flex-col items-center">
                                                <div
                                                    class="mb-2 rounded-2xl border border-stone-200 bg-white p-3 shadow-sm transition-colors hover:border-amber-300">
                                                    {!! $svgPreview !!}
                                                </div>

                                                <div class="flex flex-col items-center gap-1">
                                                    <button type="button"
                                                        onclick="downloadQRPng('{{ $base64Svg }}', '{{ $downloadName }}')"
                                                        class="inline-flex cursor-pointer items-center gap-1 text-[10px] font-black uppercase tracking-wider text-amber-600 hover:text-amber-700">
                                                        Unduh PNG
                                                    </button>

                                                    <button type="button"
                                                        onclick="copyCustomerUrl('{{ $qrUrl }}')"
                                                        class="text-[10px] font-black uppercase tracking-wider text-stone-500 hover:text-stone-700">
                                                        Salin Link
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-rose-50 px-3 py-1 text-xs font-black text-rose-600">
                                                QR token kosong
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="text-base font-black text-stone-900">
                                            {{ $table->name }}
                                        </p>

                                        <p class="mt-1 max-w-[260px] truncate font-mono text-xs text-stone-400">
                                            Token: {{ $table->qr_token ?? '-' }}
                                        </p>

                                        <p class="mt-1 max-w-[360px] truncate text-xs font-semibold text-stone-500">
                                            {{ $qrUrl }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-full bg-stone-100 px-3 py-1.5 text-xs font-black text-stone-700">
                                            {{ $table->code }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if ($table->is_active)
                                            <span
                                                class="inline-flex items-center rounded-full border border-emerald-100 bg-emerald-50 px-3 py-1.5 text-xs font-black text-emerald-600">
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full border border-rose-100 bg-rose-50 px-3 py-1.5 text-xs font-black text-rose-600">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.tables.edit', $table) }}"
                                                class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-4 py-2 text-xs font-black text-stone-700 transition hover:border-amber-300 hover:bg-stone-50 hover:text-amber-600">
                                                Edit
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
    </script>
@endsection
