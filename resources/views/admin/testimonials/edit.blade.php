@extends('layouts.admin')

@section('title', 'Edit Testimoni')
@section('page-title', 'Edit Testimoni')
@section('page-subtitle', 'Perbarui kutipan, rating, status, atau urutan tampilan')

@section('content')
    <x-ui.card title="Edit Testimoni" subtitle="{{ $testimonial->name }}" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.testimonials.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.testimonials.partials.form', [
                'testimonial' => $testimonial,
                'formAction' => route('admin.testimonials.update', $testimonial),
                'formMethod' => 'PUT',
            ])
        </div>
    </x-ui.card>
@endsection
