@extends('layouts.master')

@section('title', 'Edit Kasir')
@section('header-title', 'Edit Kasir')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                Edit Cashier
            </p>

            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">
                Edit Kasir
            </h2>

            <p class="mt-2 text-sm leading-6 text-stone-600">
                Ubah data akun kasir. Kosongkan password jika tidak ingin mengganti password.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.cashiers.update', $cashier) }}"
            class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            @include('admin.cashiers._form', ['cashier' => $cashier])

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.cashiers.index') }}"
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
