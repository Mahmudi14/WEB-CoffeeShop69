@extends('layouts.master')

@section('title', 'Detail Kasir')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <section class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-[#171412] p-6 shadow-xl">
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)]">
            </div>

            <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5">
                        <span
                            class="h-2 w-2 rounded-full {{ $cashier->is_active ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>

                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Cashier Detail
                        </span>
                    </div>

                    <h2 class="text-2xl font-black tracking-tight text-white sm:text-3xl">
                        {{ $cashier->name }}
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-300">
                        Detail informasi kasir, status akun, serta tindakan lanjutan seperti edit, aktivasi, nonaktivasi,
                        atau penghapusan data kasir.
                    </p>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <a href="{{ route('admin.cashiers.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-5 py-3 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                        Kembali
                    </a>

                    <a href="{{ route('admin.cashiers.edit', $cashier) }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                        Edit
                    </a>
                </div>
            </div>
        </section>

        {{-- Alert --}}
        @if (session('success'))
            <div
                class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="space-y-6">
            {{-- Informasi Kasir --}}
            <section class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                <div class="border-b border-stone-100 px-6 py-5">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                        Informasi Kasir
                    </p>

                    <h3 class="mt-2 text-xl font-black tracking-tight text-stone-950">
                        Data Akun Kasir
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-stone-500">
                        Informasi utama akun kasir yang tersimpan di sistem.
                    </p>
                </div>

                <div class="divide-y divide-stone-100">
                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Nama Kasir
                        </p>

                        <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                            {{ $cashier->name }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Email
                        </p>

                        <p class="break-words text-sm font-bold text-stone-900 sm:col-span-2">
                            {{ $cashier->email }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Nomor Telepon
                        </p>

                        <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                            {{ $cashier->phone ?: '-' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Status
                        </p>

                        <div class="sm:col-span-2">
                            @if ($cashier->is_active)
                                <span
                                    class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                    Aktif
                                </span>
                            @else
                                <span
                                    class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-black text-rose-700">
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
                            {{ $cashier->created_at->format('d M Y H:i') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-3">
                        <p class="text-sm font-black text-stone-500">
                            Terakhir Diubah
                        </p>

                        <p class="text-sm font-bold text-stone-900 sm:col-span-2">
                            {{ $cashier->updated_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- Danger Zone --}}
            <section class="rounded-[2rem] border border-rose-200 bg-rose-50 p-5 shadow-sm">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-rose-500">
                            Danger Zone
                        </p>

                        <h3 class="mt-2 text-lg font-black text-rose-800">
                            Hapus Kasir
                        </h3>

                        <p class="mt-2 text-sm font-semibold leading-6 text-rose-700">
                            Tindakan ini akan menghapus data kasir dari sistem. Pastikan kasir ini memang tidak lagi
                            dibutuhkan.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.cashiers.destroy', $cashier) }}" class="shrink-0"
                        onsubmit="return confirm('Yakin ingin menghapus kasir ini? Data yang dihapus tidak bisa dikembalikan.')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-2xl border border-rose-300 bg-rose-600 px-5 py-3 text-sm font-black text-white transition hover:bg-rose-700 active:scale-[0.98] lg:w-auto">
                            Hapus Kasir
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
