@extends('layouts.public') 

@section('content')
<section class="py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            <img src="{{ asset('storage/' . $destinasi->gambar) }}" alt="{{ $destinasi->nama }}"
                class="w-full h-96 object-cover">
            <div class="p-8">
                <div class="mb-4">
                    <span class="bg-blue-600 text-white text-xs px-3 py-1 rounded-full shadow">
                        {{ $destinasi->kategori->nama ?? 'Wisata' }}
                    </span>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $destinasi->nama }}</h1>
                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-6">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-blue-500"></i>
                        {{ $destinasi->lokasi }}
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-ticket-alt text-green-500"></i>
                        {{ $destinasi->harga_tiket > 0 ? 'Rp' . number_format($destinasi->harga_tiket, 0, ',', '.') : 'Gratis' }}
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-clock text-yellow-500"></i>
                        {{ \Carbon\Carbon::parse($destinasi->jam_buka)->format('H:i') }} - {{ \Carbon\Carbon::parse($destinasi->jam_tutup)->format('H:i') }}
                    </div>
                </div>

                <p class="text-gray-700 leading-relaxed mb-6">
                    {{ $destinasi->deskripsi }}
                </p>

                @if ($destinasi->maps_embed)
                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                        {!! $destinasi->maps_embed !!}
                    </div>
                @endif

                <div class="mt-10">
                    <a href="{{ route('destinations') }}"
                        class="inline-block text-sm text-blue-600 hover:underline">
                        ← Kembali ke daftar destinasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
