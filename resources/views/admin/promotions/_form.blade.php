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

<div class="grid gap-5" x-data="{
    scope: @js((string) $currentScope),
    discountType: @js((string) $currentDiscountType),
    scopeOpen: false,
    discountOpen: false,

    closeAll() {
        this.scopeOpen = false;
        this.discountOpen = false;
    },

    selectScope(value) {
        this.scope = value;
        this.scopeOpen = false;
    },

    selectDiscountType(value) {
        this.discountType = value;
        this.discountOpen = false;
    }
}" @keydown.escape.window="closeAll()">

    {{-- Nama Promo --}}
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

    {{-- Deskripsi --}}
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

    {{-- Cakupan + Prioritas --}}
    <div class="grid gap-5 md:grid-cols-2">
        {{-- Cakupan Promo --}}
        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Cakupan Promo
            </label>

            <input type="hidden" name="scope" x-model="scope">

            <div class="relative">
                <button type="button" @click="scopeOpen = !scopeOpen; discountOpen = false"
                    class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                    <span class="min-w-0 truncate">
                        <span x-show="scope === 'all_menu'">
                            Semua Menu
                        </span>

                        <span x-show="scope === 'selected_menu'" x-cloak>
                            Menu Tertentu
                        </span>
                    </span>

                    <svg class="h-4 w-4 shrink-0 text-stone-400 transition" :class="scopeOpen ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="scopeOpen" x-cloak x-transition.origin.top @click.outside="scopeOpen = false"
                    class="absolute left-0 right-0 top-full z-50 mt-2 overflow-hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                    <button type="button" @click="selectScope('all_menu')"
                        class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-sky-50 hover:text-sky-700"
                        :class="scope === 'all_menu' ? 'bg-sky-100 text-sky-800' : 'text-stone-700'">
                        <span>Semua Menu</span>

                        <svg x-show="scope === 'all_menu'" x-cloak class="h-4 w-4 shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </button>

                    <button type="button" @click="selectScope('selected_menu')"
                        class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                        :class="scope === 'selected_menu' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                        <span>Menu Tertentu</span>

                        <svg x-show="scope === 'selected_menu'" x-cloak class="h-4 w-4 shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </div>

            @error('scope')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Prioritas --}}
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

    {{-- Jenis Diskon + Nilai Diskon --}}
    <div class="grid gap-5 md:grid-cols-2">
        {{-- Jenis Diskon --}}
        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Jenis Diskon
            </label>

            <input type="hidden" name="discount_type" x-model="discountType">

            <div class="relative">
                <button type="button" @click="discountOpen = !discountOpen; scopeOpen = false"
                    class="flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                    <span class="min-w-0 truncate">
                        <span x-show="discountType === 'percentage'">
                            Persentase (%)
                        </span>

                        <span x-show="discountType === 'fixed'" x-cloak>
                            Nominal Rupiah
                        </span>
                    </span>

                    <svg class="h-4 w-4 shrink-0 text-stone-400 transition" :class="discountOpen ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="discountOpen" x-cloak x-transition.origin.top @click.outside="discountOpen = false"
                    class="absolute left-0 right-0 top-full z-50 mt-2 overflow-hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                    <button type="button" @click="selectDiscountType('percentage')"
                        class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-emerald-50 hover:text-emerald-700"
                        :class="discountType === 'percentage' ? 'bg-emerald-100 text-emerald-800' : 'text-stone-700'">
                        <span>Persentase (%)</span>

                        <svg x-show="discountType === 'percentage'" x-cloak class="h-4 w-4 shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </button>

                    <button type="button" @click="selectDiscountType('fixed')"
                        class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                        :class="discountType === 'fixed' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                        <span>Nominal Rupiah</span>

                        <svg x-show="discountType === 'fixed'" x-cloak class="h-4 w-4 shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </div>

            @error('discount_type')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nilai Diskon --}}
        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Nilai Diskon
            </label>

            <input type="number" step="0.01" name="discount_value"
                value="{{ old('discount_value', isset($promotion) ? $promotion->discount_value : '') }}"
                min="0.01" required
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                :placeholder="discountType === 'percentage' ? 'Contoh: 10' : 'Contoh: 5000'">

            <p class="mt-2 text-xs font-semibold text-stone-500">
                <span x-show="discountType === 'percentage'">
                    Isi angka persentase, contoh 10 untuk diskon 10%.
                </span>

                <span x-show="discountType === 'fixed'" x-cloak>
                    Isi nominal rupiah tanpa titik, contoh 5000.
                </span>
            </p>

            @error('discount_value')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Periode --}}
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

    {{-- Pilih Menu --}}
    <div x-show="scope === 'selected_menu'" x-cloak x-transition
        class="rounded-[2rem] border border-stone-200 bg-stone-50 p-4">
        <label class="mb-3 block text-sm font-bold text-stone-700">
            Pilih Menu
        </label>

        <div class="max-h-72 overflow-y-auto rounded-2xl border border-stone-200 bg-white p-3">
            <div class="grid gap-2 md:grid-cols-2">
                @foreach ($menus as $menu)
                    <label class="flex items-start gap-3 rounded-xl bg-stone-50 p-3 transition hover:bg-amber-50">
                        <input type="checkbox" name="menu_ids[]" value="{{ $menu->id }}"
                            @checked(in_array((string) $menu->id, $selectedMenus, true))
                            class="mt-1 h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

                        <span class="min-w-0">
                            <span class="block truncate text-sm font-black text-stone-800">
                                {{ $menu->name }}
                            </span>

                            <span class="block truncate text-xs font-semibold text-stone-500">
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

    {{-- Status --}}
    <label class="flex items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
        <input type="hidden" name="is_active" value="0">

        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $promotion->is_active ?? true))
            class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

        <span class="text-sm font-bold text-stone-700">
            Promo aktif
        </span>
    </label>
</div>
