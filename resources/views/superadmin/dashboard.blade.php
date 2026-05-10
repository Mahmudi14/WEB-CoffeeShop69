@extends('layouts.master')

@section('title', 'Dashboard Superadmin')
@section('header-title', 'Dashboard Superadmin')

@section('content')
    @php
        $stats = [
            [
                'label' => 'Admin',
                'value' => number_format($adminCount ?? 0, 0, ',', '.'),
                'description' => number_format($activeAdminCount ?? 0, 0, ',', '.') . ' admin aktif',
                'tone' => 'amber',
                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            ],
            [
                'label' => 'Kasir',
                'value' => number_format($cashierCount ?? 0, 0, ',', '.'),
                'description' => number_format($activeCashierCount ?? 0, 0, ',', '.') . ' kasir aktif',
                'tone' => 'emerald',
                'icon' =>
                    'M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm6 0a3 3 0 100-6 3 3 0 000 6z',
            ],
            [
                'label' => 'Shift Aktif',
                'value' => number_format($activeShiftCount ?? 0, 0, ',', '.'),
                'description' => 'Shift kasir sedang berjalan',
                'tone' => 'sky',
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            [
                'label' => 'Audit Terbaru',
                'value' => number_format(($latestActivities ?? collect())->count(), 0, ',', '.'),
                'description' => 'Aktivitas sistem terakhir',
                'tone' => 'stone',
                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z',
            ],
        ];
    @endphp

    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-[2rem] bg-[#171412] p-6 shadow-xl border border-white/10">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)] pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-3 py-1.5">
                        <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300">
                            System Control
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Dashboard Superadmin
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-stone-300">
                        Kelola akses admin dan kasir, pantau shift aktif, serta lihat jejak aktivitas penting sistem.
                    </p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/10 px-5 py-4 backdrop-blur-md">
                    <p class="text-[11px] font-black uppercase tracking-widest text-stone-400">
                        Role Aktif
                    </p>
                    <p class="mt-1 text-base font-black text-amber-300">
                        Superadmin
                    </p>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <div class="rounded-[2rem] border border-stone-100 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-stone-400">
                                {{ $stat['label'] }}
                            </p>

                            <p class="mt-3 text-3xl font-black tracking-tight text-stone-950">
                                {{ $stat['value'] }}
                            </p>

                            <p class="mt-2 text-xs font-semibold text-stone-500">
                                {{ $stat['description'] }}
                            </p>
                        </div>

                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl
                            @if ($stat['tone'] === 'amber') bg-amber-100 text-amber-700
                            @elseif ($stat['tone'] === 'emerald') bg-emerald-100 text-emerald-700
                            @elseif ($stat['tone'] === 'sky') bg-sky-100 text-sky-700
                            @else bg-stone-100 text-stone-700 @endif">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="{{ $stat['icon'] }}" />
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm xl:col-span-2">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-black text-stone-950">
                            Aktivitas Terbaru
                        </h3>
                        <p class="mt-1 text-sm text-stone-500">
                            Jejak aktivitas penting yang dilakukan oleh superadmin.
                        </p>
                    </div>

                    <a href="{{ route('superadmin.audit-logs.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-stone-200 bg-white px-4 py-2.5 text-xs font-black text-stone-700 transition hover:bg-stone-50">
                        Lihat Semua
                    </a>
                </div>

                @if (($latestActivities ?? collect())->isEmpty())
                    <div class="rounded-2xl border border-dashed border-stone-300 bg-stone-50 p-8 text-center">
                        <p class="text-sm font-bold text-stone-600">
                            Belum ada aktivitas tercatat.
                        </p>
                        <p class="mt-2 text-xs text-stone-500">
                            Aktivitas akan muncul setelah superadmin membuat, mengubah, atau menonaktifkan akun.
                        </p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($latestActivities as $activity)
                            <div class="flex gap-4 rounded-2xl border border-stone-100 bg-stone-50 p-4">
                                <div class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-amber-500"></div>

                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-black text-stone-900">
                                        {{ $activity->description }}
                                    </p>

                                    <div class="mt-1 flex flex-wrap gap-x-3 gap-y-1 text-xs font-semibold text-stone-500">
                                        <span>{{ $activity->user?->name ?? 'System' }}</span>
                                        <span>{{ $activity->module }}</span>
                                        <span>{{ $activity->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-black text-stone-950">
                    Akses Cepat
                </h3>

                <p class="mt-1 text-sm text-stone-500">
                    Pintasan ke modul kontrol sistem.
                </p>

                <div class="mt-5 space-y-3">
                    <a href="{{ route('superadmin.admins.index') }}"
                        class="flex items-center justify-between rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm font-bold text-stone-700 transition hover:bg-stone-100">
                        Kelola Admin
                        <span>→</span>
                    </a>

                    <a href="{{ route('superadmin.cashiers.index') }}"
                        class="flex items-center justify-between rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm font-bold text-stone-700 transition hover:bg-stone-100">
                        Kelola Kasir
                        <span>→</span>
                    </a>

                    <a href="{{ route('superadmin.audit-logs.index') }}"
                        class="flex items-center justify-between rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm font-bold text-stone-700 transition hover:bg-stone-100">
                        Audit Aktivitas
                        <span>→</span>
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
