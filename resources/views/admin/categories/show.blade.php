@extends('layouts.master')

@section('title', 'Detail Kategori')
@section('header-title', 'Detail Kategori')

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
                            class="h-2 w-2 rounded-full {{ $category->is_active ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>

                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Category Detail
                        </span>
                    </div>

                    <h2 class="truncate text-2xl font-black tracking-tight text-white sm:text-3xl">
                        {{ $category->name }}
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-300">
                        Detail informasi kategori menu, status, urutan tampil, jumlah menu, dan tindakan penghapusan.
                    </p>
                </div>

                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
                    <a href="{{ route('admin.categories.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-5 py-3 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                        Kembali
                    </a>

                    <a href="{{ route('admin.categories.edit', $category) }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                        Edit
                    </a>
                </div>
            </div>
        </section>

        {{-- Informasi Kategori --}}
        <section class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-100 px-6 py-5">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                    Informasi Kategori
                </p>

                <h3 class="mt-2 text-xl font-black tracking-tight text-stone-950">
                    Data Kategori
                </h3>

                <p class="mt-2 text-sm leading-6 text-stone-500">
                    Informasi utama kategori yang tersimpan di sistem.
                </p>
            </div>

            <div class="divide-y divide-stone-100">
                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Nama Kategori
                    </p>

                    <p class="min-w-0 break-words text-sm font-bold text-stone-900 sm:col-span-2">
                        {{ $category->name }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Deskripsi
                    </p>

                    <p
                        class="min-w-0 whitespace-pre-line break-words text-sm font-semibold leading-6 text-stone-700 sm:col-span-2">
                        {{ $category->description ?: 'Tanpa deskripsi' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Urutan
                    </p>

                    <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                        {{ $category->sort_order }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Jumlah Menu
                    </p>

                    <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                        {{ $category->menus_count ?? $category->menus()->count() }} menu
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Status
                    </p>

                    <div class="sm:col-span-2">
                        @if ($category->is_active)
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
                        {{ $category->created_at->format('d M Y H:i') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                    <p class="text-sm font-black text-stone-500">
                        Terakhir Diubah
                    </p>

                    <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                        {{ $category->updated_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </section>

        {{-- Danger Zone --}}
        <section
            class="{{ $category->is_active ? 'border-rose-200 bg-rose-50' : 'border-emerald-200 bg-emerald-50' }} rounded-[2rem] border p-5 shadow-sm">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <p
                        class="{{ $category->is_active ? 'text-rose-500' : 'text-emerald-600' }} text-xs font-black uppercase tracking-[0.22em]">
                        Status Action
                    </p>

                    <h3 class="{{ $category->is_active ? 'text-rose-800' : 'text-emerald-800' }} mt-2 text-lg font-black">
                        {{ $category->is_active ? 'Nonaktifkan Kategori' : 'Aktifkan Kategori' }}
                    </h3>

                    <p
                        class="{{ $category->is_active ? 'text-rose-700' : 'text-emerald-700' }} mt-2 text-sm font-semibold leading-6">
                        @if ($category->is_active)
                            Kategori tidak akan dihapus dari sistem, tetapi akan dinonaktifkan agar tidak digunakan sebagai
                            kategori aktif.
                        @else
                            Kategori ini sedang nonaktif. Aktifkan kembali jika kategori ini ingin digunakan lagi.
                        @endif
                    </p>
                </div>

                <div x-data="{ confirmOpen: false }" class="shrink-0">
                    <button type="button" @click="confirmOpen = true"
                        class="{{ $category->is_active
                            ? 'border-rose-300 bg-rose-600 text-white hover:bg-rose-700'
                            : 'border-emerald-300 bg-emerald-600 text-white hover:bg-emerald-700' }} inline-flex w-full items-center justify-center rounded-2xl border px-5 py-3 text-sm font-black transition active:scale-[0.98] lg:w-auto">
                        {{ $category->is_active ? 'Nonaktifkan Kategori' : 'Aktifkan Kategori' }}
                    </button>

                    {{-- Modal Confirmation --}}
                    <div x-cloak x-show="confirmOpen" x-transition.opacity
                        class="fixed inset-0 z-50 flex items-center justify-center bg-stone-950/60 px-4">
                        <div x-show="confirmOpen" x-transition.scale.origin.center @click.outside="confirmOpen = false"
                            class="w-full max-w-md rounded-[2rem] border border-stone-200 bg-white p-6 shadow-2xl">

                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $category->is_active ? 'bg-rose-100' : 'bg-emerald-100' }}">
                                @if ($category->is_active)
                                    <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                            d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                    </svg>
                                @else
                                    <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </div>

                            <h3 class="mt-5 text-lg font-black text-stone-950">
                                {{ $category->is_active ? 'Nonaktifkan Kategori?' : 'Aktifkan Kategori?' }}
                            </h3>

                            <p class="mt-2 text-sm font-semibold leading-6 text-stone-500">
                                @if ($category->is_active)
                                    Kategori ini akan dinonaktifkan dan tidak digunakan sebagai kategori aktif.
                                @else
                                    Kategori ini akan diaktifkan kembali dan bisa digunakan lagi.
                                @endif
                            </p>

                            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                <button type="button" @click="confirmOpen = false"
                                    class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 transition hover:bg-stone-50">
                                    Batal
                                </button>

                                <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                        class="{{ $category->is_active ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }} inline-flex w-full items-center justify-center rounded-2xl px-5 py-3 text-sm font-black text-white transition active:scale-[0.98] sm:w-auto">
                                        {{ $category->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}
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
