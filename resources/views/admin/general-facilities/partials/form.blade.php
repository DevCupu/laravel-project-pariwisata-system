@php
    $isEdit = $facility->exists;
@endphp

<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="lg:grid lg:grid-cols-3 lg:gap-5">
        <div class="lg:col-span-2">
            <div class="space-y-5">
                <x-ui.section title="Detail Fasilitas">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Nama Fasilitas" name="name" :value="$facility->name" required />
                        <x-ui.input label="Slug (opsional)" name="slug" :value="$facility->slug"
                            hint="Kosongkan untuk generate otomatis dari nama" />
                    </div>
                    <x-ui.input label="Ikon Font Awesome" name="icon" :value="$facility->icon"
                        class="mt-4" placeholder="contoh: fa-solid fa-restroom"
                        hint="Gunakan class ikon Font Awesome" />
                    <x-ui.textarea label="Deskripsi" name="description" :value="$facility->description" rows="5" class="mt-4" />
                    <x-ui.input label="Catatan Ketersediaan" name="availability_note" :value="$facility->availability_note"
                        class="mt-4" hint="Contoh: buka 08.00 - 17.00" />
                </x-ui.section>
            </div>
        </div>

        <div class="mt-5 space-y-5 lg:col-span-1 lg:mt-0">
            <x-ui.section title="Publikasi">
                <x-ui.input label="Urutan" name="sort_order" type="number" :value="$facility->sort_order ?? 0"
                    min="0" max="1000" />
                <div class="mt-4">
                    <x-ui.toggle name="is_active" label="Tampilkan di publik"
                        helper="Nonaktifkan bila informasi sedang direvisi."
                        :checked="old('is_active', $facility->is_active ?? true)" />
                </div>
            </x-ui.section>

            <x-ui.section class="sticky bottom-0">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <x-ui.button type="submit" class="flex-1">
                        <i class="fas fa-save text-xs"></i>
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Fasilitas' }}
                    </x-ui.button>
                    <x-ui.button variant="secondary" :href="route('admin.general-facilities.index')" class="flex-1">
                        Batal
                    </x-ui.button>
                </div>
            </x-ui.section>
        </div>
    </div>
</form>
