@extends('layouts.admin')

@section('title', 'Edit Event')
@section('page-title', 'Edit Event')
@section('page-subtitle', 'Perbarui data event')

@section('content')
    <div class="space-y-5">
        @if (session('admin_success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('admin_success') }}
            </div>
        @endif

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <h3 class="text-lg font-semibold text-gray-800">Form Event</h3>
            <p class="text-sm text-gray-500">Perbarui data event atau promo.</p>

            @include('admin.events.partials.form', ['formAction' => route('admin.events.update', $event), 'event' => $event])
        </div>
    </div>
@endsection
