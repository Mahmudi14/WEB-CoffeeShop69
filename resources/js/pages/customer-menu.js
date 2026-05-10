let custCart = [];
let activeCategory = "all";
let storageKey = null;

document.addEventListener("DOMContentLoaded", () => {
    const page = document.getElementById("customer-menu-page");

    if (!page) {
        return;
    }

    storageKey = page.dataset.storageKey;

    if (!storageKey) {
        console.error("Storage key tidak ditemukan.");
        return;
    }

    loadCustomerCartFromStorage();
    bindMenuFilter();
    bindCartActions();
    bindCheckoutForm();
});

window.addEventListener("pageshow", () => {
    if (!storageKey) {
        return;
    }

    loadCustomerCartFromStorage();
});

function loadCustomerCartFromStorage() {
    const savedCart = localStorage.getItem(storageKey);

    if (!savedCart) {
        custCart = [];
        updateFloatingCart();
        closeCheckoutModal();
        return;
    }

    try {
        custCart = JSON.parse(savedCart) || [];
    } catch (error) {
        custCart = [];
        localStorage.removeItem(storageKey);
    }

    updateFloatingCart();

    if (custCart.length === 0) {
        closeCheckoutModal();
    }
}

function saveCart() {
    try {
        localStorage.setItem(storageKey, JSON.stringify(custCart));
    } catch (error) {
        console.error("Gagal menyimpan cart ke localStorage:", error);
    }
}

function bindCartActions() {
    document.addEventListener("click", (event) => {
        const addButton = event.target.closest(".add-cart-btn");

        if (addButton) {
            addCustomerCart(
                addButton.dataset.menuId,
                addButton.dataset.menuName,
                addButton.dataset.menuPrice,
            );

            return;
        }

        const qtyButton = event.target.closest("[data-cart-qty-action]");

        if (qtyButton) {
            updateCustQty(
                qtyButton.dataset.cartKey,
                Number(qtyButton.dataset.cartQtyAction),
            );

            return;
        }

        const removeButton = event.target.closest("[data-cart-remove]");

        if (removeButton) {
            removeCustomerItem(removeButton.dataset.cartRemove);
        }
    });
}

function bindCheckoutForm() {
    const form = document.getElementById("checkout-form");

    if (!form) {
        return;
    }

    form.addEventListener("submit", () => {
        prepareCartSubmit();
    });
}

function bindMenuFilter() {
    const searchInput = document.getElementById("menu-search-input");

    document.querySelectorAll(".category-filter-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            activeCategory = btn.dataset.category || "all";

            document
                .querySelectorAll(".category-filter-btn")
                .forEach((item) => {
                    item.classList.remove(
                        "active",
                        "bg-[#1f1a17]",
                        "text-white",
                        "shadow-sm",
                        "shadow-stone-900/20",
                    );

                    item.classList.add(
                        "bg-slate-50",
                        "text-slate-600",
                        "border",
                        "border-slate-100",
                    );
                });

            btn.classList.add(
                "active",
                "bg-[#1f1a17]",
                "text-white",
                "shadow-sm",
                "shadow-stone-900/20",
            );

            btn.classList.remove(
                "bg-slate-50",
                "text-slate-600",
                "border",
                "border-slate-100",
            );

            applyFilter();
        });
    });

    searchInput?.addEventListener("input", applyFilter);
}

function applyFilter() {
    const keyword = (document.getElementById("menu-search-input")?.value || "")
        .toLowerCase()
        .trim();

    const cards = document.querySelectorAll(".menu-card");
    const emptyState = document.getElementById("empty-menu-state");

    let visibleCount = 0;

    cards.forEach((card) => {
        const categoryId = card.dataset.categoryId;
        const menuName = (card.dataset.menuName || "").toLowerCase();

        const matchCategory =
            activeCategory === "all" || activeCategory === categoryId;

        const matchKeyword = keyword === "" || menuName.includes(keyword);

        card.classList.toggle("hidden", !(matchCategory && matchKeyword));

        if (matchCategory && matchKeyword) {
            visibleCount++;
        }
    });

    emptyState?.classList.toggle("hidden", visibleCount > 0);
}

function formatRp(value) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(Number(value));
}

function addCustomerCart(id, name, price) {
    const existing = custCart.find((item) => {
        return Number(item.menu_id) === Number(id);
    });

    if (existing) {
        existing.quantity += 1;
    } else {
        custCart.push({
            key: `${id}-${Date.now()}-${Math.random().toString(16).slice(2)}`,
            menu_id: Number(id),
            name,
            normal_price: Number(price),
            quantity: 1,
            note: "",
        });
    }

    saveCart();
    updateFloatingCart();
    animateFloatingCart();
}

function updateCustQty(key, change) {
    const item = custCart.find((item) => item.key === key);

    if (!item) {
        return;
    }

    item.quantity += change;

    if (item.quantity <= 0) {
        custCart = custCart.filter((cartItem) => cartItem.key !== key);
    }

    saveCart();
    updateFloatingCart();
    renderCheckoutItems();

    if (custCart.length === 0) {
        closeCheckoutModal();
    }
}

