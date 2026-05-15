<div class="grid gap-5">
    {{-- Nama + Kategori --}}
    <div class="grid gap-5 md:grid-cols-2">
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
    </div>

    {{-- Harga + Status --}}
    <div class="grid gap-5 lg:grid-cols-[260px_1fr_1fr] lg:items-end xl:grid-cols-[280px_1fr_1fr]">
        <div x-data="rupiahInput(@js(old('normal_price', $menu->normal_price ?? 0)))">
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Harga Normal
            </label>

            <input type="text" x-model="formattedPrice" @input="updatePrice($event)" inputmode="numeric"
                autocomplete="off" required
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                placeholder="Rp 18.000">

            <input type="hidden" name="normal_price" x-model="rawPrice">

            @error('normal_price')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex min-h-[48px] items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $menu->is_active ?? true))
                class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

            <span class="text-sm font-bold text-stone-700">
                Menu aktif
            </span>
        </label>

        <label class="flex min-h-[48px] items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
            <input type="checkbox" name="is_available" value="1" @checked(old('is_available', $menu->is_available ?? true))
                class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

            <span class="text-sm font-bold text-stone-700">
                Menu tersedia
            </span>
        </label>
    </div>

    {{-- Deskripsi --}}
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

    {{-- Gambar Menu --}}
    <div x-data="menuImagePreview(@js(isset($menu) && $menu->image_path ? Storage::url($menu->image_path) : null))">
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Gambar Menu
        </label>

        <div class="grid gap-4 lg:grid-cols-[180px,1fr] lg:items-start">
            {{-- Preview --}}
            <div class="h-44 w-full overflow-hidden rounded-3xl border border-stone-200 bg-stone-100 lg:h-40 lg:w-44">
                <template x-if="imagePreview">
                    <img :src="imagePreview" alt="Preview gambar menu" class="h-full w-full object-cover">
                </template>

                <template x-if="!imagePreview">
                    <div class="flex h-full w-full flex-col items-center justify-center px-4 text-center">
                        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-white shadow-sm">
                            <svg class="h-6 w-6 text-stone-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                            No Image
                        </p>

                        <p class="mt-1 text-xs font-semibold text-stone-500">
                            Belum ada gambar
                        </p>
                    </div>
                </template>
            </div>

            {{-- Input --}}
            <div class="min-w-0">
                <label for="image"
                    class="flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed border-stone-200 bg-stone-50 px-5 py-6 text-center transition hover:border-amber-400 hover:bg-amber-50">
                    <svg class="h-8 w-8 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 16V4m0 0L8 8m4-4l4 4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                    </svg>

                    <span class="mt-3 text-sm font-black text-stone-800">
                        Pilih Gambar
                    </span>

                    <span class="mt-1 text-xs font-semibold text-stone-500">
                        JPG, PNG, atau WEBP.
                    </span>

                    <span x-show="fileName" x-cloak
                        class="mt-3 max-w-full truncate rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-700"
                        x-text="fileName">
                    </span>
                </label>

                <input id="image" type="file" name="image" accept="image/png,image/jpeg,image/jpg,image/webp"
                    class="hidden" @change="previewImage($event)">

                @if (isset($menu) && $menu->image_path)
                    <p class="mt-3 text-xs font-semibold text-stone-500">
                        Gambar lama akan tetap digunakan jika tidak memilih gambar baru.
                    </p>
                @else
                    <p class="mt-3 text-xs font-semibold text-stone-500">
                        Jika tidak memilih gambar, menu tetap tersimpan tanpa gambar.
                    </p>
                @endif

                @error('image')
                    <p class="mt-2 text-xs font-bold text-rose-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', function() {
        if (!window.__menuFormComponentsRegistered) {
            window.__menuFormComponentsRegistered = true;

            Alpine.data('rupiahInput', function(initialValue = 0) {
                return {
                    rawPrice: '',
                    formattedPrice: '',

                    init() {
                        this.rawPrice = this.onlyNumber(initialValue);
                        this.formattedPrice = this.formatRupiah(this.rawPrice);
                    },

                    onlyNumber(value) {
                        return String(value ?? '').replace(/\D/g, '');
                    },

                    formatRupiah(value) {
                        const number = this.onlyNumber(value);

                        if (!number) {
                            return '';
                        }

                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(number));
                    },

                    updatePrice(event) {
                        this.rawPrice = this.onlyNumber(event.target.value);
                        this.formattedPrice = this.formatRupiah(this.rawPrice);
                    }
                };
            });

            Alpine.data('menuImagePreview', function(initialImage = null) {
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
