@extends('layouts.admin')

@section('title', 'Tambah Menu Kafe')
@section('page-title', 'Menu Kafe Baru')
@section('page-subtitle', 'Masukkan item kafe untuk halaman menu')

@section('content')
    <x-ui.card title="Form Menu" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.menu-items.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.menu-items.partials.form', [
                'formAction' => route('admin.menu-items.store'),
            ])
        </div>
    </x-ui.card>
@endsection
