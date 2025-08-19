@extends('layouts.admin')

@section('title', 'Edit Destinasi')
@section('page-title', 'Edit Destinasi Wisata')
@section('page-subtitle', 'Ubah informasi destinasi wisata')

@section('content')
    <form action="{{ route('admin.destinasi.update', $destinasi->id) }}" method="POST" enctype="multipart/form-data"
        class="bg-white p-6 sm:p-8 rounded-xl shadow border border-gray-200 space-y-6">
        @csrf
        @method('PUT')

        {{-- Nama Wisata --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="nama">Nama Destinasi <span
                    class="text-red-500">*</span></label>
            <input type="text" id="nama" name="nama"
                class="form-input w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('nama') border-red-500 @enderror"
                required placeholder="Masukkan nama destinasi wisata" value="{{ old('nama', $destinasi->nama) }}""
                autocomplete="off">
            <p class="text-xs text-gray-500 mt-1">Contoh: Pantai Parangtritis</p>
            @error('nama')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
            <textarea name="deskripsi" rows="4" required class="textarea textarea-bordered w-full"
                placeholder="Tulis deskripsi...">{{ old('deskripsi', $destinasi->deskripsi) }}</textarea>
        </div>

        <!-- Lokasi -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
            <input type="text" name="lokasi" value="{{ old('lokasi', $destinasi->lokasi) }}" required
                class="input input-bordered w-full" placeholder="Contoh: Makassar, Sulawesi Selatan">
        </div>

        <!-- Harga Tiket -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Tiket (Rp)</label>
            <input type="number" name="harga_tiket" value="{{ old('harga_tiket', $destinasi->harga_tiket) }}" required
                class="input input-bordered w-full" placeholder="Contoh: 15000">
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status
                <span class="text-gray-400 text-xs">(tampilkan di aplikasi)</span></label>

            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200 shadow-sm">
                {{-- Hidden input to ensure 0 is sent if checkbox is unchecked --}}
                <input type="hidden" name="status" value="0">

                <input type="checkbox" name="status" id="status" value="1"
                    class="toggle toggle-primary focus:ring-2 focus:ring-primary-500 h-6 w-11 rounded-full border-gray-300 transition-all duration-200 checked:bg-green-500 checked:border-green-500"
                    {{ old('status', $destinasi->status ?? true) ? 'checked' : '' }}>

                <label for="status" class="text-sm font-semibold text-gray-700 select-none cursor-pointer">
                    Aktif
                </label>
                <span class="ml-2 text-xs text-gray-500">
                    Jika dinonaktifkan, destinasi tidak akan muncul di aplikasi.
                </span>
            </div>
        </div>


        <!-- Jam Operasional -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Buka</label>
                <input type="time" name="jam_buka" value="{{ old('jam_buka', $destinasi->jam_buka) }}" required
                    class="input input-bordered w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Tutup</label>
                <input type="time" name="jam_tutup" value="{{ old('jam_tutup', $destinasi->jam_tutup) }}" required
                    class="input input-bordered w-full">
            </div>
        </div>

        <!-- Kategori -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Wisata</label>
            <select name="kategori_id" class="select select-bordered w-full">
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" @selected($kategori->id == $destinasi->kategori_id)>
                        {{ $kategori->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Gambar -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar (opsional)</label>
            @if ($destinasi->gambar)
                <img src="{{ asset('storage/' . $destinasi->gambar) }}" alt="Gambar Destinasi"
                    class="w-32 h-24 rounded object-cover mb-2">
            @endif
            <input type="file" name="gambar" class="file-input file-input-bordered w-full">
        </div>

        <!-- Maps Embed -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Embed Google Maps (opsional)</label>
            <input type="text" name="maps_embed" value="{{ old('maps_embed', $destinasi->maps_embed) }}"
                class="input input-bordered w-full" placeholder="URL embed Google Maps">
        </div>

        <!-- Tombol -->
        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('admin.destinasi.index') }}" class="btn btn-outline btn-sm">Batal</a>
            <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
        </div>
    </form>
@endsection
