@extends('layouts.admin')

@section('title', 'Edit Booking')
@section('page-title', 'Perbarui Booking')
@section('page-subtitle', 'Sunting detail booking dan statusnya')

@section('content')
    <x-ui.card title="Edit Booking" subtitle="Kode {{ $booking->booking_code }} &middot; {{ $booking->updated_at->diffForHumans() }}" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.bookings.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.booking.partials.form', [
                'formAction' => route('admin.bookings.update', $booking),
                'formMethod' => 'PUT',
                'submitLabel' => 'Perbarui Booking',
                'booking' => $booking,
                'facilityGroups' => $facilityGroups,
                'statusOptions' => $statusOptions,
            ])
        </div>
    </x-ui.card>
@endsection
