@extends('layouts.master')

@section('title', 'Kelola Admin')
@section('header-title', 'Kelola Admin')

@section('content')
    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-[2rem] bg-[#171412] p-6 shadow-xl border border-white/10">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)] pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                <div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-3 py-1.5 mb-4">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            Admin Account Management
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Kelola Admin
                    </h1>

                    <p class="mt-2 text-sm leading-6 text-stone-300 max-w-3xl">
                        Superadmin mengatur akun admin yang bertanggung jawab atas operasional sistem.
                    </p>
                </div>

                <a href="{{ route('superadmin.admins.create') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-stone-950 shadow-lg shadow-amber-500/25 transition hover:bg-amber-400 active:scale-[0.98]">
                    Tambah Admin
                </a>
            </div>
        </section>

        <section class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
            <form action="{{ route('superadmin.admins.index') }}" method="GET">
                <div class="grid gap-4 lg:grid-cols-[1fr_220px_auto] lg:items-end">
                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-stone-400">
                            Cari Admin
                        </label>

                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Nama, email, atau nomor HP"
                            class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-700 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-stone-400">
                            Status
                        </label>

                        <select name="status"
                            class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-700 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">
                            <option value="">Semua</option>
                            <option value="active" @selected($status === 'active')>Aktif</option>
                            <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="h-[52px] rounded-2xl bg-[#1f1a17] px-7 text-sm font-black text-white shadow-sm transition hover:bg-[#2a231f] active:scale-[0.98]">
                            Filter
                        </button>

                        <a href="{{ route('superadmin.admins.index') }}"
                            class="inline-flex h-[52px] items-center justify-center rounded-2xl border border-stone-200 bg-white px-7 text-sm font-black text-stone-700 shadow-sm transition hover:bg-stone-50 active:scale-[0.98]">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-[2rem] border border-stone-100 bg-white shadow-sm">
            <div class="border-b border-stone-100 px-6 py-5">
                <h2 class="text-lg font-black text-stone-950">
                    Daftar Admin
                </h2>

                <p class="mt-1 text-sm text-stone-500">
                    Total data: {{ $admins->total() }}
                </p>
            </div>

            @if ($admins->isEmpty())
                <div class="p-10 text-center">
                    <p class="text-sm font-black text-stone-700">
                        Belum ada admin.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Admin
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Kontak
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100">
                            @foreach ($admins as $admin)
                                <tr class="hover:bg-stone-50">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-black text-stone-950">
                                            {{ $admin->name }}
                                        </p>
                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            Dibuat {{ $admin->created_at->format('d M Y H:i') }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-stone-800">
                                            {{ $admin->email }}
                                        </p>
                                        <p class="mt-1 text-xs font-semibold text-stone-500">
                                            {{ $admin->phone ?: 'Nomor HP belum diisi' }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($admin->is_active)
                                            <span
                                                class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-black text-rose-700">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('superadmin.admins.edit', $admin) }}"
                                                class="inline-flex rounded-2xl border border-stone-200 bg-white px-4 py-2 text-xs font-black text-stone-700 shadow-sm transition hover:bg-stone-50">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                action="{{ route('superadmin.admins.toggle-status', $admin) }}">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="{{ $admin->is_active
                                                        ? 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100'
                                                        : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} inline-flex rounded-2xl border px-4 py-2 text-xs font-black transition">
                                                    {{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-stone-100 px-6 py-4">
                    {{ $admins->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
