<header class="z-30 border-b border-base-300 bg-base-100">
    <div class="flex items-center justify-between gap-4 px-4 py-3 sm:px-6">
        <div class="flex min-w-0 flex-1 items-center">
            <button id="sidebar-toggle"
                class="-ml-2 mr-3 btn btn-ghost btn-sm btn-circle lg:hidden"
                aria-label="Buka menu navigasi">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <div class="min-w-0 flex-1">
                <h2 class="truncate text-lg font-bold tracking-tight">@yield('page-title', 'Dashboard')</h2>
                <p class="hidden truncate text-xs text-base-content/60 sm:block">
                    @yield('page-subtitle', 'Selamat datang kembali!')
                </p>
            </div>
        </div>

        <div class="flex flex-shrink-0 items-center gap-2">
            <div class="hidden sm:block">
                @auth
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button"
                            class="btn btn-ghost gap-2 px-2 py-1 rounded-full">
                            <span class="hidden max-w-[120px] truncate text-sm font-semibold md:block">
                                {{ Auth::user()->name }}
                            </span>
                            <div class="avatar placeholder">
                                <div class="w-8 rounded-full bg-primary text-primary-content ring ring-base-100">
                                    <span class="text-xs font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] text-base-content/40"></i>
                        </div>
                        <ul tabindex="0"
                            class="dropdown-content menu bg-base-100 rounded-box z-50 mt-2 w-56 p-2 shadow-lg border border-base-300">
                            <li class="menu-title px-3 py-2">
                                <span class="font-semibold text-base-content truncate">{{ Auth::user()->name }}</span>
                                <span class="text-xs font-normal text-base-content/50 truncate">{{ Auth::user()->email }}</span>
                            </li>
                            <li>
                                <a href="{{ route('admin.profile.edit') }}">
                                    <i class="fas fa-user w-4 text-center"></i>
                                    {{ __('Profile') }}
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2.5 text-error">
                                        <i class="fas fa-sign-out-alt w-4 text-center"></i>
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
