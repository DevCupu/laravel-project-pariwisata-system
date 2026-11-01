@extends('layouts.admin')

@section('title', 'Tambah Paket Camping')
@section('page-title', 'Tambah Paket Camping Baru')
@section('page-subtitle', 'Lengkapi detail paket agar sinkron dengan booking')

@section('content')
    <x-ui.card title="Form Paket Camping" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.camping-packages.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.camping-packages.partials.form', [
                'package' => $package,
                'formAction' => route('admin.camping-packages.store'),
                'formMethod' => 'POST',
            ])
        </div>
    </x-ui.card>
@endsection
