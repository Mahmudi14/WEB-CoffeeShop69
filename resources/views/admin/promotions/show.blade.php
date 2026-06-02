@extends('layouts.master')

@section('title', 'Detail Promo')
@section('header-title', 'Detail Promo')

@section('content')
    <div class="w-full space-y-8">
        {{-- Header --}}
        <section class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-[#171412] p-6 shadow-xl">
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)]">
            </div>

            <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0">
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5">
                        <span
                            class="h-2 w-2 rounded-full {{ $promotion->is_active ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>

                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Promotion Detail
                        </span>
                    </div>

                    <h2 class="truncate text-2xl font-black tracking-tight text-white sm:text-3xl">
                        {{ $promotion->name }}
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-300">
                        Detail informasi promo, nilai diskon, cakupan menu, periode aktif, status, dan prioritas promo.
                    </p>
                </div>

                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
                    <a href="{{ route('admin.promotions.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-5 py-3 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                        Kembali
                    </a>

                    <a href="{{ route('admin.promotions.edit', $promotion) }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                        Edit Promo
                    </a>
                </div>
            </div>
        </section>

        {{-- Informasi Promo --}}
        <section class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-100 px-6 py-5">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                    Informasi Promo
                </p>

                <h3 class="mt-2 text-xl font-black tracking-tight text-stone-950">
                    Data Promo
                </h3>

                <p class="mt-2 text-sm leading-6 text-stone-500">
                    Informasi utama promo yang tersimpan di sistem.
                </p>
            </div>

            <div class="divide-y divide-stone-100">
                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Nama Promo
                    </p>

                    <p class="min-w-0 break-words text-sm font-bold text-stone-900 sm:col-span-2">
                        {{ $promotion->name }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Deskripsi
                    </p>

                    <p
                        class="min-w-0 whitespace-pre-line break-words text-sm font-semibold leading-6 text-stone-700 sm:col-span-2">
                        {{ $promotion->description ?: 'Tanpa deskripsi' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Diskon
                    </p>

                    <div class="sm:col-span-2">
                        @if ($promotion->discount_type === 'percentage')
                            <p class="text-sm font-black text-emerald-700">
                                {{ rtrim(rtrim(number_format($promotion->discount_value, 2, ',', '.'), '0'), ',') }}%
                            </p>

                            <p class="mt-1 text-xs font-semibold text-stone-500">
                                Tipe diskon: Persentase
                            </p>
                        @else
                            <p class="text-sm font-black text-emerald-700">
                                Rp{{ number_format($promotion->discount_value, 0, ',', '.') }}
                            </p>

                            <p class="mt-1 text-xs font-semibold text-stone-500">
                                Tipe diskon: Nominal
                            </p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Cakupan
                    </p>

                    <div class="sm:col-span-2">
                        @if ($promotion->scope === 'all_menu')
                            <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-black text-sky-700">
                                Semua Menu
                            </span>
                        @else
                            <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-700">
                                {{ $promotion->menus_count ?? $promotion->menus()->count() }} Menu
                            </span>
                        @endif
                    </div>
                </div>

                @if ($promotion->scope !== 'all_menu')
                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Menu Promo
                        </p>

                        <div class="sm:col-span-2">
                            @if ($promotion->menus->isEmpty())
                                <p class="text-sm font-semibold text-stone-500">
                                    Tidak ada menu yang dipilih.
                                </p>
                            @else
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($promotion->menus as $menu)
                                        <span
                                            class="inline-flex rounded-full bg-stone-100 px-3 py-1 text-xs font-black text-stone-700">
                                            {{ $menu->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Periode
                    </p>

                    <div class="text-sm font-semibold leading-6 text-stone-700 sm:col-span-2">
                        <p>
                            Mulai:
                            <span class="font-black text-stone-950">
                                {{ $promotion->starts_at ? $promotion->starts_at->format('d M Y H:i') : 'Langsung' }}
                            </span>
                        </p>

                        <p>
                            Berakhir:
                            <span class="font-black text-stone-950">
                                {{ $promotion->ends_at ? $promotion->ends_at->format('d M Y H:i') : 'Tanpa batas' }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Prioritas
                    </p>

                    <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                        {{ $promotion->priority }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Status
                    </p>

                    <div class="sm:col-span-2">
                        @if ($promotion->is_active)
                            <span
                                class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-black text-rose-700">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Dibuat Pada
                    </p>

                    <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                        {{ $promotion->created_at->format('d M Y H:i') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Terakhir Diubah
                    </p>

                    <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                        {{ $promotion->updated_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </section>
        {{-- Aksi Berbahaya --}}
        <section class="rounded-[2rem] border border-rose-200 bg-rose-50 p-6 shadow-sm">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-rose-600">
                        Aksi Berbahaya
                    </p>

                    <h3 class="mt-2 text-xl font-black tracking-tight text-rose-950">
                        Hapus Promo
                    </h3>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-rose-700">
                        Promo yang dihapus tidak akan tampil lagi di daftar promo dan tidak akan digunakan pada transaksi
                        baru.
                    </p>
                </div>

                <div x-data="{ confirmOpen: false }" class="shrink-0">
                    <button type="button" @click="confirmOpen = true"
                        class="inline-flex w-full items-center justify-center rounded-2xl border border-rose-300 bg-rose-600 px-5 py-3 text-sm font-black text-white transition hover:bg-rose-700 active:scale-[0.98] lg:w-auto">
                        Hapus Promo
                    </button>

                    {{-- Modal Confirmation --}}
                    <div x-cloak x-show="confirmOpen" x-transition.opacity @keydown.escape.window="confirmOpen = false"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-stone-950/60 px-4">
                        <div x-show="confirmOpen" x-transition.scale.origin.center @click.outside="confirmOpen = false"
                            class="w-full max-w-md rounded-[2rem] border border-stone-200 bg-white p-6 shadow-2xl">

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-100">
                                <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                        d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                </svg>
                            </div>

                            <h3 class="mt-5 text-lg font-black text-stone-950">
                                Hapus Promo?
                            </h3>

                            <p class="mt-2 text-sm font-semibold leading-6 text-stone-500">
                                Promo <span class="font-black text-stone-900">{{ $promotion->name }}</span> akan dihapus
                                dari
                                daftar promo. Promo ini tidak akan digunakan lagi pada transaksi baru.
                            </p>

                            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                <button type="button" @click="confirmOpen = false"
                                    class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 transition hover:bg-stone-50">
                                    Batal
                                </button>

                                <form method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="inline-flex w-full items-center justify-center rounded-2xl bg-rose-600 px-5 py-3 text-sm font-black text-white transition hover:bg-rose-700 active:scale-[0.98] sm:w-auto">
                                        Ya, Hapus Promo
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
