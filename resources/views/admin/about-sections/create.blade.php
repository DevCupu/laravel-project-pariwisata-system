@extends('layouts.admin')

@section('title', 'Tambah Konten Tentang Kami')
@section('page-title', 'Konten Tentang Kami Baru')
@section('page-subtitle', 'Isi detail yang akan tampil pada dropdown Tentang Kami di website publik')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <p class="text-sm text-gray-500">Anda dapat menambahkan beberapa blok konten lalu mengatur urutan tampilnya.</p>
            <a href="{{ route('admin.about-sections.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke daftar
            </a>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-800">Detail Konten</h2>
            <p class="text-sm text-gray-500">Konten ini akan muncul pada halaman beranda sesuai anchor slug.</p>

            <div class="mt-6">
                @include('admin.about-sections.partials.form', [
                    'formAction' => route('admin.about-sections.store'),
                    'formMethod' => 'POST',
                    'submitLabel' => 'Simpan Konten',
                    'section' => $section,
                ])
            </div>
        </div>
    </div>
@endsection
