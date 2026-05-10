<section>
    <header>
        <h2 class="text-lg font-black text-stone-950">
            Ganti Password
        </h2>

        <p class="mt-1 text-sm leading-6 text-stone-500">
            Gunakan password yang kuat dan tidak mudah ditebak.
        </p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="update_password_current_password" class="mb-2 block text-sm font-bold text-stone-700">
                Password Saat Ini
            </label>

            <div class="relative">
                <input id="update_password_current_password" name="current_password" type="password"
                    autocomplete="current-password"
                    class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 pr-14 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">

                <button type="button" data-toggle-password="update_password_current_password"
                    class="absolute inset-y-0 right-4 flex items-center text-stone-400 transition hover:text-amber-600"
                    aria-label="Lihat password">
                    <svg class="h-5 w-5 password-eye" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    <svg class="hidden h-5 w-5 password-eye-off" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.973 9.973 0 012.223-3.592m3.31-2.13A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.132 5.236M15 12a3 3 0 00-3-3m0 0a3 3 0 00-3 3m3-3l9 9M3 3l18 18" />
                    </svg>
                </button>
            </div>

            @if ($errors->updatePassword->has('current_password'))
                <p class="mt-2 text-xs font-bold text-rose-600">
                    {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="mb-2 block text-sm font-bold text-stone-700">
                Password Baru
            </label>

            <div class="relative">
                <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                    class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 pr-14 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">

                <button type="button" data-toggle-password="update_password_password"
                    class="absolute inset-y-0 right-4 flex items-center text-stone-400 transition hover:text-amber-600"
                    aria-label="Lihat password">
                    <svg class="h-5 w-5 password-eye" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    <svg class="hidden h-5 w-5 password-eye-off" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.973 9.973 0 012.223-3.592m3.31-2.13A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.132 5.236M15 12a3 3 0 00-3-3m0 0a3 3 0 00-3 3m3-3l9 9M3 3l18 18" />
                    </svg>
                </button>
            </div>

            @if ($errors->updatePassword->has('password'))
                <p class="mt-2 text-xs font-bold text-rose-600">
                    {{ $errors->updatePassword->first('password') }}
                </p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="mb-2 block text-sm font-bold text-stone-700">
                Konfirmasi Password Baru
            </label>

            <div class="relative">
                <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    autocomplete="new-password"
                    class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 pr-14 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">

                <button type="button" data-toggle-password="update_password_password_confirmation"
                    class="absolute inset-y-0 right-4 flex items-center text-stone-400 transition hover:text-amber-600"
                    aria-label="Lihat password">
                    <svg class="h-5 w-5 password-eye" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    <svg class="hidden h-5 w-5 password-eye-off" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.973 9.973 0 012.223-3.592m3.31-2.13A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.132 5.236M15 12a3 3 0 00-3-3m0 0a3 3 0 00-3 3m3-3l9 9M3 3l18 18" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <button type="submit"
                class="inline-flex h-[52px] items-center justify-center rounded-2xl bg-[#1f1a17] px-6 text-sm font-black text-white shadow-lg shadow-stone-900/10 transition hover:bg-[#2a231f] active:scale-[0.98]">
                Simpan Password
            </button>
        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-toggle-password]').forEach(function(button) {
            button.addEventListener('click', function() {
                const inputId = button.getAttribute('data-toggle-password');
                const input = document.getElementById(inputId);

                if (!input) return;

                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';

                const eyeIcon = button.querySelector('.password-eye');
                const eyeOffIcon = button.querySelector('.password-eye-off');

                if (eyeIcon && eyeOffIcon) {
                    eyeIcon.classList.toggle('hidden', isHidden);
                    eyeOffIcon.classList.toggle('hidden', !isHidden);
                }

                button.setAttribute(
                    'aria-label',
                    isHidden ? 'Sembunyikan password' : 'Lihat password'
                );
            });
        });
    });
</script>
