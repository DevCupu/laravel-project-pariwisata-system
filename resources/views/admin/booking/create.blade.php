@extends('layouts.admin')

@section('title', 'Tambah Booking')
@section('page-title', 'Tambah Booking Manual')
@section('page-subtitle', 'Catat permintaan booking secara langsung dari tim')

@section('content')
    <x-ui.card title="Form Booking" subtitle="Isi informasi tamu dan tentukan fasilitas yang dipesan." :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.bookings.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.booking.partials.form', [
                'formAction' => route('admin.bookings.store'),
                'formMethod' => 'POST',
                'submitLabel' => 'Simpan Booking',
                'booking' => null,
                'facilityGroups' => $facilityGroups,
                'statusOptions' => $statusOptions,
            ])
        </div>
    </x-ui.card>
@endsection
