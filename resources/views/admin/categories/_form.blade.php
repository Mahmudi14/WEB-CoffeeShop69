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

    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Urutan Tampil
        </label>

        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
            min="0" required
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">

        <p class="mt-2 text-xs font-semibold text-stone-500">
            Angka lebih kecil tampil lebih dulu.
        </p>

        @error('sort_order')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <label class="flex items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))
            class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

        <span class="text-sm font-bold text-stone-700">
            Kategori aktif
        </span>
    </label>
</div>
