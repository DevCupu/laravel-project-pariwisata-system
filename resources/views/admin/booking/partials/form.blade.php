@php
    $booking = $booking ?? null;
    $formMethod = $formMethod ?? 'POST';
    $submitLabel = $submitLabel ?? 'Simpan';
    $visitDateValue = old('visit_date', $booking?->visit_date ? \Illuminate\Support\Carbon::parse($booking->visit_date)->format('Y-m-d') : null);
    $selectedFacilityId = old('facility_id', optional($booking)->facility_id ?? '');
@endphp

<form method="POST" action="{{ $formAction }}" class="space-y-6" data-admin-booking-form>
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="text-sm font-medium text-gray-700">Nama Pemesan</label>
            <input type="text" name="name" value="{{ old('name', optional($booking)->name) }}"
                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                required>
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', optional($booking)->email) }}"
                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                required>
            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Nomor WhatsApp</label>
            <input type="text" name="phone" value="{{ old('phone', optional($booking)->phone) }}"
                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                required>
            @error('phone')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Tanggal Kunjungan</label>
            <input type="date" name="visit_date" value="{{ $visitDateValue }}"
                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                required>
            @error('visit_date')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Jumlah Tamu</label>
            <input type="number" name="guests" min="1" max="50"
                value="{{ old('guests', optional($booking)->guests ?? 1) }}"
                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                required>
            @error('guests')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Status</label>
            <select name="status"
                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                @foreach ($statusOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', optional($booking)->status ?? 'pending') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="text-sm font-medium text-gray-700">Fasilitas</label>
            <select name="facility_id"
                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                required>
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
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Dipakai untuk menghitung kuota & rekomendasi paket.</p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Nama Paket</label>
            <input type="text" name="package"
                value="{{ old('package', optional($booking)->package) }}"
                placeholder="Otomatis mengikuti kategori fasilitas"
                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                data-package-input>
            @error('package')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700">Catatan Internal</label>
        <textarea name="notes" rows="4"
            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
            placeholder="Tambahkan detail tambahan untuk tim operasional">{{ old('notes', optional($booking)->notes) }}</textarea>
        @error('notes')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        @if ($booking)
            <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 text-sm text-gray-600">
                <p><span class="font-semibold text-gray-800">Kode:</span> <span class="font-mono text-gray-900">{{ $booking->booking_code }}</span></p>
                <p class="text-xs text-gray-500">Dibuat {{ $booking->created_at->diffForHumans() }}</p>
            </div>
        @endif
        <div class="flex flex-col gap-3 md:flex-row md:gap-4">
            <a href="{{ route('admin.bookings.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">{{ $submitLabel }}</button>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('[data-admin-booking-form]');
        if (!form) {
            return;
        }

        const facilitySelect = form.querySelector('select[name="facility_id"]');
        const packageInput = form.querySelector('[data-package-input]');

        if (!facilitySelect || !packageInput) {
            return;
        }

        const syncPackage = () => {
            const selected = facilitySelect.selectedOptions[0];
            if (!selected) {
                return;
            }
            const label = selected.dataset.categoryLabel;
            if (!label) {
                return;
            }

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
