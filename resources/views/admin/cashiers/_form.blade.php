<div class="grid gap-5">
    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Nama Kasir
        </label>

        <input type="text" name="name" value="{{ old('name', $cashier->name ?? '') }}" required
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
            placeholder="Contoh: Andi">

        @error('name')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Email
        </label>

        <input type="email" name="email" value="{{ old('email', $cashier->email ?? '') }}" required
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
            placeholder="kasir@69coffeeshop.test">

        @error('email')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Nomor HP
        </label>

        <input type="text" name="phone" value="{{ old('phone', $cashier->phone ?? '') }}"
            class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
            placeholder="Opsional">

        @error('phone')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Password
            </label>

            <input type="password" name="password" {{ isset($cashier) ? '' : 'required' }}
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                placeholder="{{ isset($cashier) ? 'Kosongkan jika tidak diganti' : 'Minimal 8 karakter' }}">

            @error('password')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Konfirmasi Password
            </label>

            <input type="password" name="password_confirmation" {{ isset($cashier) ? '' : 'required' }}
                class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100"
                placeholder="Ulangi password">
        </div>
    </div>

    <label class="flex items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $cashier->is_active ?? true))
            class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

        <span class="text-sm font-bold text-stone-700">
            Akun kasir aktif
        </span>
    </label>
</div>
