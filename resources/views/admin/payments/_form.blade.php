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

                <div class="grid gap-5 lg:grid-cols-[1fr_260px]">
                    <div>
                        <label for="qr_image" class="mb-2 block text-sm font-bold text-stone-700">
                            Gambar QRIS
                        </label>

                        <input id="qr_image" type="file" name="qr_image" accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 py-3 text-sm font-semibold text-stone-900 outline-none transition file:mr-4 file:rounded-xl file:border-0 file:bg-stone-950 file:px-4 file:py-2 file:text-sm file:font-black file:text-white hover:file:bg-stone-800 focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">

                        <p class="mt-2 text-xs font-semibold text-stone-500">
                            Dipakai untuk metode QRIS. Format: JPG, PNG, WEBP. Maksimal 2MB.
                        </p>

                        @error('qr_image')
                            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <p class="mb-2 text-sm font-bold text-stone-700">
                            Preview
                        </p>

                        <div
                            class="flex min-h-[160px] items-center justify-center rounded-2xl border border-dashed border-stone-300 bg-stone-50 p-4">
                            @if ($channel->qr_image_path)
                                <img src="{{ asset('storage/' . $channel->qr_image_path) }}" alt="{{ $channel->name }}"
                                    class="max-h-40 rounded-2xl object-contain">
                            @else
                                <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                                    Belum ada QR
                                </p>
                            @endif
                        </div>

                        @if ($channel->qr_image_path)
                            <label class="mt-3 flex items-center gap-2 text-xs font-bold text-rose-600">
                                <input type="checkbox" name="remove_qr_image" value="1"
                                    class="rounded border-stone-300 text-rose-600 focus:ring-rose-500">
                                Hapus gambar QR
                            </label>
                        @endif
                    </div>
                </div>

                <div>
                    <label for="note" class="mb-2 block text-sm font-bold text-stone-700">
                        Catatan / Instruksi
                    </label>

                    <textarea id="note" name="note" rows="4" placeholder="Contoh: Setelah transfer, upload bukti pembayaran."
                        class="w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 py-4 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">{{ old('note', $channel->note) }}</textarea>

                    @error('note')
                        <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-5 lg:grid-cols-2">
                    <div>
                        <label for="sort_order" class="mb-2 block text-sm font-bold text-stone-700">
                            Urutan
                        </label>

                        <input id="sort_order" type="number" name="sort_order" min="0"
                            value="{{ old('sort_order', $channel->sort_order ?? 0) }}"
                            class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">

                        @error('sort_order')
                            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-stone-700">
                            Status
                        </label>

                        <input type="hidden" name="is_active" value="0">

                        <label
                            class="flex h-[52px] items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-5">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $channel->exists ? $channel->is_active : true))
                                class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">

                            <span class="text-sm font-black text-stone-800">
                                Aktif
                            </span>
                        </label>
                    </div>
                </div>

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
@endsection
