<div id="cashier-global-order-toast-container"
    class="pointer-events-none fixed bottom-5 right-5 z-[9999] flex w-full max-w-sm flex-col gap-3 px-4 sm:px-0">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pollUrl = "{{ route('cashier.incoming-orders.poll') }}";
        const incomingOrdersUrl = "{{ route('cashier.incoming-orders.index') }}";
        const currentRoute = "{{ request()->route()?->getName() }}";

        const safeReloadRoutes = [
            'cashier.dashboard',
            'cashier.incoming-orders.index',
        ];

        const toastContainer = document.getElementById('cashier-global-order-toast-container');

        let lastCount = Number("{{ (int) ($cashierIncomingOrderCount ?? 0) }}");
        let initialized = false;
        let isReloading = false;

        function updateBadgeElements(count) {
            const badges = document.querySelectorAll('[data-cashier-incoming-order-badge]');
            const labels = document.querySelectorAll('[data-cashier-incoming-order-count]');

            badges.forEach(function(badge) {
                if (count > 0) {
                    badge.classList.remove('hidden');
                    badge.classList.add('inline-flex');
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('inline-flex');
                }
            });

            labels.forEach(function(label) {
                label.textContent = count;
            });
        }

        function playNotificationSound() {
            try {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                if (!AudioContextClass) return;

                const audioContext = new AudioContextClass();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(880, audioContext.currentTime);

                gainNode.gain.setValueAtTime(0.001, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.18, audioContext.currentTime + 0.01);
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 0.35);

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.start();
                oscillator.stop(audioContext.currentTime + 0.35);
            } catch (error) {
                console.error('Gagal memutar bunyi notifikasi:', error);
            }
        }

        function showBrowserNotification(title, body) {
            if (!('Notification' in window)) return;

            if (Notification.permission === 'granted') {
                new Notification(title, {
                    body: body,
                    icon: '/favicon.ico'
                });
                return;
            }

            if (Notification.permission !== 'denied') {
                Notification.requestPermission().then(function(permission) {
                    if (permission === 'granted') {
                        new Notification(title, {
                            body: body,
                            icon: '/favicon.ico'
                        });
                    }
                });
            }
        }

        function showToast(title, body) {
            if (!toastContainer) return;

            const toast = document.createElement('div');
            toast.className =
                'pointer-events-auto overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-2xl';

            toast.innerHTML = `
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-10 w-10 flex-none items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 01-6 0m6 0H9" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-black text-stone-900">${title}</p>
                            <p class="mt-1 text-xs font-semibold leading-5 text-stone-500">${body}</p>
                        </div>

                        <button type="button" class="toast-close text-stone-400 hover:text-stone-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <a href="${incomingOrdersUrl}"
                        class="mt-4 flex h-10 items-center justify-center rounded-xl bg-[#171412] text-xs font-black text-white transition hover:bg-[#2a231f]">
                        Buka Order Masuk
                    </a>
                </div>
            `;

            toastContainer.appendChild(toast);

            const removeToast = function() {
                toast.classList.add('opacity-0', 'translate-y-2', 'transition', 'duration-300');
                setTimeout(function() {
                    toast.remove();
                }, 300);
            };

            toast.querySelector('.toast-close')?.addEventListener('click', removeToast);

            setTimeout(removeToast, 7000);
        }

        async function pollIncomingOrders() {
            try {
                const response = await fetch(pollUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    cache: 'no-store',
                });

                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                const data = await response.json();
                const currentCount = Number(data.count ?? 0);

                updateBadgeElements(currentCount);

                if (initialized && currentCount > lastCount) {
                    const diff = currentCount - lastCount;
                    const latestOrder = data.latest_order;

                    const title = diff === 1 ?
                        'Ada 1 order baru' :
                        `Ada ${diff} order baru`;

                    const body = latestOrder ?
                        `${latestOrder.order_number} • ${latestOrder.customer_name}` :
                        'Silakan cek halaman Order Masuk.';

                    showToast(title, body);
                    playNotificationSound();
                    showBrowserNotification('Order baru masuk', body);

                    if (safeReloadRoutes.includes(currentRoute) && !isReloading) {
                        isReloading = true;

                        setTimeout(function() {
                            window.location.reload();
                        }, 5000);
                    }
                }

                lastCount = currentCount;
                initialized = true;
            } catch (error) {
                console.error('Polling order masuk gagal:', error);
            }
        }

        updateBadgeElements(lastCount);
        pollIncomingOrders();
        setInterval(pollIncomingOrders, 10000);
    });
</script>
