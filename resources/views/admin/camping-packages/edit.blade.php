@extends('layouts.admin')

@section('title', 'Edit Paket Camping')
@section('page-title', 'Edit Paket Camping')
@section('page-subtitle', 'Perbarui harga, fasilitas, atau status paket')

@section('content')
    <x-ui.card title="Edit Paket Camping" subtitle="{{ $package->name }}" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.camping-packages.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.camping-packages.partials.form', [
                'package' => $package,
                'formAction' => route('admin.camping-packages.update', $package),
                'formMethod' => 'PUT',
            ])
        </div>
    </x-ui.card>
@endsection
