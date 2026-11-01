@php
    $titleValue = old('title', $section->title ?? '');
    $contentValue = old('content', $section->content ?? '');
    $existingImageUrl = $section->image_path ? Storage::url($section->image_path) : null;
@endphp

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if (!empty($formMethod) && strtoupper($formMethod) !== 'POST')
        @method($formMethod)
    @endif

    <x-ui.form-errors />

    <div class="lg:grid lg:grid-cols-3 lg:gap-5">
        <div class="lg:col-span-2">
            <div class="space-y-5">
                <x-ui.section title="Konten Utama" subtitle="Judul dan deskripsi akan muncul pada dropdown navigasi dan halaman beranda.">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="block">
                            <div class="flex items-center justify-between">
                                <label for="title" class="text-sm font-semibold text-slate-700">Judul<span class="text-red-500"> *</span></label>
                                <span id="title-count" class="text-xs text-slate-400">{{ mb_strlen($titleValue) }}/120</span>
                            </div>
                            <input type="text" id="title" name="title" value="{{ $titleValue }}" maxlength="120" required
                                placeholder="Contoh: Sejarah Kami"
                                class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                            @error('title')
                                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="block">
                            <div class="flex items-center justify-between">
                                <label for="slug" class="text-sm font-semibold text-slate-700">Slug (Anchor)<span class="text-red-500"> *</span></label>
                                <button type="button" id="slugify"
                                    class="text-xs font-semibold text-blue-600 hover:text-blue-800">Gunakan judul</button>
                            </div>
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $section->slug) }}" maxlength="120" required
                                placeholder="contoh: tentang-kami"
                                class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 font-mono text-sm text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                            <p class="mt-1 text-xs text-slate-500">Slug dipakai sebagai target anchor (misal <code>#tentang-kami</code>).</p>
                            @error('slug')
                                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center justify-between">
                            <label for="content" class="text-sm font-semibold text-slate-700">Konten</label>
                            <span id="content-count" class="text-xs text-slate-400">{{ mb_strlen($contentValue) }}/800</span>
                        </div>
                        <textarea id="content" name="content" rows="6" maxlength="800"
                            placeholder="Tuliskan deskripsi singkat mengenai sejarah, lokasi, atau layanan unggulan"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30">{{ $contentValue }}</textarea>
                        <p class="mt-1 text-xs text-slate-500">Maks 800 karakter.</p>
                        @error('content')
                            <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </x-ui.section>

                <x-ui.section title="Urutan & Status">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Urutan Tampil" name="display_order" type="number"
                            :value="old('display_order', $section->display_order ?? 0)" min="0"
                            hint="Angka kecil tampil lebih dulu." />
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Status Penayangan</p>
                            <div class="mt-3">
                                <x-ui.toggle name="is_active" label="Tampilkan di website"
                                    helper="Nonaktifkan bila konten sedang direvisi."
                                    :checked="old('is_active', $section->is_active ?? true)" />
                            </div>
                        </div>
                    </div>
                </x-ui.section>
            </div>
        </div>

        <div class="mt-5 space-y-5 lg:col-span-1 lg:mt-0">
            <x-ui.section title="Tips Konten">
                <ul class="space-y-1 text-xs text-slate-600">
                    <li>• Ceritakan manfaat utama untuk pengunjung.</li>
                    <li>• Cantumkan data penting (jam buka, kapasitas, kontak).</li>
                    <li>• Gunakan nada suara yang konsisten dengan brand Dante Pine.</li>
                </ul>
                <p class="mt-3 text-[11px] text-slate-400">Konten terbaik biasanya 80–120 kata dan disertai visual pendukung.</p>
            </x-ui.section>

            <x-ui.section title="Gambar Sorotan" subtitle="Format JPG/PNG/WEBP maks 2MB. Ideal 1200 x 800 px.">
                <div class="relative flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center">
                    <input type="file" name="image" id="image" accept="image/*"
                        class="absolute inset-0 z-20 h-full w-full cursor-pointer opacity-0">
                    <div class="pointer-events-none z-10 space-y-2">
                        <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-slate-200">
                            <i class="fas fa-cloud-arrow-up text-slate-400"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Klik untuk unggah</p>
                        <p class="text-xs text-slate-500">File baru akan menggantikan gambar sebelumnya.</p>
                    </div>
                </div>
                <div id="image-preview-wrapper" class="mt-4 {{ $existingImageUrl ? '' : 'hidden' }}">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Pratinjau</p>
                    <div class="relative overflow-hidden rounded-xl ring-1 ring-slate-200">
                        <img id="image-preview" src="{{ $existingImageUrl ?? '' }}" alt="Pratinjau gambar"
                            class="h-40 w-full object-cover {{ $existingImageUrl ? '' : 'hidden' }}">
                        <div class="absolute bottom-2 left-2 rounded-full bg-white/80 px-3 py-1 text-xs font-semibold text-slate-700">
                            {{ $existingImageUrl ? 'Gambar aktif' : 'Gambar baru' }}
                        </div>
                    </div>
                </div>
                @if ($section->image_path)
                    <label class="mt-3 inline-flex items-center gap-2 text-xs text-slate-600">
                        <input type="checkbox" name="remove_image" value="1" class="h-4 w-4 rounded border-slate-300 text-red-500 focus:ring-red-400"
                            {{ old('remove_image') ? 'checked' : '' }}>
                        Hapus gambar saat disimpan
                    </label>
                @endif
            </x-ui.section>

            <x-ui.section class="sticky bottom-0">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <x-ui.button type="submit" class="flex-1">
                        <i class="fas fa-save text-xs"></i>
                        {{ $submitLabel }}
                    </x-ui.button>
                    <x-ui.button variant="secondary" :href="route('admin.about-sections.index')" class="flex-1">
                        Batal
                    </x-ui.button>
                </div>
            </x-ui.section>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');
            const slugButton = document.getElementById('slugify');
            const titleCounter = document.getElementById('title-count');
            const contentInput = document.getElementById('content');
            const contentCounter = document.getElementById('content-count');
            const imageInput = document.getElementById('image');
            const previewWrapper = document.getElementById('image-preview-wrapper');
            const previewImage = document.getElementById('image-preview');

            const updateCounter = (input, counterEl, max) => {
                if (!input || !counterEl) return;
                counterEl.textContent = `${input.value.length}/${max}`;
            };

            const slugify = (value) => value
                .toString()
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');

            titleInput?.addEventListener('input', () => updateCounter(titleInput, titleCounter, 120));
            contentInput?.addEventListener('input', () => updateCounter(contentInput, contentCounter, 800));

            slugButton?.addEventListener('click', () => {
                if (!titleInput || !slugInput) return;
                slugInput.value = slugify(titleInput.value);
                slugInput.focus();
            });

            imageInput?.addEventListener('change', (event) => {
                const file = event.target.files?.[0];
                if (!file || !previewWrapper || !previewImage) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImage.src = e.target?.result;
                    previewImage.classList.remove('hidden');
                    previewWrapper.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush
