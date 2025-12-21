@extends('layouts.admin')

@section('title', 'Tambah Menu Kafe')
@section('page-title', 'Menu Kafe Baru')
@section('page-subtitle', 'Masukkan item kafe untuk halaman menu')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Form Menu</h2>
            <a href="{{ route('admin.menu-items.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700">Kembali</a>
        </div>

        @include('admin.menu-items.partials.form', [
            'formAction' => route('admin.menu-items.store'),
        ])
    </div>
@endsection
