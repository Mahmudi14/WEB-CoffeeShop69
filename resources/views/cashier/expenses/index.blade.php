@extends('layouts.master')

@section('title', 'Pengeluaran Shift')
@section('header-title', 'Pengeluaran Shift')

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
                        Pengeluaran Shift
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-stone-300">
                        Catat biaya operasional selama shift aktif.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-1 xl:w-auto">
                    <a href="{{ route('cashier.expenses.create') }}"
                        class="inline-flex h-full min-h-[55px] items-center justify-center rounded-2xl bg-amber-500 px-5 py-4 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                        Tambah Pengeluaran
                    </a>
                </div>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-stone-500">Kasir</p>
                <p class="mt-3 text-xl font-black text-stone-950">
                    {{ auth()->user()->name }}
                </p>
            </div>

            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-stone-500">Shift Dibuka</p>
                <p class="mt-3 text-xl font-black text-stone-950">
                    {{ $activeShift->opened_at->format('d M Y H:i') }}
                </p>
            </div>

            <div class="rounded-3xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-rose-700">Total Pengeluaran</p>
                <p class="mt-3 text-2xl font-black text-rose-800">
                    Rp{{ number_format($totalExpense, 0, ',', '.') }}
                </p>
            </div>
        </section>

        <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-200 px-6 py-5">
                <h3 class="text-lg font-black text-stone-950">
                    Daftar Pengeluaran
                </h3>
                <p class="mt-1 text-sm text-stone-500">
                    Pengeluaran ini akan mengurangi kas estimasi shift, bukan mengurangi omzet.
                </p>
            </div>

            @if ($expenses->isEmpty())
                <div class="p-8 text-center">
                    <p class="text-sm font-bold text-stone-600">
                        Belum ada pengeluaran.
                    </p>
                    <p class="mt-2 text-xs text-stone-500">
                        Jika ada biaya operasional selama shift, tambahkan dari tombol di atas.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Waktu
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Kategori
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Catatan
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Nominal
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100 bg-white">
                            @foreach ($expenses as $expense)
                                <tr class="hover:bg-stone-50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-stone-700">
                                        {{ $expense->created_at->format('d M Y H:i') }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-stone-950">
                                        {{ $expense->category }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-stone-600">
                                        {{ $expense->note ?: '-' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-black text-rose-600">
                                        Rp{{ number_format($expense->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot class="bg-stone-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-black text-stone-700">
                                    Total
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-black text-rose-700">
                                    Rp{{ number_format($totalExpense, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
