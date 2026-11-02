<!DOCTYPE html>
<html lang="id" data-theme="dante">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') · Dante Pine Enrekang</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets-beranda/logo.jpg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="min-h-screen overflow-x-hidden bg-base-200 font-sans text-base-content antialiased">

    <!-- Mobile Menu Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 z-40 hidden bg-base-content/50 backdrop-blur-sm lg:hidden"></div>

    <div class="flex h-screen relative">
        @include('layouts.partials.admin.sidebar')

        <div class="min-w-0 flex-1 flex flex-col overflow-hidden w-full lg:w-auto">
            @include('layouts.partials.admin.header')

            <main class="admin-scroll min-w-0 flex-1 overflow-x-hidden overflow-y-auto bg-base-200 p-4 pb-24 lg:p-6 lg:pb-6">
                <div class="mx-auto min-w-0 w-full max-w-7xl space-y-5">
                    @if (session('admin_success'))
                        <div class="admin-animate-fade alert alert-success shadow-sm" role="status">
                            <i class="fas fa-circle-check"></i>
                            <span>{{ session('admin_success') }}</span>
                        </div>
                    @endif

                    @if (session('admin_error'))
                        <div class="admin-animate-fade alert alert-error shadow-sm" role="alert">
                            <i class="fas fa-circle-exclamation"></i>
                            <span>{{ session('admin_error') }}</span>
                        </div>
                    @endif

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
