@extends('layouts.public')
@php use Illuminate\Support\Str; @endphp

@section('title', 'Booking')

@section('content')
    <section class="pt-36 pb-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4">
            <div class="text-center mb-10">
                <p class="text-xs font-semibold tracking-[0.35em] text-gray-500 uppercase">Formulir Reservasi</p>
                <h1 class="text-4xl md:text-5xl font-semibold text-gray-900 mt-4 mb-3">Permintaan Kunjungan Dante Pine</h1>
                <p class="text-base text-gray-600 max-w-3xl mx-auto">Mohon lengkapi data berikut dengan informasi yang valid.
                    Petugas reservasi kami akan menghubungi Anda melalui nomor yang tercantum untuk verifikasi lanjutan.</p>
            </div>

            <div class="mb-12 grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-gray-200 bg-white/90 px-5 py-4 shadow-sm">
                    <p class="text-xs font-semibold tracking-[0.35em] text-gray-500 uppercase mb-2">Langkah 1</p>
                    <p class="text-sm text-gray-700">Isi data kontak & tanggal kunjungan minimum H - 0.</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white/90 px-5 py-4 shadow-sm">
                    <p class="text-xs font-semibold tracking-[0.35em] text-gray-500 uppercase mb-2">Langkah 2</p>
                    <p class="text-sm text-gray-700">Pilih kategori fasilitas (aktivitas, camping, venue) dan cek kuota otomatis.</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white/90 px-5 py-4 shadow-sm">
                    <p class="text-xs font-semibold tracking-[0.35em] text-gray-500 uppercase mb-2">Langkah 3</p>
                    <p class="text-sm text-gray-700">Tim reservasi akan menghubungi anda via WhatsApp untuk konfirmasi & pembayaran DP.</p>
                </div>
            </div>

            @if (session('booking_success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 px-5 py-4 space-y-2 shadow-sm">
                    <p class="font-semibold">{{ session('booking_success') }}</p>
                    @if (session('booking_code'))
                        <p class="text-sm">Kode booking Anda: <span
                                class="font-bold tracking-wide">{{ session('booking_code') }}</span></p>
                    @endif
                    <p class="text-sm text-green-800">Simpan kode tersebut sebagai referensi ketika tim admin menghubungi
                        Anda.</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 shadow-sm">
                    <p class="font-semibold mb-2">Periksa kembali data Anda:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-8 grid gap-5 md:grid-cols-7">
                <div class="md:col-span-4 rounded-2xl border border-gray-200 bg-white/90 backdrop-blur px-6 py-5 shadow-sm">
                    <p class="text-sm font-semibold text-gray-900 mb-1">Sudah mengirim permintaan sebelumnya?</p>
                    <p class="text-sm text-gray-600">Masukkan kode referensi booking Anda pada menu pemantauan status untuk melihat perkembangan terbaru tanpa perlu login.</p>
                </div>
                <a href="{{ route('booking.status') }}"
                    class="md:col-span-3 inline-flex items-center justify-center gap-3 rounded-2xl border border-gray-900/10 bg-gray-900 text-white px-5 py-4 text-sm font-semibold tracking-wide shadow-sm hover:bg-gray-800 transition">
                    <span>Cek Status Booking</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            @php
                $groupedFacilities = ($facilityCategories ?? collect())->filter();
                $prefilled = $prefilledFacility ?? null;
                $selectedFacilityId = old('facility_id', $prefilled?->id);
                $selectedCategory = old('facility_category', $prefilled?->category);
                $categoryDisplayLabel = 'Pilih kategori fasilitas';
                $guestDisplayLabel = old('guests') ? old('guests') . ' orang' : 'Pilih jumlah tamu';
                $facilityDisplayLabel = 'Pilih fasilitas sesuai kategori';

                if ($selectedCategory && $groupedFacilities->has($selectedCategory)) {
                    $selectedFacilities = $groupedFacilities[$selectedCategory];
                    $selectedMinPrice = $selectedFacilities->pluck('booking_price_amount')->filter()->min();
                    $selectedPriceSuffix = $selectedMinPrice
                        ? ' • mulai Rp ' . number_format($selectedMinPrice, 0, ',', '.')
                        : '';
                    $categoryDisplayLabel = Str::title($selectedCategory) . ' (' . $selectedFacilities->count() . ' pilihan' . $selectedPriceSuffix . ')';
                }

                $selectedFacility = $groupedFacilities->collapse()->firstWhere('id', $selectedFacilityId);
                if ($selectedFacility) {
                    $selectedBaseLabel = trim($selectedFacility->name . ($selectedFacility->booking_price_label ? ' — ' . $selectedFacility->booking_price_label : ''));
                    $selectedCapacityLabel = $selectedFacility->capacity_per_day
                        ? 'Kuota harian ' . $selectedFacility->capacity_per_day
                        : 'Kuota fleksibel';
                    $facilityDisplayLabel = $selectedBaseLabel . ' (' . $selectedCapacityLabel . ')';
                }
            @endphp

            @if ($prefilled)
                <div class="mb-8 rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-5 text-sm text-emerald-900 shadow-sm flex flex-col gap-1">
                    <p class="font-semibold">Anda memilih {{ $prefilled->name }} dari halaman sebelumnya.</p>
                    <p>Kategori otomatis diset ke <span class="font-semibold">{{ Str::title($prefilled->category) }}</span>. Silakan pilih tanggal kunjungan untuk melihat ketersediaan slot.</p>
                </div>
            @endif

            <form action="{{ route('booking.store') }}" method="POST"
                class="bg-white rounded-3xl shadow-xl border border-gray-200/80 p-6 md:p-10 space-y-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold tracking-widest text-gray-500 uppercase mb-2" for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
                        <p class="text-xs text-gray-500 mt-2">Sesuai KTP/paspor untuk memudahkan verifikasi di lokasi.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-widest text-gray-500 uppercase mb-2" for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
                        <p class="text-xs text-gray-500 mt-2">Kami kirimkan salinan formulir & update status ke email ini.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-widest text-gray-500 uppercase mb-2" for="phone">Nomor Telepon / WhatsApp</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                            class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
                        <p class="text-xs text-gray-500 mt-2">Gunakan format 08xxxxxxxxxx. Admin akan menghubungi melalui WA.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-widest text-gray-500 uppercase mb-2" for="visit_date">Tanggal Kunjungan</label>
                        <input type="date" id="visit_date" name="visit_date" value="{{ old('visit_date') }}" required
                            class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
                        <p class="text-xs text-gray-500 mt-2">Jika tanggal penuh, sistem akan memberi info dan Anda bisa memilih tanggal lain.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-widest text-gray-500 uppercase mb-2" for="guests">Jumlah Tamu</label>
                        <select id="guests" name="guests" required class="sr-only" tabindex="-1" aria-hidden="true">
                            <option value="" data-display-label="Pilih jumlah tamu">Pilih jumlah tamu</option>
                            @for ($i = 1; $i <= 20; $i++)
                                <option value="{{ $i }}" data-display-label="{{ $i }} orang"
                                    {{ old('guests') == $i ? 'selected' : '' }}>
                                    {{ $i }} orang
                                </option>
                            @endfor
                        </select>
                        <div class="rounded-2xl border border-gray-200 bg-gray-50/80 p-4">
                            <div class="relative" data-custom-select-target="guests">
                                <button type="button"
                                    class="flex w-full items-center justify-between rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-800 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-gray-900/10"
                                    data-custom-trigger data-placeholder="Pilih jumlah tamu">
                                    <span data-custom-label>{{ $guestDisplayLabel }}</span>
                                    <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform" data-custom-arrow></i>
                                </button>
                                <div class="absolute left-0 right-0 mt-2 rounded-2xl border border-gray-200 bg-white shadow-xl hidden z-20" data-custom-options>
                                    <div class="max-h-60 overflow-y-auto py-2">
                                        @for ($i = 1; $i <= 20; $i++)
                                            <button type="button"
                                                class="flex w-full items-center justify-between px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-xl"
                                                data-custom-option data-value="{{ $i }}" data-label="{{ $i }} orang">
                                                <span class="font-semibold text-slate-900">{{ $i }} orang</span>
                                                <span class="text-xs text-slate-500">Klik untuk memilih</span>
                                            </button>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-3">Lebih dari 20 orang? Pilih 20 lalu jelaskan detailnya pada catatan.</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-widest text-gray-500 uppercase mb-2" for="facility_category">Kategori Fasilitas</label>
                        <select id="facility_category" name="facility_category" required class="sr-only" tabindex="-1" aria-hidden="true">
                            <option value="" data-display-label="Pilih kategori fasilitas">Pilih kategori fasilitas</option>
                            @foreach ($groupedFacilities as $category => $facilities)
                                @php
                                    $categoryMinPrice = $facilities->pluck('booking_price_amount')->filter()->min();
                                    $categoryPriceSuffix = $categoryMinPrice
                                        ? ' • mulai Rp ' . number_format($categoryMinPrice, 0, ',', '.')
                                        : '';
                                    $optionLabel = Str::title($category) . ' (' . $facilities->count() . ' pilihan' . $categoryPriceSuffix . ')';
                                @endphp
                                <option value="{{ $category }}" data-display-label="{{ $optionLabel }}"
                                    {{ $selectedCategory === $category ? 'selected' : '' }}>
                                    {{ $optionLabel }}
                                </option>
                            @endforeach
                        </select>
                        <div class="rounded-2xl border border-gray-200 bg-gray-50/80 p-4">
                            <div class="relative" data-custom-select-target="facility_category">
                                <button type="button"
                                    class="flex w-full items-center justify-between rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-800 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-gray-900/10"
                                    data-custom-trigger data-placeholder="Pilih kategori fasilitas">
                                    <span data-custom-label>{{ $categoryDisplayLabel }}</span>
                                    <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform" data-custom-arrow></i>
                                </button>
                                <div class="absolute left-0 right-0 mt-2 rounded-2xl border border-gray-200 bg-white shadow-xl hidden z-20" data-custom-options>
                                    <div class="max-h-64 overflow-y-auto py-2">
                                        @foreach ($groupedFacilities as $category => $facilities)
                                            @php
                                                $categoryMinPrice = $facilities->pluck('booking_price_amount')->filter()->min();
                                                $categoryPriceSuffix = $categoryMinPrice
                                                    ? ' • mulai Rp ' . number_format($categoryMinPrice, 0, ',', '.')
                                                    : '';
                                                $optionLabel = Str::title($category) . ' (' . $facilities->count() . ' pilihan' . $categoryPriceSuffix . ')';
                                            @endphp
                                            <button type="button"
                                                class="flex w-full flex-col items-start gap-1 px-4 py-2 text-left text-sm text-slate-600 hover:bg-slate-50 rounded-xl"
                                                data-custom-option data-value="{{ $category }}" data-label="{{ $optionLabel }}">
                                                <span class="font-semibold text-slate-900">{{ $optionLabel }}</span>
                                                <span class="text-xs text-slate-500">Klik untuk memilih kategori ini.</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-3">Contoh kategori: Camping, Activity, Venue. Sesuaikan dengan kebutuhan Anda.</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-widest text-gray-500 uppercase mb-2" for="facility_id">Fasilitas yang Dibooking</label>
                        <select id="facility_id" name="facility_id" required class="sr-only" tabindex="-1" aria-hidden="true"
                            data-availability-url="{{ route('booking.availability') }}">
                            <option value="" data-display-label="Pilih fasilitas sesuai kategori">Pilih fasilitas sesuai kategori</option>
                            @foreach ($groupedFacilities as $category => $facilities)
                                @foreach ($facilities as $facility)
                                    @php
                                        $baseLabel = trim($facility->name . ($facility->booking_price_label ? ' — ' . $facility->booking_price_label : ''));
                                        $capacityLabel = $facility->capacity_per_day ? 'Kuota harian ' . $facility->capacity_per_day : 'Kuota fleksibel';
                                        $optionDisplayLabel = $baseLabel . ' (' . $capacityLabel . ')';
                                    @endphp
                                    <option value="{{ $facility->id }}" data-category="{{ $category }}"
                                        data-capacity="{{ $facility->capacity_per_day }}"
                                        data-base-label="{{ $baseLabel }}"
                                        data-price="{{ $facility->booking_price_amount ?? '' }}"
                                        data-price-label="{{ $facility->booking_price_label ?? '' }}"
                                        data-display-label="{{ $optionDisplayLabel }}"
                                        {{ (string) $selectedFacilityId === (string) $facility->id ? 'selected' : '' }}>
                                        {{ $optionDisplayLabel }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        <div class="rounded-2xl border border-gray-200 bg-gray-50/80 p-4">
                            <div class="relative" data-custom-select-target="facility_id">
                                <button type="button"
                                    class="flex w-full items-center justify-between rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-800 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-gray-900/10"
                                    data-custom-trigger data-placeholder="Pilih fasilitas sesuai kategori">
                                    <span data-custom-label>{{ $facilityDisplayLabel }}</span>
                                    <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform" data-custom-arrow></i>
                                </button>
                                <div class="absolute left-0 right-0 mt-2 rounded-2xl border border-gray-200 bg-white shadow-xl hidden z-20" data-custom-options>
                                    <div class="max-h-72 overflow-y-auto py-2">
                                        @foreach ($groupedFacilities as $category => $facilities)
                                            @foreach ($facilities as $facility)
                                                @php
                                                    $baseLabel = trim($facility->name . ($facility->booking_price_label ? ' — ' . $facility->booking_price_label : ''));
                                                    $capacityLabel = $facility->capacity_per_day ? 'Kuota harian ' . $facility->capacity_per_day : 'Kuota fleksibel';
                                                    $optionDisplayLabel = $baseLabel . ' (' . $capacityLabel . ')';
                                                @endphp
                                                <button type="button"
                                                    class="flex w-full flex-col items-start gap-1 px-4 py-2 text-left text-sm text-slate-600 hover:bg-slate-50 rounded-xl"
                                                    data-custom-option data-target-select="facility_id" data-target-category="{{ $category }}"
                                                    data-value="{{ $facility->id }}" data-label="{{ $optionDisplayLabel }}">
                                                    <span class="font-semibold text-slate-900">{{ $baseLabel }}</span>
                                                    <span class="text-xs text-slate-500">{{ $capacityLabel }}</span>
                                                    @if ($facility->booking_price_label)
                                                        <span class="text-xs font-medium text-emerald-600">{{ $facility->booking_price_label }}</span>
                                                    @endif
                                                </button>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-3" id="facility-price-hint">Pilih fasilitas untuk melihat harga referensi.</p>
                            <p class="text-xs text-gray-500 mt-2" id="facility-availability-hint">Pilih kategori lalu tanggal untuk mengecek slot otomatis.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-widest text-gray-500 uppercase mb-2" for="notes">Catatan Tambahan</label>
                    <textarea id="notes" name="notes" rows="4"
                        class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-1 focus:ring-gray-900 focus:border-gray-900"
                        placeholder="Beritahu kami preferensi tenda, jadwal, atau kebutuhan khusus Anda">{{ old('notes') }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">Opsional namun berguna untuk permintaan khusus (alat BBQ, dekorasi, dsb.).</p>
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <p class="text-sm text-gray-500 flex items-center gap-2">
                        <i class="fas fa-lock text-gray-700"></i>
                        Seluruh informasi akan dijaga kerahasiaannya sesuai kebijakan privasi Dante Pine Enrekang.
                    </p>
                    <button type="submit"
                        class="inline-flex items-center justify-center bg-gray-900 hover:bg-gray-800 text-white font-semibold px-8 py-3 rounded-2xl shadow-sm transition">
                        Kirim Permintaan Booking
                        <i class="fas fa-arrow-right ml-3"></i>
                    </button>
                </div>
            </form>

            @if ($groupedFacilities->isNotEmpty())
                <div class="mt-10 grid gap-4 md:grid-cols-3">
                    @foreach ($groupedFacilities as $category => $facilities)
                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-2">Kategori</p>
                            <h3 class="text-lg font-semibold text-gray-900">{{ Str::title($category) }}</h3>
                            <ul class="mt-3 space-y-2 text-sm text-gray-600">
                                @foreach ($facilities as $facility)
                                    <li class="flex items-center justify-between">
                                        <span>{{ $facility->name }}</span>
                                        <span
                                            class="text-xs font-medium {{ $facility->capacity_per_day ? 'text-emerald-600' : 'text-gray-500' }}">
                                            {{ $facility->capacity_per_day ? $facility->capacity_per_day . ' slot/hari' : 'Tanpa batas' }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-6 rounded-2xl border border-yellow-200 bg-yellow-50 px-4 py-4 text-sm text-yellow-900">
                    Fasilitas belum tersedia. Silakan hubungi admin sebelum mengirim permintaan booking.
                </div>
            @endif
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categorySelect = document.getElementById('facility_category');
            const facilitySelect = document.getElementById('facility_id');
            const dateInput = document.getElementById('visit_date');
            const hint = document.getElementById('facility-availability-hint');
            const priceHint = document.getElementById('facility-price-hint');
            const availabilityUrl = facilitySelect?.dataset?.availabilityUrl;
            const facilityOptions = Array.from(facilitySelect.options).filter(opt => opt.dataset.category);
            const facilityCustomOptions = document.querySelectorAll('[data-custom-option][data-target-select="facility_id"]');

            const setHint = (message, tone = 'text-gray-500') => {
                hint.textContent = message;
                hint.className = `text-xs mt-2 ${tone}`;
            };

            const resetPriceHint = () => {
                if (!priceHint) return;
                priceHint.textContent = 'Pilih fasilitas untuk melihat harga referensi.';
                priceHint.className = 'text-xs text-gray-500 mt-2';
            };

            const setPriceHint = (message, tone = 'text-emerald-600') => {
                if (!priceHint) return;
                priceHint.textContent = message;
                priceHint.className = `text-xs mt-2 ${tone}`;
            };

            const filterFacilities = () => {
                const selectedCategory = categorySelect.value;
                let hasVisible = false;

                facilityOptions.forEach(option => {
                    const match = !selectedCategory || option.dataset.category === selectedCategory;
                    option.hidden = !match;
                    if (!match && option.selected) {
                        facilitySelect.value = '';
                        facilitySelect.dispatchEvent(new Event('change', { bubbles: true }));
                        resetPriceHint();
                    }
                    if (match) {
                        hasVisible = true;
                    }
                });

                facilityCustomOptions.forEach(button => {
                    const match = !selectedCategory || button.dataset.targetCategory === selectedCategory;
                    button.classList.toggle('hidden', !match);
                });

                if (!selectedCategory) {
                    setHint('Pilih kategori terlebih dahulu untuk menampilkan fasilitas.');
                } else if (!hasVisible) {
                    setHint('Belum ada fasilitas di kategori ini. Hubungi admin.', 'text-yellow-600');
                }
            };

            const updatePriceHint = () => {
                const selectedOption = facilitySelect?.selectedOptions?.[0];
                if (!selectedOption || !selectedOption.value) {
                    resetPriceHint();
                    return;
                }

                const priceLabel = selectedOption.dataset.priceLabel;
                if (priceLabel) {
                    setPriceHint(`Harga referensi: ${priceLabel}`);
                } else {
                    resetPriceHint();
                }
            };

            const applyAvailability = (data) => {
                const selectedId = facilitySelect.value;
                let selectedMessage = 'Silakan pilih fasilitas untuk melihat ketersediaan.';
                let tone = 'text-gray-500';

                facilityOptions.forEach(option => {
                    const info = data?.[option.value];
                    if (!info) return;

                    const baseLabel = option.dataset.baseLabel || option.textContent.split(' (')[0];
                    const isFull = info.is_full;
                    option.disabled = isFull;
                    let capacityText = 'Kuota fleksibel';
                    if (isFull) {
                        capacityText = 'Penuh';
                    } else if (info.remaining !== null) {
                        capacityText = `${info.remaining} slot tersisa`;
                    }
                    option.textContent = `${baseLabel} (${capacityText})`;

                    if (option.value === selectedId) {
                        if (isFull) {
                            selectedMessage = `${info.name} sudah penuh di tanggal tersebut.`;
                            tone = 'text-red-600';
                        } else if (info.remaining !== null) {
                            selectedMessage = `${info.name}: tersisa ${info.remaining} slot dari ${info.capacity} kuota.`;
                            tone = 'text-emerald-600';
                        } else {
                            selectedMessage = `${info.name}: kuota fleksibel, langsung lanjutkan booking.`;
                            tone = 'text-emerald-600';
                        }
                    }
                });

                setHint(selectedMessage, tone);
            };

            const fetchAvailability = async () => {
                if (!availabilityUrl || !dateInput.value) {
                    return;
                }

                setHint('Memeriksa ketersediaan slot...', 'text-blue-600');

                try {
                    const response = await fetch(`${availabilityUrl}?date=${dateInput.value}`);
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data');
                    }
                    const payload = await response.json();
                    applyAvailability(payload.data || {});
                } catch (error) {
                    setHint('Tidak dapat memuat data ketersediaan. Coba lagi nanti.', 'text-red-600');
                }
            };

            categorySelect?.addEventListener('change', () => {
                filterFacilities();
                fetchAvailability();
                updatePriceHint();
            });

            facilitySelect?.addEventListener('change', () => {
                fetchAvailability();
                updatePriceHint();
            });
            dateInput?.addEventListener('change', () => fetchAvailability());

            const initCustomSelects = () => {
                document.querySelectorAll('[data-custom-select-target]').forEach(wrapper => {
                    const targetId = wrapper.dataset.customSelectTarget;
                    const targetSelect = document.getElementById(targetId);
                    const trigger = wrapper.querySelector('[data-custom-trigger]');
                    const optionsPanel = wrapper.querySelector('[data-custom-options]');
                    const arrow = wrapper.querySelector('[data-custom-arrow]');
                    const label = wrapper.querySelector('[data-custom-label]');
                    const optionButtons = wrapper.querySelectorAll('[data-custom-option]');
                    if (!targetSelect || !trigger || !optionsPanel || !label) {
                        return;
                    }

                    const placeholder = trigger.dataset.placeholder || 'Pilih opsi';
                    const closePanel = () => {
                        optionsPanel.classList.add('hidden');
                        arrow?.classList.remove('rotate-180');
                    };
                    const openPanel = () => {
                        optionsPanel.classList.remove('hidden');
                        arrow?.classList.add('rotate-180');
                    };

                    trigger.addEventListener('click', event => {
                        event.preventDefault();
                        if (optionsPanel.classList.contains('hidden')) {
                            openPanel();
                        } else {
                            closePanel();
                        }
                    });

                    optionButtons.forEach(button => {
                        button.addEventListener('click', () => {
                            const value = button.dataset.value;
                            const displayLabel = button.dataset.label || button.textContent.trim();
                            if (value && targetSelect.value !== value) {
                                targetSelect.value = value;
                                targetSelect.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                            label.textContent = displayLabel;
                            optionButtons.forEach(btn => {
                                const isActive = btn.dataset.value === value;
                                btn.classList.toggle('bg-slate-100', isActive);
                                btn.classList.toggle('text-slate-900', isActive);
                                btn.classList.toggle('text-slate-600', !isActive);
                            });
                            closePanel();
                        });
                    });

                    document.addEventListener('click', event => {
                        if (!wrapper.contains(event.target)) {
                            closePanel();
                        }
                    });

                    const syncFromSelect = () => {
                        const selectedOption = targetSelect.selectedOptions[0];
                        if (!selectedOption || !selectedOption.value) {
                            label.textContent = placeholder;
                            optionButtons.forEach(btn => {
                                btn.classList.remove('bg-slate-100', 'text-slate-900');
                                btn.classList.add('text-slate-600');
                            });
                            return;
                        }

                        const selectedValue = selectedOption.value;
                        const displayLabel = selectedOption.dataset.displayLabel || selectedOption.textContent.trim();
                        label.textContent = displayLabel;
                        optionButtons.forEach(btn => {
                            const isActive = btn.dataset.value === selectedValue;
                            btn.classList.toggle('bg-slate-100', isActive);
                            btn.classList.toggle('text-slate-900', isActive);
                            btn.classList.toggle('text-slate-600', !isActive);
                        });
                    };

                    targetSelect.addEventListener('change', syncFromSelect);
                    syncFromSelect();
                });
            };

            filterFacilities();
            if (dateInput?.value) {
                fetchAvailability();
            }
            updatePriceHint();
            if (!facilitySelect.value) {
                resetPriceHint();
            }
            initCustomSelects();
        });
    </script>
@endsection
