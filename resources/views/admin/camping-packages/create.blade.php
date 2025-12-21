@extends('layouts.admin')

@section('title', 'Tambah Paket Camping')
@section('page-title', 'Tambah Paket Camping Baru')
@section('page-subtitle', 'Lengkapi detail paket agar sinkron dengan booking')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        @include('admin.camping-packages.partials.form', [
            'package' => $package,
            'formAction' => route('admin.camping-packages.store'),
            'formMethod' => 'POST',
        ])
    </div>
@endsection
