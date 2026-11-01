@extends('layouts.admin')

@section('title', 'Edit Fasilitas')
@section('page-title', 'Perbarui Fasilitas')
@section('page-subtitle', 'Sunting informasi fasilitas dan pengaturan ketersediaan')

@section('content')
    <x-ui.card title="Edit Fasilitas" subtitle="Slug: {{ $facility->slug }} &middot; Diperbarui {{ $facility->updated_at->diffForHumans() }}" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.facilities.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.facilities.partials.form', [
                'formAction' => route('admin.facilities.update', $facility),
                'formMethod' => 'PUT',
                'submitLabel' => 'Simpan Perubahan',
                'facility' => $facility,
                'categoryOptions' => $categoryOptions,
            ])
        </div>
    </x-ui.card>
@endsection
