@extends('layouts.admin')

@section('title', 'Edit Fasilitas Umum')
@section('page-title', 'Edit Fasilitas Umum')
@section('page-subtitle', 'Perbarui deskripsi atau status penayangan')

@section('content')
    <x-ui.card title="Edit Fasilitas Umum" subtitle="{{ $facility->name }}" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.general-facilities.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.general-facilities.partials.form', [
                'facility' => $facility,
                'formAction' => route('admin.general-facilities.update', $facility),
                'formMethod' => 'PUT',
            ])
        </div>
    </x-ui.card>
@endsection
