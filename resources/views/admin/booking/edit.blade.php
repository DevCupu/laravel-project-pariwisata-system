@extends('layouts.admin')

@section('title', 'Edit Booking')
@section('page-title', 'Perbarui Booking')
@section('page-subtitle', 'Sunting detail booking dan statusnya')

@section('content')
    <div class="space-y-6">
        @if (session('admin_success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('admin_success') }}
            </div>
        @endif

        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm text-gray-500">Periksa ulang kontak, jadwal, dan catatan untuk booking ini.</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke daftar
            </a>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Detail Booking</h2>
                    <p class="text-sm text-gray-500">Kode booking <span class="font-mono text-gray-900">{{ $booking->booking_code }}</span></p>
                </div>
                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    Terakhir diperbarui {{ $booking->updated_at->diffForHumans() }}
                </span>
            </div>

            <div class="mt-6">
                @include('admin.booking.partials.form', [
                    'formAction' => route('admin.bookings.update', $booking),
                    'formMethod' => 'PUT',
                    'submitLabel' => 'Perbarui Booking',
                    'booking' => $booking,
                    'facilityGroups' => $facilityGroups,
                    'statusOptions' => $statusOptions,
                ])
            </div>
        </div>
    </div>
@endsection
