<div id="sidebar"
    class="fixed lg:relative inset-y-0 left-0 z-50 flex w-64 min-h-screen flex-col border-r border-base-300 bg-base-100 -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-out">
    <div class="flex items-center gap-3 border-b border-base-300 px-5 py-4">
        <img src="{{ asset('logo-dpe.svg') }}" alt="Dante Pine Enrekang" class="h-9 w-auto flex-shrink-0">
        <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-bold tracking-tight">Dante Pine Enrekang</p>
            <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/50">Admin Panel</p>
        </div>
    </div>

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
                ['label' => 'Testimoni', 'route' => 'admin.testimonials.index', 'active' => 'admin.testimonials.*', 'icon' => 'fa-comment-dots'],
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

    <nav class="admin-scroll flex-1 overflow-y-auto px-3 py-4">
        <ul class="menu w-full gap-1">
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
                    <li>
                        <details @if ($groupHasActive) open @endif>
                            <summary class="px-3 py-2 text-[11px] font-bold uppercase tracking-wider text-base-content/40">
                                {{ $title }}
                            </summary>
                            <ul>
                                @foreach ($items as $item)
                                    @php
                                        $hasRoute = isset($item['route']);
                                        $href = $hasRoute ? route($item['route']) : ($item['url'] ?? '#');
                                        $activePattern = $item['active'] ?? ($hasRoute ? $item['route'] : null);
                                        $isActive = $activePattern ? request()->routeIs($activePattern) : false;
                                    @endphp
                                    <li>
                                        <a href="{{ $href }}" @class([
                                            'active' => $isActive,
                                        ])>
                                            <i class="fas {{ $item['icon'] ?? 'fa-circle' }} w-5 flex-shrink-0 text-center text-[15px]"></i>
                                            <span class="truncate">{{ $item['label'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </details>
                    </li>
                @else
                    @foreach ($items as $item)
                        @php
                            $hasRoute = isset($item['route']);
                            $href = $hasRoute ? route($item['route']) : ($item['url'] ?? '#');
                            $activePattern = $item['active'] ?? ($hasRoute ? $item['route'] : null);
                            $isActive = $activePattern ? request()->routeIs($activePattern) : false;
                        @endphp
                        <li>
                            <a href="{{ $href }}" @class(['active' => $isActive])>
                                <i class="fas {{ $item['icon'] ?? 'fa-circle' }} w-5 flex-shrink-0 text-center text-[15px]"></i>
                                <span class="truncate">{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            @endforeach
        </ul>
    </nav>

    @auth
        <div class="border-t border-base-300 p-3">
            <div class="flex items-center gap-3 rounded-box bg-base-200 px-3 py-2.5">
                <span
                    class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-primary text-sm font-bold text-primary-content">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold">{{ Auth::user()->name }}</p>
                    <p class="flex items-center gap-1.5 text-xs text-base-content/60">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-success opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-success"></span>
                        </span>
                        Online
                    </p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar"
                        class="btn btn-ghost btn-sm btn-circle text-base-content/50 hover:text-error">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="border-t border-base-300 p-4">
            <p class="text-sm font-medium">Guest</p>
            <p class="text-xs text-base-content/60">Silakan masuk untuk mengelola konten.</p>
        </div>
    @endauth
</div>
