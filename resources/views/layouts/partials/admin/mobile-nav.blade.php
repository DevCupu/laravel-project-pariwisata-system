<nav class="dock dock-sm border-t border-base-300 bg-base-100/95 backdrop-blur-md lg:hidden"
    style="padding-bottom: env(safe-area-inset-bottom);">
    <a href="{{ route('admin.dashboard') }}"
        @class(['active' => request()->routeIs('admin.dashboard')])>
        <i class="fas fa-gauge"></i>
        <span class="text-[11px] font-semibold">Dashboard</span>
    </a>
    <a href="{{ route('admin.activities.index') }}"
        @class(['active' => request()->routeIs('admin.activities.*')])>
        <i class="fas fa-mountain"></i>
        <span class="text-[11px] font-semibold">Aktivitas</span>
    </a>
    <a href="{{ route('admin.camping-packages.index') }}"
        @class(['active' => request()->routeIs('admin.camping-packages.*')])>
        <i class="fas fa-campground"></i>
        <span class="text-[11px] font-semibold">Camping</span>
    </a>
    <a href="{{ route('admin.bookings.index') }}"
        @class(['active' => request()->routeIs('admin.bookings.*')])>
        <i class="fas fa-calendar-check"></i>
        <span class="text-[11px] font-semibold">Booking</span>
    </a>
</nav>
