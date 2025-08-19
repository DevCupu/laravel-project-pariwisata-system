@extends('layouts.admin')

@section('title', 'Tambah Destinasi')

@section('content')
    <form action="{{ route('admin.destinasi.store') }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 bg-white shadow-xl rounded-xl p-8" novalidate>
        @csrf

        {{-- Nama Wisata --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="nama">Nama Destinasi <span
                    class="text-red-500">*</span></label>
            <input type="text" id="nama" name="nama"
                class="form-input w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('nama') border-red-500 @enderror"
                required placeholder="Masukkan nama destinasi wisata" value="{{ old('nama') }}" autocomplete="off">
            <p class="text-xs text-gray-500 mt-1">Contoh: Pantai Parangtritis</p>
            @error('nama')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Desc --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="deskripsi">Deskripsi <span
                    class="text-red-500">*</span></label>
            <textarea id="deskripsi" name="deskripsi" rows="4"
                class="form-textarea w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('deskripsi') border-red-500 @enderror"
                required placeholder="Tuliskan deskripsi singkat mengenai destinasi">{{ old('deskripsi') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Jelaskan daya tarik, fasilitas, atau keunikan destinasi ini.</p>
            @error('deskripsi')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alamat --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="lokasi">Lokasi <span
                    class="text-red-500">*</span></label>
            <input type="text" id="lokasi" name="lokasi"
                class="form-input w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('lokasi') border-red-500 @enderror"
                required placeholder="Alamat lengkap atau nama daerah" value="{{ old('lokasi') }}" autocomplete="off">
            <p class="text-xs text-gray-500 mt-1">Contoh: Jl. Pantai Selatan, Bantul, Yogyakarta</p>
            @error('lokasi')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Set Harga --}}
        <div class="grid grid-cols-2 gap-8">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="harga_tiket">
                    Harga Tiket <span class="text-red-500">*</span>
                </label>

                <div class="flex items-center space-x-2 mb-2">
                    <input type="checkbox" id="gratis-checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                    <label for="gratis-checkbox" class="text-sm text-gray-700">Gratis</label>
                </div>

                <input type="number" id="harga_tiket" name="harga_tiket" pattern="\d*" inputmode="numeric"
                    class="form-input w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('harga_tiket') border-red-500 @enderror"
                    required placeholder="Masukkan harga tiket masuk" value="{{ old('harga_tiket') }}" min="0"
                    autocomplete="off">
                <p class="text-xs text-gray-500 mt-1">Masukkan angka tanpa tanda titik/koma. Contoh: 15000

                </p>

                @error('harga_tiket')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Script kecil untuk toggle -->
            @push('scripts')
                <script>
                    const checkbox = document.getElementById('gratis-checkbox');
                    const inputHarga = document.getElementById('harga_tiket');

                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            inputHarga.value = 0;
                            inputHarga.setAttribute('readonly', true);
                            inputHarga.classList.add('bg-gray-100');
                        } else {
                            inputHarga.removeAttribute('readonly');
                            inputHarga.classList.remove('bg-gray-100');
                            inputHarga.value = '';
                        }
                    });
                </script>
            @endpush

            {{-- Kategori --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="kategori_id">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        Kategori <span class="text-red-500">*</span>
                    </span>
                </label>
                <div class="relative">
                    <select id="kategori_id" name="kategori_id"
                        class="peer form-select w-full rounded-xl border border-gray-300 bg-white py-3 pl-4 pr-10 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 shadow-sm @error('kategori_id') border-red-500 bg-red-50 @enderror"
                        required>
                        <option value="" disabled {{ old('kategori_id') ? '' : 'selected' }}>Pilih kategori</option>
                        @foreach ($kategoris as $item)
                            <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                    <span
                        class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400 peer-focus:text-primary-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </div>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs text-gray-500">Pilih kategori yang sesuai dengan destinasi.</span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-xs font-medium">
                        Kategori Wajib
                    </span>
                </div>
                @error('kategori_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Set Jam --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label for="jam_buka" class="block text-sm font-medium text-gray-700 mb-1">
                    Jam Buka <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="time" id="jam_buka" name="jam_buka"
                        class="form-input w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('jam_buka') border-red-500 @enderror pr-10"
                        required value="{{ old('jam_buka', '08:00') }}" aria-describedby="jam_buka_help">
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                            <circle cx="12" cy="12" r="9" />
                        </svg>
                    </span>
                </div>
                <div id="jam_buka_help" class="flex items-center gap-2 mt-1">
                    <span class="text-xs text-gray-500">Contoh: 08:00</span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-xs font-medium">
                        24 Jam Format
                    </span>
                </div>
                @error('jam_buka')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="jam_tutup" class="block text-sm font-medium text-gray-700 mb-1">
                    Jam Tutup <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="time" id="jam_tutup" name="jam_tutup"
                        class="form-input w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('jam_tutup') border-red-500 @enderror pr-10"
                        required value="{{ old('jam_tutup') }}" aria-describedby="jam_tutup_help">
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                            <circle cx="12" cy="12" r="9" />
                        </svg>
                    </span>
                </div>
                <div id="jam_tutup_help" class="flex items-center gap-2 mt-1">
                    <span class="text-xs text-gray-500">Contoh: 17:00</span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-xs font-medium">
                        24 Jam Format
                    </span>
                </div>
                @error('jam_tutup')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{--  Status --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status <span
                    class="text-gray-400 text-xs">(tampilkan di aplikasi)</span></label>
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200 shadow-sm">
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

        {{-- Image --}}
        <div>
            <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Gambar <span
                    class="text-red-500">*</span></label>

            <input id="gambar" name="gambar" type="file" accept="image/*"
                class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gambar') border-red-500 @enderror"
                onchange="previewGambar(event)" required>

            <p class="mt-1 text-xs text-gray-500">Pilih gambar JPG atau PNG, maks 2MB</p>
            @error('gambar')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror

            <div class="mt-4" id="preview-container">
                <img id="preview-img" src="#" alt="Preview"
                    class="hidden w-32 h-32 object-cover rounded-lg border border-gray-300 shadow" />
            </div>
        </div>

        <script>
            function previewGambar(event) {
                const input = event.target;
                const preview = document.getElementById('preview-img');
                const container = document.getElementById('preview-container');

                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    const maxSize = 2 * 1024 * 1024;

                    if (!file.type.startsWith('image/')) {
                        alert('File bukan gambar!');
                        input.value = '';
                        return;
                    }

                    if (file.size > maxSize) {
                        alert('Ukuran gambar maksimal 2MB.');
                        input.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            }
        </script>

        @push('scripts')
            <script>
                function previewImage(event) {
                    const file = event.target.files[0];
                    const preview = document.getElementById('preview-gambar');
                    preview.innerHTML = '';

                    if (file && file.type.startsWith('image/')) {
                        if (file.size > 2 * 1024 * 1024) {
                            preview.innerHTML = '<p class="text-xs text-red-600 mt-2">Ukuran gambar maksimal 2MB.</p>';
                            event.target.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'w-32 h-32 object-cover rounded-lg border border-gray-200 shadow';
                            preview.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    }
                }
            </script>
        @endpush

        {{-- Section Maps --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="maps_embed">Maps Embed <span
                    class="text-gray-400 text-xs">(opsional)</span></label>
            <textarea id="maps_embed" name="maps_embed" rows="2"
                class="form-textarea w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('maps_embed') border-red-500 @enderror"
                placeholder="Tempel kode embed Google Maps di sini">{{ old('maps_embed') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Dapatkan kode embed dari Google Maps untuk menampilkan lokasi pada peta.
            </p>
            @error('maps_embed')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex justify-end gap-4 mt-8">
            <a href="{{ route('admin.destinasi.index') }}"
                class="inline-flex items-center px-6 py-3 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-lg shadow transition-all duration-200 border border-red-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Destinasi
            </button>
        </div>
    </form>
@endsection
