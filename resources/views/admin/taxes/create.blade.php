@extends('layouts.master')

@section('title', 'Tambah Pajak')
@section('header-title', 'Tambah Pajak')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                New Tax Setting
            </p>

            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">
                Tambah Pajak
            </h2>

            <p class="mt-2 text-sm leading-6 text-stone-600">
                Tambahkan pengaturan pajak baru untuk digunakan dalam transaksi berikutnya.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.taxes.store') }}"
            class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            @csrf

            @include('admin.taxes._form')

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.taxes.index') }}"
                    class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                    Batal
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-amber-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700">
                    Simpan Pajak
                </button>
            </div>
        </form>
    </div>
@endsection
