@php
    $isEdit = $package->exists;
    $includesText = old('includes');
    if ($includesText === null) {
        $includesText = $package->includes ? implode("\n", $package->includes) : '';
    }
@endphp

<form method="POST" action="{{ $formAction }}" class="space-y-6" enctype="multipart/form-data">
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-4">
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Nama Paket</label>
                <input type="text" name="name" value="{{ old('name', $package->name) }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500"
                    required>
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Slug (opsional)</label>
                <input type="text" name="slug" value="{{ old('slug', $package->slug) }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                @error('slug')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Tagline</label>
                <input type="text" name="tagline" value="{{ old('tagline', $package->tagline) }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $package->price) }}" min="0"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500" required>
                @error('price')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Malam</label>
                    <input type="number" name="nights" value="{{ old('nights', $package->nights) }}" min="1" max="30"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Kapasitas</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $package->capacity) }}" min="1" max="500"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $package->sort_order ?? 0) }}" min="0" max="1000"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Gambar Paket (upload)</label>
                @if($package->image_path)
                    <div class="mb-2">
                        <img src="{{ Storage::url($package->image_path) }}" alt="preview" class="h-28 w-auto rounded-md object-cover border">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                <p class="mt-1 text-xs text-gray-500">Biarkan kosong untuk mempertahankan gambar lama. Maks 5MB. Format: jpg, png, webp.</p>
                <input type="hidden" name="image_path" value="{{ old('image_path', $package->image_path) }}">
                @error('image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Deskripsi</label>
                <textarea name="description" rows="8"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">{{ old('description', $package->description) }}</textarea>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Fasilitas Termasuk (1 baris = 1 item)</label>
                <textarea name="includes" rows="6"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">{{ $includesText }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $package->is_active ?? true))
                class="h-5 w-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
            <div>
                <p class="text-sm font-semibold text-gray-800">Tampilkan paket</p>
                <p class="text-xs text-gray-500">Menyembunyikan paket juga menonaktifkan slot booking.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $package->is_featured))
                class="h-5 w-5 rounded border-gray-300 text-amber-500 focus:ring-amber-500">
            <div>
                <p class="text-sm font-semibold text-gray-800">Tandai highlight</p>
                <p class="text-xs text-gray-500">Highlight akan tampil di bagian hero destinasi.</p>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-green-700">
            <i class="fas fa-save text-xs"></i>
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Paket' }}
        </button>
        <a href="{{ route('admin.camping-packages.index') }}"
            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Batal
        </a>
    </div>
</form>
