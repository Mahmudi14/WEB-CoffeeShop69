@extends('layouts.master')

@section('title', 'Edit Menu')
@section('header-title', 'Edit Menu')

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
                        <span class="h-2 w-2 rounded-full bg-amber-400"></span>

                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Edit Menu
                        </span>
                    </div>

                    <h2 class="text-2xl font-black tracking-tight text-white sm:text-3xl">
                        Edit Menu
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-300">
                        Ubah data menu. Jika gambar tidak diganti, gambar lama tetap digunakan.
                    </p>
                </div>

                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
                    <a href="{{ route('admin.menus.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-5 py-3 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                        Kembali
                    </a>

                    <a href="{{ route('admin.menus.show', $menu) }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                        Detail Menu
                    </a>
                </div>
            </div>
        </section>

        {{-- Form Section --}}
        <section class="rounded-[2rem] border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-100 px-6 py-5">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                    Form Menu
                </p>

                <h3 class="mt-2 text-xl font-black tracking-tight text-stone-950">
                    Data Menu
                </h3>

                <p class="mt-2 text-sm leading-6 text-stone-500">
                    Pastikan nama, kategori, harga, status, dan gambar menu sudah benar sebelum menyimpan perubahan.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.menus.update', $menu) }}" enctype="multipart/form-data"
                class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    @include('admin.menus._form', ['menu' => $menu])
                </div>

                <div class="mt-8 flex flex-col gap-3 border-t border-stone-100 pt-6 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.menus.index') }}"
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
@endsection
