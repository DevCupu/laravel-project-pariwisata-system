@php
    $isEdit = $menuItem->exists;
@endphp

<form method="POST" action="{{ $formAction }}" class="space-y-6" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-gray-700">Nama Menu *</label>
            <input type="text" name="name" value="{{ old('name', $menuItem->name) }}" required
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $menuItem->slug) }}"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div>
            <label class="text-sm font-semibold text-gray-700">Section *</label>
            <input type="text" name="section" value="{{ old('section', $menuItem->section) }}" required
                placeholder="contoh: signature-coffee"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Tipe *</label>
            <select name="type" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
                @php
                    $selectedType = old('type', $menuItem->type ?? 'drink');
                @endphp
                @foreach (['drink' => 'Minuman', 'food' => 'Makanan', 'snack' => 'Snack'] as $value => $label)
                    <option value="{{ $value }}" @selected($selectedType === $value)> {{ $label }} </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Harga (Rp) *</label>
            <input type="number" name="price" value="{{ old('price', $menuItem->price) }}" min="0" required
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div>
            <label class="text-sm font-semibold text-gray-700">Badge</label>
            <input type="text" name="badge" value="{{ old('badge', $menuItem->badge) }}"
                placeholder="Signature, Limited"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Urutan tampil</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $menuItem->sort_order) }}" min="0"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Gambar (upload)</label>
            @if($menuItem->image_path)
                <div class="mb-2">
                    <img src="{{ Storage::url($menuItem->image_path) }}" alt="preview" class="h-24 w-auto rounded-md object-cover border">
                </div>
            @endif
            <input type="file" name="image" accept="image/*"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
            <p class="mt-1 text-xs text-gray-500">Biarkan kosong untuk mempertahankan gambar lama. Maks 5MB. Format: jpg, png, webp.</p>
            <input type="hidden" name="image_path" value="{{ old('image_path', $menuItem->image_path) }}">
            @error('image')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class="text-sm font-semibold text-gray-700">Ringkasan</label>
        <textarea name="short_description" rows="2"
            class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">{{ old('short_description', $menuItem->short_description) }}</textarea>
    </div>

    <div>
        <label class="text-sm font-semibold text-gray-700">Deskripsi lengkap</label>
        <textarea name="description" rows="4"
            class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">{{ old('description', $menuItem->description) }}</textarea>
    </div>

    <label class="inline-flex items-center gap-2 rounded-2xl border border-gray-100 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700">
        <input type="checkbox" name="is_available" value="1" class="rounded"
            {{ old('is_available', $menuItem->is_available ?? true) ? 'checked' : '' }}>
        Item tersedia di kafe
    </label>

    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.menu-items.index') }}" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700">Batal</a>
        <button type="submit" class="rounded-xl bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
            {{ $isEdit ? 'Perbarui' : 'Simpan' }}
        </button>
    </div>
</form>
