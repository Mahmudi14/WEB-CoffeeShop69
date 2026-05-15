<div class="grid gap-5">
    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Nama Kategori
        </label>

        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
            placeholder="Contoh: Coffee">

        @error('name')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Deskripsi
        </label>

        <textarea name="description" rows="4"
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
            placeholder="Opsional">{{ old('description', $category->description ?? '') }}</textarea>

        @error('description')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 lg:grid-cols-[260px_1fr] lg:items-start">
        <div>
            <label for="sort_order" class="mb-2 block text-sm font-bold text-stone-700">
                Urutan Tampil
            </label>

            <input type="number" id="sort_order" name="sort_order"
                value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0" required
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">

            <p class="mt-2 text-xs font-semibold text-stone-500">
                Angka lebih kecil tampil lebih dulu.
            </p>

            @error('sort_order')
                <p class="mt-2 text-xs font-bold text-rose-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <p class="mb-2 block text-sm font-bold text-stone-700">
                Status Kategori
            </p>

            <label
                class="flex min-h-[48px] items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 transition hover:bg-stone-100">
                <input type="hidden" name="is_active" value="0">

                <input type="checkbox" name="is_active" value="1" @checked((bool) old('is_active', $category->is_active ?? true))
                    class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

                <span class="text-sm font-bold text-stone-700">
                    Kategori aktif
                </span>
            </label>

            <p class="mt-2 text-xs font-semibold text-stone-500">
                Kategori aktif akan ditampilkan dan bisa digunakan di menu.
            </p>

            @error('is_active')
                <p class="mt-2 text-xs font-bold text-rose-600">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>
</div>
