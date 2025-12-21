@extends('layouts.admin')

@section('title', 'Edit Menu Kafe')
@section('page-title', 'Edit Menu Kafe')
@section('page-subtitle', 'Perbarui informasi menu kafe')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $menuItem->name }}</h2>
                <p class="text-sm text-gray-500">Terakhir diperbarui {{ $menuItem->updated_at?->diffForHumans() }}</p>
            </div>
            <a href="{{ route('admin.menu-items.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700">Kembali</a>
        </div>

        @include('admin.menu-items.partials.form', [
            'formAction' => route('admin.menu-items.update', $menuItem),
        ])
    </div>
@endsection
