@extends('layouts.admin')

@section('title', 'Tambah Fasilitas')
@section('page-title', 'Tambah Fasilitas Baru')
@section('page-subtitle', 'Masukkan detail fasilitas agar bisa dipilih saat booking')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <p class="text-sm text-gray-500">Isikan nama, kategori, kapasitas, dan status aktif.</p>
            <a href="{{ route('admin.facilities.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke daftar
            </a>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-800">Detail Fasilitas</h2>
            <p class="text-sm text-gray-500">Semua input dapat disesuaikan kembali setelah tersimpan.</p>

            <div class="mt-6">
                @include('admin.facilities.partials.form', [
                    'formAction' => route('admin.facilities.store'),
                    'formMethod' => 'POST',
                    'submitLabel' => 'Simpan Fasilitas',
                    'facility' => $facility,
                    'categoryOptions' => $categoryOptions,
                ])
            </div>
        </div>
    </div>
@endsection
