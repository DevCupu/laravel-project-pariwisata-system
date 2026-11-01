@php
    $isEdit = $activity->exists;
@endphp

<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="lg:grid lg:grid-cols-3 lg:gap-5">
        <div class="lg:col-span-2">
            <div class="space-y-5">
                <x-ui.section title="Detail Aktivitas">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Judul Aktivitas" name="title" :value="$activity->title" required
                            placeholder="Judul aktivitas" />
                        <x-ui.input label="Slug (opsional)" name="slug" :value="$activity->slug"
                            hint="Kosongkan untuk generate otomatis dari judul" />
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-3">
                        <x-ui.input label="Kategori" name="category" :value="$activity->category" required
                            placeholder="contoh: adventure" />
                        <x-ui.input label="Durasi (menit)" name="duration_minutes" type="number"
                            :value="$activity->duration_minutes" min="0" max="1440" />
                        <x-ui.input label="Harga mulai (Rp)" name="price_from" type="number"
                            :value="$activity->price_from" min="0" />
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-3">
                        <x-ui.input label="Usia minimum" name="min_age" type="number" :value="$activity->min_age"
                            min="0" max="100" />
                        <x-ui.input label="Urutan tampil" name="sort_order" type="number" :value="$activity->sort_order" min="0" />
                        <x-ui.input label="Hero image (path)" name="hero_image_path" :value="$activity->hero_image_path"
                            hint="Path file gambar hero, opsional" />
                    </div>
                    <x-ui.textarea label="Ringkasan" name="short_description" :value="$activity->short_description"
                        rows="2" class="mt-4" hint="Deskripsi singkat yang tampil di kartu aktivitas" />
                    <x-ui.textarea label="Deskripsi lengkap" name="description" :value="$activity->description" rows="5" class="mt-4" />
                </x-ui.section>
            </div>
        </div>

        <div class="mt-5 space-y-5 lg:col-span-1 lg:mt-0">
            <x-ui.section title="Publikasi">
                <div class="space-y-4">
                    <x-ui.toggle name="is_featured" label="Jadikan highlight"
                        helper="Tampil di bagian hero halaman publik."
                        :checked="old('is_featured', $activity->is_featured)" />
                    <x-ui.toggle name="is_active" label="Aktifkan aktivitas"
                        helper="Nonaktifkan bila aktivitas sedang tidak tersedia."
                        :checked="old('is_active', $activity->is_active ?? true)" />
                </div>
            </x-ui.section>

            <x-ui.section class="sticky bottom-0">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <x-ui.button type="submit" class="flex-1">
                        <i class="fas fa-save text-xs"></i>
                        {{ $isEdit ? 'Perbarui' : 'Simpan' }}
                    </x-ui.button>
                    <x-ui.button variant="secondary" :href="route('admin.activities.index')" class="flex-1">
                        Batal
                    </x-ui.button>
                </div>
            </x-ui.section>
        </div>
    </div>
</form>
