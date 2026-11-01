@extends('layouts.admin')

@section('title', 'Tambah Fasilitas')
@section('page-title', 'Tambah Fasilitas Baru')
@section('page-subtitle', 'Masukkan detail fasilitas agar bisa dipilih saat booking')

@section('content')
    <x-ui.card title="Form Fasilitas" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.facilities.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.facilities.partials.form', [
                'formAction' => route('admin.facilities.store'),
                'formMethod' => 'POST',
                'submitLabel' => 'Simpan Fasilitas',
                'facility' => $facility,
                'categoryOptions' => $categoryOptions,
            ])
        </div>
    </x-ui.card>
@endsection
