<div id="sidebar"
    class="bg-white w-64 min-h-screen flex flex-col border-r border-slate-200 fixed lg:relative z-50 transform -translate-x-full lg:translate-x-0 transition-transform">
    <div class="p-4 border-b border-slate-100">
        <div class="flex items-center">
            <img src="{{ asset('logo-dpe.svg') }}" alt="Dante Pine Enrekang" class="h-10 w-auto mr-3 flex-shrink-0">
            <div class="min-w-0 flex-1">
                <h1 class="text-base font-semibold text-slate-900 truncate">Dante Pine Enrekang</h1>
                <p class="text-xs text-slate-500">Admin Panel</p>
            </div>
        </div>
    </div>

    @auth
        <div class="p-4 border-b border-slate-100">
            <div class="flex items-center">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ Auth::user()->role ?? 'Admin' }}</p>
                </div>
                <span class="text-[10px] uppercase tracking-wide text-emerald-600">Online</span>
            </div>
        </div>
    @else
        <div class="p-4 border-b border-slate-100">
            <p class="text-sm font-medium text-slate-700">Guest</p>
            <p class="text-xs text-slate-500">Silakan masuk untuk mengelola konten.</p>
        </div>
    @endauth

    @php
        $groups = [
            ['title' => null, 'items' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'fa-gauge'],
            ]],
            ['title' => 'Konten', 'items' => [
                ['label' => 'Aktivitas', 'route' => 'admin.activities.index', 'active' => 'admin.activities.*', 'icon' => 'fa-mountain'],
                ['label' => 'Spot Foto', 'route' => 'admin.photo-spots.index', 'active' => 'admin.photo-spots.*', 'icon' => 'fa-camera-retro'],
                ['label' => 'Paket Camping', 'route' => 'admin.camping-packages.index', 'active' => 'admin.camping-packages.*', 'icon' => 'fa-campground'],
                ['label' => 'Event & Promo', 'route' => 'admin.events.index', 'active' => 'admin.events.*', 'icon' => 'fa-calendar-days'],
                ['label' => 'Tentang Kami', 'route' => 'admin.about-sections.index', 'active' => 'admin.about-sections.*', 'icon' => 'fa-circle-info'],
            ]],
            ['title' => 'Fasilitas & Booking', 'items' => [
                ['label' => 'Fasilitas Booking', 'route' => 'admin.facilities.index', 'active' => 'admin.facilities.*', 'icon' => 'fa-list-check'],
                ['label' => 'Fasilitas Umum', 'route' => 'admin.general-facilities.index', 'active' => 'admin.general-facilities.*', 'icon' => 'fa-toilet'],
                ['label' => 'Daftar Booking', 'route' => 'admin.bookings.index', 'active' => 'admin.bookings.*', 'icon' => 'fa-calendar-check'],
            ]],
            ['title' => 'Cafe & Lainnya', 'items' => [
                ['label' => 'Menu & Café', 'route' => 'admin.menu-items.index', 'active' => 'admin.menu-items.*', 'icon' => 'fa-mug-hot'],
                ['label' => 'Kontak', 'url' => '#kontak', 'icon' => 'fa-phone'],
            ]],
        ];
    @endphp

    <nav class="flex-1 px-3 py-4 overflow-y-auto">
        @foreach ($groups as $group)
            @php
                $title = $group['title'];
                $items = $group['items'];
                $groupHasActive = collect($items)->contains(function ($it) {
                    $pattern = $it['active'] ?? ($it['route'] ?? null);
                    return $pattern ? request()->routeIs($pattern) : false;
                });
            @endphp

            @if ($title)
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide px-2 mb-3 mt-4">{{ $title }}</p>
            @endif

            <div class="mb-2">
                <details class="group" @if($groupHasActive) open @endif>
                    <summary class="flex items-center justify-between cursor-pointer rounded-md px-2 py-1 text-sm text-slate-600 hover:bg-slate-50">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-folder mr-1 text-sm text-slate-400"></i>
                            <span class="font-medium">{{ $title ?? 'Umum' }}</span>
                        </span>
                        <span class="text-xs text-slate-400 group-open:rotate-90 transition-transform">▸</span>
                    </summary>

                    <ul class="mt-2 space-y-1 px-1">
                        @foreach ($items as $item)
                            @php
                                $hasRoute = isset($item['route']);
                                $href = $hasRoute ? route($item['route']) : ($item['url'] ?? '#');
                                $activePattern = $item['active'] ?? ($hasRoute ? $item['route'] : null);
                                $isActive = $activePattern ? request()->routeIs($activePattern) : false;
                                $baseClasses = 'flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors';
                                $stateClasses = $isActive ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50';
                            @endphp
                            <li>
                                <a href="{{ $href }}" class="{{ $baseClasses }} {{ $stateClasses }}">
                                    <i class="fas {{ $item['icon'] ?? 'fa-circle' }} mr-3 text-base flex-shrink-0"></i>
                                    <span class="truncate">{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </details>
            </div>
        @endforeach
    </nav>

    <div class="p-4 border-t border-slate-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center px-3 py-2 text-sm font-medium text-rose-600 hover:bg-rose-50 rounded-lg">
                <i class="fas fa-sign-out-alt mr-3 flex-shrink-0"></i>
                <span class="truncate">Keluar</span>
            </button>
        </form>
    </div>
</div>
