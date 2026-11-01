@extends('layouts.admin')

@section('title', 'Tambah Event')
@section('page-title', 'Tambah Event')
@section('page-subtitle', 'Buat event atau promo baru')

@section('content')
    <x-ui.card title="Form Event" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.events.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.events.partials.form', [
                'formAction' => route('admin.events.store'),
                'event' => $event,
            ])
        </div>
    </x-ui.card>
@endsection
