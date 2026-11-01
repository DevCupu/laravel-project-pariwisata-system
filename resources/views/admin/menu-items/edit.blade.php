@extends('layouts.admin')

@section('title', 'Edit Menu Kafe')
@section('page-title', 'Edit Menu Kafe')
@section('page-subtitle', 'Perbarui informasi menu kafe')

@section('content')
    <x-ui.card title="Edit Menu" subtitle="{{ $menuItem->name }} &middot; {{ $menuItem->updated_at?->diffForHumans() }}" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.menu-items.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.menu-items.partials.form', [
                'formAction' => route('admin.menu-items.update', $menuItem),
            ])
        </div>
    </x-ui.card>
@endsection
