@php
    $isEdit = $testimonial->exists;
@endphp

<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="lg:grid lg:grid-cols-3 lg:gap-5">
        <div class="lg:col-span-2">
            <div class="space-y-5">
                <x-ui.section title="Informasi Pengunjung">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Nama" name="name" :value="$testimonial->name" required />
                        <x-ui.input label="Peran/Profesi (opsional)" name="role" :value="$testimonial->role"
                            hint="Contoh: Traveler, Keluarga, Camping Enthusiast" />
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Asal Kota (opsional)" name="location" :value="$testimonial->location" />
                        <x-ui.select label="Rating" name="rating" :selected="$testimonial->rating ?? 5"
                            :options="[5 => '5 - Sangat Baik', 4 => '4 - Baik', 3 => '3 - Cukup', 2 => '2 - Kurang', 1 => '1 - Buruk']" />
                    </div>
                    <x-ui.textarea label="Kutipan Testimoni" name="quote" :value="$testimonial->quote" rows="5"
                        class="mt-4" required placeholder="Tulis ulasan pengunjung..." />
                </x-ui.section>
            </div>
        </div>

        <div class="mt-5 space-y-5 lg:col-span-1 lg:mt-0">
            <x-ui.section title="Publikasi">
                <div class="space-y-4">
                    <x-ui.toggle name="is_active" label="Tampilkan di publik"
                        helper="Nonaktifkan jika testimoni belum siap ditampilkan."
                        :checked="old('is_active', $testimonial->is_active ?? true)" />
                    <x-ui.toggle name="is_featured" label="Tandai highlight"
                        helper="Testimoni highlight ditampilkan lebih dahulu di beranda."
                        :checked="old('is_featured', $testimonial->is_featured)" />
                </div>
            </x-ui.section>

            <x-ui.section title="Foto & Urutan">
                @if ($testimonial->avatar_path)
                    <div class="mb-3 overflow-hidden rounded-xl ring-1 ring-slate-200">
                        <img src="{{ $testimonial->avatar_url }}" alt="preview" class="h-28 w-full object-cover">
                    </div>
                @endif
                <x-ui.file label="Foto Pengunjung (opsional)" name="avatar" accept="image/*"
                    hint="Kosongkan untuk memakai inisial nama. Maks 5MB." />
                <x-ui.input label="Urutan" name="sort_order" type="number" :value="$testimonial->sort_order ?? 0"
                    min="0" max="1000" class="mt-4" />
            </x-ui.section>

            <x-ui.section class="sticky bottom-0">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <x-ui.button type="submit" class="flex-1">
                        <i class="fas fa-save text-xs"></i>
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Testimoni' }}
                    </x-ui.button>
                    <x-ui.button variant="secondary" :href="route('admin.testimonials.index')" class="flex-1">
                        Batal
                    </x-ui.button>
                </div>
            </x-ui.section>
        </div>
    </div>
</form>
