<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '69 Coffee Shop') }}</title>

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

<body class="min-h-screen bg-[#171412] text-stone-900 antialiased">
    <div class="relative min-h-screen overflow-hidden">
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.24),transparent_34%),radial-gradient(circle_at_bottom_left,rgba(120,53,15,0.28),transparent_38%)]">
        </div>

        <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-8">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
