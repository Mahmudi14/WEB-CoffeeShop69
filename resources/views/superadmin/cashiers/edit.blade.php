@extends('layouts.master')

@section('title', 'Edit Kasir')
@section('header-title', 'Edit Kasir')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                Edit Cashier Account
            </p>

            <h1 class="mt-2 text-3xl font-black tracking-tight text-stone-950">
                Edit Kasir
            </h1>

            <p class="mt-2 text-sm leading-6 text-stone-600">
                Perbarui data akun kasir. Kosongkan password jika tidak ingin mengganti password.
            </p>
        </div>

        <form method="POST" action="{{ route('superadmin.cashiers.update', $cashier) }}"
            class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            @include('superadmin.cashiers._form', ['cashier' => $cashier])

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('superadmin.cashiers.index') }}"
                    class="inline-flex h-[52px] items-center justify-center rounded-2xl border border-stone-200 bg-white px-6 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                    Batal
                </a>

                <button type="submit"
                    class="inline-flex h-[52px] items-center justify-center rounded-2xl bg-amber-600 px-6 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
