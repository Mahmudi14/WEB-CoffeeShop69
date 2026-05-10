@props([
    'size' => 'md',
    'withText' => false,
    'label' => '69 Coffee Shop',
])

@php
    $sizes = [
        'sm' => 'h-10 w-10',
        'md' => 'h-14 w-14',
        'lg' => 'h-28 w-28',
        'xl' => 'h-40 w-40',
    ];

    $textSizes = [
        'sm' => 'text-xl',
        'md' => 'text-3xl',
        'lg' => 'text-6xl',
        'xl' => 'text-7xl',
    ];

    $logoSize = $sizes[$size] ?? $sizes['md'];
    $numberSize = $textSizes[$size] ?? $textSizes['md'];
@endphp

<div {{ $attributes->class(['inline-flex items-center gap-4']) }}>
    <div
        class="{{ $logoSize }} relative isolate flex shrink-0 items-center justify-center overflow-hidden rounded-[1.35rem] border border-amber-400/25 bg-[#15110f] shadow-2xl shadow-amber-600/20">

        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_35%_25%,rgba(255,226,161,0.24),transparent_32%),linear-gradient(135deg,rgba(245,166,35,0.18),transparent_48%)]">
        </div>

        <div class="absolute inset-[1px] rounded-[1.28rem] border border-white/5"></div>

        <span
            class="{{ $numberSize }} relative -mt-1 font-black text-transparent drop-shadow-[0_8px_18px_rgba(245,166,35,0.32)]
            bg-gradient-to-br from-[#FFE2A1] via-[#F5A623] to-[#B86B09] bg-clip-text"
            style="font-family: ui-serif, Georgia, Cambria, 'Times New Roman', serif;">
            69
        </span>

        <span
            class="absolute bottom-3 h-[2px] w-8 rounded-full bg-gradient-to-r from-transparent via-amber-300/70 to-transparent">
        </span>
    </div>

    @if ($withText)
        <div class="leading-none">
            <div class="text-sm font-black uppercase tracking-[0.28em] text-amber-400">
                69 Coffee Shop
            </div>
            <div class="mt-1 text-xs font-semibold text-stone-400">
                Kasir Panel
            </div>
        </div>
    @endif
</div>
