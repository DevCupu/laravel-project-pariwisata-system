@php
    $facility = $facility ?? null;
    $formMethod = $formMethod ?? 'POST';
    $submitLabel = $submitLabel ?? 'Simpan';
    $categoryOptions = $categoryOptions ?? [];
@endphp

<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="lg:grid lg:grid-cols-3 lg:gap-5">
        <div class="lg:col-span-2">
            <div class="space-y-5">
                <x-ui.section title="Detail Fasilitas">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Nama Fasilitas" name="name" :value="$facility->name ?? ''" required />
                        <div class="block">
                            <label for="category" class="text-sm font-semibold text-slate-700">Kategori<span class="text-red-500"> *</span></label>
                            <input type="text" name="category" id="category" list="facility-categories"
                                value="{{ old('category', $facility->category ?? '') }}" required
                                placeholder="Misal: wahana, camping, cafe"
                                class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                            <datalist id="facility-categories">
                                @foreach ($categoryOptions as $category)
                                    <option value="{{ $category }}">{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $category)) }}</option>
                                @endforeach
                            </datalist>
                            @error('category')
                                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-slate-500">Fasilitas dengan kategori yang sama dikelompokkan bersama saat pengunjung booking.</p>
                            @enderror
                        </div>
                    </div>
                    <x-ui.textarea label="Deskripsi" name="description" :value="$facility->description ?? ''" rows="4"
                        class="mt-4" placeholder="Opsional, jelaskan highlight fasilitas" />
                </x-ui.section>
            </div>
        </div>

        <div class="mt-5 space-y-5 lg:col-span-1 lg:mt-0">
            <x-ui.section title="Ketersediaan">
                <div class="grid grid-cols-2 gap-4">
                    <x-ui.input label="Kapasitas tamu per hari" name="capacity_per_day" type="number" min="0" max="1000"
                        :value="$facility->capacity_per_day ?? 0" hint="Isi 0 jika tidak dibatasi." required />
                    <x-ui.input label="Urutan tampil" name="sort_order" type="number" min="0" max="5000"
                        :value="$facility->sort_order ?? 0" hint="Angka kecil tampil lebih dulu." />
                </div>
                <div class="mt-4">
                    <x-ui.toggle name="is_active" label="Aktif untuk booking"
                        helper="Fasilitas nonaktif tidak muncul di formulir booking publik."
                        :checked="old('is_active', ($facility->is_active ?? true))" />
                </div>
            </x-ui.section>

            <x-ui.section class="sticky bottom-0">
                <p class="mb-3 text-xs text-slate-400">Slug akan dibuat otomatis berdasarkan nama fasilitas.</p>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <x-ui.button type="submit" class="flex-1">{{ $submitLabel }}</x-ui.button>
                    <x-ui.button variant="secondary" :href="route('admin.facilities.index')" class="flex-1">
                        Batal
                    </x-ui.button>
                </div>
            </x-ui.section>
        </div>
    </div>
</form>
