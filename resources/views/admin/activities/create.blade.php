@extends('layouts.admin')

@section('title', 'Tambah Aktivitas')
@section('page-title', 'Aktivitas Baru')
@section('page-subtitle', 'Masukkan detail aktivitas untuk halaman publik')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Form Aktivitas</h2>
            <a href="{{ route('admin.activities.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700">Kembali</a>
        </div>

        @include('admin.activities.partials.form', [
            'formAction' => route('admin.activities.store'),
        ])
    </div>
@endsection
