@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan aktivitas dan statistik website Anda')

@php
    $statusMeta = [
        'pending' => ['label' => 'Pending', 'tone' => 'warning', 'bar' => 'bg-amber-400'],
        'confirmed' => ['label' => 'Dikonfirmasi', 'tone' => 'info', 'bar' => 'bg-blue-500'],
        'completed' => ['label' => 'Selesai', 'tone' => 'success', 'bar' => 'bg-green-500'],
        'cancelled' => ['label' => 'Dibatalkan', 'tone' => 'danger', 'bar' => 'bg-red-400'],
    ];

    $statusTotal = max(1, array_sum($statusCounts));
    $maxTrend = max(1, $bookingTrend->max('count'));
@endphp

@section('content')
    {{-- Ringkasan cepat --}}
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">
        <x-ui.stat-card label="Wahana" :value="number_format($counts['activities'])" icon="fa-mountain" tone="blue"
            :meta="$counts['activities_active'] . ' aktif'" />
        <x-ui.stat-card label="Paket Camping" :value="number_format($counts['camping_packages'])" icon="fa-campground"
            tone="green" meta="Paket terdaftar" />
        <x-ui.stat-card label="Event & Promo" :value="number_format($counts['events'])" icon="fa-calendar-days"
            tone="violet" meta="Event terdaftar" />
        <x-ui.stat-card label="Spot Foto" :value="number_format($counts['photo_spots'])" icon="fa-camera-retro"
            tone="amber" meta="Spot terdaftar" />
        <x-ui.stat-card label="Booking" :value="number_format($counts['bookings_total'])" icon="fa-calendar-check"
            tone="slate" meta="Total seluruhnya" />
        <x-ui.stat-card label="Pending" :value="number_format($counts['bookings_pending'])" icon="fa-clock"
            tone="red" meta="Menunggu konfirmasi" />
    </div>

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
        <div class="space-y-5 xl:col-span-2">
            {{-- Tren booking 7 hari --}}
            <x-ui.card title="Booking 7 Hari Terakhir" subtitle="Jumlah booking baru per hari">
                <div class="px-6 py-5">
                    @if ($bookingTrend->sum('count') === 0)
                        <x-ui.empty-state icon="fa-chart-simple" title="Belum ada booking minggu ini"
                            text="Booking baru akan muncul sebagai grafik di sini." />
                    @else
                        <div class="flex h-32 items-end gap-2 sm:gap-3">
                            @foreach ($bookingTrend as $point)
                                <div class="flex flex-1 flex-col items-center gap-1.5" title="{{ $point['date'] }}: {{ $point['count'] }} booking">
                                    <span class="text-[10px] font-semibold text-gray-500">{{ $point['count'] ?: '' }}</span>
                                    <div class="w-full max-w-[36px] rounded-t-lg {{ $point['count'] ? 'bg-blue-600' : 'bg-slate-200' }}"
                                        style="height: {{ max(6, (int) round($point['count'] / $maxTrend * 96)) }}px"></div>
                                    <span class="text-[10px] font-medium uppercase tracking-wide text-gray-400">{{ $point['day'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </x-ui.card>

            {{-- Booking terbaru --}}
            <x-ui.card title="Booking Terbaru" subtitle="Deretan booking terbaru yang masuk">
                @slot('actions')
                    <a href="{{ route('admin.bookings.index') }}"
                        class="text-sm font-semibold text-blue-600 transition-colors hover:text-blue-700">
                        Lihat semua &rarr;
                    </a>
                @endslot

                @if ($recentBookings->isEmpty())
                    <x-ui.empty-state icon="fa-calendar-xmark" title="Belum ada booking"
                        text="Booking dari halaman publik akan tampil di sini." />
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($recentBookings as $booking)
                            <li>
                                <a href="{{ route('admin.bookings.edit', $booking) }}"
                                    class="flex items-center gap-3 px-6 py-3.5 transition-colors hover:bg-slate-50">
                                    <span
                                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">
                                        {{ strtoupper(substr($booking->name, 0, 1)) }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-gray-900">{{ $booking->name }}</p>
                                        <p class="truncate text-xs text-gray-500">
                                            {{ $booking->email }}
                                            @if ($booking->facility)
                                                &middot; {{ $booking->facility->name }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="hidden text-right sm:block">
                                        <p class="text-xs font-medium text-gray-600">
                                            {{ $booking->visit_date ? \Illuminate\Support\Carbon::parse($booking->visit_date)->isoFormat('d MMM Y') : '–' }}
                                        </p>
                                    </div>
                                    @if (isset($statusMeta[$booking->status]))
                                        <x-ui.badge :tone="$statusMeta[$booking->status]['tone']" dot>
                                            {{ $statusMeta[$booking->status]['label'] }}
                                        </x-ui.badge>
                                    @endif
                                    <i class="fas fa-chevron-right text-xs text-slate-300"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>
        </div>

        <div class="space-y-5">
            {{-- Breakdown status --}}
            <x-ui.card title="Status Booking" subtitle="Distribusi seluruh booking">
                <div class="px-6 py-5">
                    <div class="flex h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                        @foreach ($statusMeta as $status => $meta)
                            @if ($statusCounts[$status] > 0)
                                <div class="{{ $meta['bar'] }}" style="width: {{ $statusCounts[$status] / $statusTotal * 100 }}%"></div>
                            @endif
                        @endforeach
                    </div>

                    <ul class="mt-4 space-y-2.5">
                        @foreach ($statusMeta as $status => $meta)
                            <li class="flex items-center gap-2.5 text-sm">
                                <span class="h-2.5 w-2.5 rounded-full {{ $meta['bar'] }}"></span>
                                <span class="flex-1 text-gray-600">{{ $meta['label'] }}</span>
                                <span class="font-semibold text-gray-900">{{ number_format($statusCounts[$status]) }}</span>
                                <span class="w-10 text-right text-xs text-gray-400">
                                    {{ round($statusCounts[$status] / $statusTotal * 100) }}%
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </x-ui.card>

            {{-- Jadwal kunjungan --}}
            <x-ui.card title="Jadwal Kunjungan" subtitle="Booking dengan tanggal kunjungan terdekat">
                @if ($upcomingBookings->isEmpty())
                    <x-ui.empty-state icon="fa-calendar-day" title="Tidak ada jadwal"
                        text="Belum ada booking dengan tanggal kunjungan mendatang." />
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($upcomingBookings as $upcoming)
                            <li class="flex items-center gap-3 px-6 py-3">
                                <span
                                    class="flex h-10 w-10 flex-shrink-0 flex-col items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                                    <span class="text-sm font-bold leading-none">{{ \Illuminate\Support\Carbon::parse($upcoming->visit_date)->format('d') }}</span>
                                    <span class="text-[9px] font-semibold uppercase leading-none">{{ \Illuminate\Support\Carbon::parse($upcoming->visit_date)->format('M') }}</span>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-gray-900">{{ $upcoming->name }}</p>
                                    <p class="truncate text-xs text-gray-500">{{ $upcoming->guests }} tamu</p>
                                </div>
                                @if (isset($statusMeta[$upcoming->status]))
                                    <x-ui.badge :tone="$statusMeta[$upcoming->status]['tone']">
                                        {{ $statusMeta[$upcoming->status]['label'] }}
                                    </x-ui.badge>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>

            {{-- Quick actions --}}
            <x-ui.card title="Akses Cepat">
                <div class="grid grid-cols-1 gap-2 px-6 py-5 sm:grid-cols-2">
                    <x-ui.button variant="secondary" href="{{ route('admin.activities.index') }}"
                        class="justify-between">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-mountain w-4 text-center text-slate-400"></i>
                            Wahana
                        </span>
                        <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
                    </x-ui.button>
                    <x-ui.button variant="secondary" href="{{ route('admin.camping-packages.index') }}"
                        class="justify-between">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-campground w-4 text-center text-slate-400"></i>
                            Camping
                        </span>
                        <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
                    </x-ui.button>
                    <x-ui.button variant="secondary" href="{{ route('admin.events.index') }}" class="justify-between">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-calendar-days w-4 text-center text-slate-400"></i>
                            Event & Promo
                        </span>
                        <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
                    </x-ui.button>
                    <x-ui.button variant="secondary" href="{{ route('admin.bookings.index') }}" class="justify-between">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-calendar-check w-4 text-center text-slate-400"></i>
                            Booking
                        </span>
                        <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
                    </x-ui.button>
                    <x-ui.button variant="secondary" href="{{ route('admin.menu-items.index') }}"
                        class="justify-between sm:col-span-2">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-mug-hot w-4 text-center text-slate-400"></i>
                            Menu & Café
                        </span>
                        <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
                    </x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection
