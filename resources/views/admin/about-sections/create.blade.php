@extends('layouts.admin')

@section('title', 'Tambah Konten Tentang Kami')
@section('page-title', 'Konten Tentang Kami Baru')
@section('page-subtitle', 'Isi detail yang akan tampil pada dropdown Tentang Kami di website publik')

@section('content')
    <x-ui.card title="Form Konten" subtitle="Konten ini akan muncul pada halaman beranda sesuai anchor slug." :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.about-sections.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.about-sections.partials.form', [
                'formAction' => route('admin.about-sections.store'),
                'formMethod' => 'POST',
                'submitLabel' => 'Simpan Konten',
                'section' => $section,
            ])
        </div>
    </x-ui.card>
@endsection
