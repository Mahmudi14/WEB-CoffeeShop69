@extends('layouts.master')

@section('title', 'Edit Promo')
@section('header-title', 'Edit Promo')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                Edit Promotion
            </p>

            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">
                Edit Promo
            </h2>

            <p class="mt-2 text-sm leading-6 text-stone-600">
                Ubah konfigurasi promo. Perubahan akan memengaruhi transaksi berikutnya.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.promotions.update', $promotion) }}"
            class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            @include('admin.promotions._form', ['promotion' => $promotion])

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.promotions.index') }}"
                    class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                    Batal
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-amber-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
