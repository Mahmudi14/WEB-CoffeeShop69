<div class="grid gap-5">
    {{-- Nama Meja + Kode Meja --}}
    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="name" class="mb-2 block text-sm font-bold text-stone-700">
                Nama Meja
            </label>

            <input type="text" id="name" name="name" value="{{ old('name', $table->name ?? '') }}" required
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                placeholder="Contoh: Meja 1">

            @error('name')
                <p class="mt-2 text-xs font-bold text-rose-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="code" class="mb-2 block text-sm font-bold text-stone-700">
                Kode Meja
            </label>

            <input type="text" id="code" name="code" value="{{ old('code', $table->code ?? '') }}" required
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold uppercase text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                placeholder="Contoh: TBL-001">

            @error('code')
                <p class="mt-2 text-xs font-bold text-rose-600">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <p class="-mt-2 text-xs font-semibold text-stone-500">
        Kode meja harus unik. Contoh: TBL-001, TBL-002.
    </p>

    {{-- Status Meja --}}
    <div>
        <p class="mb-2 block text-sm font-bold text-stone-700">
            Status Meja
        </p>

        <div class="grid gap-3 md:grid-cols-2">
            <label
                class="flex cursor-pointer items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 transition hover:bg-emerald-100">
                <input type="radio" name="is_active" value="1" @checked((string) old('is_active', $table->is_active ?? true) === '1')
                    class="h-4 w-4 border-stone-300 text-emerald-600 focus:ring-emerald-500">

                <div>
                    <p class="text-sm font-black text-emerald-800">
                        Aktif
                    </p>

                    <p class="mt-1 text-xs font-semibold text-emerald-700">
                        QR meja dapat digunakan customer.
                    </p>
                </div>
            </label>

            <label
                class="flex cursor-pointer items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 transition hover:bg-rose-100">
                <input type="radio" name="is_active" value="0" @checked((string) old('is_active', $table->is_active ?? true) === '0')
                    class="h-4 w-4 border-stone-300 text-rose-600 focus:ring-rose-500">

                <div>
                    <p class="text-sm font-black text-rose-800">
                        Nonaktif
                    </p>

                    <p class="mt-1 text-xs font-semibold text-rose-700">
                        QR meja tidak digunakan untuk ordering.
                    </p>
                </div>
            </label>
        </div>

        @error('is_active')
            <p class="mt-2 text-xs font-bold text-rose-600">
                {{ $message }}
            </p>
        @enderror
    </div>
</div>
