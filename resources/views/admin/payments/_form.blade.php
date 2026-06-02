@extends('layouts.master')

@section('title', $channel->exists ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran')
@section('header-title', $channel->exists ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran')

@section('content')
    @php
        $isEdit = $channel->exists;
        $selectedMethod = old('method', $channel->method ?: \App\Models\PaymentChannel::METHOD_QRIS);
    @endphp

    <div class="mx-auto max-w-5xl space-y-6">
        <section class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-[#171412] p-6 shadow-xl">
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)]">
            </div>

            <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5">
                        <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Payment Channel
                        </span>
                    </div>

                    <h1 class="text-2xl font-black tracking-tight text-white sm:text-3xl">
                        {{ $isEdit ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran' }}
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-stone-300">
                        Data ini ditampilkan pada halaman pembayaran customer.
                    </p>
                </div>

                <a href="{{ route('admin.payment-channels.index') }}"
                    class="inline-flex h-[52px] items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-6 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                    Kembali
                </a>
            </div>
        </section>

        <section class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
            <form method="POST"
                action="{{ $isEdit ? route('admin.payment-channels.update', $channel) : route('admin.payment-channels.store') }}"
                enctype="multipart/form-data" class="space-y-6">
                @csrf

                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="grid gap-5 lg:grid-cols-2">
                    <div>
                        <label for="method" class="mb-2 block text-sm font-bold text-stone-700">
                            Jenis Metode
                        </label>

                        <select id="method" name="method"
                            class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-4 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">
                            @foreach ($methodLabels as $value => $label)
                                <option value="{{ $value }}" @selected($selectedMethod === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        @error('method')
                            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="mb-2 block text-sm font-bold text-stone-700">
                            Nama Tampilan
                        </label>

                        <input id="name" type="text" name="name" value="{{ old('name', $channel->name) }}"
                            placeholder="Contoh: QRIS 69 Coffee / BCA / DANA"
                            class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">

                        @error('name')
                            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid gap-5 lg:grid-cols-2">
                    <div>
                        <label for="account_name" class="mb-2 block text-sm font-bold text-stone-700">
                            Nama Pemilik
                        </label>

                        <input id="account_name" type="text" name="account_name"
                            value="{{ old('account_name', $channel->account_name) }}" placeholder="Contoh: 69 Coffee Shop"
                            class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">

                        @error('account_name')
                            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="account_number" class="mb-2 block text-sm font-bold text-stone-700">
                            Nomor Rekening / E-Wallet
                        </label>

                        <input id="account_number" type="text" name="account_number"
                            value="{{ old('account_number', $channel->account_number) }}"
                            placeholder="Contoh: 1234567890 / 085355600550"
                            class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">

                        @error('account_number')
                            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Gambar QRIS --}}
                <div x-data="paymentQrPreview(@js($channel->qr_image_path ? asset('storage/' . $channel->qr_image_path) : null))">
                    <label class="mb-2 block text-sm font-bold text-stone-700">
                        Gambar QRIS
                    </label>

                    <div class="grid gap-4 lg:grid-cols-[180px,1fr] lg:items-start">
                        {{-- Preview --}}
                        <div
                            class="h-44 w-full overflow-hidden rounded-3xl border border-stone-200 bg-stone-100 lg:h-40 lg:w-44">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" alt="Preview gambar QRIS"
                                    class="h-full w-full object-contain p-3">
                            </template>

                            <template x-if="!imagePreview">
                                <div class="flex h-full w-full flex-col items-center justify-center px-4 text-center">
                                    <div
                                        class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-white shadow-sm">
                                        <svg class="h-6 w-6 text-stone-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4h6v6H3V4zm12 0h6v6h-6V4zM3 14h6v6H3v-6zm12 0h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v2h-2v-2z" />
                                        </svg>
                                    </div>

                                    <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                                        No QR
                                    </p>

                                    <p class="mt-1 text-xs font-semibold text-stone-500">
                                        Belum ada QRIS
                                    </p>
                                </div>
                            </template>
                        </div>

                        {{-- Input --}}
                        <div class="min-w-0">
                            <label for="qr_image"
                                class="flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed border-stone-200 bg-stone-50 px-5 py-6 text-center transition hover:border-amber-400 hover:bg-amber-50">
                                <svg class="h-8 w-8 text-stone-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 16V4m0 0L8 8m4-4l4 4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                                </svg>

                                <span class="mt-3 text-sm font-black text-stone-800">
                                    Pilih Gambar QRIS
                                </span>

                                <span class="mt-1 text-xs font-semibold text-stone-500">
                                    JPG, PNG, atau WEBP. Maksimal 2MB.
                                </span>

                                <span x-show="fileName" x-cloak
                                    class="mt-3 max-w-full truncate rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-700"
                                    x-text="fileName">
                                </span>
                            </label>

                            <input id="qr_image" type="file" name="qr_image"
                                accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden"
                                @change="previewImage($event)">

                            @if ($channel->qr_image_path)
                                <p class="mt-3 text-xs font-semibold text-stone-500">
                                    Gambar QR lama akan tetap digunakan jika tidak memilih gambar baru.
                                </p>

                                <label
                                    class="mt-3 flex items-center gap-2 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-xs font-bold text-rose-700">
                                    <input type="checkbox" name="remove_qr_image" value="1"
                                        class="rounded border-stone-300 text-rose-600 focus:ring-rose-500">

                                    Hapus gambar QR
                                </label>
                            @else
                                <p class="mt-3 text-xs font-semibold text-stone-500">
                                    Jika tidak memilih gambar, metode pembayaran tetap tersimpan tanpa QRIS.
                                </p>
                            @endif

                            @error('qr_image')
                                <p class="mt-2 text-xs font-bold text-rose-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                @if ($isEdit)
                    <div>
                        <label class="mb-2 block text-sm font-bold text-stone-700">
                            Status
                        </label>

                        <input type="hidden" name="is_active" value="0">

                        <label
                            class="flex h-[52px] items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-5">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $channel->is_active))
                                class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">

                            <span class="text-sm font-black text-stone-800">
                                Aktif
                            </span>
                        </label>

                        @error('is_active')
                            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.payment-channels.index') }}"
                        class="inline-flex h-[52px] items-center justify-center rounded-2xl border border-stone-200 bg-white px-6 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98]">
                        Batal
                    </a>

                    <button type="submit"
                        class="inline-flex h-[52px] items-center justify-center rounded-2xl bg-amber-600 px-6 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700 active:scale-[0.98]">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Metode' }}
                    </button>
                </div>
            </form>
        </section>
    </div>
    <script>
        document.addEventListener('alpine:init', function() {
            if (!window.__paymentQrPreviewRegistered) {
                window.__paymentQrPreviewRegistered = true;

                Alpine.data('paymentQrPreview', function(initialImage = null) {
                    return {
                        imagePreview: initialImage,
                        fileName: '',
                        objectUrl: null,

                        previewImage(event) {
                            const file = event.target.files[0];

                            if (!file) {
                                this.fileName = '';
                                return;
                            }

                            if (this.objectUrl) {
                                URL.revokeObjectURL(this.objectUrl);
                            }

                            this.fileName = file.name;
                            this.objectUrl = URL.createObjectURL(file);
                            this.imagePreview = this.objectUrl;
                        }
                    };
                });
            }
        });
    </script>
@endsection
