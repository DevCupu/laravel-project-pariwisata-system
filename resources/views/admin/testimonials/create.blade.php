@extends('layouts.admin')

@section('title', 'Tambah Testimoni')
@section('page-title', 'Tambah Testimoni Baru')
@section('page-subtitle', 'Masukkan ulasan pengunjung untuk ditampilkan di beranda')

@section('content')
    <x-ui.card title="Form Testimoni" :padded="false">
        @slot('actions')
            <x-ui.button variant="secondary" :href="route('admin.testimonials.index')">
                &larr; Kembali
            </x-ui.button>
        @endslot

        <div class="p-6">
            @include('admin.testimonials.partials.form', [
                'testimonial' => $testimonial,
                'formAction' => route('admin.testimonials.store'),
                'formMethod' => 'POST',
            ])
        </div>
    </x-ui.card>
@endsection
