@extends('layouts.admin')

@section('title', 'Tambah Fasilitas Umum')
@section('page-title', 'Tambah Fasilitas Umum Baru')
@section('page-subtitle', 'Pastikan ikon dan deskripsi mudah dipahami pengunjung')

@section('content')
    <x-ui.card title="Form Fasilitas Umum" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.general-facilities.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.general-facilities.partials.form', [
                'facility' => $facility,
                'formAction' => route('admin.general-facilities.store'),
                'formMethod' => 'POST',
            ])
        </div>
    </x-ui.card>
@endsection
