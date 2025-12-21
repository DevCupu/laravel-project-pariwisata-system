@php
    $isEdit = $spot->exists;
@endphp

<form method="POST" action="{{ $formAction }}" class="space-y-6" enctype="multipart/form-data">
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-4">
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Judul Spot</label>
                <input type="text" name="title" value="{{ old('title', $spot->title) }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500"
                    required>
                @error('title')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Slug (opsional)</label>
                <input type="text" name="slug" value="{{ old('slug', $spot->slug) }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                @error('slug')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Lokasi</label>
                    <input type="text" name="location_text" value="{{ old('location_text', $spot->location_text) }}"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Waktu Terbaik</label>
                    <input type="text" name="best_time" value="{{ old('best_time', $spot->best_time) }}"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Gambar Spot (upload)</label>
                @if($spot->image_path)
                    <div class="mb-2">
                        <img src="{{ Storage::url($spot->image_path) }}" alt="preview" class="h-28 w-auto rounded-md object-cover border">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                <p class="mt-1 text-xs text-gray-500">Biarkan kosong untuk mempertahankan gambar lama. Maks 2MB. Format: jpg, png, webp.</p>
                <input type="hidden" name="image_path" value="{{ old('image_path', $spot->image_path) }}">
                @error('image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Urutan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $spot->sort_order ?? 0) }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500" min="0" max="1000">
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Deskripsi</label>
                <textarea name="description" rows="6"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">{{ old('description', $spot->description) }}</textarea>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Tips</label>
                <textarea name="tips" rows="4"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">{{ old('tips', $spot->tips) }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $spot->is_active ?? true))
                class="h-5 w-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
            <div>
                <p class="text-sm font-semibold text-gray-800">Tampilkan di publik</p>
                <p class="text-xs text-gray-500">Nonaktifkan jika spot belum siap ditampilkan.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $spot->is_featured))
                class="h-5 w-5 rounded border-gray-300 text-amber-500 focus:ring-amber-500">
            <div>
                <p class="text-sm font-semibold text-gray-800">Tandai highlight</p>
                <p class="text-xs text-gray-500">Spot highlight muncul di bagian hero galeri.</p>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-green-700">
            <i class="fas fa-save text-xs"></i>
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Spot' }}
        </button>
        <a href="{{ route('admin.photo-spots.index') }}"
            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Batal
        </a>
    </div>
</form>
