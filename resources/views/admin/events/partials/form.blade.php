@php
    $isEdit = $event->exists;
@endphp

<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="lg:grid lg:grid-cols-3 lg:gap-5">
        <div class="lg:col-span-2">
            <div class="space-y-5">
                <x-ui.section title="Detail Event">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Judul Event" name="title" :value="$event->title" required />
                        <x-ui.input label="Slug (opsional)" name="slug" :value="$event->slug"
                            hint="Kosongkan untuk generate otomatis dari judul" />
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-3">
                        <x-ui.select label="Tipe" name="type"
                            :options="['event' => 'Event', 'promo' => 'Promo']"
                            :selected="old('type', $event->type ?? 'event')" />
                        <x-ui.input label="Tanggal mulai" name="start_date" type="date"
                            :value="old('start_date', optional($event->start_date)->format('Y-m-d'))" />
                        <x-ui.input label="Tanggal akhir" name="end_date" type="date"
                            :value="old('end_date', optional($event->end_date)->format('Y-m-d'))" />
                    </div>
                    <x-ui.textarea label="Ringkasan" name="excerpt" :value="$event->excerpt" rows="2" class="mt-4" />
                    <x-ui.textarea label="Deskripsi lengkap" name="content" :value="$event->content" rows="6" class="mt-4" />
                </x-ui.section>
            </div>
        </div>

        <div class="mt-5 space-y-5 lg:col-span-1 lg:mt-0">
            <x-ui.section title="Publikasi">
                <div class="space-y-4">
                    <x-ui.toggle name="is_featured" label="Jadikan highlight"
                        helper="Tampil di bagian hero halaman publik."
                        :checked="old('is_featured', $event->is_featured)" />
                    <x-ui.toggle name="is_active" label="Aktifkan event"
                        helper="Nonaktifkan bila event sudah berakhir."
                        :checked="old('is_active', $event->is_active ?? true)" />
                </div>
            </x-ui.section>

            <x-ui.section title="Media">
                @if ($event->image_url)
                    <div class="mb-3 overflow-hidden rounded-xl ring-1 ring-slate-200">
                        <img src="{{ $event->image_url }}" alt="preview" class="h-28 w-full object-cover">
                    </div>
                @endif
                <x-ui.file label="Gambar" name="image" accept="image/*"
                    hint="Ukuran maksimum 5MB." />
            </x-ui.section>

            <x-ui.section class="sticky bottom-0">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <x-ui.button type="submit" class="flex-1">
                        <i class="fas fa-save text-xs"></i>
                        {{ $isEdit ? 'Perbarui' : 'Simpan' }}
                    </x-ui.button>
                    <x-ui.button variant="secondary" :href="route('admin.events.index')" class="flex-1">
                        Batal
                    </x-ui.button>
                </div>
            </x-ui.section>
        </div>
    </div>
</form>
