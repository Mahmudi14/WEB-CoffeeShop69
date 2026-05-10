<div class="grid gap-5">
    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Nama Meja
        </label>

        <input type="text" name="name" value="{{ old('name', $table->name ?? '') }}" required
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
            placeholder="Contoh: Meja 1">

        @error('name')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Kode Meja
        </label>

        <input type="text" name="code" value="{{ old('code', $table->code ?? '') }}" required
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold uppercase text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
            placeholder="Contoh: TBL-001">

        <p class="mt-2 text-xs font-semibold text-stone-500">
            Kode harus unik. Contoh: TBL-001, TBL-002.
        </p>

        @error('code')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    @if (!isset($table))
        <label class="flex items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))
                class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

            <span class="text-sm font-bold text-stone-700">
                Meja aktif
            </span>
        </label>
    @endif
</div>
