@extends('layouts.admin')

@section('title', 'Edit Konten Tentang Kami')
@section('page-title', 'Perbarui Konten Tentang Kami')
@section('page-subtitle', 'Sesuaikan teks atau status tampil untuk dropdown Tentang Kami')

@section('content')
    <x-ui.card title="Edit Konten" subtitle="{{ $section->title }}" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.about-sections.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.about-sections.partials.form', [
                'formAction' => route('admin.about-sections.update', $section),
                'formMethod' => 'PUT',
                'submitLabel' => 'Perbarui Konten',
                'section' => $section,
            ])
        </div>
    </x-ui.card>
@endsection
