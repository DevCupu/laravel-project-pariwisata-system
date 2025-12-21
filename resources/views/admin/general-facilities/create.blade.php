@extends('layouts.admin')

@section('title', 'Tambah Fasilitas Umum')
@section('page-title', 'Tambah Fasilitas Umum Baru')
@section('page-subtitle', 'Pastikan ikon dan deskripsi mudah dipahami pengunjung')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        @include('admin.general-facilities.partials.form', [
            'facility' => $facility,
            'formAction' => route('admin.general-facilities.store'),
            'formMethod' => 'POST',
        ])
    </div>
@endsection
