@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan aktivitas dan statistik website Anda')

@section('content')
<!-- Simplified Dashboard: Quick Stats + Recent Bookings -->

<div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-semibold text-gray-500">Wahana</h3>
        <div class="mt-3 text-2xl font-bold">{{ $counts['activities'] ?? 0 }}</div>
        <p class="text-xs text-gray-500">Total wahana terdaftar</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-semibold text-gray-500">Paket Camping</h3>
        <div class="mt-3 text-2xl font-bold">{{ $counts['camping_packages'] ?? 0 }}</div>
        <p class="text-xs text-gray-500">Paket aktif</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-semibold text-gray-500">Booking</h3>
        <div class="mt-3 text-2xl font-bold">{{ $counts['bookings_total'] ?? 0 }}</div>
        <p class="text-xs text-gray-500">{{ $counts['bookings_pending'] ?? 0 }} menunggu konfirmasi</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Booking Terbaru</h3>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm text-blue-600">Lihat semua</a>
        </div>

        @if($recentBookings->isEmpty())
            <div class="text-sm text-gray-500">Belum ada booking.</div>
        @else
            <div class="divide-y">
                @foreach($recentBookings as $b)
                    <div class="py-3 flex items-start justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-800">{{ $b->name }} — <span class="text-xs text-gray-500">{{ $b->email }}</span></div>
                            <div class="text-xs text-gray-500">Tanggal kunjungan: {{ optional($b->visit_date)->format('d M Y') ?? '–' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold">{{ ucfirst($b->status) }}</div>
                            <a href="{{ route('admin.bookings.edit', $b) }}" class="text-xs text-blue-600">Detail</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="flex flex-col gap-3">
            <a href="{{ route('admin.activities.index') }}" class="text-sm text-gray-700 hover:text-gray-900">• Kelola Wahana</a>
            <a href="{{ route('admin.camping-packages.index') }}" class="text-sm text-gray-700 hover:text-gray-900">• Paket Camping</a>
            <a href="{{ route('admin.events.index') }}" class="text-sm text-gray-700 hover:text-gray-900">• Event & Promo</a>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm text-gray-700 hover:text-gray-900">• Daftar Booking</a>
            <a href="{{ route('admin.menu-items.index') }}" class="text-sm text-gray-700 hover:text-gray-900">• Menu Kafe</a>
        </div>
    </div>
</div>

@endsection
