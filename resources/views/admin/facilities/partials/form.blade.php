@php
    $facility = $facility ?? null;
    $formMethod = $formMethod ?? 'POST';
    $submitLabel = $submitLabel ?? 'Simpan';
    $categoryOptions = $categoryOptions ?? [];
@endphp

<form method="POST" action="{{ $formAction }}" class="space-y-6">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-gray-700">Nama Fasilitas</label>
            <input type="text" name="name" value="{{ old('name', $facility->name ?? '') }}" required
                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Kategori</label>
            <input type="text" name="category" value="{{ old('category', $facility->category ?? '') }}" list="facility-categories"
                placeholder="Misal: wahana, camping, cafe" required
                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            <datalist id="facility-categories">
                @foreach ($categoryOptions as $category)
                    <option value="{{ $category }}">{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $category)) }}</option>
                @endforeach
            </datalist>
            @error('category')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Gunakan format slug sederhana tanpa spasi untuk konsistensi.</p>
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-3">
        <div>
            <label class="text-sm font-semibold text-gray-700">Kapasitas Per Hari</label>
            <input type="number" name="capacity_per_day" min="0" max="1000"
                value="{{ old('capacity_per_day', $facility->capacity_per_day ?? 0) }}" required
                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            @error('capacity_per_day')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Urutan Tampilan</label>
            <input type="number" name="sort_order" min="0" max="5000"
                value="{{ old('sort_order', $facility->sort_order ?? 0) }}"
                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            @error('sort_order')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex items-center gap-3">
            <div>
                <label class="text-sm font-semibold text-gray-700">Status</label>
                <div class="mt-2 flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                        @checked(old('is_active', ($facility->is_active ?? true) ? 1 : 0))
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="is_active" class="text-sm text-gray-700">Aktif ditampilkan untuk booking</label>
                </div>
                @error('is_active')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div>
        <label class="text-sm font-semibold text-gray-700">Deskripsi</label>
        <textarea name="description" rows="4" class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
            placeholder="Opsional, jelaskan highlight fasilitas">{{ old('description', $facility->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col gap-3 border-t border-gray-100 pt-4 md:flex-row md:items-center md:justify-between">
        <div class="text-xs text-gray-500">
            <p>Slug akan dibuat otomatis berdasarkan nama fasilitas.</p>
        </div>
        <div class="flex flex-col gap-3 md:flex-row md:gap-4">
            <a href="{{ route('admin.facilities.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                {{ $submitLabel }}
            </button>
        </div>
    </div>
</form>
