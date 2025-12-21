<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 lg:hidden z-40">
    <div class="grid grid-cols-4 gap-1">
        <a href="{{ route('admin.dashboard') }}"
            class="flex flex-col items-center py-2 px-1 text-blue-600 bg-blue-50">
            <i class="fas fa-tachometer-alt text-lg mb-1"></i>
            <span class="text-xs font-medium">Dashboard</span>
        </a>
        <a href="{{ route('admin.activities.index') }}"
            class="flex flex-col items-center py-2 px-1 text-gray-500 hover:text-gray-900 hover:bg-gray-50">
            <i class="fas fa-mountain text-lg mb-1"></i>
            <span class="text-xs">Aktivitas</span>
        </a>
        <a href="{{ route('admin.camping-packages.index') }}"
            class="flex flex-col items-center py-2 px-1 text-gray-500 hover:text-gray-900 hover:bg-gray-50">
            <i class="fas fa-campground text-lg mb-1"></i>
            <span class="text-xs">Camping</span>
        </a>
        <a href="{{ route('admin.bookings.index') }}"
            class="flex flex-col items-center py-2 px-1 text-gray-500 hover:text-gray-900 hover:bg-gray-50">
            <i class="fas fa-calendar-check text-lg mb-1"></i>
            <span class="text-xs">Booking</span>
        </a>
    </div>
</div>
