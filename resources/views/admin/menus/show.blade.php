@extends('layouts.master')

@section('title', 'Detail Menu')
@section('header-title', 'Detail Menu')

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
                        <span class="h-2 w-2 rounded-full {{ $menu->is_active ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>

                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Menu Detail
                        </span>
                    </div>

                    <h2 class="truncate text-2xl font-black tracking-tight text-white sm:text-3xl">
                        {{ $menu->name }}
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-300">
                        Detail informasi menu, kategori, harga, status menu, ketersediaan, gambar, dan tindakan lanjutan.
                    </p>
                </div>

                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
                    <a href="{{ route('admin.menus.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-5 py-3 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                        Kembali
                    </a>

                    <a href="{{ route('admin.menus.edit', $menu) }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                        Edit Menu
                    </a>
                </div>
            </div>
        </section>

        {{-- Content --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_320px] lg:items-stretch">
            {{-- Detail Menu --}}
            <section class="h-full overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                <div class="border-b border-stone-100 px-6 py-5">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                        Informasi Menu
                    </p>

                    <h3 class="mt-2 text-xl font-black tracking-tight text-stone-950">
                        Data Menu
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-stone-500">
                        Informasi utama menu yang tersimpan di sistem.
                    </p>
                </div>

                <div class="divide-y divide-stone-100">
                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Nama Menu
                        </p>

                        <p class="min-w-0 break-words text-sm font-bold text-stone-900 sm:col-span-2">
                            {{ $menu->name }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Kategori
                        </p>

                        <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                            {{ $menu->category?->name ?? '-' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Harga Normal
                        </p>

                        <p class="text-sm font-black text-stone-950 sm:col-span-2">
                            Rp{{ number_format($menu->normal_price, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Status
                        </p>

                        <div class="flex flex-wrap gap-2 sm:col-span-2">
                            @if ($menu->is_active)
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

                            @if ($menu->is_available)
                                <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-black text-sky-700">
                                    Tersedia
                                </span>
                            @else
                                <span
                                    class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-xs font-black text-orange-700">
                                    Habis
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Deskripsi
                        </p>

                        <p
                            class="min-w-0 whitespace-pre-line break-words text-sm font-semibold leading-6 text-stone-700 sm:col-span-2">
                            {{ $menu->description ?: 'Tanpa deskripsi' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Dibuat Pada
                        </p>

                        <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                            {{ $menu->created_at->format('d M Y H:i') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Terakhir Diubah
                        </p>

                        <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                            {{ $menu->updated_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- Gambar Menu --}}
            <section class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                <div class="border-b border-stone-100 px-6 py-5">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                        Gambar Menu
                    </p>

                    <h3 class="mt-2 text-xl font-black tracking-tight text-stone-950">
                        Preview Gambar
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-stone-500">
                        Gambar yang digunakan untuk tampilan menu.
                    </p>
                </div>

                <div class="p-6">
                    <div
                        class="mx-auto aspect-[3/4] w-full max-w-xs overflow-hidden rounded-[2rem] border border-stone-200 bg-stone-100">
                        @if ($menu->image_path)
                            <img src="{{ Storage::url($menu->image_path) }}" alt="{{ $menu->name }}"
                                class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full flex-col items-center justify-center px-5 text-center">
                                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm">
                                    <svg class="h-7 w-7 text-stone-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                                    No Image
                                </p>

                                <p class="mt-1 text-sm font-semibold text-stone-500">
                                    Menu ini belum memiliki gambar.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
