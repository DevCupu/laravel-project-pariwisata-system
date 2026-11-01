@extends('layouts.admin')

@section('title', 'Edit Event')
@section('page-title', 'Edit Event')
@section('page-subtitle', 'Perbarui data event')

@section('content')
    <x-ui.card title="Edit Event" subtitle="{{ $event->title }}" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.events.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.events.partials.form', [
                'formAction' => route('admin.events.update', $event),
                'event' => $event,
            ])
        </div>
    </x-ui.card>
@endsection
