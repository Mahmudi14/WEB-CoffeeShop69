<x-guest-layout>
    <div class="w-full max-w-5xl overflow-hidden rounded-[2rem] border border-white/10 bg-white shadow-2xl">
        <div class="grid min-h-[620px] lg:grid-cols-[1.05fr_0.95fr]">
            <div
                class="relative hidden overflow-hidden bg-[#171412] p-8 lg:flex lg:flex-col lg:items-center lg:justify-center">
                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(245,158,11,0.26),transparent_34%),linear-gradient(135deg,rgba(255,255,255,0.08),transparent_45%)]">
                </div>

                <div class="relative z-10 flex flex-col items-center text-center">

                    <x-brand-logo size="xl" />

                    <div class="mt-8 inline-flex rounded-full border border-white/10 bg-white/10 px-4 py-2">
                        <span class="text-xs font-black uppercase tracking-[0.22em] text-amber-300">
                            Coffee Shop System
                        </span>
                    </div>

                    <h1 class="mt-5 max-w-md text-4xl font-black leading-tight tracking-tight text-white">
                        Sistem operasional kasir dan QR ordering Cafe 69.
                    </h1>
                </div>
            </div>

            <div class="flex flex-col justify-center p-6 sm:p-8 lg:p-10">
                <div class="lg:hidden">
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-600 text-xl font-black text-white shadow-lg shadow-amber-600/20">
                        69
                    </div>
                </div>

                <div class="mt-6 lg:mt-0">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                        Secure Login
                    </p>

                    <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">
                        Masuk ke Sistem
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-stone-500">
                        Gunakan akun admin dan kasir.
                    </p>
                </div>

                @if (session('status'))
                    <div
                        class="mt-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mt-6 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-bold text-stone-700">
                            Email
                        </label>

                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autofocus autocomplete="username"
                            class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100"
                            placeholder="nama@email.com">

                        @error('email')
                            <p class="mt-2 text-xs font-bold text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-bold text-stone-700">
                            Password
                        </label>

                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password"
                                class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 pr-14 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100"
                                placeholder="Masukkan password">

                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-4 flex items-center text-stone-400 transition hover:text-amber-600"
                                aria-label="Lihat password">
                                <svg id="eyeIcon" class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>

                                <svg id="eyeOffIcon" class="hidden h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.973 9.973 0 012.223-3.592m3.31-2.13A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.132 5.236M15 12a3 3 0 00-3-3m0 0a3 3 0 00-3 3m3-3l9 9M3 3l18 18" />
                                </svg>
                            </button>
                        </div>

                        @error('password')
                            <p class="mt-2 text-xs font-bold text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <label for="remember_me" class="inline-flex items-center gap-2">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-sm font-semibold text-stone-600">
                                Ingat saya
                            </span>
                        </label>
                    </div>

                    <button type="submit"
                        class="flex h-[54px] w-full items-center justify-center rounded-2xl bg-[#1f1a17] px-6 text-sm font-black text-white shadow-lg shadow-stone-900/10 transition hover:bg-[#2a231f] active:scale-[0.98]">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        if (!passwordInput || !togglePassword || !eyeIcon || !eyeOffIcon) return;

        togglePassword.addEventListener('click', function() {
            const isHidden = passwordInput.type === 'password';

            passwordInput.type = isHidden ? 'text' : 'password';

            eyeIcon.classList.toggle('hidden', isHidden);
            eyeOffIcon.classList.toggle('hidden', !isHidden);

            togglePassword.setAttribute(
                'aria-label',
                isHidden ? 'Sembunyikan password' : 'Lihat password'
            );
        });
    });
</script>
