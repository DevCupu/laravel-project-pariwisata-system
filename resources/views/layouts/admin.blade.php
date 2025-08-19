<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>@yield('title') - Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        'xs': '475px',
                        '3xl': '1600px',
                        '4xl': '1920px'
                    },
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#64748B',
                        success: '#10B981',
                        warning: '#F59E0B',
                        danger: '#EF4444'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'bounce-in': 'bounceIn 0.6s ease-out',
                        'pulse-slow': 'pulse 3s infinite'
                    },
                    spacing: {
                        '18': '4.5rem',
                        '88': '22rem',
                        '128': '32rem'
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            50% {
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Responsive utilities */
        .sidebar-item {
            transition: all 0.3s ease;
        }

        .sidebar-item:hover {
            transform: translateX(4px);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .mobile-padding {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .mobile-text {
                font-size: 0.875rem !important;
            }

            .mobile-hidden {
                display: none !important;
            }
        }

        /* Touch optimizations */
        @media (hover: none) and (pointer: coarse) {
            .card-hover:hover {
                transform: none;
            }

            .sidebar-item:hover {
                transform: none;
            }
        }

        /* High DPI displays */
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .crisp-edges {
                image-rendering: -webkit-optimize-contrast;
                image-rendering: crisp-edges;
            }
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .auto-dark {
                background-color: #1f2937;
                color: #f9fafb;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen overflow-x-hidden">

    <!-- Loading Screen -->
    <div id="loading-screen" class="fixed inset-0 bg-white z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 sm:h-16 sm:w-16 border-b-2 border-blue-600 mx-auto mb-4">
            </div>
            <p class="text-gray-600 font-medium text-sm sm:text-base">Memuat Dashboard...</p>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <div class="flex h-screen relative">
        <!-- Sidebar -->
        <div id="sidebar"
            class="bg-white shadow-xl w-64 sm:w-72 lg:w-64 xl:w-72 min-h-screen flex flex-col transition-all duration-300 border-r border-gray-200 fixed lg:relative z-50 transform -translate-x-full lg:translate-x-0">
            <!-- Logo -->
            <div class="p-4 sm:p-6 border-b border-gray-100">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-2 rounded-xl mr-3 flex-shrink-0">
                        <i class="fas fa-mountain text-lg sm:text-xl text-white"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-lg sm:text-xl font-bold text-gray-800 truncate">Wisata Nusantara</h1>
                        <p class="text-xs sm:text-sm text-gray-500">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            @auth
            <div class="p-3 sm:p-4 border-b border-gray-100">
                <div class="flex items-center bg-gradient-to-r from-blue-50 to-purple-50 p-3 rounded-xl">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->role ?? 'Admin' }}</p>
                    </div>
                    <div class="w-3 h-3 bg-green-400 rounded-full border-2 border-white flex-shrink-0"></div>
                </div>
            </div>
            @else
            <div class="p-3 sm:p-4 border-b border-gray-100">
                <div class="flex items-center bg-gradient-to-r from-blue-50 to-purple-50 p-3 rounded-xl">
                    <img src="/placeholder.svg?height=40&width=40" alt="Guest"
                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full mr-3 border-2 border-white shadow-sm flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">Guest</p>
                        <p class="text-xs text-gray-500 truncate">-</p>
                    </div>
                    <div class="w-3 h-3 bg-gray-300 rounded-full border-2 border-white flex-shrink-0"></div>
                </div>
            </div>
            @endauth

            <!-- Navigation -->
            <nav class="flex-1 px-3 sm:px-4 py-4 sm:py-6 overflow-y-auto">
                <ul class="space-y-1 sm:space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="sidebar-item flex items-center px-3 sm:px-4 py-2 sm:py-3 text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-sm">
                            <i class="fas fa-tachometer-alt mr-3 text-base sm:text-lg flex-shrink-0"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Dashboard</span>
                            <div class="ml-auto w-2 h-2 bg-white rounded-full opacity-75 flex-shrink-0"></div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.destinasi.index') }}"
                            class="sidebar-item flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl">
                            <i class="fas fa-map-marked-alt mr-3 text-base sm:text-lg flex-shrink-0"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Destinasi</span>
                            <span
                                class="ml-auto bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full flex-shrink-0">
                                {{ $jumlahDestinasi ?? 0 }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="sidebar-item flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-xl">
                            <i class="fas fa-newspaper mr-3 text-base sm:text-lg flex-shrink-0"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Artikel</span>
                            <span
                                class="ml-auto bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full flex-shrink-0">89</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="sidebar-item flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-600 hover:text-orange-600 hover:bg-orange-50 rounded-xl">
                            <i class="fas fa-tags mr-3 text-base sm:text-lg flex-shrink-0"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Kategori Wisata</span>
                            <span
                                class="ml-auto bg-orange-100 text-orange-600 text-xs px-2 py-1 rounded-full flex-shrink-0">
                                {{ $jumlahKategori ?? 0 }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="sidebar-item flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-xl">
                            <i class="fas fa-calendar-alt mr-3 text-base sm:text-lg flex-shrink-0"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Event</span>
                            <span
                                class="ml-auto bg-purple-100 text-purple-600 text-xs px-2 py-1 rounded-full flex-shrink-0">23</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="sidebar-item flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-600 hover:text-pink-600 hover:bg-pink-50 rounded-xl">
                            <i class="fas fa-images mr-3 text-base sm:text-lg flex-shrink-0"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Galeri</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="sidebar-item flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-600 hover:text-yellow-600 hover:bg-yellow-50 rounded-xl">
                            <i class="fas fa-star mr-3 text-base sm:text-lg flex-shrink-0"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Review</span>
                            <span
                                class="ml-auto bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full flex-shrink-0">5</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="sidebar-item flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-600 hover:text-teal-600 hover:bg-teal-50 rounded-xl">
                            <i class="fas fa-envelope mr-3 text-base sm:text-lg flex-shrink-0"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Pesan</span>
                            <span
                                class="ml-auto bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full flex-shrink-0">12</span>
                        </a>
                    </li>
                </ul>

                <!-- Divider -->
                <div class="my-4 sm:my-6 border-t border-gray-200"></div>

                <ul class="space-y-1 sm:space-y-2">
                    <li>
                        <a href="#"
                            class="sidebar-item flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded-xl">
                            <i class="fas fa-chart-bar mr-3 text-base sm:text-lg flex-shrink-0"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Analytics</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="sidebar-item flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded-xl">
                            <i class="fas fa-cog mr-3 text-base sm:text-lg flex-shrink-0"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Pengaturan</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Logout -->
            <div class="p-3 sm:p-4 border-t border-gray-100">
                <button
                    class="w-full flex items-center px-3 sm:px-4 py-2 sm:py-3 text-red-600 hover:bg-red-50 rounded-xl transition-colors duration-200">
                    <i class="fas fa-sign-out-alt mr-3 flex-shrink-0"></i>
                    <span class="font-medium text-sm sm:text-base truncate">Keluar</span>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden w-full lg:w-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 relative z-30">
                <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4">
                    <div class="flex items-center min-w-0 flex-1">
                        <button id="sidebar-toggle"
                            class="text-gray-500 hover:text-gray-700 lg:hidden mr-3 sm:mr-4 p-2 -ml-2">
                            <i class="fas fa-bars text-lg sm:text-xl"></i>
                        </button>
                        <div class="min-w-0 flex-1">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 truncate">@yield('page-title', 'Dashboard')</h2>
                            <p class="text-xs sm:text-sm text-gray-500 truncate hidden sm:block">@yield('page-subtitle', 'Selamat datang kembali!')</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <input type="text" placeholder="Cari apapun..."
                                class="w-48 lg:w-64 xl:w-80 pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                        </div>

                        <!-- Mobile Search Toggle -->
                        <button id="search-toggle"
                            class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors duration-200 md:hidden">
                            <i class="fas fa-search text-lg"></i>
                        </button>

                        <!-- Quick Actions -->
                        <div class="hidden sm:flex items-center space-x-1 sm:space-x-2">
                            <button
                                class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors duration-200"
                                title="Tambah Destinasi">
                                <i class="fas fa-plus text-base sm:text-lg"></i>
                            </button>
                            <button
                                class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-xl transition-colors duration-200"
                                title="Tulis Artikel">
                                <i class="fas fa-edit text-base sm:text-lg"></i>
                            </button>
                        </div>

                        <!-- Notifications -->
                        <div class="relative">
                            <button
                                class="p-2 text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-xl transition-colors duration-200 relative">
                                <i class="fas fa-bell text-base sm:text-lg"></i>
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 sm:h-5 sm:w-5 flex items-center justify-center animate-bounce-in">3</span>
                            </button>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            @auth
                                <div class="relative">
                                    <button id="user-menu-button" type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                        aria-expanded="false" aria-haspopup="true">
                                        <span class="truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 20 20" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7l3-3 3 3m0 6l-3 3-3-3" />
                                        </svg>
                                    </button>
                                    <div id="user-menu-dropdown"
                                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-100">
                                        <a href="{{ route('profile.edit') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            {{ __('Profile') }}
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                {{ __('Log Out') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const btn = document.getElementById('user-menu-button');
                                        const dropdown = document.getElementById('user-menu-dropdown');
                                        if (btn && dropdown) {
                                            btn.addEventListener('click', function(e) {
                                                e.stopPropagation();
                                                dropdown.classList.toggle('hidden');
                                            });
                                            document.addEventListener('click', function() {
                                                dropdown.classList.add('hidden');
                                            });
                                        }
                                    });
                                </script>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Mobile Search Bar -->
                <div id="mobile-search" class="hidden md:hidden px-4 pb-4">
                    <div class="relative">
                        <input type="text" placeholder="Cari apapun..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main
                class="flex-1 overflow-x-hidden overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100 p-3 sm:p-4 lg:p-6">
                <div class="animate-fade-in max-w-full">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm"></div>

    <!-- Confirmation Modal -->
    <div id="confirmation-modal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 animate-bounce-in">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Konfirmasi Tindakan</h3>
                <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin melakukan tindakan ini?</p>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <button
                        class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-xl hover:bg-gray-300 transition-colors duration-200">
                        Batal
                    </button>
                    <button
                        class="flex-1 bg-red-600 text-white py-2 px-4 rounded-xl hover:bg-red-700 transition-colors duration-200">
                        Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation for Mobile -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 lg:hidden z-40">
        <div class="grid grid-cols-5 gap-1">
            <button class="flex flex-col items-center py-2 px-1 text-blue-600 bg-blue-50">
                <i class="fas fa-tachometer-alt text-lg mb-1"></i>
                <span class="text-xs font-medium">Dashboard</span>
            </button>
            <button class="flex flex-col items-center py-2 px-1 text-gray-500 hover:text-blue-600 hover:bg-blue-50">
                <i class="fas fa-map-marked-alt text-lg mb-1"></i>
                <span class="text-xs">Destinasi</span>
            </button>
            <button class="flex flex-col items-center py-2 px-1 text-gray-500 hover:text-green-600 hover:bg-green-50">
                <i class="fas fa-plus text-lg mb-1 bg-blue-600 text-white rounded-full p-2 -mt-4 shadow-lg"></i>
                <span class="text-xs">Tambah</span>
            </button>
            <button
                class="flex flex-col items-center py-2 px-1 text-gray-500 hover:text-purple-600 hover:bg-purple-50">
                <i class="fas fa-newspaper text-lg mb-1"></i>
                <span class="text-xs">Artikel</span>
            </button>
            <button class="flex flex-col items-center py-2 px-1 text-gray-500 hover:text-gray-700 hover:bg-gray-50">
                <i class="fas fa-cog text-lg mb-1"></i>
                <span class="text-xs">Setting</span>
            </button>
        </div>
    </div>

    <script>
        // Responsive utilities
        let isDesktop = window.innerWidth >= 1024;
        let isMobile = window.innerWidth < 768;
        let isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;

        // Loading screen
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('loading-screen').style.display = 'none';
            }, 800);
        });

        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            mobileOverlay.classList.toggle('hidden');
        });

        mobileOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            mobileOverlay.classList.add('hidden');
        });

        // Mobile search toggle
        const searchToggle = document.getElementById('search-toggle');
        const mobileSearch = document.getElementById('mobile-search');

        if (searchToggle && mobileSearch) {
            searchToggle.addEventListener('click', function() {
                mobileSearch.classList.toggle('hidden');
                if (!mobileSearch.classList.contains('hidden')) {
                    mobileSearch.querySelector('input').focus();
                }
            });
        }

        // Responsive resize handler
        window.addEventListener('resize', function() {
            const newIsDesktop = window.innerWidth >= 1024;
            const newIsMobile = window.innerWidth < 768;

            if (newIsDesktop !== isDesktop) {
                isDesktop = newIsDesktop;
                if (isDesktop) {
                    sidebar.classList.remove('-translate-x-full');
                    mobileOverlay.classList.add('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            }

            isMobile = newIsMobile;
            isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;
        });

        // Touch gestures for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const swipeDistance = touchEndX - touchStartX;

            if (Math.abs(swipeDistance) > swipeThreshold) {
                if (swipeDistance > 0 && touchStartX < 50) {
                    // Swipe right from left edge - open sidebar
                    sidebar.classList.remove('-translate-x-full');
                    mobileOverlay.classList.remove('hidden');
                } else if (swipeDistance < 0 && !sidebar.classList.contains('-translate-x-full')) {
                    // Swipe left - close sidebar
                    sidebar.classList.add('-translate-x-full');
                    mobileOverlay.classList.add('hidden');
                }
            }
        }

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            const icon = type === 'success' ? 'fa-check' : type === 'error' ? 'fa-times' : 'fa-info';

            toast.className =
                `${bgColor} text-white px-4 py-3 rounded-xl shadow-lg flex items-center space-x-3 animate-slide-in max-w-sm`;
            toast.innerHTML = `
                <i class="fas ${icon} flex-shrink-0"></i>
                <span class="text-sm flex-1">${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-2 hover:bg-white hover:bg-opacity-20 rounded-full p-1 flex-shrink-0">
                    <i class="fas fa-times text-xs"></i>
                </button>
            `;

            document.getElementById('toast-container').appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[placeholder*="Cari"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }

            // Escape to close sidebar on mobile
            if (e.key === 'Escape' && !isDesktop) {
                sidebar.classList.add('-translate-x-full');
                mobileOverlay.classList.add('hidden');
            }
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', function() {
            const animateElements = document.querySelectorAll('.card-hover, .stats-card');
            animateElements.forEach(el => observer.observe(el));
        });

        // Auto-hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Prevent zoom on double tap for iOS
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        // Performance monitoring
        if ('performance' in window) {
            window.addEventListener('load', function() {
                setTimeout(function() {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    if (perfData.loadEventEnd - perfData.loadEventStart > 3000) {
                        console.warn('Slow page load detected');
                    }
                }, 0);
            });
        }
    </script>
</body>

</html>
