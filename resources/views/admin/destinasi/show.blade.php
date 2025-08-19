@extends('layouts.admin')

@section('title', 'Detail Destinasi')
@section('page-title', 'Detail Destinasi')
@section('page-subtitle', $destinasi->nama)

@section('content')
    <div class="bg-white rounded-xl shadow p-6 space-y-6">
        <!-- Gambar -->
        @if ($destinasi->gambar)
            <img src="{{ asset('storage/' . $destinasi->gambar) }}" class="w-full max-h-96 object-cover rounded-xl">
        @endif

        <!-- Info -->
        <div class="space-y-2">
            <h2 class="text-2xl font-bold text-gray-800">{{ $destinasi->nama }}</h2>
            <p class="text-sm text-gray-500">{{ $destinasi->kategori->nama ?? 'Tanpa Kategori' }} | {{ $destinasi->lokasi }}
            </p>
        </div>

        <div>
            <h4 class="text-lg font-semibold mb-1">Deskripsi</h4>
            <p class="text-gray-700 leading-relaxed">{{ $destinasi->deskripsi }}</p>
        </div>

        <!-- Detail Lain -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm text-gray-600">
            <div><strong>Harga Tiket:</strong> Rp{{ number_format($destinasi->harga_tiket, 0, ',', '.') }}</div>
            <div><strong>Jam Buka:</strong> {{ $destinasi->jam_buka }}</div>
            <div><strong>Jam Tutup:</strong> {{ $destinasi->jam_tutup }}</div>
        </div>

        @if ($destinasi->maps_embed)
            <div class="pt-6">
                <h4 class="text-lg font-semibold mb-2">Peta Lokasi</h4>
                <div class="aspect-w-16 aspect-h-9">
                    {!! $destinasi->maps_embed !!}
                </div>
            </div>
        @endif

        <div class="pt-4">
            <a href="{{ route('admin.destinasi.index') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                Kembali ke Daftar
            </a>
        </div>
    </div>
@endsection
