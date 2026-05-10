@extends('layouts.master')

@section('title', 'Admin')
@section('header-title', 'Metode Pembayaran')

@section('content')
    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-[#171412] p-6 shadow-xl">
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)]">
            </div>

            <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Payment Channels
                        </span>
                    </div>

                    <h1 class="text-2xl font-black tracking-tight text-white sm:text-3xl">
                        Metode Pembayaran
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-stone-300">
                        Kelola QRIS, rekening transfer, dan nomor e-wallet untuk halaman customer.
                    </p>
                </div>

                <a href="{{ route('admin.payment-channels.create') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                    Tambah Metode
                </a>
            </div>
        </section>

        <section class="overflow-hidden rounded-[2rem] border border-stone-100 bg-white shadow-sm">
            <div class="border-b border-stone-100 px-6 py-5">
                <h2 class="text-lg font-black text-stone-950">
                    Daftar Metode Pembayaran
                </h2>
            </div>

            @if ($channels->isEmpty())
                <div class="p-10 text-center">
                    <p class="text-sm font-black text-stone-700">
                        Belum ada metode pembayaran.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-100">
                        <thead class="bg-stone-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-400">
                                    Metode
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-400">
                                    Informasi
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-400">
                                    QR
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-400">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-400">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100">
                            @foreach ($channels as $channel)
                                <tr class="hover:bg-stone-50">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <p class="text-sm font-black text-stone-950">
                                            {{ $channel->method_label }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="text-sm font-black text-stone-900">
                                            {{ $channel->name }}
                                        </p>

                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            a.n. {{ $channel->account_name ?: '-' }}
                                        </p>

                                        <p class="mt-1 text-xs font-black text-stone-700">
                                            {{ $channel->account_number ?: '-' }}
                                        </p>

                                        @if ($channel->note)
                                            <p class="mt-2 max-w-md text-xs leading-5 text-stone-500">
                                                {{ $channel->note }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($channel->qr_image_path)
                                            <img src="{{ asset('storage/' . $channel->qr_image_path) }}"
                                                alt="{{ $channel->name }}"
                                                class="h-16 w-16 rounded-2xl border border-stone-200 object-cover">
                                        @else
                                            <span class="text-xs font-bold text-stone-400">
                                                Tidak ada
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        @if ($channel->is_active)
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
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.payment-channels.edit', $channel) }}"
                                                class="inline-flex rounded-2xl border border-stone-200 bg-white px-4 py-2 text-xs font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                action="{{ route('admin.payment-channels.destroy', $channel) }}"
                                                onsubmit="return confirm('Hapus metode pembayaran ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="inline-flex rounded-2xl bg-rose-600 px-4 py-2 text-xs font-black text-white shadow-sm transition hover:bg-rose-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
