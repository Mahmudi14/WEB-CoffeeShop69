@extends('layouts.master')

@section('title', 'Tambah Pengeluaran')
@section('header-title', 'Tambah Pengeluaran')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <section class="relative overflow-hidden rounded-[2rem] bg-[#171412] p-6 shadow-xl border border-white/10">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)] pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                <div>
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-3 py-1.5">
                        <span class="h-2 w-2 rounded-full bg-rose-400"></span>

                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Cashier Expense
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Tambah Pengeluaran
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-stone-300">
                        Catat pengeluaran operasional selama shift aktif.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-1 xl:w-auto">
                    <a href="{{ route('cashier.expenses.index') }}"
                        class="inline-flex h-[52px] items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-5 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                        Kembali
                    </a>
                </div>
            </div>
        </section>
        {{-- Form --}}
        <div class="w-full">
            <div class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('cashier.expenses.store') }}" class="space-y-5">
                    @csrf
                    <div class="grid gap-5 lg:grid-cols-2">
                        <div>
                            <label for="category" class="mb-2 block text-sm font-bold text-stone-700">
                                Kategori Pengeluaran
                            </label>

                            <input id="category" type="text" name="category" value="{{ old('category') }}" required
                                placeholder="Contoh: Beli es batu, plastik, gas"
                                class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">
                        </div>

                        <div>
                            <label for="amount" class="mb-2 block text-sm font-bold text-stone-700">
                                Nominal
                            </label>

                            <div class="relative">
                                <span
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5 text-sm font-black text-stone-500">
                                    Rp
                                </span>

                                <input id="amount" type="text" name="amount" inputmode="numeric"
                                    value="{{ old('amount') }}" required placeholder="20.000" data-money-input
                                    class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 pl-12 pr-5 text-sm font-black text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">
                            </div>

                            <p class="mt-2 text-xs font-semibold text-stone-500">
                                Isi nominal dalam rupiah.
                            </p>
                        </div>
                    </div>

                    <div>
                        <label for="note" class="mb-2 block text-sm font-bold text-stone-700">
                            Catatan
                        </label>

                        <textarea id="note" name="note" rows="5" placeholder="Opsional"
                            class="w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 py-4 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">{{ old('note') }}</textarea>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <a href="{{ route('cashier.expenses.index') }}"
                            class="inline-flex h-[52px] items-center justify-center rounded-2xl border border-stone-200 bg-white px-6 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                            Batal
                        </a>

                        <button type="submit"
                            class="inline-flex h-[52px] items-center justify-center rounded-2xl bg-amber-600 px-6 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700 active:scale-[0.98]">
                            Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
