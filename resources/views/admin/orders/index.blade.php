@extends('layouts.master')

@section('title', 'Admin')
@section('header-title', 'Order')

@section('content')
    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-[2rem] bg-[#171412] p-6 shadow-xl border border-white/10">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)] pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                <div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-3 py-1.5 mb-4">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Order Monitoring
                        </span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Data Order
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-stone-300 max-w-3xl">
                        Pantau seluruh order, lihat detail pesanan, serta batalkan order
                        aktif jika diperlukan.
                    </p>
                </div>
            </div>
        </section>
        <section class="rounded-3xl border border-stone-200 bg-white p-4 shadow-sm lg:p-5">
            @php
                $orderStatusOptions = [
                    '' => [
                        'label' => 'Semua',
                        'active' => 'bg-amber-100 text-amber-800',
                        'hover' => 'hover:bg-amber-50 hover:text-amber-700',
                    ],
                    'pending_payment' => [
                        'label' => 'Menunggu Bayar',
                        'active' => 'bg-amber-100 text-amber-800',
                        'hover' => 'hover:bg-amber-50 hover:text-amber-700',
                    ],
                    'pending_payment_verification' => [
                        'label' => 'Menunggu Verifikasi',
                        'active' => 'bg-sky-100 text-sky-800',
                        'hover' => 'hover:bg-sky-50 hover:text-sky-700',
                    ],
                    'processing' => [
                        'label' => 'Processing',
                        'active' => 'bg-orange-100 text-orange-800',
                        'hover' => 'hover:bg-orange-50 hover:text-orange-700',
                    ],
                    'completed' => [
                        'label' => 'Completed',
                        'active' => 'bg-emerald-100 text-emerald-800',
                        'hover' => 'hover:bg-emerald-50 hover:text-emerald-700',
                    ],
                    'cancelled' => [
                        'label' => 'Cancelled',
                        'active' => 'bg-rose-100 text-rose-800',
                        'hover' => 'hover:bg-rose-50 hover:text-rose-700',
                    ],
                    'rejected' => [
                        'label' => 'Rejected',
                        'active' => 'bg-rose-100 text-rose-800',
                        'hover' => 'hover:bg-rose-50 hover:text-rose-700',
                    ],
                    'expired' => [
                        'label' => 'Expired',
                        'active' => 'bg-stone-200 text-stone-900',
                        'hover' => 'hover:bg-stone-100 hover:text-stone-800',
                    ],
                ];

                $sourceOptions = [
                    '' => [
                        'label' => 'Semua',
                        'active' => 'bg-amber-100 text-amber-800',
                        'hover' => 'hover:bg-amber-50 hover:text-amber-700',
                    ],
                    'cashier_pos' => [
                        'label' => 'POS Kasir',
                        'active' => 'bg-amber-100 text-amber-800',
                        'hover' => 'hover:bg-amber-50 hover:text-amber-700',
                    ],
                    'customer_qr' => [
                        'label' => 'QR Customer',
                        'active' => 'bg-sky-100 text-sky-800',
                        'hover' => 'hover:bg-sky-50 hover:text-sky-700',
                    ],
                ];
            @endphp

            <form action="{{ route('admin.orders.index') }}" method="GET"
                class="grid gap-3 lg:grid-cols-[minmax(0,1.2fr)_minmax(220px,1fr)_minmax(160px,0.8fr)_max-content] lg:items-end xl:grid-cols-[minmax(0,1.4fr)_minmax(260px,1fr)_minmax(180px,0.8fr)_max-content]">

                {{-- Search --}}
                <div class="min-w-0">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Cari Order
                    </label>

                    <input type="text" name="search" value="{{ request('search', $search ?? '') }}"
                        placeholder="Nomor / customer"
                        class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                </div>

                {{-- Status Order --}}
                <div class="min-w-0" x-data="filterDropdown(@js((string) ($orderStatus ?? request('order_status'))))" @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Status Order
                    </label>

                    <input type="hidden" name="order_status" x-model="selectedValue">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                @foreach ($orderStatusOptions as $value => $option)
                                    <span x-show="selectedValue === @js((string) $value)" x-cloak>
                                        {{ $option['label'] }}
                                    </span>
                                @endforeach
                            </span>

                            <svg class="h-4 w-4 shrink-0 text-stone-400 transition"
                                :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" x-cloak x-transition.origin.top @click.outside="close()"
                            class="absolute left-0 right-0 top-full z-50 mt-2 max-h-72 overflow-y-auto rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                            @foreach ($orderStatusOptions as $value => $option)
                                <button type="button" @click="select(@js((string) $value))"
                                    class="{{ $loop->first ? '' : 'mt-1' }} flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition {{ $option['hover'] }}"
                                    :class="selectedValue === @js((string) $value) ? '{{ $option['active'] }}' :
                                        'text-stone-700'">
                                    <span class="truncate">
                                        {{ $option['label'] }}
                                    </span>

                                    <svg x-show="selectedValue === @js((string) $value)" x-cloak
                                        class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Source --}}
                <div class="min-w-0" x-data="filterDropdown(@js((string) ($source ?? request('source'))))" @keydown.escape.window="close()">
                    <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-400">
                        Sumber
                    </label>

                    <input type="hidden" name="source" x-model="selectedValue">

                    <div class="relative">
                        <button type="button" @click="toggle()"
                            class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                            <span class="min-w-0 truncate">
                                @foreach ($sourceOptions as $value => $option)
                                    <span x-show="selectedValue === @js((string) $value)" x-cloak>
                                        {{ $option['label'] }}
                                    </span>
                                @endforeach
                            </span>

                            <svg class="h-4 w-4 shrink-0 text-stone-400 transition"
                                :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" x-cloak x-transition.origin.top @click.outside="close()"
                            class="absolute left-0 right-0 top-full z-50 mt-2 overflow-hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                            @foreach ($sourceOptions as $value => $option)
                                <button type="button" @click="select(@js((string) $value))"
                                    class="{{ $loop->first ? '' : 'mt-1' }} flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition {{ $option['hover'] }}"
                                    :class="selectedValue === @js((string) $value) ? '{{ $option['active'] }}' :
                                        'text-stone-700'">
                                    <span>
                                        {{ $option['label'] }}
                                    </span>

                                    <svg x-show="selectedValue === @js((string) $value)" x-cloak
                                        class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2 lg:justify-end">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center whitespace-nowrap rounded-2xl bg-stone-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-stone-800 active:scale-[0.98] lg:flex-none">
                        Filter
                    </button>

                    <a href="{{ route('admin.orders.index') }}"
                        class="inline-flex flex-1 items-center justify-center whitespace-nowrap rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98] lg:flex-none">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
            <div class="border-b border-stone-200 px-6 py-5">
                <h3 class="text-lg font-black text-stone-950">
                    Daftar Order
                </h3>

                <p class="mt-1 text-sm text-stone-500">
                    Total data: {{ $orders->total() }}
                </p>
            </div>

            @if ($orders->isEmpty())
                <div class="p-10 text-center">
                    <p class="text-sm font-black text-stone-700">
                        Belum ada order.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Order</th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Sumber</th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Total</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-stone-50">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <p class="text-sm font-black text-stone-950">
                                            {{ $order->order_number }}
                                        </p>
                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            {{ $order->created_at->format('d M Y H:i') }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-stone-800">
                                            {{ $order->customer_name }}
                                        </p>
                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            {{ $order->table?->name ?? 'Takeaway / Tanpa Meja' }}
                                        </p>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            class="rounded-full bg-stone-100 px-3 py-1 text-xs font-black text-stone-700">
                                            {{ $order->order_source === 'cashier_pos' ? 'POS Kasir' : 'QR Customer' }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <span
                                                class="w-fit rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-700">
                                                {{ str_replace('_', ' ', $order->order_status) }}
                                            </span>

                                            <span
                                                class="w-fit rounded-full bg-sky-100 px-3 py-1 text-xs font-black text-sky-700">
                                                {{ str_replace('_', ' ', $order->payment_status) }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-black text-stone-950">
                                        Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="inline-flex rounded-2xl border border-stone-200 bg-white px-4 py-2 text-xs font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-stone-200 px-6 py-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </section>
    </div>
    <script>
        function filterDropdown(initialValue) {
            return {
                selectedValue: initialValue || '',
                dropdownOpen: false,

                toggle() {
                    this.dropdownOpen = !this.dropdownOpen;
                },

                close() {
                    this.dropdownOpen = false;
                },

                select(value) {
                    this.selectedValue = value;
                    this.close();
                },
            };
        }
    </script>
@endsection
