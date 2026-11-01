@php
    $isEdit = $spot->exists;
@endphp

<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="lg:grid lg:grid-cols-3 lg:gap-5">
        <div class="lg:col-span-2">
            <div class="space-y-5">
                <x-ui.section title="Informasi Spot">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Judul Spot" name="title" :value="$spot->title" required />
                        <x-ui.input label="Slug (opsional)" name="slug" :value="$spot->slug"
                            hint="Kosongkan untuk generate otomatis dari judul" />
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Lokasi" name="location_text" :value="$spot->location_text" />
                        <x-ui.input label="Waktu Terbaik" name="best_time" :value="$spot->best_time" />
                    </div>
                    <x-ui.textarea label="Deskripsi" name="description" :value="$spot->description" rows="5" class="mt-4" />
                    <x-ui.textarea label="Tips" name="tips" :value="$spot->tips" rows="4" class="mt-4"
                        hint="Tips singkat untuk pengunjung saat berfoto" />
                </x-ui.section>
            </div>
        </div>

        <div class="mt-5 space-y-5 lg:col-span-1 lg:mt-0">
            <x-ui.section title="Publikasi">
                <div class="space-y-4">
                    <x-ui.toggle name="is_active" label="Tampilkan di publik"
                        helper="Nonaktifkan jika spot belum siap ditampilkan."
                        :checked="old('is_active', $spot->is_active ?? true)" />
                    <x-ui.toggle name="is_featured" label="Tandai highlight"
                        helper="Spot highlight muncul di bagian hero galeri."
                        :checked="old('is_featured', $spot->is_featured)" />
                </div>
            </x-ui.section>

            <x-ui.section title="Media & Urutan">
                @if ($spot->image_path)
                    <div class="mb-3 overflow-hidden rounded-xl ring-1 ring-slate-200">
                        <img src="{{ Storage::url($spot->image_path) }}" alt="preview" class="h-28 w-full object-cover">
                    </div>
                @endif
                <x-ui.file label="Gambar Spot" name="image" accept="image/*"
                    hint="Biarkan kosong untuk mempertahankan gambar lama. Maks 2MB." />
                <input type="hidden" name="image_path" value="{{ old('image_path', $spot->image_path) }}">
                <x-ui.input label="Urutan" name="sort_order" type="number" :value="$spot->sort_order ?? 0"
                    min="0" max="1000" class="mt-4" />
            </x-ui.section>

            <x-ui.section class="sticky bottom-0">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <x-ui.button type="submit" class="flex-1">
                        <i class="fas fa-save text-xs"></i>
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Spot' }}
                    </x-ui.button>
                    <x-ui.button variant="secondary" :href="route('admin.photo-spots.index')" class="flex-1">
                        Batal
                    </x-ui.button>
                </div>
            </x-ui.section>
        </div>
    </div>
</form>
