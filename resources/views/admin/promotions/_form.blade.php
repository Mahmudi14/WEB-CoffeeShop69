@php
    $selectedMenus = collect(old('menu_ids', isset($promotion) ? $promotion->menus->pluck('id')->toArray() : []))
        ->map(fn($id) => (string) $id)
        ->toArray();

    $currentScope = old('scope', $promotion->scope ?? \App\Models\Promotion::SCOPE_ALL_MENU);
    $currentDiscountType = old(
        'discount_type',
        $promotion->discount_type ?? \App\Models\Promotion::DISCOUNT_PERCENTAGE,
    );
@endphp

<div class="grid gap-5">
    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Nama Promo
        </label>

        <input type="text" name="name" value="{{ old('name', $promotion->name ?? '') }}" required
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
            placeholder="Contoh: Promo Kopi Sore">

        @error('name')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Deskripsi
        </label>

        <textarea name="description" rows="3"
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
            placeholder="Opsional">{{ old('description', $promotion->description ?? '') }}</textarea>

        @error('description')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Cakupan Promo
            </label>

            <select name="scope" id="promo-scope"
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                <option value="all_menu" @selected($currentScope === 'all_menu')>Semua Menu</option>
                <option value="selected_menu" @selected($currentScope === 'selected_menu')>Menu Tertentu</option>
            </select>

            @error('scope')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Prioritas
            </label>

            <input type="number" name="priority" value="{{ old('priority', $promotion->priority ?? 1) }}"
                min="1" required
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">

            <p class="mt-2 text-xs font-semibold text-stone-500">
                Angka kecil diproses lebih dulu.
            </p>

            @error('priority')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Jenis Diskon
            </label>

            <select name="discount_type"
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                <option value="percentage" @selected($currentDiscountType === 'percentage')>Persentase (%)</option>
                <option value="fixed" @selected($currentDiscountType === 'fixed')>Nominal Rupiah</option>
            </select>

            @error('discount_type')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Nilai Diskon
            </label>

            <input type="number" step="0.01" name="discount_value"
                value="{{ old('discount_value', isset($promotion) ? $promotion->discount_value : '') }}" min="0.01"
                required
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                placeholder="Contoh: 10 atau 5000">

            @error('discount_value')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Mulai Berlaku
            </label>

            <input type="datetime-local" name="starts_at"
                value="{{ old('starts_at', isset($promotion) && $promotion->starts_at ? $promotion->starts_at->format('Y-m-d\TH:i') : '') }}"
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">

            @error('starts_at')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Berakhir
            </label>

            <input type="datetime-local" name="ends_at"
                value="{{ old('ends_at', isset($promotion) && $promotion->ends_at ? $promotion->ends_at->format('Y-m-d\TH:i') : '') }}"
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">

            @error('ends_at')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div id="selected-menu-section" class="{{ $currentScope === 'selected_menu' ? '' : 'hidden' }}">
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Pilih Menu
        </label>

        <div class="max-h-72 overflow-y-auto rounded-2xl border border-stone-200 bg-stone-50 p-3">
            <div class="grid gap-2 md:grid-cols-2">
                @foreach ($menus as $menu)
                    <label class="flex items-start gap-3 rounded-xl bg-white p-3 shadow-sm">
                        <input type="checkbox" name="menu_ids[]" value="{{ $menu->id }}"
                            @checked(in_array((string) $menu->id, $selectedMenus, true))
                            class="mt-1 h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

                        <span>
                            <span class="block text-sm font-black text-stone-800">
                                {{ $menu->name }}
                            </span>
                            <span class="block text-xs font-semibold text-stone-500">
                                {{ $menu->category?->name ?? 'Tanpa kategori' }} •
                                Rp{{ number_format($menu->normal_price, 0, ',', '.') }}
                            </span>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        @error('menu_ids')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <label class="flex items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $promotion->is_active ?? true))
            class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

        <span class="text-sm font-bold text-stone-700">
            Promo aktif
        </span>
    </label>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scopeInput = document.getElementById('promo-scope');
        const selectedMenuSection = document.getElementById('selected-menu-section');

        function syncSelectedMenuSection() {
            if (!scopeInput || !selectedMenuSection) return;

            if (scopeInput.value === 'selected_menu') {
                selectedMenuSection.classList.remove('hidden');
            } else {
                selectedMenuSection.classList.add('hidden');
            }
        }

        syncSelectedMenuSection();

        if (scopeInput) {
            scopeInput.addEventListener('change', syncSelectedMenuSection);
        }
    });
</script>
