@extends('layouts.master')

@section('title', 'Tambah Promo')
@section('header-title', 'Tambah Promo')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                New Promotion
            </p>

            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">
                Tambah Promo
            </h2>

            <p class="mt-2 text-sm leading-6 text-stone-600">
                Buat promo baru untuk semua menu atau menu tertentu.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.promotions.store') }}"
            class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            @csrf

            @include('admin.promotions._form')

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.promotions.index') }}"
                    class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                    Batal
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-amber-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700">
                    Simpan Promo
                </button>
            </div>
        </form>
    </div>
@endsection
