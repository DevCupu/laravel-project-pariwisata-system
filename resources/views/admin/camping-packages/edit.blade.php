@extends('layouts.admin')

@section('title', 'Edit Paket Camping')
@section('page-title', 'Edit Paket Camping')
@section('page-subtitle', 'Perbarui harga, fasilitas, atau status paket')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        @include('admin.camping-packages.partials.form', [
            'package' => $package,
            'formAction' => route('admin.camping-packages.update', $package),
            'formMethod' => 'PUT',
        ])
    </div>
@endsection
