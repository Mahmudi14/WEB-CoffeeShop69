@extends('layouts.customer', ['title' => 'Checkout'])

@section('content')
    <div x-data="{ paymentMethod: '{{ old('payment_method', \App\Models\Payment::METHOD_CASH) }}' }" class="mx-auto min-h-screen max-w-md px-4 py-6">
        <header class="mb-6">
            <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                Checkout
            </p>
            <h1 class="mt-2 text-3xl font-black text-stone-950">
                {{ $table->name }}
            </h1>
            <p class="mt-2 text-sm text-stone-500">
                Periksa pesanan dan pilih metode pembayaran.
            </p>
        </header>

        @if ($errors->any())
            <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-bold text-rose-700">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="mb-5 rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-black text-stone-950">Ringkasan Pesanan</h2>

            <div class="mt-4 space-y-3">
                @foreach ($pricing['items'] as $item)
                    <div class="rounded-2xl bg-stone-50 p-4">
                        <div class="flex justify-between gap-4">
                            <div>
                                <p class="text-sm font-black text-stone-950">{{ $item['menu_name'] }}</p>
                                <p class="mt-1 text-xs text-stone-500">
                                    {{ $item['quantity'] }} x Rp{{ number_format($item['final_price'], 0, ',', '.') }}
                                </p>

                                @if ($item['total_discount'] > 0)
                                    <p class="mt-1 text-xs font-bold text-emerald-700">
                                        Diskon Rp{{ number_format($item['total_discount'], 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>

                            <p class="text-sm font-black text-stone-950">
                                Rp{{ number_format($item['subtotal_after_discount'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-5 space-y-3 border-t border-stone-200 pt-5">
                <div class="flex justify-between text-sm">
                    <span class="font-bold text-stone-500">Subtotal Normal</span>
                    <span
                        class="font-black text-stone-950">Rp{{ number_format($pricing['subtotal_before_discount'], 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="font-bold text-stone-500">Diskon</span>
                    <span
                        class="font-black text-emerald-700">-Rp{{ number_format($pricing['discount_total'], 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="font-bold text-stone-500">PPN {{ $pricing['tax_rate'] }}%</span>
                    <span
                        class="font-black text-stone-950">Rp{{ number_format($pricing['tax_total'], 0, ',', '.') }}</span>
                </div>

                <div class="flex items-center justify-between rounded-2xl bg-amber-50 px-4 py-4">
                    <span class="text-sm font-black text-amber-800">Total Bayar</span>
                    <span class="text-2xl font-black text-amber-800">
                        Rp{{ number_format($pricing['grand_total'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </section>

        <form method="POST" action="{{ route('customer.qr.store', $table->qr_token) }}" enctype="multipart/form-data"
            class="space-y-5 rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-bold text-stone-700">Nama Customer</label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                    class="w-full rounded-2xl border border-stone-200 px-4 py-3 text-sm font-semibold outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                    placeholder="Masukkan nama">
            </div>

            <div x-data="{
                paymentMethod: @js(old('payment_method', 'cash')),
                resetProofIfCash() {
                    if (this.paymentMethod === 'cash' && this.$refs.proofInput) {
                        this.$refs.proofInput.value = '';
                    }
                }
            }" x-effect="resetProofIfCash()">

                <div>
                    <label class="mb-3 block text-sm font-bold text-stone-700">
                        Metode Pembayaran
                    </label>

                    <div class="grid gap-3 sm:grid-cols-3">
                        <label class="cursor-pointer rounded-2xl border border-stone-200 p-4 transition hover:bg-stone-50"
                            :class="paymentMethod === 'cash' ? 'border-amber-500 bg-amber-50' : ''">
                            <input type="radio" name="payment_method" value="cash" x-model="paymentMethod"
                                class="text-amber-600 focus:ring-amber-500">

                            <span class="ml-2 text-sm font-black text-stone-800">
                                Cash
                            </span>
                        </label>

                        <label class="cursor-pointer rounded-2xl border border-stone-200 p-4 transition hover:bg-stone-50"
                            :class="paymentMethod === 'qris' ? 'border-amber-500 bg-amber-50' : ''">
                            <input type="radio" name="payment_method" value="qris" x-model="paymentMethod"
                                class="text-amber-600 focus:ring-amber-500">

                            <span class="ml-2 text-sm font-black text-stone-800">
                                QRIS
                            </span>
                        </label>

                        <label class="cursor-pointer rounded-2xl border border-stone-200 p-4 transition hover:bg-stone-50"
                            :class="paymentMethod === 'transfer' ? 'border-amber-500 bg-amber-50' : ''">
                            <input type="radio" name="payment_method" value="transfer" x-model="paymentMethod"
                                class="text-amber-600 focus:ring-amber-500">

                            <span class="ml-2 text-sm font-black text-stone-800">
                                Transfer
                            </span>
                        </label>
                    </div>
                </div>

                {{-- QRIS --}}
                <div x-show="paymentMethod === 'qris'" x-cloak class="mt-4 space-y-3">
                    @forelse (($paymentChannels['qris'] ?? collect()) as $channel)
                        <div x-data="{
                            downloaded: false,
                            downloadQris() {
                                if (this.downloaded) return;
                        
                                this.$refs.downloadLink.click();
                                this.downloaded = true;
                        
                                setTimeout(() => {
                                    this.downloaded = false;
                                }, 2000);
                            }
                        }" class="rounded-2xl border border-stone-200 bg-white p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-black text-stone-950">
                                        {{ $channel->name }}
                                    </h3>

                                    @if ($channel->note)
                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            {{ $channel->note }}
                                        </p>
                                    @endif
                                </div>

                                @if ($channel->qr_image_path)
                                    @php
                                        $qrisExtension = pathinfo($channel->qr_image_path, PATHINFO_EXTENSION) ?: 'png';
                                    @endphp

                                    <a x-ref="downloadLink" href="{{ asset('storage/' . $channel->qr_image_path) }}"
                                        download="qris-{{ \Illuminate\Support\Str::slug($channel->name) }}.{{ $qrisExtension }}"
                                        class="hidden"></a>

                                    <button type="button" :disabled="downloaded" @click="downloadQris()"
                                        class="shrink-0 rounded-xl px-3 py-1.5 text-xs font-black transition disabled:cursor-not-allowed"
                                        :class="downloaded
                                            ?
                                            'bg-emerald-100 text-emerald-700' :
                                            'bg-stone-950 text-white'">
                                        <span x-text="downloaded ? 'Diunduh' : 'Unduh'"></span>
                                    </button>
                                @endif
                            </div>

                            @if ($channel->qr_image_path)
                                <div class="mt-3 flex justify-center">
                                    <img src="{{ asset('storage/' . $channel->qr_image_path) }}"
                                        alt="{{ $channel->name }}"
                                        class="w-full max-w-56 rounded-2xl border border-stone-200">
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-3 text-xs font-bold text-rose-700">
                            QRIS belum tersedia.
                        </div>
                    @endforelse
                </div>

                {{-- Transfer --}}
                <div x-show="paymentMethod === 'transfer'" x-cloak class="mt-4 space-y-3">
                    @forelse (($paymentChannels['transfer'] ?? collect()) as $channel)
                        <div x-data="{ copied: false }" class="rounded-2xl border border-stone-200 bg-white p-4">
                            <p class="text-[11px] font-black uppercase tracking-wider text-stone-400">
                                {{ $channel->name }}
                            </p>

                            <div class="mt-2 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="truncate text-base font-black text-stone-950">
                                        {{ $channel->account_number }}
                                    </h3>

                                    <p class="mt-0.5 truncate text-xs font-semibold text-stone-500">
                                        a.n. {{ $channel->account_name ?: '-' }}
                                    </p>
                                </div>

                                <button type="button" :disabled="copied"
                                    @click="
                        navigator.clipboard.writeText(@js($channel->account_number));
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                    "
                                    class="shrink-0 rounded-xl px-3 py-1.5 text-xs font-black transition disabled:cursor-not-allowed"
                                    :class="copied
                                        ?
                                        'bg-emerald-100 text-emerald-700' :
                                        'bg-stone-950 text-white'">
                                    <span x-text="copied ? 'Tersalin' : 'Salin'"></span>
                                </button>
                            </div>

                            @if ($channel->note)
                                <p class="mt-2 text-xs font-semibold text-stone-500">
                                    {{ $channel->note }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-3 text-xs font-bold text-rose-700">
                            Rekening transfer belum tersedia.
                        </div>
                    @endforelse
                </div>

                {{-- Upload Bukti --}}
                <div x-show="paymentMethod === 'qris' || paymentMethod === 'transfer'" x-cloak x-data="{
                    proofName: '',
                    clearProof() {
                        this.proofName = '';
                        this.$refs.proofInput.value = '';
                    }
                }"
                    class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                    <p class="text-sm font-black text-amber-800">
                        Upload Bukti Pembayaran
                    </p>

                    <p class="mt-1 text-xs font-semibold text-amber-700">
                        Pesanan diproses setelah kasir memverifikasi bukti pembayaran.
                    </p>

                    <input x-ref="proofInput" type="file" name="proof" accept=".jpg,.jpeg,.png,.webp,.pdf"
                        class="hidden" @change="proofName = $event.target.files[0]?.name || ''">

                    <button type="button" @click="$refs.proofInput.click()"
                        class="mt-4 flex w-full items-center justify-center rounded-2xl border-2 border-dashed border-amber-300 bg-white px-4 py-5 text-center transition hover:border-amber-400 hover:bg-amber-100">
                        <div>
                            <p class="text-sm font-black text-amber-800">
                                Pilih bukti pembayaran
                            </p>
                            <p class="mt-1 text-xs font-semibold text-amber-600">
                                JPG, PNG, WEBP, atau PDF
                            </p>
                        </div>
                    </button>

                    <template x-if="proofName">
                        <div
                            class="mt-3 flex items-center justify-between gap-3 rounded-2xl border border-amber-200 bg-white px-4 py-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-black text-stone-900" x-text="proofName"></p>
                                <p class="mt-0.5 text-xs font-semibold text-stone-500">
                                    File siap diupload
                                </p>
                            </div>

                            <button type="button" @click="clearProof()"
                                class="shrink-0 rounded-xl bg-rose-50 px-3 py-1.5 text-xs font-black text-rose-600">
                                Hapus
                            </button>
                        </div>
                    </template>

                    @error('proof')
                        <p class="mt-2 text-xs font-bold text-rose-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-stone-700">Catatan</label>
                <textarea name="note" rows="3"
                    class="w-full rounded-2xl border border-stone-200 px-4 py-3 text-sm outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                    placeholder="Opsional">{{ old('note') }}</textarea>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('customer.qr.menu', $table->qr_token) }}"
                    class="flex-1 rounded-2xl border border-stone-200 bg-white px-5 py-3 text-center text-sm font-black text-stone-700">
                    Kembali
                </a>

                <button type="submit" class="flex-1 rounded-2xl bg-amber-600 px-5 py-3 text-sm font-black text-white">
                    Kirim Order
                </button>
            </div>
        </form>
    </div>
@endsection
