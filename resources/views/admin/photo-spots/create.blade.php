@extends('layouts.admin')

@section('title', 'Tambah Spot Foto')
@section('page-title', 'Tambah Spot Foto Baru')
@section('page-subtitle', 'Masukkan informasi lengkap agar mudah dipahami pengunjung')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        @include('admin.photo-spots.partials.form', [
            'spot' => $spot,
            'formAction' => route('admin.photo-spots.store'),
            'formMethod' => 'POST',
        ])
    </div>
@endsection
