<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Wisata Nusantara</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="{{ '/' }}" class="flex items-center gap-2 group">
                    <i class="fas fa-mountain text-3xl text-blue-600 group-hover:text-blue-800 transition"></i>
                    <span
                        class="text-2xl font-extrabold text-gray-900 tracking-tight group-hover:text-blue-800 transition">Wisata
                        Nusantara</span>
                </a>
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6 mr-9">
                    <a href="{{ route('home') }}"
                        class="relative text-gray-700 hover:text-blue-700 font-semibold transition duration-200 after:block after:h-0.5 after:bg-blue-600 after:scale-x-0 hover:after:scale-x-100 after:transition-transform after:duration-300 after:origin-left">Beranda</a>
                    <a href="{{ route('destinations') }}"
                        class="relative text-gray-700 hover:text-blue-700 font-semibold transition duration-200 after:block after:h-0.5 after:bg-blue-600 after:scale-x-0 hover:after:scale-x-100 after:transition-transform after:duration-300 after:origin-left">Destinasi</a>
                    <a href="{{ route('artikel') }}"
                        class="relative text-gray-700 hover:text-blue-700 font-semibold transition duration-200 after:block after:h-0.5 after:bg-blue-600 after:scale-x-0 hover:after:scale-x-100 after:transition-transform after:duration-300 after:origin-left">Artikel</a>
                    <a href="#"
                        class="relative text-gray-700 hover:text-blue-700 font-semibold transition duration-200 after:block after:h-0.5 after:bg-blue-600 after:scale-x-0 hover:after:scale-x-100 after:transition-transform after:duration-300 after:origin-left">Event</a>
                    <a href="#"
                        class="relative text-gray-700 hover:text-blue-700 font-semibold transition duration-200 after:block after:h-0.5 after:bg-blue-600 after:scale-x-0 hover:after:scale-x-100 after:transition-transform after:duration-300 after:origin-left">Galeri</a>
                    <a href="#"
                        class="relative text-gray-700 hover:text-blue-700 font-semibold transition duration-200 after:block after:h-0.5 after:bg-blue-600 after:scale-x-0 hover:after:scale-x-100 after:transition-transform after:duration-300 after:origin-left">Kontak</a>
                    <a href="{{ route('login') }}"
                        class="ml-4 px-5 py-2 bg-gradient-to-r from-blue-600 to-blue-400 text-white rounded-full shadow-lg hover:from-blue-700 hover:to-blue-500 transition duration-300 flex items-center gap-2 font-semibold">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                </div>
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" aria-label="Buka menu navigasi"
                        class="p-2 rounded-full text-gray-700 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t shadow-lg animate-fade-in-down">
            <div class="px-4 py-4 space-y-2">
                <a href="#"
                    class="block py-2 px-2 rounded text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-semibold transition">Beranda</a>
                <a href="#"
                    class="block py-2 px-2 rounded text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-semibold transition">Destinasi</a>
                <a href="#"
                    class="block py-2 px-2 rounded text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-semibold transition">Artikel</a>
                <a href="#"
                    class="block py-2 px-2 rounded text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-semibold transition">Event</a>
                <a href="#"
                    class="block py-2 px-2 rounded text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-semibold transition">Galeri</a>
                <a href="#"
                    class="block py-2 px-2 rounded text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-semibold transition">Kontak</a>
                <a href="{{ route('login') }}"
                    class="block py-2 px-2 rounded bg-gradient-to-r from-blue-600 to-blue-400 text-white font-semibold shadow hover:from-blue-700 hover:to-blue-500 transition flex items-center gap-2 mt-2">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
            </div>
        </div>
    </nav>

    <style>
        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-16px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Smooth shadow for mobile menu */
        #mobile-menu {
            box-shadow: 0 8px 24px rgba(30, 64, 175, 0.08);
        }

        /* Responsive font for logo */
        .logo-text {
            font-size: clamp(1.25rem, 2vw, 2rem);
        }
    </style>

    <!-- Main Content -->
    <main class="pt-15"\>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-mountain text-2xl text-blue-400 mr-2"></i>
                        <span class="text-xl font-bold">Wisata Nusantara</span>
                    </div>
                    <p class="text-gray-300">Jelajahi keindahan Indonesia bersama kami. Temukan destinasi wisata terbaik
                        di seluruh Nusantara.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Menu</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">Beranda</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-300 hover:text-white transition duration-300">Destinasi</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">Artikel</a>
                        </li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">Event</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><i class="fas fa-phone mr-2"></i> +62 123 456 789</li>
                        <li><i class="fas fa-envelope mr-2"></i> info@wisatanusantara.com</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Jakarta, Indonesia</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Ikuti Kami</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white text-xl transition duration-300">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white text-xl transition duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white text-xl transition duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white text-xl transition duration-300">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">&copy; 2024 Wisata Nusantara. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>

</html>
