@extends('layouts.master')

@section('title', 'Mulai Shift')
@section('header-title', 'Mulai Shift')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <section class="overflow-hidden rounded-[2rem] border border-stone-100 bg-white shadow-sm">
            <div class="bg-[#171412] p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div
                            class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5">
                            <span class="h-2 w-2 rounded-full bg-amber-400"></span>

                            <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                                Cashier Shift
                            </span>
                        </div>

                        <h1 class="text-2xl font-black tracking-tight text-white sm:text-3xl">
                            Mulai Shift
                        </h1>

                        <p class="mt-2 text-sm font-semibold text-stone-300">
                            Isi kas awal sebelum transaksi dimulai.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/10 px-5 py-4 backdrop-blur-md">
                        <p class="text-[11px] font-black uppercase tracking-widest text-stone-400">
                            Tanggal
                        </p>

                        <p class="mt-1 text-sm font-black text-amber-300">
                            {{ now()->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('cashier.shifts.store') }}" class="space-y-5 p-6">
                @csrf

                <div class="grid gap-5 lg:grid-cols-2">
                    <div>
                        <label for="opening_cash" class="mb-2 block text-sm font-bold text-stone-700">
                            Kas Awal
                        </label>

                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5 text-sm font-black text-stone-500">
                                Rp
                            </span>

                            <input id="opening_cash" type="text" name="opening_cash" inputmode="numeric"
                                value="{{ old('opening_cash', 0) }}" required data-money-input
                                class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 pl-12 pr-5 text-sm font-black text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">
                        </div>

                        <p class="mt-2 text-xs font-semibold text-stone-500">
                            Nominal kas awal dalam rupiah.
                        </p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-stone-700">
                            Kasir
                        </label>

                        <div
                            class="flex h-[52px] items-center rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-black text-stone-900">
                            {{ auth()->user()->name }}
                        </div>

                        <p class="mt-2 text-xs font-semibold text-stone-500">
                            Shift tercatat atas akun ini.
                        </p>
                    </div>
                </div>

                <div>
                    <label for="opening_note" class="mb-2 block text-sm font-bold text-stone-700">
                        Catatan Pembukaan Shift
                    </label>

                    <textarea id="opening_note" name="opening_note" rows="3"
                        class="w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 py-4 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100"
                        placeholder="Opsional">{{ old('opening_note') }}</textarea>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('cashier.dashboard') }}"
                        class="inline-flex h-[52px] items-center justify-center rounded-2xl border border-stone-200 bg-white px-6 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98]">
                        Batal
                    </a>

                    <button type="submit"
                        class="inline-flex h-[52px] items-center justify-center rounded-2xl bg-amber-600 px-6 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700 active:scale-[0.98]">
                        Mulai Shift
                    </button>
                </div>
            </form>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-money-input]').forEach(function(input) {
                input.addEventListener('input', function() {
                    let rawValue = input.value.replace(/\D/g, '');

                    if (rawValue === '') {
                        input.value = '';
                        return;
                    }

                    input.value = new Intl.NumberFormat('id-ID').format(Number(rawValue));
                });
            });
        });
    </script>
@endsection
