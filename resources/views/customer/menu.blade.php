@extends('layouts.customer')

@section('title', 'Katalog Menu - Cafe 69')

@section('content')
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .bottom-sheet-up {
            transform: translateY(100%);
            transition: transform 0.35s ease;
        }

        .bottom-sheet-active {
            transform: translateY(0);
        }
    </style>

    @php
        $orderingOpen = $orderingOpen ?? false;
    @endphp

    <div class="min-h-screen bg-white" data-table-token="{{ $table->qr_token }}">
        {{-- HERO --}}
        <div
            class="relative z-20 overflow-hidden rounded-b-[2rem] border-b border-white/10 bg-[#11100f] px-5 pb-7 pt-7 shadow-[0_18px_45px_rgba(0,0,0,0.22)]">
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.18),transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_45%)]">
            </div>

            <div
                class="absolute left-0 right-0 top-0 h-px bg-gradient-to-r from-transparent via-amber-300/40 to-transparent">
            </div>

            <div class="relative z-10 flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <div class="mb-3 inline-flex items-center gap-2">
                        <span class="h-px w-7 bg-amber-400/70"></span>
                        <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-amber-300">
                            Modern Cafe & Dining
                        </p>
                    </div>

                    <h1 class="text-3xl font-black leading-none tracking-tight text-white sm:text-4xl">
                        Cafe 69
                    </h1>

                    <p class="mt-2 max-w-[250px] text-xs font-medium leading-relaxed text-stone-300 sm:text-sm">
                        Nikmati pilihan menu terbaik dengan pengalaman pesan yang praktis dan nyaman.
                    </p>

                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <div
                            class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/[0.08] px-3.5 py-2 shadow-sm backdrop-blur-md">
                            <span class="relative flex h-2.5 w-2.5">
                                <span
                                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"></span>
                                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                            </span>

                            <p class="text-[11px] font-semibold text-stone-300">
                                Nomor Meja
                                <span class="ml-1 font-black text-white">
                                    {{ $table->name }}
                                </span>
                            </p>

                        </div>

                        <a href="{{ route('customer.orders.track', ['table' => $table->qr_token]) }}"
                            class="inline-flex items-center gap-2 rounded-2xl border border-amber-400/30 bg-amber-400/10 px-3.5 py-2 text-[11px] font-black text-amber-300 shadow-sm backdrop-blur-md transition hover:bg-amber-400/20 active:scale-[0.98]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>

                            Tracking Pesanan
                        </a>
                    </div>
                </div>

                <div class="relative shrink-0">
                    <div class="absolute inset-0 rounded-full bg-amber-400/30 blur-xl"></div>

                    <div
                        class="relative h-16 w-16 rounded-[1.35rem] bg-gradient-to-br from-[#f7d08a] via-[#d99a35] to-[#7a4b18] p-[1px] shadow-2xl shadow-black/30">
                        <div
                            class="flex h-full w-full items-center justify-center rounded-[1.3rem] border border-white/10 bg-[#1d1916]">
                            <span class="text-3xl drop-shadow-md">🍽️</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (!$orderingOpen)
            <div class="bg-white px-4 pt-5 sm:px-6">
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-4">
                    <p class="text-sm font-black text-rose-700">
                        Pemesanan Belum Tersedia
                    </p>

                    <p class="mt-1 text-xs font-semibold leading-5 text-rose-600">
                        Saat ini belum ada kasir yang membuka shift. Kamu masih bisa melihat menu, tapi belum bisa membuat
                        pesanan.
                    </p>
                </div>
            </div>
        @endif
        {{-- CATEGORY FILTER --}}
        <div class="sticky top-0 z-10 border-b border-slate-100 bg-white px-4 py-5 shadow-sm">
            <div class="hide-scrollbar flex snap-x gap-3 overflow-x-auto pb-1" id="category-filter-bar">
                <button type="button" data-category="all"
                    class="category-filter-btn active snap-start whitespace-nowrap rounded-2xl bg-[#1f1a17] px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-stone-900/20 transition-colors">
                    Semua Menu
                </button>

                @foreach ($categories as $category)
                    @if ($category->menus->isNotEmpty())
                        <button type="button" data-category="{{ $category->id }}"
                            class="category-filter-btn snap-start whitespace-nowrap rounded-2xl border border-slate-100 bg-slate-50 px-5 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-800">
                            {{ $category->name }}
                        </button>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- SEARCH --}}
        <div class="bg-white px-4 pt-5 sm:px-6">
            @if (session('success'))
                <div class="mb-4 rounded-xl bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <input id="menu-search-input" type="search" placeholder="Cari menu di Cafe 69..." enterkeyhint="search"
                    autocomplete="off"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-12 pr-4 text-sm font-medium text-slate-800 outline-none transition-all focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10">
            </div>
        </div>

        {{-- MENU GRID --}}
        <div class="bg-white px-4 pt-5 pb-32 sm:px-6">
            <div id="empty-menu-state" class="hidden py-20 text-center">
                <div
                    class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-slate-300">
                    <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-slate-800">Menu Tidak Ditemukan</h4>
                <p class="mt-1 text-sm text-slate-500">Coba kata kunci atau kategori lain.</p>
            </div>

            <div class="grid grid-cols-2 gap-4 sm:gap-6 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($categories as $category)
                    @foreach ($category->menus as $menu)
                        <div class="menu-card group relative flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-100 bg-white p-2.5 shadow-sm transition-all duration-300 hover:shadow-xl"
                            data-category-id="{{ $category->id }}" data-menu-name="{{ strtolower($menu->name) }}">
                            <div class="relative aspect-[4/3] w-full flex-shrink-0 overflow-hidden rounded-2xl bg-slate-50">
                                @if ($menu->image_path)
                                    <img src="{{ asset('storage/' . $menu->image_path) }}"
                                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        alt="{{ $menu->name }}">
                                @else
                                    <div
                                        class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-stone-100 to-stone-50">
                                        <span class="text-2xl font-black tracking-tight text-stone-300">
                                            69
                                        </span>
                                    </div>
                                @endif

                                <span
                                    class="absolute left-2 top-2 rounded-lg bg-white/90 px-2.5 py-1 text-[10px] font-bold text-slate-600 shadow-sm backdrop-blur-sm">
                                    {{ $category->name }}
                                </span>
                            </div>

                            <div class="flex flex-1 flex-col justify-between px-1 pb-1 pt-3">
                                <div>
                                    <h3
                                        class="line-clamp-2 text-sm font-extrabold leading-tight text-slate-800 sm:text-base">
                                        {{ $menu->name }}
                                    </h3>
                                </div>

                                <div class="mt-4 flex items-end justify-between">
                                    <span class="text-[15px] font-black text-emerald-600 sm:text-base">
                                        Rp {{ number_format($menu->normal_price, 0, ',', '.') }}
                                    </span>

                                    @if ($orderingOpen)
                                        <button type="button"
                                            onclick="addCustomerCart({{ $menu->id }}, @js($menu->name), {{ (int) $menu->normal_price }})"
                                            class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-2xl bg-[#1f1a17] text-white shadow-sm transition-all hover:bg-amber-500 active:scale-90">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        {{-- FLOATING CART --}}
        <div id="floating-cart"
            class="fixed bottom-6 left-1/2 z-40 flex w-[calc(100%-2rem)] -translate-x-1/2 translate-y-32 cursor-pointer items-center justify-between rounded-[1.75rem] border border-white/10 bg-[#1f1a17] p-2 pr-4 text-white opacity-0 shadow-2xl shadow-black/30 transition-all duration-500 hover:bg-[#2a231f] md:max-w-md"
            onclick="toggleCheckoutModal()">
            <div class="flex items-center gap-3">
                <div id="cart-total-items"
                    class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-500 text-base font-black text-white shadow-inner">
                    0
                </div>

                <div class="text-left">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Total Pesanan
                    </p>
                    <p id="cart-total-price" class="mt-0.5 text-sm font-black leading-none md:text-base">
                        Rp 0
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-1.5 rounded-2xl bg-white px-4 py-2 text-sm font-bold text-stone-900">
                Checkout
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </div>

        {{-- CART MODAL --}}
        <div id="checkout-modal" class="pointer-events-none fixed inset-0 z-50 flex flex-col justify-end">
            <div id="checkout-backdrop"
                class="absolute inset-0 bg-slate-900/40 opacity-0 backdrop-blur-sm transition-opacity duration-300"
                onclick="toggleCheckoutModal()"></div>

            <div id="checkout-panel"
                class="bottom-sheet-up hide-scrollbar relative mx-auto flex w-full flex-col overflow-y-auto rounded-t-[2rem] bg-white shadow-2xl md:max-w-2xl lg:max-w-4xl"
                style="max-height: 92vh;">
                <div
                    class="sticky top-0 z-40 flex shrink-0 items-center justify-between rounded-t-[2rem] border-b border-slate-100 bg-white/95 p-6 backdrop-blur-sm">
                    <h2 class="text-xl font-black text-slate-800">
                        Keranjang 🛒
                    </h2>

                    <button type="button" onclick="toggleCheckoutModal()"
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition-colors hover:bg-rose-100 hover:text-rose-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('customer.qr.prepare-checkout', $table->qr_token) }}" method="POST"
                    class="flex flex-col">
                    @csrf

                    <input type="hidden" name="cart_json" id="cart-json-input">

                    <div class="space-y-7 p-6">
                        <div>
                            <h4 class="mb-3 text-xs font-bold uppercase tracking-widest text-slate-500">
                                Pesanan Anda
                            </h4>

                            <div id="checkout-items-list" class="space-y-3"></div>
                        </div>

                        <div class="h-px w-full bg-slate-100"></div>

                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                            <p class="text-sm font-black text-amber-800">
                                Estimasi total:
                                <span id="modal-total-price">Rp 0</span>
                            </p>
                            <p class="mt-1 text-xs font-semibold text-amber-700">
                                Promo dan PPN akan disesuaikan otomatis saat checkout.
                            </p>
                        </div>
                    </div>

                    <div class="sticky bottom-0 z-40 mt-auto border-t border-slate-100 bg-white p-6">
                        <button type="submit" onclick="prepareCartSubmit()"
                            class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[#1f1a17] py-4 text-lg font-black text-white shadow-xl shadow-black/20 transition-transform hover:bg-[#2a231f] active:scale-[0.98]">
                            Lanjut Checkout • <span id="final-btn-price">Rp 0</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let custCart = [];
        let activeCategory = 'all';

        const storageKey = 'customer_cart_{{ $table->qr_token }}';

        function loadCustomerCartFromStorage() {
            const savedCart = localStorage.getItem(storageKey);

            if (savedCart) {
                try {
                    custCart = JSON.parse(savedCart) || [];
                } catch (error) {
                    custCart = [];
                    localStorage.removeItem(storageKey);
                }
            } else {
                custCart = [];
            }

            updateFloatingCart();

            if (custCart.length === 0) {
                closeCheckoutModal();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadCustomerCartFromStorage();
            bindMenuFilter();
        });

        window.addEventListener('pageshow', () => {
            loadCustomerCartFromStorage();
        });

        function bindMenuFilter() {
            const searchInput = document.getElementById('menu-search-input');

            document.querySelectorAll('.category-filter-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    activeCategory = btn.dataset.category || 'all';

                    document.querySelectorAll('.category-filter-btn').forEach(item => {
                        item.classList.remove('active', 'bg-[#1f1a17]', 'text-white', 'shadow-sm',
                            'shadow-stone-900/20');
                        item.classList.add('bg-slate-50', 'text-slate-600', 'border',
                            'border-slate-100');
                    });

                    btn.classList.add('active', 'bg-[#1f1a17]', 'text-white', 'shadow-sm',
                        'shadow-stone-900/20');
                    btn.classList.remove('bg-slate-50', 'text-slate-600', 'border', 'border-slate-100');

                    applyFilter();
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', applyFilter);
            }
        }

        function applyFilter() {
            const keyword = (document.getElementById('menu-search-input')?.value || '').toLowerCase().trim();
            const cards = document.querySelectorAll('.menu-card');
            const emptyState = document.getElementById('empty-menu-state');

            let visibleCount = 0;

            cards.forEach(card => {
                const categoryId = card.dataset.categoryId;
                const menuName = card.dataset.menuName || '';

                const matchCategory = activeCategory === 'all' || activeCategory === categoryId;
                const matchKeyword = keyword === '' || menuName.includes(keyword);

                if (matchCategory && matchKeyword) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            if (emptyState) {
                emptyState.classList.toggle('hidden', visibleCount > 0);
            }
        }

        const saveCart = () => localStorage.setItem(storageKey, JSON.stringify(custCart));

        const formatRp = (angka) => new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(angka);

        function addCustomerCart(id, name, price) {
            const existing = custCart.find(item => Number(item.menu_id) === Number(id));

            if (existing) {
                existing.quantity += 1;
            } else {
                custCart.push({
                    key: `${id}-${Date.now()}-${Math.random().toString(16).slice(2)}`,
                    menu_id: Number(id),
                    name: name,
                    normal_price: Number(price),
                    quantity: 1,
                    note: '',
                });
            }

            saveCart();
            updateFloatingCart();

            const cartBtn = document.getElementById('floating-cart');

            if (cartBtn) {
                cartBtn.classList.add('scale-105');
                setTimeout(() => cartBtn.classList.remove('scale-105'), 200);
            }
        }

        function updateCustQty(key, change) {
            const item = custCart.find(item => item.key === key);

            if (!item) return;

            item.quantity += change;

            if (item.quantity <= 0) {
                custCart = custCart.filter(cartItem => cartItem.key !== key);
            }

            saveCart();
            updateFloatingCart();
            renderCheckoutItems();

            if (custCart.length === 0) {
                closeCheckoutModal();
            }
        }

        function removeCustomerItem(key) {
            custCart = custCart.filter(item => item.key !== key);
            saveCart();
            updateFloatingCart();
            renderCheckoutItems();

            if (custCart.length === 0) {
                closeCheckoutModal();
            }
        }

        function getCartTotal() {
            return custCart.reduce((total, item) => {
                return total + (Number(item.normal_price) * Number(item.quantity));
            }, 0);
        }

        function updateFloatingCart() {
            const bar = document.getElementById('floating-cart');
            const totalItemsEl = document.getElementById('cart-total-items');
            const totalPriceEl = document.getElementById('cart-total-price');
            const finalBtnPrice = document.getElementById('final-btn-price');
            const modalTotalPrice = document.getElementById('modal-total-price');

            const totalQty = custCart.reduce((total, item) => total + Number(item.quantity), 0);
            const totalPrice = getCartTotal();

            if (totalItemsEl) totalItemsEl.innerText = totalQty;
            if (totalPriceEl) totalPriceEl.innerText = formatRp(totalPrice);
            if (finalBtnPrice) finalBtnPrice.innerText = formatRp(totalPrice);
            if (modalTotalPrice) modalTotalPrice.innerText = formatRp(totalPrice);

            if (!bar) return;

            if (totalQty > 0) {
                bar.classList.remove('translate-y-32', 'opacity-0');
            } else {
                bar.classList.add('translate-y-32', 'opacity-0');
            }
        }

        function toggleCheckoutModal() {
            const modal = document.getElementById('checkout-modal');

            if (!modal) return;

            if (modal.classList.contains('pointer-events-none')) {
                openCheckoutModal();
            } else {
                closeCheckoutModal();
            }
        }

        function openCheckoutModal() {
            if (custCart.length === 0) return;

            const modal = document.getElementById('checkout-modal');
            const panel = document.getElementById('checkout-panel');
            const backdrop = document.getElementById('checkout-backdrop');

            if (!modal || !panel || !backdrop) return;

            renderCheckoutItems();

            modal.classList.remove('pointer-events-none');
            backdrop.classList.remove('opacity-0');
            panel.classList.add('bottom-sheet-active');
            document.body.style.overflow = 'hidden';
        }

        function closeCheckoutModal() {
            const modal = document.getElementById('checkout-modal');
            const panel = document.getElementById('checkout-panel');
            const backdrop = document.getElementById('checkout-backdrop');

            if (!modal || !panel || !backdrop) return;

            backdrop.classList.add('opacity-0');
            panel.classList.remove('bottom-sheet-active');

            setTimeout(() => {
                modal.classList.add('pointer-events-none');
                document.body.style.overflow = '';
            }, 350);
        }

        function escapeHtml(value) {
            return String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function renderCheckoutItems() {
            const list = document.getElementById('checkout-items-list');

            if (!list) return;

            list.innerHTML = '';

            custCart.forEach(item => {
                const safeName = escapeHtml(item.name);
                const safeKey = escapeHtml(item.key);
                const safeQty = Number(item.quantity);
                const safePrice = Number(item.normal_price);

                list.innerHTML += `
                    <div class="mb-3 flex items-center justify-between rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                        <div class="flex-1 pr-3">
                            <p class="mb-1 line-clamp-1 text-sm font-extrabold text-slate-800">${safeName}</p>
                            <p class="text-xs font-bold text-emerald-600">${formatRp(safePrice)}</p>
                        </div>

                        <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-1">
                            <button
                                type="button"
                                onclick="updateCustQty('${safeKey}', -1)"
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-white pb-0.5 font-bold text-slate-600 shadow-sm transition-colors hover:bg-rose-100 hover:text-rose-600"
                            >
                                -
                            </button>

                            <span class="w-4 text-center text-sm font-black text-slate-800">${safeQty}</span>

                            <button
                                type="button"
                                onclick="updateCustQty('${safeKey}', 1)"
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1f1a17] pb-0.5 font-bold text-white shadow-sm transition-colors hover:bg-[#2a231f]"
                            >
                                +
                            </button>
                        </div>

                        <button
                            type="button"
                            onclick="removeCustomerItem('${safeKey}')"
                            class="ml-3 text-xs font-black text-rose-600"
                        >
                            Hapus
                        </button>
                    </div>
                `;
            });

            updateFloatingCart();
        }

        function prepareCartSubmit() {
            const input = document.getElementById('cart-json-input');

            if (!input) return;

            const payload = custCart
                .filter(item => Number(item.quantity) > 0)
                .map(item => ({
                    menu_id: Number(item.menu_id),
                    quantity: Number(item.quantity),
                    note: item.note || null,
                }));

            input.value = JSON.stringify(payload);
        }
    </script>
@endsection
