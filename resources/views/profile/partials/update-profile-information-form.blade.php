<section>
    <header>
        <h2 class="text-lg font-black text-stone-950">
            Informasi Profil
        </h2>

        <p class="mt-1 text-sm leading-6 text-stone-500">
            Perbarui nama, email, dan nomor HP akun kamu.
        </p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('PATCH')

        <div>
            <label for="name" class="mb-2 block text-sm font-bold text-stone-700">
                Nama
            </label>

            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus
                autocomplete="name"
                class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">

            @error('name')
                <p class="mt-2 text-xs font-bold text-rose-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="email" class="mb-2 block text-sm font-bold text-stone-700">
                Email
            </label>

            <input id="email" type="email" value="{{ $user->email }}" disabled
                class="h-[52px] w-full cursor-not-allowed rounded-2xl border border-stone-200 bg-stone-100 px-5 text-sm font-bold text-stone-500 outline-none"
                placeholder="email">

            <p class="mt-2 text-xs font-semibold text-stone-400">
                Email digunakan untuk login dan tidak dapat diubah.
            </p>
        </div>

        <div>
            <label for="phone" class="mb-2 block text-sm font-bold text-stone-700">
                Nomor HP
            </label>

            <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}"
                autocomplete="tel"
                class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100"
                placeholder="Opsional">

            @error('phone')
                <p class="mt-2 text-xs font-bold text-rose-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <button type="submit"
                class="inline-flex h-[52px] items-center justify-center rounded-2xl bg-amber-600 px-6 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700 active:scale-[0.98]">
                Simpan Profil
            </button>
        </div>
    </form>
</section>
