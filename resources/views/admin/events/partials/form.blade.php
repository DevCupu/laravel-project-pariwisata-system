@php
    $isEdit = $event->exists;
@endphp

<form method="POST" action="{{ $formAction }}" class="space-y-6" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-gray-700">Judul Event *</label>
            <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Slug (opsional)</label>
            <input type="text" name="slug" value="{{ old('slug', $event->slug) }}"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-gray-700">Tipe</label>
            <select name="type" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                <option value="event" @selected(old('type', $event->type ?? 'event') === 'event')>Event</option>
                <option value="promo" @selected(old('type', $event->type ?? 'event') === 'promo')>Promo</option>
            </select>
        </div>
        <div></div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-gray-700">Tanggal mulai</label>
            <input type="date" name="start_date" value="{{ old('start_date', optional($event->start_date)->format('Y-m-d')) }}"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Tanggal akhir</label>
            <input type="date" name="end_date" value="{{ old('end_date', optional($event->end_date)->format('Y-m-d')) }}"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <div>
        <label class="text-sm font-semibold text-gray-700">Ringkasan</label>
        <textarea name="excerpt" rows="2"
            class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ old('excerpt', $event->excerpt) }}</textarea>
    </div>

    <div>
        <label class="text-sm font-semibold text-gray-700">Deskripsi lengkap</label>
        <textarea name="content" rows="6"
            class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ old('content', $event->content) }}</textarea>
    </div>

    <div class="grid gap-4 md:grid-cols-2 items-start">
        <div>
            <label class="text-sm font-semibold text-gray-700">Gambar (opsional)</label>
            <input type="file" name="image" accept="image/*"
                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">

            @if ($event->image_url)
                <div class="mt-3">
                    <p class="text-xs text-gray-500">Preview saat ini:</p>
                    <img src="{{ $event->image_url }}" alt="preview" class="mt-2 max-h-40 w-auto rounded-lg object-cover">
                </div>
            @endif
        </div>

        <div class="flex flex-col gap-3 rounded-2xl border border-gray-100 bg-gray-50 p-4">
            <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                <input type="checkbox" name="is_featured" value="1" class="rounded"
                    {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}>
                Jadikan highlight di halaman publik
            </label>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                <input type="checkbox" name="is_active" value="1" class="rounded"
                    {{ old('is_active', $event->is_active ?? true) ? 'checked' : '' }}>
                Aktifkan event ini
            </label>
            <div class="text-xs text-gray-500">Catatan: ukuran maksimum gambar 5MB.</div>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.events.index') }}" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700">Batal</a>
        <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
            {{ $isEdit ? 'Perbarui' : 'Simpan' }}
        </button>
    </div>
</form>
