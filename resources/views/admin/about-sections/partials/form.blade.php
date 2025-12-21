@php
    $titleValue = old('title', $section->title ?? '');
    $contentValue = old('content', $section->content ?? '');
    $existingImageUrl = $section->image_path ? Storage::url($section->image_path) : null;
@endphp

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf
    @if (!empty($formMethod) && strtoupper($formMethod) !== 'POST')
        @method($formMethod)
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold mb-1">Periksa kembali data berikut:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <div class="rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-emerald-600">Blok Informasi</p>
                        <h3 class="text-lg font-semibold text-gray-900">Konten utama Tentang Kami</h3>
                        <p class="text-sm text-gray-500">Judul dan deskripsi akan muncul pada dropdown navigasi dan halaman beranda.</p>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Wajib</span>
                </div>

                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="title" class="text-sm font-semibold text-gray-700">Judul</label>
                        <input type="text" id="title" name="title" value="{{ $titleValue }}" maxlength="120"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="Contoh: Sejarah Kami" required>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Gunakan 3-5 kata kunci yang mudah diingat.</span>
                            <span id="title-count">{{ mb_strlen($titleValue) }}/120</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="slug" class="text-sm font-semibold text-gray-700">Slug (Anchor)</label>
                            <button type="button" id="slugify"
                                class="text-xs font-semibold text-emerald-700 hover:text-emerald-900">
                                Gunakan judul
                            </button>
                        </div>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $section->slug) }}" maxlength="120"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 font-mono text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="contoh: tentang-kami" required>
                        <p class="text-xs text-gray-500">Slug dipakai sebagai target anchor (misal <code>#tentang-kami</code>).</p>
                    </div>
                </div>

                <div class="mt-6 space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="content" class="text-sm font-semibold text-gray-700">Konten</label>
                        <span id="content-count" class="text-xs text-gray-500">{{ mb_strlen($contentValue) }}/800</span>
                    </div>
                    <textarea id="content" name="content" rows="6" maxlength="800"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Tuliskan deskripsi singkat mengenai sejarah, lokasi, atau layanan unggulan">{{ $contentValue }}</textarea>
                    <p class="text-xs text-gray-500">Gunakan paragraf ringkas dan padat informasi (maks 800 karakter).</p>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2 rounded-2xl border border-gray-200 p-5 shadow-sm">
                    <label for="display_order" class="text-sm font-semibold text-gray-700">Urutan Tampil</label>
                    <input type="number" id="display_order" name="display_order"
                        value="{{ old('display_order', $section->display_order ?? 0) }}" min="0"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <p class="text-xs text-gray-500">Angka kecil tampil lebih dulu di dropdown <strong>Tentang Kami</strong>.</p>
                </div>

                <div class="space-y-2 rounded-2xl border border-gray-200 p-5 shadow-sm">
                    <label class="text-sm font-semibold text-gray-700">Status Penayangan</label>
                    <div class="flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            class="h-5 w-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                            {{ old('is_active', $section->is_active ?? true) ? 'checked' : '' }}>
                        <div>
                            <label for="is_active" class="text-sm font-semibold text-gray-700">Tampilkan di website</label>
                            <p class="text-xs text-gray-500">Nonaktifkan bila konten sedang direvisi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-5 text-sm text-emerald-900 shadow-sm">
                <h4 class="text-base font-semibold mb-2">Tips konten informatif</h4>
                <ul class="space-y-1">
                    <li>• Ceritakan manfaat utama untuk pengunjung.</li>
                    <li>• Cantumkan data penting (jam buka, kapasitas, kontak).</li>
                    <li>• Gunakan nada suara yang konsisten dengan brand Dante Pine.</li>
                </ul>
                <p class="mt-3 text-xs text-emerald-800">Konten terbaik biasanya 80-120 kata dan disertai visual pendukung.</p>
            </div>

            <div class="rounded-2xl border border-dashed border-gray-300 p-5 shadow-sm bg-white">
                <label for="image" class="text-sm font-semibold text-gray-700">Gambar Sorotan</label>
                <p class="text-xs text-gray-500 mb-4">Format JPG/PNG/WEBP maks 2MB. Ideal 1200 x 800 px.</p>
                <div class="relative flex flex-col items-center justify-center rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-center">
                    <input type="file" name="image" id="image" accept="image/*"
                        class="absolute inset-0 z-20 h-full w-full cursor-pointer opacity-0">
                    <div class="pointer-events-none z-10 space-y-2">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-white shadow">
                            <i class="fas fa-cloud-arrow-up text-gray-500"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-700">Tarik & letakkan atau klik untuk unggah</p>
                        <p class="text-xs text-gray-500">File baru akan menggantikan gambar sebelumnya.</p>
                    </div>
                </div>

                <div id="image-preview-wrapper" class="mt-4 {{ $existingImageUrl ? '' : 'hidden' }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Pratinjau</p>
                    <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-gray-900/5">
                        <img id="image-preview" src="{{ $existingImageUrl ?? '' }}" alt="Pratinjau gambar"
                            class="h-48 w-full object-cover {{ $existingImageUrl ? '' : 'hidden' }}">
                        <div class="absolute bottom-2 left-2 rounded-full bg-white/80 px-3 py-1 text-xs font-semibold text-gray-700">
                            {{ $existingImageUrl ? 'Gambar aktif' : 'Gambar baru' }}
                        </div>
                    </div>
                </div>

                @if ($section->image_path)
                    <label class="mt-3 inline-flex items-center gap-2 text-xs text-gray-600">
                        <input type="checkbox" name="remove_image" value="1" class="h-4 w-4 rounded border-gray-300 text-red-500 focus:ring-red-400"
                            {{ old('remove_image') ? 'checked' : '' }}>
                        Hapus gambar saat disimpan
                    </label>
                @endif
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="text-sm text-gray-500">
            <p class="font-semibold text-gray-700">Checklist singkat</p>
            <ul class="list-disc pl-4 space-y-1">
                <li>Slug unik dan tanpa spasi.</li>
                <li>Konten maksimal 800 karakter.</li>
                <li>Gambar opsional namun disarankan.</li>
            </ul>
        </div>
        <button type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-emerald-700">
            <i class="fas fa-save"></i>
            {{ $submitLabel }}
        </button>
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
                const length = input.value.length;
                counterEl.textContent = `${length}/${max}`;
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
                if (!file || !previewWrapper || !previewImage) {
                    return;
                }

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
