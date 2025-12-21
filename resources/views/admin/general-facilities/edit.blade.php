@extends('layouts.admin')

@section('title', 'Edit Fasilitas Umum')
@section('page-title', 'Edit Fasilitas Umum')
@section('page-subtitle', 'Perbarui deskripsi atau status penayangan')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        @include('admin.general-facilities.partials.form', [
            'facility' => $facility,
            'formAction' => route('admin.general-facilities.update', $facility),
            'formMethod' => 'PUT',
        ])
    </div>
@endsection
