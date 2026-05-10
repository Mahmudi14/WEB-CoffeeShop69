<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Customer Order' }} - 69 Coffee Shop</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    </style>
</head>

<body class="min-h-screen bg-stone-50 text-stone-900 antialiased">
    @yield('content')
    @stack('scripts')
</body>

</html>
