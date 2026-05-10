<div class="grid gap-5">
    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Kategori
        </label>

        <select name="category_id" required
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $menu->category_id ?? '') === (string) $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        @error('category_id')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Nama Menu
        </label>

        <input type="text" name="name" value="{{ old('name', $menu->name ?? '') }}" required
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
            placeholder="Contoh: Es Kopi Susu">

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
            placeholder="Opsional">{{ old('description', $menu->description ?? '') }}</textarea>

        @error('description')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Harga Normal
            </label>

            <input type="number" name="normal_price" value="{{ old('normal_price', $menu->normal_price ?? 0) }}"
                min="0" required
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">

            @error('normal_price')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Gambar Menu
        </label>

        @if (isset($menu) && $menu->image)
            <div class="mb-3 h-32 w-32 overflow-hidden rounded-3xl bg-stone-100">
                <img src="{{ \Illuminate\Support\Facades\Storage::url($menu->image) }}" alt="{{ $menu->name }}"
                    class="h-full w-full object-cover">
            </div>
        @endif

        <input type="file" name="image" accept="image/*"
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition file:mr-4 file:rounded-xl file:border-0 file:bg-amber-100 file:px-4 file:py-2 file:text-sm file:font-black file:text-amber-700 hover:file:bg-amber-200">

        <p class="mt-2 text-xs font-semibold text-stone-500">
            Opsional. Maksimal 2MB.
        </p>

        @error('image')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-3 md:grid-cols-2">
        <label class="flex items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $menu->is_active ?? true))
                class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

            <span class="text-sm font-bold text-stone-700">
                Menu aktif
            </span>
        </label>

        <label class="flex items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
            <input type="checkbox" name="is_available" value="1" @checked(old('is_available', $menu->is_available ?? true))
                class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

            <span class="text-sm font-bold text-stone-700">
                Menu tersedia
            </span>
        </label>
    </div>
</div>
