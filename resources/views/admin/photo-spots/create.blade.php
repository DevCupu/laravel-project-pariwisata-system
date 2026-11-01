@extends('layouts.admin')

@section('title', 'Tambah Spot Foto')
@section('page-title', 'Tambah Spot Foto Baru')
@section('page-subtitle', 'Masukkan informasi lengkap agar mudah dipahami pengunjung')

@section('content')
    <x-ui.card title="Form Spot Foto" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.photo-spots.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.photo-spots.partials.form', [
                'spot' => $spot,
                'formAction' => route('admin.photo-spots.store'),
                'formMethod' => 'POST',
            ])
        </div>
    </x-ui.card>
@endsection
