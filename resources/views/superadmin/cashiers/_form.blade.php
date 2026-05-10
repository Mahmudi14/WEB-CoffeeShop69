<div class="grid gap-5">
    <div>
        <label class="mb-2 block text-sm font-bold text-stone-700">
            Nama Kasir
        </label>

        <input type="text" name="name" value="{{ old('name', $cashier->name ?? '') }}" required
            class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100"
            placeholder="Contoh: Kasir Shift Pagi">

        @error('name')
            <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Email
            </label>

            <input type="email" name="email" value="{{ old('email', $cashier->email ?? '') }}" required
                class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100"
                placeholder="kasir@email.com">

            @error('email')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Nomor HP
            </label>

            <input type="text" name="phone" value="{{ old('phone', $cashier->phone ?? '') }}"
                class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100"
                placeholder="Opsional">

            @error('phone')
                <p class="mt-2 text-xs font-bold text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-bold text-stone-700">
                Password {{ isset($cashier) ? '(Opsional)' : '' }}
            </label>

            <input type="password" name="password" {{ isset($cashier) ? '' : 'required' }}
                class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100"
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
                class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100"
                placeholder="Ulangi password">
        </div>
    </div>

    <label class="flex items-start gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $cashier->is_active ?? true))
            class="mt-1 h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">

        <span>
            <span class="block text-sm font-bold text-stone-700">
                Kasir aktif
            </span>
            <span class="mt-1 block text-xs font-semibold text-stone-500">
                Kasir nonaktif tidak boleh login dan menjalankan operasional kasir.
            </span>
        </span>
    </label>
</div>
