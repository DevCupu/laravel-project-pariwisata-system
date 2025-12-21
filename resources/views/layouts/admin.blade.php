<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') · Dante Pine Enrekang</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo-dpe.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .admin-shell {
            background: #f8fafc;
        }

        .admin-panel-shadow {
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen overflow-x-hidden admin-shell">


    <!-- Mobile Menu Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <div class="flex h-screen relative">
        @include('layouts.partials.admin.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden w-full lg:w-auto">
            @include('layouts.partials.admin.header')

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-white p-4 lg:p-6 admin-panel-shadow">
                <div class="max-w-full">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @include('layouts.partials.admin.toast-container')
    @include('layouts.partials.admin.confirmation-modal')
    @include('layouts.partials.admin.mobile-nav')

    @include('layouts.partials.admin.scripts')
    @stack('scripts')
</body>

</html>
