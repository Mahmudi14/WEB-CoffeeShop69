@extends('layouts.master')

@section('title', 'Checkout POS')

@section('content')
    <div class="w-full">
        <div class="mb-6">
            <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                POS Checkout
            </p>

            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">
                Checkout Order
            </h2>

            <p class="mt-2 text-sm leading-6 text-stone-600">
                Pastikan data customer, tipe order, dan pembayaran sudah benar.
            </p>
        </div>

        <div
            class="grid w-full items-start gap-6 lg:grid-cols-[minmax(0,7fr)_minmax(360px,5fr)] 2xl:grid-cols-[minmax(0,8fr)_minmax(420px,4fr)]">
            {{-- LEFT: Customer & Payment Form --}}
            <section class="min-w-0">
                <form method="POST" action="{{ route('cashier.pos.store') }}" x-data="checkoutPosForm({
                    orderType: @js(old('order_type', \App\Models\Order::TYPE_DINE_IN)),
                    dineInType: @js(\App\Models\Order::TYPE_DINE_IN),
                    paymentMethod: @js(old('payment_method', \App\Models\Payment::METHOD_CASH)),
                    cashMethod: @js(\App\Models\Payment::METHOD_CASH),
                    grandTotal: @js((int) $pricing['grand_total']),
                    paidAmount: @js(old('paid_amount', number_format($pricing['grand_total'], 0, ',', '.')))
                })"
                    @submit="handleSubmit($event)"
                    class="space-y-6 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                    @csrf

                    <input type="hidden" name="order_submit_token" value="{{ $orderSubmitToken }}">

                    <input type="hidden" name="paid_amount"
                        :value="paymentMethod === cashMethod ? parseMoney(paidAmount) : grandTotal">

                    {{-- Customer --}}
                    <div>
                        <label class="mb-2 block text-sm font-bold text-stone-700">
                            Nama Customer
                        </label>

                        <input type="text" name="customer_name" value="{{ old('customer_name', 'Walk-in Customer') }}"
                            required
                            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                    </div>

                    {{-- Order Type --}}
                    <div>
                        <label class="mb-3 block text-sm font-bold text-stone-700">
                            Tipe Order
                        </label>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <label
                                class="cursor-pointer rounded-2xl border border-stone-200 p-4 transition hover:bg-stone-50">
                                <input type="radio" name="order_type" value="{{ \App\Models\Order::TYPE_DINE_IN }}"
                                    x-model="orderType" class="text-amber-600 focus:ring-amber-500">

                                <span class="ml-2 text-sm font-black text-stone-800">
                                    Dine-in
                                </span>
                            </label>

                            <label
                                class="cursor-pointer rounded-2xl border border-stone-200 p-4 transition hover:bg-stone-50">
                                <input type="radio" name="order_type" value="{{ \App\Models\Order::TYPE_TAKEAWAY }}"
                                    x-model="orderType" class="text-amber-600 focus:ring-amber-500">

                                <span class="ml-2 text-sm font-black text-stone-800">
                                    Takeaway
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div x-show="orderType === dineInType" x-cloak x-data="tableDropdown(@js((string) old('table_id', '')))" @keydown.escape.window="close()">
                        <label class="mb-2 block text-sm font-bold text-stone-700">
                            Pilih Meja
                        </label>

                        <input type="hidden" name="table_id" :value="orderType === dineInType ? selectedTable : ''">

                        <div class="relative">
                            <button type="button" @click="toggle()"
                                class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                                <span class="min-w-0 truncate">
                                    <span x-show="selectedTable === ''">
                                        Pilih meja
                                    </span>

                                    @foreach ($tables as $table)
                                        <span x-show="selectedTable === @js((string) $table->id)" x-cloak>
                                            {{ $table->name }}
                                        </span>
                                    @endforeach
                                </span>

                                <svg class="h-4 w-4 shrink-0 text-stone-400 transition"
                                    :class="tableOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="tableOpen" x-cloak x-transition.origin.top @click.outside="close()"
                                class="absolute left-0 right-0 top-[54px] z-50 max-h-64 overflow-y-auto rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                                <button type="button" @click="select('')"
                                    class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                    :class="selectedTable === '' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                                    <span>Pilih meja</span>

                                    <svg x-show="selectedTable === ''" x-cloak class="h-4 w-4 shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>

                                @foreach ($tables as $table)
                                    <button type="button" @click="select(@js((string) $table->id))"
                                        class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                        :class="selectedTable === @js((string) $table->id) ? 'bg-amber-100 text-amber-800' :
                                            'text-stone-700'">
                                        <span class="truncate">
                                            {{ $table->name }}
                                        </span>

                                        <svg x-show="selectedTable === @js((string) $table->id)" x-cloak
                                            class="h-4 w-4 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <label class="mb-3 block text-sm font-bold text-stone-700">
                            Metode Pembayaran
                        </label>

                        <div class="grid gap-3 sm:grid-cols-3">
                            <label
                                class="cursor-pointer rounded-2xl border border-stone-200 p-4 transition hover:bg-stone-50">
                                <input type="radio" name="payment_method" value="{{ \App\Models\Payment::METHOD_CASH }}"
                                    x-model="paymentMethod" class="text-amber-600 focus:ring-amber-500">

                                <span class="ml-2 text-sm font-black text-stone-800">
                                    Cash
                                </span>
                            </label>

                            <label
                                class="cursor-pointer rounded-2xl border border-stone-200 p-4 transition hover:bg-stone-50">
                                <input type="radio" name="payment_method" value="{{ \App\Models\Payment::METHOD_QRIS }}"
                                    x-model="paymentMethod" class="text-amber-600 focus:ring-amber-500">

                                <span class="ml-2 text-sm font-black text-stone-800">
                                    QRIS
                                </span>
                            </label>

                            <label
                                class="cursor-pointer rounded-2xl border border-stone-200 p-4 transition hover:bg-stone-50">
                                <input type="radio" name="payment_method"
                                    value="{{ \App\Models\Payment::METHOD_TRANSFER }}" x-model="paymentMethod"
                                    class="text-amber-600 focus:ring-amber-500">

                                <span class="ml-2 text-sm font-black text-stone-800">
                                    Transfer
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Customer Note --}}
                    <div>
                        <label class="mb-2 block text-sm font-bold text-stone-700">
                            Catatan Pesanan
                        </label>

                        <textarea name="customer_note" rows="3" placeholder="Opsional"
                            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">{{ old('customer_note') }}</textarea>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <a href="{{ route('cashier.pos.index') }}"
                            :class="submitting ? 'pointer-events-none opacity-60' : ''"
                            class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                            Kembali ke POS
                        </a>

                        <button type="button" :disabled="submitting" @click="handleOrderButtonClick($el.closest('form'))"
                            class="inline-flex min-w-[180px] items-center justify-center rounded-2xl bg-amber-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700 disabled:cursor-not-allowed disabled:opacity-60">
                            Order
                        </button>
                    </div>

                    {{-- Payment Modal - Cash Only --}}
                    <div x-show="showPaymentModal && paymentMethod === cashMethod" x-cloak x-transition.opacity
                        @keydown.escape.window="closePaymentModal()"
                        class="fixed inset-0 z-[999] flex items-center justify-center bg-stone-950/60 px-4">
                        <div x-show="showPaymentModal && paymentMethod === cashMethod" x-transition.scale.origin.center
                            @click.outside="closePaymentModal()"
                            class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                                        Konfirmasi Order
                                    </p>

                                    <h3 class="mt-2 text-2xl font-black tracking-tight text-stone-950">
                                        Masukkan Nominal
                                    </h3>

                                    <p class="mt-2 text-sm leading-6 text-stone-500">
                                        Pastikan nominal pembayaran sudah sesuai sebelum order dibuat.
                                    </p>
                                </div>

                                <button type="button" @click="closePaymentModal()" :disabled="submitting"
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-stone-100 text-stone-500 transition hover:bg-stone-200 disabled:cursor-not-allowed disabled:opacity-60">
                                    ✕
                                </button>
                            </div>

                            <div class="mt-5 rounded-2xl bg-amber-50 p-4">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-sm font-black text-amber-800">
                                        Total Bayar
                                    </span>

                                    <span class="text-xl font-black text-amber-800">
                                        Rp{{ number_format($pricing['grand_total'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-5">
                                <label class="mb-2 block text-xs font-black uppercase tracking-wider text-stone-500">
                                    Uang Diterima
                                </label>

                                <div class="relative">
                                    <span
                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-black text-stone-500">
                                        Rp
                                    </span>

                                    <input type="text" inputmode="numeric" x-ref="paidAmountInput"
                                        x-model="paidAmount" @input="formatPaidAmount()"
                                        class="w-full rounded-2xl border border-stone-200 bg-white py-4 pl-11 pr-4 text-lg font-black text-stone-950 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                                        placeholder="0">
                                </div>

                                <p class="mt-2 text-xs font-semibold text-stone-500">
                                    Nominal ini digunakan untuk menghitung pembayaran dan kembalian.
                                </p>
                            </div>

                            <div class="mt-4 rounded-2xl border border-stone-200 bg-stone-50 p-4">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-sm font-black text-stone-600">
                                        Kembalian
                                    </span>

                                    <span class="text-xl font-black text-stone-950">
                                        Rp<span x-text="formatMoney(changeAmount())"></span>
                                    </span>
                                </div>

                                <template x-if="remainingAmount() > 0">
                                    <p class="mt-2 text-xs font-bold text-rose-600">
                                        Uang diterima masih kurang Rp<span x-text="formatMoney(remainingAmount())"></span>
                                    </p>
                                </template>

                                <template x-if="remainingAmount() === 0 && parseMoney(paidAmount) >= grandTotal">
                                    <p class="mt-2 text-xs font-bold text-emerald-700">
                                        Nominal pembayaran sudah cukup.
                                    </p>
                                </template>
                            </div>

                            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                <button type="button" @click="closePaymentModal()" :disabled="submitting"
                                    class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-5 py-3 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 disabled:cursor-not-allowed disabled:opacity-60">
                                    Batal
                                </button>

                                <button type="submit" :disabled="submitting || parseMoney(paidAmount) < grandTotal"
                                    class="inline-flex min-w-[180px] items-center justify-center rounded-2xl bg-amber-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700 disabled:cursor-not-allowed disabled:opacity-60">
                                    <span x-show="!submitting">
                                        Konfirmasi Order
                                    </span>

                                    <span x-show="submitting" x-cloak>
                                        Memproses...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            {{-- RIGHT: Order Summary --}}
            <aside class="min-w-0">
                <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm lg:sticky lg:top-24">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                                Order Summary
                            </p>

                            <h3 class="mt-2 text-lg font-black text-stone-950">
                                Ringkasan Order
                            </h3>
                        </div>
                    </div>

                    <div
                        class="mt-5 max-h-none space-y-3 overflow-y-visible lg:max-h-[calc(100dvh-26rem)] lg:overflow-y-auto lg:pr-1">
                        @foreach ($pricing['items'] as $item)
                            <div class="rounded-2xl bg-stone-50 p-4">
                                <div class="flex justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-black text-stone-950">
                                            {{ $item['menu_name'] }}
                                        </p>

                                        <p class="mt-1 text-xs text-stone-500">
                                            {{ $item['quantity'] }} x
                                            Rp{{ number_format($item['final_price'], 0, ',', '.') }}
                                        </p>

                                        @if ($item['total_discount'] > 0)
                                            <p class="mt-1 text-xs font-bold text-emerald-700">
                                                Diskon Rp{{ number_format($item['total_discount'], 0, ',', '.') }}
                                            </p>
                                        @endif
                                    </div>

                                    <p class="shrink-0 text-sm font-black text-stone-950">
                                        Rp{{ number_format($item['subtotal_after_discount'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 space-y-3 border-t border-stone-200 pt-5">
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="font-bold text-stone-500">
                                Subtotal Normal
                            </span>

                            <span class="font-black text-stone-950">
                                Rp{{ number_format($pricing['subtotal_before_discount'], 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4 text-sm">
                            <span class="font-bold text-stone-500">
                                Diskon Promo
                            </span>

                            <span class="font-black text-emerald-700">
                                -Rp{{ number_format($pricing['discount_total'], 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4 text-sm">
                            <span class="font-bold text-stone-500">
                                Subtotal Setelah Diskon
                            </span>

                            <span class="font-black text-stone-950">
                                Rp{{ number_format($pricing['subtotal_after_discount'], 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4 text-sm">
                            <span class="font-bold text-stone-500">
                                PPN {{ $pricing['tax_rate'] }}%
                            </span>

                            <span class="font-black text-stone-950">
                                Rp{{ number_format($pricing['tax_total'], 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 rounded-2xl bg-amber-50 px-4 py-4">
                            <span class="text-sm font-black text-amber-800">
                                Total Bayar
                            </span>

                            <span class="text-right text-2xl font-black text-amber-800">
                                Rp{{ number_format($pricing['grand_total'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>


    <script>
        function checkoutPosForm(config) {
            return {
                submitting: false,
                showPaymentModal: false,

                orderType: config.orderType,
                dineInType: config.dineInType,

                paymentMethod: config.paymentMethod,
                cashMethod: config.cashMethod,

                grandTotal: Number(config.grandTotal || 0),
                paidAmount: config.paidAmount || '0',

                handleOrderButtonClick(form) {
                    if (this.submitting) {
                        return;
                    }

                    if (this.paymentMethod === this.cashMethod) {
                        this.openPaymentModal();
                        return;
                    }

                    form.requestSubmit();
                },

                handleSubmit(event) {
                    if (this.submitting) {
                        event.preventDefault();
                        return;
                    }

                    if (
                        this.paymentMethod === this.cashMethod &&
                        this.parseMoney(this.paidAmount) < this.grandTotal
                    ) {
                        event.preventDefault();
                        this.showPaymentModal = true;
                        return;
                    }

                    this.submitting = true;
                },

                openPaymentModal() {
                    if (this.paymentMethod !== this.cashMethod) {
                        return;
                    }

                    if (this.parseMoney(this.paidAmount) <= 0) {
                        this.paidAmount = this.formatMoney(this.grandTotal);
                    }

                    this.showPaymentModal = true;

                    this.$nextTick(() => {
                        this.$refs.paidAmountInput?.focus();
                    });
                },

                closePaymentModal() {
                    if (this.submitting) {
                        return;
                    }

                    this.showPaymentModal = false;
                },

                parseMoney(value) {
                    return Number(String(value || '').replace(/\D/g, '')) || 0;
                },

                formatMoney(value) {
                    return new Intl.NumberFormat('id-ID').format(Number(value || 0));
                },

                formatPaidAmount() {
                    this.paidAmount = this.formatMoney(this.parseMoney(this.paidAmount));
                },

                changeAmount() {
                    return Math.max(this.parseMoney(this.paidAmount) - this.grandTotal, 0);
                },

                remainingAmount() {
                    return Math.max(this.grandTotal - this.parseMoney(this.paidAmount), 0);
                },
            };
        }

        function tableDropdown(initialTableId) {
            return {
                selectedTable: initialTableId,
                tableOpen: false,

                toggle() {
                    this.tableOpen = !this.tableOpen;
                },

                close() {
                    this.tableOpen = false;
                },

                select(tableId) {
                    this.selectedTable = tableId;
                    this.close();
                },
            };
        }
    </script>
@endsection
