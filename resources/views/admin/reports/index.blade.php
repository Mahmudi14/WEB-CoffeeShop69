@extends('layouts.master')

@section('title', 'Admin')
@section('header-title', 'Analisis Pendapatan')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-[2rem] bg-[#171412] p-6 shadow-xl border border-white/10">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)] pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                <div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-3 py-1.5 mb-4">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            69 Coffee Shop
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Analisis Pendapatan
                    </h1>

                    <p class="mt-2 text-sm leading-6 text-stone-300 max-w-3xl">
                        Pantau pendapatan berdasarkan periode, kasir, dan transaksi yang sudah dibayar.
                    </p>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ url()->current() }}"
                class="grid gap-4 lg:grid-cols-[minmax(220px,1fr)_180px_180px_160px_110px] lg:items-end">

                <div x-data="autoFilterDropdown(@js((string) $selectedKasir))" @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Kasir
                    </label>

                    <input type="hidden" name="kasir" x-model="selectedValue">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                @foreach ($daftarKasir as $kasir)
                                    <span x-show="selectedValue === @js((string) $kasir['id'])" x-cloak>
                                        {{ $kasir['nama'] }}
                                    </span>
                                @endforeach
                            </span>

                            <svg class="h-4 w-4 shrink-0 text-stone-400 transition"
                                :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" x-cloak x-transition.origin.top @click.outside="close()"
                            class="absolute left-0 right-0 top-[54px] z-50 max-h-64 overflow-y-auto rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                            @foreach ($daftarKasir as $kasir)
                                <button type="button" @click="select(@js((string) $kasir['id']), $el)"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                    :class="selectedValue === @js((string) $kasir['id']) ? 'bg-amber-100 text-amber-800' :
                                        'text-stone-700'">
                                    <span class="truncate">
                                        {{ $kasir['nama'] }}
                                    </span>

                                    <svg x-show="selectedValue === @js((string) $kasir['id'])" x-cloak
                                        class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div x-data="autoFilterDropdown(@js((string) $periode))" @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Periode
                    </label>

                    <input type="hidden" name="periode" x-model="selectedValue">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                @foreach ($periodeList as $key => $label)
                                    <span x-show="selectedValue === @js((string) $key)" x-cloak>
                                        {{ $label }}
                                    </span>
                                @endforeach
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
                            @foreach ($periodeList as $key => $label)
                                <button type="button" @click="select(@js((string) $key), $el)"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                    :class="selectedValue === @js((string) $key) ? 'bg-amber-100 text-amber-800' :
                                        'text-stone-700'">
                                    <span>{{ $label }}</span>

                                    <svg x-show="selectedValue === @js((string) $key)" x-cloak
                                        class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div x-data="autoFilterDropdown(@js((string) $selectedMonth))" @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Bulan
                    </label>

                    <input type="hidden" name="bulan" x-model="selectedValue">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                @for ($m = 1; $m <= 12; $m++)
                                    <span x-show="selectedValue === @js((string) $m)" x-cloak>
                                        {{ Carbon::create($selectedYear, $m, 1)->translatedFormat('F') }}
                                    </span>
                                @endfor
                            </span>

                            <svg class="h-4 w-4 shrink-0 text-stone-400 transition"
                                :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" x-cloak x-transition.origin.top @click.outside="close()"
                            class="absolute left-0 right-0 top-[54px] z-50 max-h-64 overflow-y-auto rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                            @for ($m = 1; $m <= 12; $m++)
                                <button type="button" @click="select(@js((string) $m), $el)"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                    :class="selectedValue === @js((string) $m) ? 'bg-amber-100 text-amber-800' :
                                        'text-stone-700'">
                                    <span>
                                        {{ Carbon::create($selectedYear, $m, 1)->translatedFormat('F') }}
                                    </span>

                                    <svg x-show="selectedValue === @js((string) $m)" x-cloak
                                        class="h-4 w-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endfor
                        </div>
                    </div>
                </div>

                <div x-data="autoFilterDropdown(@js((string) $selectedYear))" @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Tahun
                    </label>

                    <input type="hidden" name="tahun" x-model="selectedValue">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                @for ($y = now()->year; $y >= now()->year - 10; $y--)
                                    <span x-show="selectedValue === @js((string) $y)" x-cloak>
                                        {{ $y }}
                                    </span>
                                @endfor
                            </span>

                            <svg class="h-4 w-4 shrink-0 text-stone-400 transition"
                                :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" x-cloak x-transition.origin.top @click.outside="close()"
                            class="absolute left-0 right-0 top-[54px] z-50 max-h-64 overflow-y-auto rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                            @for ($y = now()->year; $y >= now()->year - 10; $y--)
                                <button type="button" @click="select(@js((string) $y), $el)"
                                    class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                    :class="selectedValue === @js((string) $y) ? 'bg-amber-100 text-amber-800' :
                                        'text-stone-700'">
                                    <span>{{ $y }}</span>

                                    <svg x-show="selectedValue === @js((string) $y)" x-cloak
                                        class="h-4 w-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endfor
                        </div>
                    </div>
                </div>

                <a href="{{ url()->current() }}"
                    class="inline-flex w-full items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98]">
                    Reset
                </a>
            </form>
        </section>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($summaryCards as $card)
                <div class="relative overflow-hidden rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
                    <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r {{ $card['accent'] }}"></div>

                    <p class="text-[11px] font-black uppercase tracking-[0.2em] text-stone-400">
                        {{ $card['title'] }}
                    </p>

                    <h3 class="mt-3 text-2xl font-black leading-tight text-stone-900">
                        {{ $card['value'] }}
                    </h3>
                </div>
            @endforeach
        </div>

        <div class="rounded-[2rem] border border-stone-100 bg-white p-5 shadow-sm md:p-6">
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-xl font-black text-stone-900">
                        {{ $chartTitle }}
                    </h2>

                    <p class="mt-1 text-sm font-semibold text-stone-500">
                        {{ $periodeInfo }}
                    </p>
                </div>

                <div class="inline-flex w-fit rounded-2xl bg-stone-100 p-1">
                    <a href="{{ request()->fullUrlWithQuery(['periode' => 'harian']) }}"
                        class="rounded-xl px-4 py-2 text-xs font-black transition {{ $periode === 'harian' ? 'bg-white text-amber-600 shadow-sm' : 'text-stone-500 hover:text-stone-800' }}">
                        Harian
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['periode' => 'bulanan']) }}"
                        class="rounded-xl px-4 py-2 text-xs font-black transition {{ $periode === 'bulanan' ? 'bg-white text-amber-600 shadow-sm' : 'text-stone-500 hover:text-stone-800' }}">
                        Bulanan
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['periode' => 'tahunan']) }}"
                        class="rounded-xl px-4 py-2 text-xs font-black transition {{ $periode === 'tahunan' ? 'bg-white text-amber-600 shadow-sm' : 'text-stone-500 hover:text-stone-800' }}">
                        Tahunan
                    </a>
                </div>
            </div>

            <div class="mb-6 overflow-hidden rounded-[2rem] bg-[#171412] p-5 text-white shadow-xl">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-stone-400">
                            {{ $ramaiSubtitle ?? 'Periode Teramai' }}
                        </p>

                        <h3 class="mt-2 text-2xl font-black md:text-3xl">
                            {{ $ramaiLabel ?? $highestLabel }}
                        </h3>

                        <p class="mt-1 text-sm text-stone-400">
                            Pendapatan tertinggi pada periode terpilih.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/10 px-5 py-4 backdrop-blur-md">
                        <p class="text-xs font-bold text-stone-400">Nilai Pendapatan</p>
                        <p class="mt-1 text-xl font-black text-amber-300">
                            Rp {{ number_format($ramaiAmount ?? $highestAmount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-6 grid grid-cols-1 gap-3 md:grid-cols-3">
                <div class="rounded-2xl border border-stone-100 bg-stone-50 px-4 py-4">
                    <p class="text-xs font-bold text-stone-500">
                        @if ($periode === 'harian')
                            Hari paling ramai
                        @elseif ($periode === 'bulanan')
                            Bulan paling ramai
                        @else
                            Tahun paling ramai
                        @endif
                    </p>

                    <p class="mt-1 text-sm font-black text-stone-900">
                        {{ $ramaiLabel ?? $highestLabel }}
                    </p>
                </div>

                <div class="rounded-2xl border border-stone-100 bg-stone-50 px-4 py-4">
                    <p class="text-xs font-bold text-stone-500">Pendapatan tertinggi</p>

                    <p class="mt-1 text-sm font-black text-stone-900">
                        Rp {{ number_format($ramaiAmount ?? $highestAmount, 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-2xl border border-stone-100 bg-stone-50 px-4 py-4">
                    <p class="text-xs font-bold text-stone-500">
                        {{ $periode === 'harian' ? 'Hari tanpa transaksi' : 'Periode nol' }}
                    </p>

                    <p class="mt-1 text-sm font-black text-stone-900">
                        {{ $zeroDays }}
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto pb-5">
                <div class="relative h-[360px] {{ $periode === 'harian' ? 'min-w-[1320px]' : 'min-w-full' }} pr-10 pt-8">
                    <div class="absolute inset-x-0 top-12 bottom-10 flex flex-col justify-between pointer-events-none">
                        @for ($i = 0; $i < 5; $i++)
                            <div class="border-t border-dashed border-stone-200"></div>
                        @endfor
                    </div>

                    <div class="relative flex h-full items-stretch gap-2">
                        @foreach ($chartData as $item)
                            @php
                                $amount = (int) ($item['amount'] ?? 0);
                                $value = (int) ($item['value'] ?? 0);

                                // Jangan sampai 100%, karena tooltip dan label atas bisa kepotong.
                                $barHeight = $amount > 0 ? min(88, max($value, 8)) : 3;
                            @endphp

                            <div
                                class="{{ $periode === 'harian' ? 'w-9 shrink-0' : 'flex-1' }} flex h-full flex-col items-center">

                                <div class="relative flex w-full flex-1 items-end">
                                    <div class="group relative w-full min-h-[8px] rounded-t-[18px] transition-all duration-300
                            {{ $amount > 0
                                ? 'bg-gradient-to-t from-amber-600 via-amber-400 to-amber-200 shadow-[0_12px_24px_rgba(245,158,11,0.18)] hover:from-amber-700 hover:to-amber-300'
                                : 'bg-stone-200' }}"
                                        style="height: {{ $barHeight }}%;">

                                        <div
                                            class="absolute -top-10 left-1/2 z-30 -translate-x-1/2 whitespace-nowrap rounded-xl bg-stone-950 px-2.5 py-1 text-[10px] font-bold text-white opacity-0 shadow-lg transition group-hover:opacity-100">
                                            @if ($amount >= 1000000)
                                                Rp {{ number_format($amount / 1000000, 1, ',', '.') }}jt
                                            @elseif ($amount >= 1000)
                                                Rp {{ number_format($amount / 1000, 0, ',', '.') }}k
                                            @else
                                                Rp {{ number_format($amount, 0, ',', '.') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-2 flex min-h-[42px] flex-col items-center justify-start gap-1">
                                    <span class="text-[11px] font-bold text-stone-500">
                                        {{ $item['label'] }}
                                    </span>

                                    <span class="text-[10px] font-black text-stone-700">
                                        @if ($amount >= 1000000)
                                            {{ number_format($amount / 1000000, 1, ',', '.') }}jt
                                        @elseif ($amount >= 1000)
                                            {{ number_format($amount / 1000, 0, ',', '.') }}k
                                        @else
                                            {{ $amount }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-stone-400">Insight Utama</p>

                <h3 class="mt-3 text-lg font-black text-stone-900">
                    {{ $ramaiLabel ?? $highestLabel }}
                </h3>

                <p class="mt-2 text-sm leading-relaxed text-stone-500">
                    {{ $ramaiSubtitle ?? 'Periode dengan pendapatan tertinggi pada filter saat ini.' }}
                </p>
            </div>

            <div class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-stone-400">Pendapatan Puncak</p>

                <h3 class="mt-3 text-lg font-black text-stone-900">
                    Rp {{ number_format($ramaiAmount ?? $highestAmount, 0, ',', '.') }}
                </h3>

                <p class="mt-2 text-sm leading-relaxed text-stone-500">
                    Nilai pendapatan tertinggi pada periode terpilih.
                </p>
            </div>

            <div class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-stone-400">Catatan</p>

                <h3 class="mt-3 text-lg font-black text-stone-900">
                    {{ $periode === 'harian' ? $zeroDays . ' hari kosong' : $zeroDays . ' periode kosong' }}
                </h3>

                <p class="mt-2 text-sm leading-relaxed text-stone-500">
                    Menunjukkan jumlah periode tanpa transaksi.
                </p>
            </div>
        </div>

        <div class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-black text-stone-900">
                Ringkasan Tambahan
            </h3>

            <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-stone-100 bg-stone-50 p-4">
                    <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                        Total Diskon
                    </p>

                    <p class="mt-2 text-lg font-black text-rose-600">
                        Rp {{ number_format($totalDiscount, 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-2xl border border-stone-100 bg-stone-50 p-4">
                    <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                        Total Pajak
                    </p>

                    <p class="mt-2 text-lg font-black text-sky-600">
                        Rp {{ number_format($totalTax, 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-2xl border border-stone-100 bg-stone-50 p-4">
                    <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                        Total Pengeluaran
                    </p>

                    <p class="mt-2 text-lg font-black text-rose-700">
                        Rp {{ number_format($totalExpense, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script>
        function autoFilterDropdown(initialValue) {
            return {
                selectedValue: initialValue || '',
                dropdownOpen: false,

                toggle() {
                    this.dropdownOpen = !this.dropdownOpen;
                },

                close() {
                    this.dropdownOpen = false;
                },

                select(value, el) {
                    if (this.selectedValue === value) {
                        this.close();
                        return;
                    }

                    this.selectedValue = value;
                    this.close();

                    this.$nextTick(() => {
                        el.closest('form').submit();
                    });
                },
            };
        }
    </script>
@endsection
