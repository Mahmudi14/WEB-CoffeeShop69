@php
    $user = auth()->user();

    $role = match (true) {
        $user?->hasRole('superadmin') => 'superadmin',
        $user?->hasRole('admin') => 'admin',
        $user?->hasRole('cashier') => 'cashier',
        default => 'guest',
    };

    $roleLabel = match ($role) {
        'superadmin' => 'Superadmin',
        'admin' => 'Admin',
        'cashier' => 'Kasir',
        default => 'User',
    };

    $sidebarView = match ($role) {
        'superadmin' => 'layouts.partials.sidebars.superadmin',
        'admin' => 'layouts.partials.sidebars.admin',
        'cashier' => 'layouts.partials.sidebars.cashier',
        default => null,
    };

    $pageTitle = trim($__env->yieldContent('title')) ?: 'Dashboard';
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} - 69 Coffee Shop</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        @if ($role === 'cashier')
            html,
            body {
                scrollbar-width: none;
                -ms-overflow-style: none;
            }

            html::-webkit-scrollbar,
            body::-webkit-scrollbar {
                display: none;
            }
        @endif
    </style>
</head>

<body class="min-h-screen bg-stone-50 text-stone-900 antialiased">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        {{-- Mobile overlay --}}
        <div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-stone-950/50 lg:hidden"
            @click="sidebarOpen = false"></div>

        {{-- Sidebar by role --}}
        @if ($sidebarView)
            @include($sidebarView, [
                'role' => $role,
                'roleLabel' => $roleLabel,
            ])
        @endif

        {{-- Main content --}}
        <div class="lg:pl-72">
            @include('layouts.partials.master-topbar', [
                'pageTitle' => $pageTitle,
                'role' => $role,
                'roleLabel' => $roleLabel,
            ])

            <main class="px-4 py-6 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div
                        class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">
                        <p class="font-black">Terjadi kesalahan:</p>

                        <ul class="mt-2 list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @if (session('clear_pos_cart'))
        <script>
            localStorage.removeItem('cashier_pos_cart');
        </script>
    @endif
    @if (($role ?? null) === 'cashier')
        @include('layouts.partials.cashier-order-notification')
    @endif

</body>

</html>
