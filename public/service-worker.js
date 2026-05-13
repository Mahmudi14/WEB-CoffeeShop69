const CACHE_NAME = "coffee69-static-v3";

const STATIC_ASSETS = [
    "/manifest.json",
    "/icons/icon-192.png",
    "/icons/icon-512.png",
];

self.addEventListener("install", function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function (cache) {
            return cache.addAll(STATIC_ASSETS);
        }),
    );

    self.skipWaiting();
});

self.addEventListener("activate", function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys
                    .filter(function (key) {
                        return key !== CACHE_NAME;
                    })
                    .map(function (key) {
                        return caches.delete(key);
                    }),
            );
        }),
    );

    self.clients.claim();
});

self.addEventListener("fetch", function (event) {
    const request = event.request;
    const url = new URL(request.url);

    if (request.method !== "GET") {
        return;
    }

    if (url.origin !== location.origin) {
        return;
    }

    // Jangan pernah cache halaman HTML / route Laravel
    if (request.mode === "navigate") {
        event.respondWith(fetch(request));
        return;
    }

    const blockedPaths = [
        "/login",
        "/logout",
        "/register",
        "/forgot-password",
        "/reset-password",
        "/dashboard",
        "/cashier",
        "/admin",
        "/superadmin",
        "/profile",
    ];

    if (
        blockedPaths.some(function (path) {
            return url.pathname.startsWith(path);
        })
    ) {
        event.respondWith(fetch(request));
        return;
    }

    event.respondWith(
        caches.match(request).then(function (cachedResponse) {
            return cachedResponse || fetch(request);
        }),
    );
});
