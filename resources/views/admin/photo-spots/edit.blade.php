@extends('layouts.admin')

@section('title', 'Edit Spot Foto')
@section('page-title', 'Edit Spot Foto')
@section('page-subtitle', 'Perbarui deskripsi, status, atau urutan tampilan')

@section('content')
    <x-ui.card title="Edit Spot Foto" subtitle="{{ $spot->title }}" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.photo-spots.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.photo-spots.partials.form', [
                'spot' => $spot,
                'formAction' => route('admin.photo-spots.update', $spot),
                'formMethod' => 'PUT',
            ])
        </div>
    </x-ui.card>
@endsection
