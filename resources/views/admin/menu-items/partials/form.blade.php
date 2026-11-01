@php
    $isEdit = $menuItem->exists;
@endphp

<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="lg:grid lg:grid-cols-3 lg:gap-5">
        <div class="lg:col-span-2">
            <div class="space-y-5">
                <x-ui.section title="Detail Menu">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Nama Menu" name="name" :value="$menuItem->name" required />
                        <x-ui.input label="Slug" name="slug" :value="$menuItem->slug"
                            hint="Kosongkan untuk generate otomatis dari nama" />
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-3">
                        <x-ui.input label="Section" name="section" :value="$menuItem->section" required
                            placeholder="contoh: signature-coffee" />
                        <x-ui.select label="Tipe" name="type"
                            :options="['drink' => 'Minuman', 'food' => 'Makanan', 'snack' => 'Snack']"
                            :selected="old('type', $menuItem->type ?? 'drink')" />
                        <x-ui.input label="Harga (Rp)" name="price" type="number" :value="$menuItem->price" min="0" required />
                    </div>
                    <x-ui.textarea label="Ringkasan" name="short_description" :value="$menuItem->short_description" rows="2" class="mt-4" />
                    <x-ui.textarea label="Deskripsi lengkap" name="description" :value="$menuItem->description" rows="4" class="mt-4" />
                </x-ui.section>
            </div>
        </div>

        <div class="mt-5 space-y-5 lg:col-span-1 lg:mt-0">
            <x-ui.section title="Publikasi">
                <div class="space-y-4">
                    <x-ui.toggle name="is_available" label="Item tersedia"
                        helper="Nonaktifkan bila stok habis atau tidak tersedia."
                        :checked="old('is_available', $menuItem->is_available ?? true)" />
                </div>
                <div class="mt-4">
                    <x-ui.input label="Badge" name="badge" :value="$menuItem->badge"
                        placeholder="Signature, Limited" hint="Label kecil di kartu menu" />
                </div>
                <x-ui.input label="Urutan tampil" name="sort_order" type="number" :value="$menuItem->sort_order" min="0" class="mt-4" />
            </x-ui.section>

            <x-ui.section title="Media">
                @if ($menuItem->image_path)
                    <div class="mb-3 overflow-hidden rounded-xl ring-1 ring-slate-200">
                        <img src="{{ Storage::url($menuItem->image_path) }}" alt="preview" class="h-24 w-full object-cover">
                    </div>
                @endif
                <x-ui.file label="Gambar" name="image" accept="image/*"
                    hint="Biarkan kosong untuk mempertahankan gambar lama. Maks 5MB." />
                <input type="hidden" name="image_path" value="{{ old('image_path', $menuItem->image_path) }}">
            </x-ui.section>

            <x-ui.section class="sticky bottom-0">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <x-ui.button type="submit" class="flex-1">
                        <i class="fas fa-save text-xs"></i>
                        {{ $isEdit ? 'Perbarui' : 'Simpan' }}
                    </x-ui.button>
                    <x-ui.button variant="secondary" :href="route('admin.menu-items.index')" class="flex-1">
                        Batal
                    </x-ui.button>
                </div>
            </x-ui.section>
        </div>
    </div>
</form>
