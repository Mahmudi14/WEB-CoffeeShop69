@extends('layouts.master')

@section('title', 'Audit Aktivitas')
@section('header-title', 'Audit Aktivitas')

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
                            System Activity Audit
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Audit Aktivitas
                    </h1>

                    <p class="mt-2 text-sm leading-6 text-stone-300 max-w-3xl">
                        Catatan aktivitas penting yang dilakukan di area superadmin.
                    </p>
                </div>
            </div>
        </section>

        <section class="rounded-[2rem] border border-stone-100 bg-white p-6 shadow-sm">
            <form action="{{ route('superadmin.audit-logs.index') }}" method="GET">
                <div class="grid gap-4 lg:grid-cols-[1fr_260px_auto] lg:items-end">
                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-stone-400">
                            Cari Aktivitas
                        </label>

                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Deskripsi, modul, atau aksi"
                            class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-700 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-stone-400">
                            Modul
                        </label>

                        <select name="module"
                            class="h-[52px] w-full rounded-2xl border border-stone-200 bg-stone-50 px-5 text-sm font-bold text-stone-700 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-100">
                            <option value="">Semua Modul</option>
                            @foreach ($modules as $item)
                                <option value="{{ $item }}" @selected($module === $item)>
                                    {{ str_replace('_', ' ', $item) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="h-[52px] rounded-2xl bg-[#1f1a17] px-7 text-sm font-black text-white shadow-sm transition hover:bg-[#2a231f] active:scale-[0.98]">
                            Filter
                        </button>

                        <a href="{{ route('superadmin.audit-logs.index') }}"
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
                    Log Aktivitas
                </h2>

                <p class="mt-1 text-sm text-stone-500">
                    Total data: {{ $logs->total() }}
                </p>
            </div>

            @if ($logs->isEmpty())
                <div class="p-10 text-center">
                    <p class="text-sm font-black text-stone-700">
                        Belum ada audit aktivitas.
                    </p>
                </div>
            @else
                <div class="divide-y divide-stone-100">
                    @foreach ($logs as $log)
                        <div class="p-6 hover:bg-stone-50">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span
                                            class="rounded-full bg-amber-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-amber-700">
                                            {{ str_replace('_', ' ', $log->module) }}
                                        </span>

                                        <span
                                            class="rounded-full bg-stone-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-stone-700">
                                            {{ str_replace('_', ' ', $log->action) }}
                                        </span>
                                    </div>

                                    <p class="mt-3 text-sm font-black text-stone-950">
                                        {{ $log->description }}
                                    </p>

                                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-xs font-semibold text-stone-500">
                                        <span>Actor: {{ $log->user?->name ?? 'System' }}</span>
                                        <span>IP: {{ $log->ip_address ?? '-' }}</span>
                                        <span>{{ $log->created_at->format('d M Y H:i:s') }}</span>
                                    </div>

                                    @if ($log->properties)
                                        <details class="mt-3">
                                            <summary class="cursor-pointer text-xs font-black text-amber-700">
                                                Detail Properti
                                            </summary>

                                            <pre class="mt-2 overflow-x-auto rounded-2xl bg-stone-950 p-4 text-xs text-stone-100">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </details>
                                    @endif
                                </div>

                                <div class="text-xs font-semibold text-stone-400">
                                    #{{ $log->id }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-stone-100 px-6 py-4">
                    {{ $logs->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