function removeCustomerItem(key) {
    custCart = custCart.filter((item) => item.key !== key);

    saveCart();
    updateFloatingCart();
    renderCheckoutItems();

    if (custCart.length === 0) {
        closeCheckoutModal();
    }
}

function getCartTotal() {
    return custCart.reduce((total, item) => {
        return total + Number(item.normal_price) * Number(item.quantity);
    }, 0);
}

function updateFloatingCart() {
    const bar = document.getElementById("floating-cart");
    const totalItemsEl = document.getElementById("cart-total-items");
    const totalPriceEl = document.getElementById("cart-total-price");
    const finalBtnPrice = document.getElementById("final-btn-price");
    const modalTotalPrice = document.getElementById("modal-total-price");

    const totalQty = custCart.reduce((total, item) => {
        return total + Number(item.quantity);
    }, 0);

    const totalPrice = getCartTotal();

    if (totalItemsEl) totalItemsEl.innerText = totalQty;
    if (totalPriceEl) totalPriceEl.innerText = formatRp(totalPrice);
    if (finalBtnPrice) finalBtnPrice.innerText = formatRp(totalPrice);
    if (modalTotalPrice) modalTotalPrice.innerText = formatRp(totalPrice);

    if (!bar) {
        return;
    }

    bar.classList.toggle("translate-y-32", totalQty === 0);
    bar.classList.toggle("opacity-0", totalQty === 0);
}

function animateFloatingCart() {
    const cartBtn = document.getElementById("floating-cart");

    if (!cartBtn) {
        return;
    }

    cartBtn.classList.add("scale-105");

    setTimeout(() => {
        cartBtn.classList.remove("scale-105");
    }, 200);
}

function toggleCheckoutModal() {
    const modal = document.getElementById("checkout-modal");

    if (!modal) {
        return;
    }

    if (modal.classList.contains("pointer-events-none")) {
        openCheckoutModal();
    } else {
        closeCheckoutModal();
    }
}

function openCheckoutModal() {
    if (custCart.length === 0) {
        return;
    }

    const modal = document.getElementById("checkout-modal");
    const panel = document.getElementById("checkout-panel");
    const backdrop = document.getElementById("checkout-backdrop");

    if (!modal || !panel || !backdrop) {
        return;
    }

    renderCheckoutItems();

    modal.classList.remove("pointer-events-none");
    backdrop.classList.remove("opacity-0");
    panel.classList.add("bottom-sheet-active");
    document.body.style.overflow = "hidden";
}

function closeCheckoutModal() {
    const modal = document.getElementById("checkout-modal");
    const panel = document.getElementById("checkout-panel");
    const backdrop = document.getElementById("checkout-backdrop");

    if (!modal || !panel || !backdrop) {
        return;
    }

    backdrop.classList.add("opacity-0");
    panel.classList.remove("bottom-sheet-active");

    setTimeout(() => {
        modal.classList.add("pointer-events-none");
        document.body.style.overflow = "";
    }, 350);
}

function escapeHtml(value) {
    return String(value)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

function renderCheckoutItems() {
    const list = document.getElementById("checkout-items-list");

    if (!list) {
        return;
    }

    list.innerHTML = custCart
        .map((item) => {
            const safeName = escapeHtml(item.name);
            const safeKey = escapeHtml(item.key);
            const quantity = Number(item.quantity);
            const price = Number(item.normal_price);

            return `
                <div class="mb-3 flex items-center justify-between rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <div class="flex-1 pr-3">
                        <p class="mb-1 line-clamp-1 text-sm font-extrabold text-slate-800">
                            ${safeName}
                        </p>

                        <p class="text-xs font-bold text-emerald-600">
                            ${formatRp(price)}
                        </p>
                    </div>

                    <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-1">
                        <button
                            type="button"
                            data-cart-key="${safeKey}"
                            data-cart-qty-action="-1"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-white pb-0.5 font-bold text-slate-600 shadow-sm transition-colors hover:bg-rose-100 hover:text-rose-600"
                        >
                            -
                        </button>

                        <span class="w-4 text-center text-sm font-black text-slate-800">
                            ${quantity}
                        </span>

                        <button
                            type="button"
                            data-cart-key="${safeKey}"
                            data-cart-qty-action="1"
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1f1a17] pb-0.5 font-bold text-white shadow-sm transition-colors hover:bg-[#2a231f]"
                        >
                            +
                        </button>
                    </div>

                    <button
                        type="button"
                        data-cart-remove="${safeKey}"
                        class="ml-3 text-xs font-black text-rose-600"
                    >
                        Hapus
                    </button>
                </div>
            `;
        })
        .join("");

    updateFloatingCart();
}

function prepareCartSubmit() {
    const input = document.getElementById("cart-json-input");

    if (!input) {
        return;
    }

    const payload = custCart
        .filter((item) => Number(item.quantity) > 0)
        .map((item) => ({
            menu_id: Number(item.menu_id),
            quantity: Number(item.quantity),
            note: item.note || null,
        }));

    input.value = JSON.stringify(payload);
}
