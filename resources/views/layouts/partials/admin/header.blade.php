<header class="bg-white border-b border-slate-200">
    <div class="flex items-center justify-between px-4 sm:px-6 py-3">
        <div class="flex items-center min-w-0 flex-1">
            <button id="sidebar-toggle" class="text-slate-500 hover:text-slate-700 lg:hidden mr-3 p-2 -ml-2">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <div class="min-w-0 flex-1">
                <h2 class="text-xl font-semibold text-slate-900 truncate">@yield('page-title', 'Dashboard')</h2>
                <p class="text-xs text-slate-500 truncate hidden sm:block">
                    @yield('page-subtitle', 'Selamat datang kembali!')
                </p>
            </div>
        </div>

        <div class="flex items-center space-x-2 flex-shrink-0">
            <div class="hidden sm:flex sm:items-center">
                @auth
                    <div class="relative">
                        <button id="user-menu-button" type="button"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-slate-600 hover:text-slate-900 focus:outline-none"
                            aria-expanded="false" aria-haspopup="true">
                            <span class="truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l3-3 3 3m0 6l-3 3-3-3" />
                            </svg>
                        </button>
                        <div id="user-menu-dropdown"
                            class="hidden absolute right-0 mt-2 w-44 bg-white rounded-md shadow border border-slate-100 py-1 z-50">
                            <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                {{ __('Profile') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
