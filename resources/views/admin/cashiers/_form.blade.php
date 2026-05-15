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

    {{-- Password dan Konfirmasi Password --}}
    <div class="grid gap-5 md:grid-cols-2" x-data="{ showPassword: false, showPasswordConfirmation: false }">
        <div>
            <label for="password" class="mb-2 block text-sm font-bold text-stone-700">
                Password
            </label>

            <div class="relative">
                <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                    @if (!isset($cashier)) required @endif
                    placeholder="{{ isset($cashier) ? 'Kosongkan jika tidak diganti' : 'Masukkan password' }}"
                    class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 pr-12 text-sm font-semibold text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-100">

                <button type="button" @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-3 inline-flex items-center justify-center text-stone-400 transition hover:text-stone-700">
                    <svg x-show="!showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.249-2.383A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.043 5.306M15 12a3 3 0 00-3-3m0 0a3 3 0 00-3 3m3-3l9 9M3 3l18 18" />
                    </svg>
                </button>
            </div>

            @error('password')
                <p class="mt-2 text-xs font-bold text-rose-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-bold text-stone-700">
                Konfirmasi Password
            </label>

            <div class="relative">
                <input :type="showPasswordConfirmation ? 'text' : 'password'" id="password_confirmation"
                    name="password_confirmation" @if (!isset($cashier)) required @endif
                    placeholder="{{ isset($cashier) ? 'Ulangi password baru' : 'Ulangi password' }}"
                    class="w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 pr-12 text-sm font-semibold text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-100">

                <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation"
                    class="absolute inset-y-0 right-3 inline-flex items-center justify-center text-stone-400 transition hover:text-stone-700">
                    <svg x-show="!showPasswordConfirmation" x-cloak class="h-5 w-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    <svg x-show="showPasswordConfirmation" x-cloak class="h-5 w-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.249-2.383A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.043 5.306M15 12a3 3 0 00-3-3m0 0a3 3 0 00-3 3m3-3l9 9M3 3l18 18" />
                    </svg>
                </button>
            </div>

            @error('password_confirmation')
                <p class="mt-2 text-xs font-bold text-rose-600">
                    {{ $message }}
                </p>
            @enderror
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
