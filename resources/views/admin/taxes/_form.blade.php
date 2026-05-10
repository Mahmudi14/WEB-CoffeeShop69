<div class="grid gap-5">
    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Nama Pajak
        </label>

        <input type="text" name="name" value="{{ old('name', $tax->name ?? 'PPN') }}" required
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
            placeholder="Contoh: PPN">

        @error('name')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Persentase Pajak
        </label>

        <div class="relative">
            <input type="number" step="0.01" min="0" max="100" name="rate"
                value="{{ old('rate', isset($tax) ? $tax->rate : 0) }}" required
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 pr-12 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                placeholder="Contoh: 11">

            <span
                class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-sm font-black text-stone-400">
                %
            </span>
        </div>

        @error('rate')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-3 md:grid-cols-2">
        <label class="flex items-start gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $tax->is_active ?? true))
                class="mt-1 h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

            <span>
                <span class="block text-sm font-bold text-stone-700">
                    Pajak aktif
                </span>
                <span class="mt-1 block text-xs font-semibold text-stone-500">
                    Jika aktif, pajak lain otomatis dinonaktifkan.
                </span>
            </span>
        </label>

        <label class="flex items-start gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
            <input type="checkbox" name="price_includes_tax" value="1" @checked(old('price_includes_tax', $tax->price_includes_tax ?? false))
                class="mt-1 h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

            <span>
                <span class="block text-sm font-bold text-stone-700">
                    Harga sudah termasuk pajak
                </span>
                <span class="mt-1 block text-xs font-semibold text-stone-500">
                    Centang jika harga menu sudah include pajak.
                </span>
            </span>
        </label>
    </div>
</div>
