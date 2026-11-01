@extends('layouts.admin')

@section('title', 'Edit Aktivitas')
@section('page-title', 'Edit Aktivitas')
@section('page-subtitle', 'Perbarui detail aktivitas')

@section('content')
    <x-ui.card :title="$activity->title"
        :subtitle="'Terakhir diperbarui ' . ($activity->updated_at?->diffForHumans() ?? '')"
        :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.activities.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.activities.partials.form', [
                'formAction' => route('admin.activities.update', $activity),
            ])
        </div>
    </x-ui.card>
@endsection
