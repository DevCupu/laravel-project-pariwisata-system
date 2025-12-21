@extends('layouts.admin')

@section('title', 'Edit Spot Foto')
@section('page-title', 'Edit Spot Foto')
@section('page-subtitle', 'Perbarui deskripsi, status, atau urutan tampilan')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        @include('admin.photo-spots.partials.form', [
            'spot' => $spot,
            'formAction' => route('admin.photo-spots.update', $spot),
            'formMethod' => 'PUT',
        ])
    </div>
@endsection
