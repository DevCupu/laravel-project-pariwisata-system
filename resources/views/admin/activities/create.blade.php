@extends('layouts.admin')

@section('title', 'Tambah Aktivitas')
@section('page-title', 'Aktivitas Baru')
@section('page-subtitle', 'Masukkan detail aktivitas untuk halaman publik')

@section('content')
    <x-ui.card title="Form Aktivitas" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.activities.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.activities.partials.form', [
                'formAction' => route('admin.activities.store'),
            ])
        </div>
    </x-ui.card>
@endsection
