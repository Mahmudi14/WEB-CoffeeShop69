@extends('layouts.master')
@section('title', 'POS Kasir')

@section('header-title', 'POS Kasir')
@section('content')
    <div x-data="posCart(@js($menusForJs), @js($initialCart), @js((bool) session('clear_pos_cart')))" x-init="init()" class="space-y-6">
        {{-- Header --}}
        <section class="relative overflow-visible rounded-[2rem] bg-[#171412] p-6 shadow-xl border border-white/10">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.22),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)] pointer-events-none">
            </div>
            <div class="relative z-10 flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                <div>
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-3 py-1.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span> <span
                            class="text-[11px] font-black uppercase tracking-[0.22em] text-stone-300"> Cashier POS </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight"> POS Kasir </h1>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-stone-300"> Pilih menu, atur jumlah, lalu
                        lanjutkan checkout. </p>
                </div>
                {{-- Search & Filter --}}
                <div class="w-full xl:max-w-2xl">
                    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_220px_110px]">
                        <div class="relative"> <svg
                                class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="search" x-model.debounce.200ms="searchQuery"
                                class="h-[52px] w-full rounded-2xl border border-white/10 bg-white px-12 text-sm font-bold text-stone-900 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-500/20"
                                placeholder="Cari menu...">
                        </div>
                        {{-- Custom Category Dropdown --}}
                        <div x-data="{ openCategory: false }" class="relative">
                            <button type="button" @click="openCategory = !openCategory"
                                @keydown.escape.window="openCategory = false"
                                class="flex h-[52px] w-full items-center justify-between gap-3 rounded-2xl border border-white/10 bg-white px-4 text-left text-sm font-bold text-stone-700 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-500/20">
                                <span class="min-w-0 truncate">
                                    <span x-show="selectedCategory === 'all'">Semua
                                        Kategori
                                    </span>
                                    @foreach ($categories as $category)
                                        @if ($category->menus->isNotEmpty())
                                            <span x-show="selectedCategory === '{{ $category->id }}'" x-cloak>
                                                {{ $category->name }}
                                            </span>
                                        @endif
                                    @endforeach
                                </span>
                                <svg class="h-4 w-4 shrink-0 text-stone-400 transition"
                                    :class="openCategory ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="openCategory" x-cloak x-transition.origin.top @click.outside="openCategory = false"
                                class="absolute left-0 right-0 top-[60px] z-50 overflow-hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                                <button type="button" @click="selectedCategory = 'all'; openCategory = false"
                                    class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                    :class="selectedCategory === 'all' ? 'bg-amber-100 text-amber-800' : 'text-stone-700'">
                                    <span>Semua Kategori
                                    </span>
                                    <svg x-show="selectedCategory === 'all'" x-cloak class="h-4 w-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                                @foreach ($categories as $category)
                                    @if ($category->menus->isNotEmpty())
                                        <button type="button"
                                            @click="selectedCategory = '{{ $category->id }}'; openCategory = false"
                                            class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-black transition hover:bg-amber-50 hover:text-amber-700"
                                            :class="selectedCategory === '{{ $category->id }}' ? 'bg-amber-100 text-amber-800' :
                                                'text-stone-700'">
                                            <span class="truncate">{{ $category->name }}</span>
                                            <svg x-show="selectedCategory === '{{ $category->id }}'" x-cloak
                                                class="h-4 w-4 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <button type="button" @click="searchQuery = ''; selectedCategory = 'all'"
                            class="h-[52px] rounded-2xl border border-white/10 bg-white/10 px-5 text-sm font-black text-white transition hover:bg-white/15 active:scale-[0.98]">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <div
            class="grid items-start gap-4 min-[768px]:grid-cols-[minmax(0,7fr)_minmax(280px,3fr)] xl:grid-cols-[minmax(0,7fr)_minmax(320px,3fr)]">
            {{-- Menu Area - 70% --}}
            <section class="min-w-0">
                <div class="space-y-8">
                    @forelse ($categories as $category)
                        @if ($category->menus->isNotEmpty())
                            <div x-show="categoryHasVisible({{ $category->id }})" x-cloak
                                class="rounded-[2rem] border border-stone-100 bg-white p-5 shadow-sm">
                                <div class="mb-5 flex items-center justify-between gap-4">
                                    <h2 class="text-lg font-black text-stone-950">
                                        {{ $category->name }}
                                    </h2>

                                    <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-black text-stone-500">
                                        {{ $category->menus->count() }} menu
                                    </span>
                                </div>

                                <div
                                    class="grid grid-cols-1 gap-3 min-[768px]:grid-cols-2 min-[1180px]:grid-cols-3 xl:gap-4">
                                    @foreach ($category->menus as $menu)
                                        <div x-show="menuVisible({{ $category->id }}, @js($menu->name), @js($menu->description ?? ''))"
                                            x-cloak
                                            class="group overflow-hidden rounded-[1.75rem] border border-stone-100 bg-stone-50 p-4 transition hover:-translate-y-0.5 hover:border-amber-200 hover:bg-white hover:shadow-md">
                                            <div
                                                class="mb-3 flex h-24 items-center justify-center overflow-hidden rounded-2xl border border-stone-100 bg-white text-stone-400 min-[1200px]:h-28 xl:mb-4 xl:h-32">
                                                @if ($menu->image_path)
                                                    <img src="{{ asset('storage/' . $menu->image_path) }}"
                                                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                                        alt="{{ $menu->name }}">
                                                @else
                                                    <span
                                                        class="text-xs font-black uppercase tracking-widest text-stone-300">
                                                        No Image
                                                    </span>
                                                @endif
                                            </div>

                                            <div>
                                                <h3 class="line-clamp-1 text-sm font-black text-stone-950 xl:text-base">
                                                    {{ $menu->name }}
                                                </h3>
                                            </div>

                                            <div class="mt-4">
                                                <p class="text-lg font-black text-stone-950 xl:text-xl">
                                                    Rp{{ number_format($menu->normal_price, 0, ',', '.') }}
                                                </p>
                                            </div>

                                            <button type="button"
                                                @click="addItem({
                                            id: {{ $menu->id }},
                                            name: @js($menu->name),
                                            normal_price: {{ (int) $menu->normal_price }}
                                        })"
                                                class="mt-3 flex w-full items-center justify-center rounded-2xl bg-amber-600 px-4 py-2.5 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700 active:scale-[0.98] xl:mt-4 xl:py-3">
                                                Tambah
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <div
                            class="rounded-[2rem] border border-dashed border-stone-300 bg-white p-10 text-center shadow-sm">
                            <p class="text-sm font-black text-stone-700">
                                Belum ada kategori menu.
                            </p>
                        </div>
                    @endforelse

                    @if ($categories->isNotEmpty())
                        <div x-show="!hasVisibleMenus()" x-cloak
                            class="rounded-[2rem] border border-dashed border-stone-300 bg-white p-10 text-center shadow-sm">
                            <p class="text-sm font-black text-stone-700">
                                Menu tidak ditemukan.
                            </p>

                            <p class="mt-2 text-sm text-stone-500">
                                Coba kata kunci atau kategori lain.
                            </p>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Cart Area - 30% --}}
            <aside
                class="min-w-0 self-start min-[768px]:sticky min-[768px]:top-24 min-[768px]:z-20 min-[768px]:max-h-[calc(100dvh-10rem)]">
                <div
                    class="flex w-full flex-col overflow-hidden rounded-[2rem] border border-stone-100 bg-white shadow-sm min-[768px]:max-h-[calc(100dvh-7rem)]">
                    {{-- Cart Header --}}
                    <div class="shrink-0 border-b border-stone-100 p-4 xl:p-5">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-black text-stone-950">
                                    Cart
                                </h2>

                                <p class="mt-1 text-xs font-semibold text-stone-500">
                                    <span x-text="cart.length"></span>
                                    item dipilih
                                </p>
                            </div>

                            <button x-show="cart.length > 0" type="button" @click="clearCart()"
                                class="rounded-full bg-rose-50 px-3 py-1.5 text-xs font-black text-rose-600 transition hover:bg-rose-100">
                                Kosongkan
                            </button>
                        </div>
                    </div>

                    {{-- Empty State --}}
                    <template x-if="cart.length === 0">
                        <div class="p-5">
                            <div class="rounded-2xl border border-dashed border-stone-300 bg-stone-50 p-8 text-center">
                                <div
                                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border border-stone-100 bg-white text-stone-300">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 7h14M9 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" />
                                    </svg>
                                </div>

                                <p class="mt-4 text-sm font-black text-stone-700">
                                    Cart masih kosong.
                                </p>
                            </div>
                        </div>
                    </template>

                    {{-- Cart Content --}}
                    <template x-if="cart.length > 0">
                        <div class="flex min-h-0 flex-1 flex-col">
                            {{-- Items --}}
                            <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain p-4 xl:p-5">
                                <div class="space-y-3">
                                    <template x-for="item in cart" :key="item.key">
                                        <div class="rounded-2xl border border-stone-100 bg-stone-50 p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm font-black text-stone-950"
                                                        x-text="item.name"></p>

                                                    <p class="mt-1 text-xs font-semibold text-stone-500">
                                                        <span x-text="formatCurrency(item.normal_price)"></span>
                                                        / item
                                                    </p>
                                                </div>

                                                <button type="button" @click="removeItem(item.key)"
                                                    class="rounded-full bg-white px-3 py-1 text-xs font-black text-rose-600 transition hover:bg-rose-50">
                                                    Hapus
                                                </button>
                                            </div>

                                            <div class="mt-4 flex items-center justify-between gap-3">
                                                <div class="flex items-center gap-2">
                                                    <button type="button" @click="decreaseQty(item.key)"
                                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-stone-200 bg-white text-lg font-black text-stone-700 transition hover:bg-stone-100">
                                                        -
                                                    </button>

                                                    <input type="number" min="1" x-model.number="item.quantity"
                                                        @input="item.quantity = Math.max(Number(item.quantity) || 1, 1); persistCart()"
                                                        class="h-9 w-16 rounded-xl border border-stone-200 bg-white px-2 text-center text-sm font-black text-stone-900 outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-100">

                                                    <button type="button" @click="increaseQty(item.key)"
                                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-stone-200 bg-white text-lg font-black text-stone-700 transition hover:bg-stone-100">
                                                        +
                                                    </button>
                                                </div>

                                                <p class="text-sm font-black text-stone-950"
                                                    x-text="formatCurrency(item.normal_price * item.quantity)"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Summary --}}
                            <div class="shrink-0 border-t border-stone-100 bg-white p-4 xl:p-5">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-stone-500">
                                            Subtotal
                                        </span>

                                        <span class="text-lg font-black text-stone-950"
                                            x-text="formatCurrency(subtotal())"></span>
                                    </div>

                                    <form method="POST" action="{{ route('cashier.pos.prepare-checkout') }}">
                                        @csrf

                                        <input type="hidden" name="cart_json" :value="JSON.stringify(cartPayload())">

                                        <button type="submit"
                                            class="flex h-[52px] w-full items-center justify-center rounded-2xl bg-amber-600 px-5 text-sm font-black text-white shadow-lg shadow-amber-600/20 transition hover:bg-amber-700 active:scale-[0.98]">
                                            Checkout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </aside>
        </div>

        <script>
            function posCart(menus, initialCart = [], shouldClear = false) {
                return {
                    menus: menus,
                    initialCart: initialCart,
                    shouldClear: shouldClear,
                    storageKey: 'cashier_pos_cart',
                    cart: [],
                    searchQuery: '',
                    selectedCategory: 'all',

                    init() {
                        if (this.shouldClear) {
                            this.cart = [];
                            localStorage.removeItem(this.storageKey);
                            return;
                        }

                        const savedCart = localStorage.getItem(this.storageKey);

                        if (savedCart) {
                            try {
                                const parsed = JSON.parse(savedCart);

                                this.cart = Array.isArray(parsed) ?
                                    parsed.map(item => this.normalizeItem(item)) :
                                    this.initialCart.map(item => this.normalizeItem(item));
                            } catch (error) {
                                this.cart = this.initialCart.map(item => this.normalizeItem(item));
                            }
                        } else {
                            this.cart = this.initialCart.map(item => this.normalizeItem(item));
                        }

                        this.$watch('cart', value => {
                            localStorage.setItem(this.storageKey, JSON.stringify(value));
                        });
                    },

                    normalizeItem(item) {
                        return {
                            key: item.key || `${item.menu_id}-${Date.now()}-${Math.random().toString(16).slice(2)}`,
                            menu_id: Number(item.menu_id),
                            name: item.name,
                            normal_price: Number(item.normal_price),
                            quantity: Math.max(Number(item.quantity) || 1, 1),
                        };
                    },

                    normalizeText(value) {
                        return String(value ?? '').toLowerCase().trim();
                    },

                    menuVisible(categoryId, menuName, menuDescription = '') {
                        const categoryMatched =
                            this.selectedCategory === 'all' ||
                            String(this.selectedCategory) === String(categoryId);

                        const keyword = this.normalizeText(this.searchQuery);
                        const text = this.normalizeText(`${menuName} ${menuDescription}`);

                        return categoryMatched && (keyword === '' || text.includes(keyword));
                    },

                    categoryHasVisible(categoryId) {
                        return this.menus.some(menu => {
                            return String(menu.category_id) === String(categoryId) &&
                                this.menuVisible(menu.category_id, menu.name, menu.description || '');
                        });
                    },

                    hasVisibleMenus() {
                        return this.menus.some(menu => {
                            return this.menuVisible(menu.category_id, menu.name, menu.description || '');
                        });
                    },

                    addItem(menu) {
                        const existing = this.cart.find(item => item.menu_id === menu.id);

                        if (existing) {
                            existing.quantity++;
                            this.persistCart();
                            return;
                        }

                        this.cart.push({
                            key: `${menu.id}-${Date.now()}-${Math.random().toString(16).slice(2)}`,
                            menu_id: menu.id,
                            name: menu.name,
                            normal_price: Number(menu.normal_price),
                            quantity: 1,
                        });

                        this.persistCart();
                    },

                    removeItem(key) {
                        this.cart = this.cart.filter(item => item.key !== key);
                        this.persistCart();
                    },

                    increaseQty(key) {
                        const item = this.cart.find(item => item.key === key);

                        if (!item) return;

                        item.quantity++;
                        this.persistCart();
                    },

                    decreaseQty(key) {
                        const item = this.cart.find(item => item.key === key);

                        if (!item) return;

                        item.quantity--;

                        if (item.quantity <= 0) {
                            this.removeItem(key);
                            return;
                        }

                        this.persistCart();
                    },

                    async clearCart() {
                        this.cart = [];
                        localStorage.removeItem(this.storageKey);

                        try {
                            await fetch('{{ route('cashier.pos.cart.clear') }}', {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                },
                            });
                        } catch (error) {
                            console.error('Gagal menghapus session cart:', error);
                        }
                    },

                    persistCart() {
                        localStorage.setItem(this.storageKey, JSON.stringify(this.cart));
                    },

                    subtotal() {
                        return this.cart.reduce((total, item) => {
                            const qty = Math.max(Number(item.quantity) || 1, 1);

                            return total + (Number(item.normal_price) * qty);
                        }, 0);
                    },

                    cartPayload() {
                        return this.cart
                            .filter(item => Number(item.quantity) > 0)
                            .map(item => ({
                                menu_id: item.menu_id,
                                quantity: Number(item.quantity),
                            }));
                    },

                    formatCurrency(value) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            maximumFractionDigits: 0,
                        }).format(value);
                    },
                };
            }
        </script>
    @endsection
