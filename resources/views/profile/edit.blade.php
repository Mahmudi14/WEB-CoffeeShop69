@extends('layouts.master')

@section('title', 'Profil Saya')
@section('header-title', 'Profil Saya')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <section class="relative overflow-hidden rounded-[2rem] bg-[#171412] p-6 shadow-xl border border-white/10">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)] pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-3 py-1.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Account Profile
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Profil Saya
                    </h1>
                </div>

                @php
                    $roleName = auth()->user()->roles->pluck('name')->first();

                    $roleLabel = match ($roleName) {
                        'superadmin' => 'Superadmin',
                        'admin' => 'Admin',
                        'cashier' => 'Kasir',
                        default => 'User',
                    };
                @endphp

                <div class="rounded-2xl border border-white/10 bg-white/10 px-5 py-4 backdrop-blur-md">
                    <p class="text-[11px] font-black uppercase tracking-widest text-stone-400">
                        Role
                    </p>

                    <p class="mt-1 text-base font-black text-amber-300">
                        {{ $roleLabel }}
                    </p>
                </div>
            </div>
        </section>

        {{-- Alert --}}
        @if (session('status') === 'profile-updated')
            <div
                class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                Profil berhasil diperbarui.
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div
                class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                Password berhasil diperbarui.
            </div>
        @endif

        {{-- Informasi Akun --}}
        <section class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-amber-600">
                        Informasi Akun
                    </p>

                    <h2 class="mt-2 text-2xl font-black tracking-tight text-stone-950">
                        {{ auth()->user()->name }}
                    </h2>
                </div>

                @if (auth()->user()->is_active)
                    <span
                        class="inline-flex w-fit rounded-full bg-emerald-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-emerald-700">
                        Aktif
                    </span>
                @else
                    <span
                        class="inline-flex w-fit rounded-full bg-rose-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-rose-700">
                        Nonaktif
                    </span>
                @endif
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-3xl border border-stone-100 bg-stone-50 p-5">
                    <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                        Nama
                    </p>

                    <p class="mt-2 text-sm font-black text-stone-900">
                        {{ auth()->user()->name }}
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-100 bg-stone-50 p-5">
                    <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                        Email Login
                    </p>

                    <p class="mt-2 break-all text-sm font-black text-stone-900">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-100 bg-stone-50 p-5">
                    <p class="text-xs font-black uppercase tracking-wider text-stone-400">
                        Nomor HP
                    </p>

                    <p class="mt-2 text-sm font-black text-stone-900">
                        {{ auth()->user()->phone ?: '-' }}
                    </p>
                </div>
            </div>
        </section>

        {{-- Form bawah --}}
        <div class="grid gap-6 xl:grid-cols-2">
            <section class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </section>

            <section class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
                @include('profile.partials.update-password-form')
            </section>
        </div>
    </div>
@endsection
