@php
    $booking = $booking ?? null;
    $formMethod = $formMethod ?? 'POST';
    $submitLabel = $submitLabel ?? 'Simpan';
    $visitDateValue = old('visit_date', $booking?->visit_date ? \Illuminate\Support\Carbon::parse($booking->visit_date)->format('Y-m-d') : null);
    $selectedFacilityId = old('facility_id', optional($booking)->facility_id ?? '');
@endphp

<form method="POST" action="{{ $formAction }}" data-admin-booking-form>
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="lg:grid lg:grid-cols-3 lg:gap-5">
        <div class="lg:col-span-2">
            <div class="space-y-5">
                <x-ui.section title="Informasi Tamu">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Nama Pemesan" name="name" :value="optional($booking)->name" required />
                        <x-ui.input label="Email" name="email" type="email" :value="optional($booking)->email" required />
                        <x-ui.input label="Nomor WhatsApp" name="phone" :value="optional($booking)->phone" required />
                        <x-ui.input label="Tanggal Kunjungan" name="visit_date" type="date" :value="$visitDateValue" required />
                        <x-ui.input label="Jumlah Tamu" name="guests" type="number" min="1" max="50"
                            :value="old('guests', optional($booking)->guests ?? 1)" required />
                    </div>
                </x-ui.section>

                <x-ui.section title="Fasilitas & Paket">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="block">
                            <label for="facility_id" class="text-sm font-semibold text-slate-700">Fasilitas<span class="text-red-500"> *</span></label>
                            <select name="facility_id" id="facility_id" required
                                class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                                <option value="">Pilih fasilitas</option>
                                @foreach ($facilityGroups as $category => $facilities)
                                    <optgroup label="{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $category)) }}">
                                        @foreach ($facilities as $facility)
                                            <option value="{{ $facility->id }}"
                                                data-category-label="{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $facility->category)) }}"
                                                data-capacity="{{ $facility->capacity_per_day }}"
                                                @selected((int) $selectedFacilityId === $facility->id)>
                                                {{ $facility->name }} (Kuota {{ $facility->capacity_per_day ?: '∞' }} / hari)
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('facility_id')
                                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-slate-500">Dipakai untuk menghitung kuota & rekomendasi paket.</p>
                            @enderror
                        </div>
                        <x-ui.input label="Nama Paket" name="package"
                            :value="old('package', optional($booking)->package)"
                            placeholder="Otomatis mengikuti kategori fasilitas"
                            hint="Isi manual atau biarkan otomatis terisi"
                            data-package-input />
                    </div>
                    <x-ui.textarea label="Catatan Internal" name="notes" :value="optional($booking)->notes" rows="4"
                        class="mt-4" placeholder="Tambahkan detail tambahan untuk tim operasional" />
                </x-ui.section>
            </div>
        </div>

        <div class="mt-5 space-y-5 lg:col-span-1 lg:mt-0">
            <x-ui.section title="Status Booking">
                <x-ui.select label="Status" name="status" :options="$statusOptions"
                    :selected="old('status', optional($booking)->status ?? 'pending')" />
            </x-ui.section>

            @if ($booking)
                <x-ui.section title="Info Booking">
                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-600 ring-1 ring-slate-100">
                        <p><span class="font-semibold text-slate-800">Kode:</span> <span class="font-mono text-slate-900">{{ $booking->booking_code }}</span></p>
                        <p class="mt-0.5 text-xs text-slate-500">Dibuat {{ $booking->created_at->diffForHumans() }}</p>
                    </div>
                </x-ui.section>
            @endif

            <x-ui.section class="sticky bottom-0">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <x-ui.button type="submit" class="flex-1">{{ $submitLabel }}</x-ui.button>
                    <x-ui.button variant="secondary" :href="route('admin.bookings.index')" class="flex-1">
                        Batal
                    </x-ui.button>
                </div>
            </x-ui.section>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('[data-admin-booking-form]');
        if (!form) return;

        const facilitySelect = form.querySelector('select[name="facility_id"]');
        const packageInput = form.querySelector('[data-package-input]');

        if (!facilitySelect || !packageInput) return;

        const syncPackage = () => {
            const selected = facilitySelect.selectedOptions[0];
            if (!selected) return;
            const label = selected.dataset.categoryLabel;
            if (!label) return;

            if (!packageInput.value.trim() || packageInput.dataset.autofilled === 'true') {
                packageInput.value = label;
                packageInput.dataset.autofilled = 'true';
            }
        };

        facilitySelect.addEventListener('change', syncPackage);
        packageInput.addEventListener('input', () => {
            packageInput.dataset.autofilled = 'false';
        });

        if (!packageInput.value.trim()) {
            syncPackage();
        }
    });
</script>
