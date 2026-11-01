@php
    $isEdit = $package->exists;
    $includesText = old('includes');
    if ($includesText === null) {
        $includesText = $package->includes ? implode("\n", $package->includes) : '';
    }
@endphp

<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="lg:grid lg:grid-cols-3 lg:gap-5">
        <div class="lg:col-span-2">
            <div class="space-y-5">
                <x-ui.section title="Detail Paket">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Nama Paket" name="name" :value="$package->name" required />
                        <x-ui.input label="Slug (opsional)" name="slug" :value="$package->slug"
                            hint="Kosongkan untuk generate otomatis dari nama" />
                    </div>
                    <x-ui.input label="Tagline" name="tagline" :value="$package->tagline" class="mt-4"
                        hint="Kalimat singkat yang menarik perhatian" />
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Harga (Rp)" name="price" type="number" :value="$package->price" min="0" required />
                        <div class="grid grid-cols-3 gap-3">
                            <x-ui.input label="Malam" name="nights" type="number" :value="$package->nights" min="1" max="30" />
                            <x-ui.input label="Kapasitas" name="capacity" type="number" :value="$package->capacity" min="1" max="500" />
                            <x-ui.input label="Urutan" name="sort_order" type="number" :value="$package->sort_order ?? 0" min="0" max="1000" />
                        </div>
                    </div>
                    <x-ui.textarea label="Deskripsi" name="description" :value="$package->description" rows="6" class="mt-4" />
                    <x-ui.textarea label="Fasilitas Termasuk" name="includes" :value="$includesText" rows="5" class="mt-4"
                        hint="1 baris = 1 item" />
                </x-ui.section>
            </div>
        </div>

        <div class="mt-5 space-y-5 lg:col-span-1 lg:mt-0">
            <x-ui.section title="Publikasi">
                <div class="space-y-4">
                    <x-ui.toggle name="is_active" label="Tampilkan paket"
                        helper="Menyembunyikan paket juga menonaktifkan slot booking."
                        :checked="old('is_active', $package->is_active ?? true)" />
                    <x-ui.toggle name="is_featured" label="Tandai highlight"
                        helper="Highlight akan tampil di bagian hero destinasi."
                        :checked="old('is_featured', $package->is_featured)" />
                </div>
            </x-ui.section>

            <x-ui.section title="Media">
                @if ($package->image_path)
                    <div class="mb-3 overflow-hidden rounded-xl ring-1 ring-slate-200">
                        <img src="{{ Storage::url($package->image_path) }}" alt="preview" class="h-28 w-full object-cover">
                    </div>
                @endif
                <x-ui.file label="Gambar Paket" name="image" accept="image/*"
                    hint="Biarkan kosong untuk mempertahankan gambar lama. Maks 5MB." />
                <input type="hidden" name="image_path" value="{{ old('image_path', $package->image_path) }}">
            </x-ui.section>

            <x-ui.section class="sticky bottom-0">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <x-ui.button type="submit" class="flex-1">
                        <i class="fas fa-save text-xs"></i>
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Paket' }}
                    </x-ui.button>
                    <x-ui.button variant="secondary" :href="route('admin.camping-packages.index')" class="flex-1">
                        Batal
                    </x-ui.button>
                </div>
            </x-ui.section>
        </div>
    </div>
</form>
