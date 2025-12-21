<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo-dpe.svg') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans">

    @php
        $isHome = request()->routeIs('home') || request()->is('/');
    @endphp


    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

    <!-- Header slot (optional) -->
    @if(isset($header))
        <header class="pt-20 bg-white shadow-sm">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Main slot -->
    <main class="pt-16 mb-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

    <footer class="bg-gray-100 text-gray-900 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-8 text-sm text-gray-600">
            &copy; {{ date('Y') }} Dante Pine Enrekang
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
