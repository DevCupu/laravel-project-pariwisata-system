@php
    $isEdit = $activity->exists;
@endphp

<form method="POST" action="{{ $formAction }}" class="space-y-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-gray-700">Judul Aktivitas *</label>
            <input type="text" name="title" value="{{ old('title', $activity->title) }}" required
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Slug (opsional)</label>
            <input type="text" name="slug" value="{{ old('slug', $activity->slug) }}"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div>
            <label class="text-sm font-semibold text-gray-700">Kategori *</label>
            <input type="text" name="category" value="{{ old('category', $activity->category) }}" required
                placeholder="contoh: adventure"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Durasi (menit)</label>
            <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $activity->duration_minutes) }}"
                min="0" max="1440"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Harga mulai (Rp)</label>
            <input type="number" name="price_from" value="{{ old('price_from', $activity->price_from) }}" min="0"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div>
            <label class="text-sm font-semibold text-gray-700">Usia minimum</label>
            <input type="number" name="min_age" value="{{ old('min_age', $activity->min_age) }}" min="0" max="100"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Urutan tampil</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $activity->sort_order) }}" min="0"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Hero image (path)</label>
            <input type="text" name="hero_image_path" value="{{ old('hero_image_path', $activity->hero_image_path) }}"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <div>
        <label class="text-sm font-semibold text-gray-700">Ringkasan</label>
        <textarea name="short_description" rows="2"
            class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ old('short_description', $activity->short_description) }}</textarea>
    </div>

    <div>
        <label class="text-sm font-semibold text-gray-700">Deskripsi lengkap</label>
        <textarea name="description" rows="5"
            class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ old('description', $activity->description) }}</textarea>
    </div>

    <div class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 md:flex-row md:items-center md:justify-between">
        <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
            <input type="checkbox" name="is_featured" value="1" class="rounded"
                {{ old('is_featured', $activity->is_featured) ? 'checked' : '' }}>
            Jadikan highlight di halaman publik
        </label>
        <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
            <input type="checkbox" name="is_active" value="1" class="rounded"
                {{ old('is_active', $activity->is_active ?? true) ? 'checked' : '' }}>
            Aktifkan aktivitas ini
        </label>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.activities.index') }}" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700">Batal</a>
        <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
            {{ $isEdit ? 'Perbarui' : 'Simpan' }}
        </button>
    </div>
</form>
