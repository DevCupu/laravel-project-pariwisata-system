@php
    $isEdit = $facility->exists;
@endphp

<form method="POST" action="{{ $formAction }}" class="space-y-6">
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-4">
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Nama Fasilitas</label>
                <input type="text" name="name" value="{{ old('name', $facility->name) }}" required
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Slug (opsional)</label>
                <input type="text" name="slug" value="{{ old('slug', $facility->slug) }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                @error('slug')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Ikon Font Awesome</label>
                <input type="text" name="icon" value="{{ old('icon', $facility->icon) }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500"
                    placeholder="contoh: fa-solid fa-restroom">
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Deskripsi</label>
                <textarea name="description" rows="6"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">{{ old('description', $facility->description) }}</textarea>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Catatan Ketersediaan</label>
                <input type="text" name="availability_note" value="{{ old('availability_note', $facility->availability_note) }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-4">
        <div class="w-full rounded-2xl border border-gray-100 bg-gray-50 p-4 sm:flex sm:items-center sm:justify-between">
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Urutan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $facility->sort_order ?? 0) }}" min="0" max="1000"
                    class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
            </div>
            <label class="mt-4 inline-flex items-center gap-3 sm:mt-0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $facility->is_active ?? true))
                    class="h-5 w-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                <span class="text-sm font-semibold text-gray-800">Tampilkan di publik</span>
            </label>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-green-700">
            <i class="fas fa-save text-xs"></i>
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Fasilitas' }}
        </button>
        <a href="{{ route('admin.general-facilities.index') }}"
            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Batal
        </a>
    </div>
</form>
