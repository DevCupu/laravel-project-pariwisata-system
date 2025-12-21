@extends('layouts.admin')

@section('title', 'Tambah Booking')
@section('page-title', 'Tambah Booking Manual')
@section('page-subtitle', 'Catat permintaan booking secara langsung dari tim')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm text-gray-500">Isi informasi tamu dan tentukan fasilitas yang dipesan.</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke daftar
            </a>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-800">Detail Booking</h2>
            <p class="text-sm text-gray-500">Semua field dapat diperbarui kembali setelah tersimpan.</p>

            <div class="mt-6">
                @include('admin.booking.partials.form', [
                    'formAction' => route('admin.bookings.store'),
                    'formMethod' => 'POST',
                    'submitLabel' => 'Simpan Booking',
                    'booking' => null,
                    'facilityGroups' => $facilityGroups,
                    'statusOptions' => $statusOptions,
                ])
            </div>
        </div>
    </div>
@endsection
