@extends('layouts.public')
@php use Illuminate\Support\Str; @endphp

@section('title', 'Booking')
@section('meta_description', 'Ajukan permintaan booking kunjungan, camping, atau aktivitas di Dante Pine Enrekang. Isi formulir reservasi online dengan cepat.')

@section('content')
    @include('partials.public.page-hero', [
        'title' => 'Permintaan Kunjungan Dante Pine',
        'subtitle' => 'Empat langkah singkat: data diri, jadwal, pilih fasilitas, lalu konfirmasi. Tim reservasi kami akan menghubungi Anda via WhatsApp.',
        'eyebrow' => 'Formulir Reservasi',
        'breadcrumb' => [['label' => 'Booking']],
    ])

    @php
        $groupedFacilities = ($facilityCategories ?? collect())->filter();
        $prefilled = $prefilledFacility ?? null;
        $selectedFacilityId = old('facility_id', $prefilled?->id);
        $selectedCategory = old('facility_category', $prefilled?->category);
        $guestDisplayLabel = old('guests') ? old('guests') . ' orang' : 'Pilih jumlah tamu';
        $categoryDisplayLabel = 'Pilih kategori fasilitas';
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

        $wizardStep = 1;
        if ($errors->any()) {
            if ($errors->hasAny(['name', 'email', 'phone'])) {
                $wizardStep = 1;
            } elseif ($errors->hasAny(['visit_date', 'guests'])) {
                $wizardStep = 2;
            } elseif ($errors->hasAny(['facility_category', 'facility_id'])) {
                $wizardStep = 3;
            } else {
                $wizardStep = 4;
            }
        }

        $serverErrors = [
            'name' => $errors->first('name'),
            'email' => $errors->first('email'),
            'phone' => $errors->first('phone'),
            'visit_date' => $errors->first('visit_date'),
            'guests' => $errors->first('guests'),
            'facility_category' => $errors->first('facility_category'),
            'facility_id' => $errors->first('facility_id'),
            'notes' => $errors->first('notes'),
        ];
    @endphp

    <section class="pn-section pn-section--tint">
        <div class="pn-container">
            @if (session('booking_success'))
                <div class="mb-8 rounded-3xl border border-emerald-200 bg-emerald-50 p-6 text-emerald-950 shadow-sm sm:p-8">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="space-y-2">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-emerald-600 text-white">
                                <i class="fas fa-check" aria-hidden="true"></i>
                            </span>
                            <p class="mt-3 text-lg font-bold">{{ session('booking_success') }}</p>
                            @if (session('booking_code'))
                                <p class="text-sm">Kode booking Anda:
                                    <span class="rounded-lg bg-white px-2.5 py-1 font-bold tracking-widest text-emerald-700 shadow-sm">{{ session('booking_code') }}</span>
                                </p>
                            @endif
                            <p class="text-sm text-emerald-800/80">Simpan kode tersebut sebagai referensi ketika tim admin menghubungi Anda.</p>
                        </div>
                        <div class="flex shrink-0 flex-col gap-3">
                            <a href="{{ route('booking.status') }}" class="pn-btn pn-btn-primary">
                                <i class="fas fa-magnifying-glass" aria-hidden="true"></i> Cek Status Booking
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-rose-700 shadow-sm">
                    <p class="mb-2 font-semibold">Periksa kembali data Anda:</p>
                    <ul class="list-inside list-disc space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-12" x-data="bookingWizard({
                step: {{ $wizardStep }},
                serverErrors: {{ \Illuminate\Support\Js::from($serverErrors) }},
            })" x-cloak>
                <div class="lg:col-span-8">
                    <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-xl">
                        {{-- Stepper --}}
                        <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-5 sm:px-8">
                            <ol class="flex items-center gap-2 sm:gap-3" aria-label="Progres booking">
                                @php
                                    $steps = [
                                        ['n' => 1, 'label' => 'Data Diri', 'icon' => 'fa-user'],
                                        ['n' => 2, 'label' => 'Jadwal', 'icon' => 'fa-calendar-days'],
                                        ['n' => 3, 'label' => 'Fasilitas', 'icon' => 'fa-ticket'],
                                        ['n' => 4, 'label' => 'Konfirmasi', 'icon' => 'fa-circle-check'],
                                    ];
                                @endphp
                                @foreach ($steps as $i => $st)
                                    @if ($i > 0)
                                        <li class="hidden h-px flex-1 bg-slate-200 sm:block" aria-hidden="true"
                                            :class="step > {{ $st['n'] - 1 }} ? 'bg-emerald-400' : 'bg-slate-200'"></li>
                                    @endif
                                    <li>
                                        <button type="button" class="group flex items-center gap-2.5 text-left disabled:cursor-not-allowed sm:gap-3"
                                            @click="goTo({{ $st['n'] }})" :disabled="{{ $st['n'] }} > maxStep"
                                            :aria-current="step === {{ $st['n'] }} ? 'step' : null">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-bold transition-all duration-300"
                                                :class="step > {{ $st['n'] }}
                                                    ? 'bg-emerald-500 text-white'
                                                    : (step === {{ $st['n'] }}
                                                        ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30 ring-4 ring-emerald-100'
                                                        : 'bg-white text-slate-400 ring-1 ring-slate-200')">
                                                <i class="fas fa-check text-xs" x-show="step > {{ $st['n'] }}" x-cloak></i>
                                                <i class="fas {{ $st['icon'] }} text-xs" x-show="step <= {{ $st['n'] }}"></i>
                                            </span>
                                            <span class="hidden text-sm font-semibold transition-colors sm:block"
                                                :class="step >= {{ $st['n'] }} ? 'text-slate-800' : 'text-slate-400'">{{ $st['label'] }}</span>
                                        </button>
                                    </li>
                                @endforeach
                            </ol>
                            <p class="mt-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 sm:hidden">
                                Langkah <span x-text="step"></span> dari 4 &mdash;
                                <span x-text="stepTitles[step]"></span>
                            </p>
                        </div>

                        @if ($prefilled)
                            <div class="border-b border-emerald-100 bg-emerald-50/70 px-5 py-4 text-sm text-emerald-900 sm:px-8">
                                <p class="font-semibold"><i class="fas fa-circle-info mr-1.5" aria-hidden="true"></i> Anda memilih {{ $prefilled->name }} dari halaman sebelumnya.</p>
                                <p class="mt-0.5 text-emerald-800/80">Kategori otomatis diset ke <span class="font-semibold">{{ Str::title($prefilled->category) }}</span>. Silakan lengkapi langkah berikutnya untuk melihat ketersediaan slot.</p>
                            </div>
                        @endif

                        <form x-ref="form" action="{{ route('booking.store') }}" method="POST" novalidate
                            @submit.prevent="onSubmit" class="px-5 py-7 sm:px-8 sm:py-9">
                            @csrf

                            {{-- Step 1: Data Diri --}}
                            <div x-show="step === 1" x-transition.opacity.duration.200ms data-step-panel="1" class="space-y-6">
                                <header class="space-y-1">
                                    <p class="pn-eyebrow text-emerald-600"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Langkah 1</p>
                                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Data diri & kontak</h2>
                                    <p class="text-sm text-slate-500">Kami gunakan informasi ini untuk verifikasi dan mengirim update booking Anda.</p>
                                </header>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <label class="pn-field-label" for="name">Nama Lengkap <span class="text-rose-500">*</span></label>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name"
                                            placeholder="Contoh: Andi Wijaya"
                                            class="pn-field" :class="errs.name && 'border-rose-400 focus:border-rose-500 focus:shadow-rose-500/10'">
                                        <p class="mt-1.5 text-xs text-rose-600" x-show="errs.name" x-text="errs.name" x-cloak></p>
                                        <p class="mt-1.5 text-xs text-slate-400">Sesuai KTP/paspor untuk memudahkan verifikasi di lokasi.</p>
                                    </div>

                                    <div>
                                        <label class="pn-field-label" for="email">Email <span class="text-rose-500">*</span></label>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                                            placeholder="nama@email.com"
                                            class="pn-field" :class="errs.email && 'border-rose-400 focus:border-rose-500 focus:shadow-rose-500/10'">
                                        <p class="mt-1.5 text-xs text-rose-600" x-show="errs.email" x-text="errs.email" x-cloak></p>
                                        <p class="mt-1.5 text-xs text-slate-400">Salinan formulir & update status dikirim ke email ini.</p>
                                    </div>

                                    <div>
                                        <label class="pn-field-label" for="phone">Nomor Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required autocomplete="tel"
                                            placeholder="08xxxxxxxxxx" inputmode="tel"
                                            class="pn-field" :class="errs.phone && 'border-rose-400 focus:border-rose-500 focus:shadow-rose-500/10'">
                                        <p class="mt-1.5 text-xs text-rose-600" x-show="errs.phone" x-text="errs.phone" x-cloak></p>
                                        <p class="mt-1.5 text-xs text-slate-400">Gunakan format 08xxxxxxxxxx. Admin akan menghubungi melalui WA.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 2: Jadwal & Tamu --}}
                            <div x-show="step === 2" x-transition.opacity.duration.200ms data-step-panel="2" class="space-y-6" x-cloak>
                                <header class="space-y-1">
                                    <p class="pn-eyebrow text-emerald-600"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Langkah 2</p>
                                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Jadwal & jumlah tamu</h2>
                                    <p class="text-sm text-slate-500">Tanggal kunjungan minimal H-0. Kuota slot akan dicek otomatis saat Anda memilih fasilitas.</p>
                                </header>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <label class="pn-field-label" for="visit_date">Tanggal Kunjungan <span class="text-rose-500">*</span></label>
                                        <input type="date" id="visit_date" name="visit_date" value="{{ old('visit_date') }}" required
                                            min="{{ now()->toDateString() }}"
                                            class="pn-field" :class="errs.visit_date && 'border-rose-400 focus:border-rose-500 focus:shadow-rose-500/10'">
                                        <p class="mt-1.5 text-xs text-rose-600" x-show="errs.visit_date" x-text="errs.visit_date" x-cloak></p>
                                        <p class="mt-1.5 text-xs text-slate-400">Jika tanggal penuh, sistem akan memberi info dan Anda bisa memilih tanggal lain.</p>
                                    </div>

                                    <div>
                                        <label class="pn-field-label" for="guests">Jumlah Tamu <span class="text-rose-500">*</span></label>
                                        <select id="guests" name="guests" required class="sr-only" tabindex="-1" aria-hidden="true">
                                            <option value="" data-display-label="Pilih jumlah tamu">Pilih jumlah tamu</option>
                                            @for ($i = 1; $i <= 20; $i++)
                                                <option value="{{ $i }}" data-display-label="{{ $i }} orang"
                                                    {{ old('guests') == $i ? 'selected' : '' }}>
                                                    {{ $i }} orang
                                                </option>
                                            @endfor
                                        </select>
                                        <div class="rounded-2xl border p-4 transition-colors"
                                            :class="errs.guests ? 'border-rose-300 bg-rose-50/50' : 'border-slate-200 bg-slate-50/70'">
                                            <div class="relative" data-custom-select-target="guests">
                                                <button type="button"
                                                    class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-emerald-500/30"
                                                    data-custom-trigger data-placeholder="Pilih jumlah tamu">
                                                    <span data-custom-label>{{ $guestDisplayLabel }}</span>
                                                    <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform" data-custom-arrow></i>
                                                </button>
                                                <div class="absolute left-0 right-0 z-20 mt-2 hidden rounded-2xl border border-slate-200 bg-white shadow-xl" data-custom-options>
                                                    <div class="max-h-60 overflow-y-auto py-2">
                                                        @for ($i = 1; $i <= 20; $i++)
                                                            <button type="button"
                                                                class="flex w-full items-center justify-between rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-50"
                                                                data-custom-option data-value="{{ $i }}" data-label="{{ $i }} orang">
                                                                <span class="font-semibold text-slate-900">{{ $i }} orang</span>
                                                                <span class="text-xs text-slate-400">Pilih</span>
                                                            </button>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-3 text-xs text-slate-400">Lebih dari 20 orang? Pilih 20 lalu jelaskan detailnya pada catatan.</p>
                                        </div>
                                        <p class="mt-1.5 text-xs text-rose-600" x-show="errs.guests" x-text="errs.guests" x-cloak></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 3: Fasilitas --}}
                            <div x-show="step === 3" x-transition.opacity.duration.200ms data-step-panel="3" class="space-y-6" x-cloak>
                                <header class="space-y-1">
                                    <p class="pn-eyebrow text-emerald-600"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Langkah 3</p>
                                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Pilih fasilitas</h2>
                                    <p class="text-sm text-slate-500">Pilih kategori dulu, lalu fasilitasnya. Ketersediaan slot dicek otomatis sesuai tanggal kunjungan.</p>
                                </header>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <label class="pn-field-label" for="facility_category">Kategori Fasilitas <span class="text-rose-500">*</span></label>
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
                                        <div class="rounded-2xl border p-4 transition-colors"
                                            :class="errs.facility_category ? 'border-rose-300 bg-rose-50/50' : 'border-slate-200 bg-slate-50/70'">
                                            <div class="relative" data-custom-select-target="facility_category">
                                                <button type="button"
                                                    class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-emerald-500/30"
                                                    data-custom-trigger data-placeholder="Pilih kategori fasilitas">
                                                    <span data-custom-label>{{ $categoryDisplayLabel }}</span>
                                                    <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform" data-custom-arrow></i>
                                                </button>
                                                <div class="absolute left-0 right-0 z-20 mt-2 hidden rounded-2xl border border-slate-200 bg-white shadow-xl" data-custom-options>
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
                                                                class="flex w-full flex-col items-start gap-1 rounded-xl px-4 py-2 text-left text-sm text-slate-600 hover:bg-slate-50"
                                                                data-custom-option data-value="{{ $category }}" data-label="{{ $optionLabel }}">
                                                                <span class="font-semibold text-slate-900">{{ $optionLabel }}</span>
                                                                <span class="text-xs text-slate-400">Klik untuk memilih kategori ini.</span>
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-3 text-xs text-slate-400">Contoh kategori: Camping, Activity, Venue. Sesuaikan dengan kebutuhan Anda.</p>
                                        </div>
                                        <p class="mt-1.5 text-xs text-rose-600" x-show="errs.facility_category" x-text="errs.facility_category" x-cloak></p>
                                    </div>

                                    <div>
                                        <label class="pn-field-label" for="facility_id">Fasilitas yang Dibooking <span class="text-rose-500">*</span></label>
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
                                        <div class="rounded-2xl border p-4 transition-colors"
                                            :class="errs.facility_id ? 'border-rose-300 bg-rose-50/50' : 'border-slate-200 bg-slate-50/70'">
                                            <div class="relative" data-custom-select-target="facility_id">
                                                <button type="button"
                                                    class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-emerald-500/30"
                                                    data-custom-trigger data-placeholder="Pilih fasilitas sesuai kategori">
                                                    <span data-custom-label>{{ $facilityDisplayLabel }}</span>
                                                    <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform" data-custom-arrow></i>
                                                </button>
                                                <div class="absolute left-0 right-0 z-20 mt-2 hidden rounded-2xl border border-slate-200 bg-white shadow-xl" data-custom-options>
                                                    <div class="max-h-72 overflow-y-auto py-2">
                                                        @foreach ($groupedFacilities as $category => $facilities)
                                                            @foreach ($facilities as $facility)
                                                                @php
                                                                    $baseLabel = trim($facility->name . ($facility->booking_price_label ? ' — ' . $facility->booking_price_label : ''));
                                                                    $capacityLabel = $facility->capacity_per_day ? 'Kuota harian ' . $facility->capacity_per_day : 'Kuota fleksibel';
                                                                    $optionDisplayLabel = $baseLabel . ' (' . $capacityLabel . ')';
                                                                @endphp
                                                                <button type="button"
                                                                    class="flex w-full flex-col items-start gap-1 rounded-xl px-4 py-2 text-left text-sm text-slate-600 hover:bg-slate-50"
                                                                    data-custom-option data-target-select="facility_id" data-target-category="{{ $category }}"
                                                                    data-value="{{ $facility->id }}" data-label="{{ $optionDisplayLabel }}">
                                                                    <span class="font-semibold text-slate-900">{{ $baseLabel }}</span>
                                                                    <span class="text-xs text-slate-400">{{ $capacityLabel }}</span>
                                                                    @if ($facility->booking_price_label)
                                                                        <span class="text-xs font-medium text-emerald-600">{{ $facility->booking_price_label }}</span>
                                                                    @endif
                                                                </button>
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-3 text-xs font-medium text-emerald-600" id="facility-price-hint">Pilih fasilitas untuk melihat harga referensi.</p>
                                            <p class="mt-1.5 text-xs text-slate-500" id="facility-availability-hint">Pilih kategori lalu tanggal untuk mengecek slot otomatis.</p>
                                        </div>
                                        <p class="mt-1.5 text-xs text-rose-600" x-show="errs.facility_id" x-text="errs.facility_id" x-cloak></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 4: Konfirmasi --}}
                            <div x-show="step === 4" x-transition.opacity.duration.200ms data-step-panel="4" class="space-y-6" x-cloak>
                                <header class="space-y-1">
                                    <p class="pn-eyebrow text-emerald-600"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Langkah 4</p>
                                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Periksa & konfirmasi</h2>
                                    <p class="text-sm text-slate-500">Pastikan ringkasan di bawah sudah benar sebelum mengirim permintaan.</p>
                                </header>

                                <dl class="divide-y divide-slate-100 rounded-2xl border border-slate-200 bg-slate-50/60">
                                    <div class="flex items-start justify-between gap-4 px-5 py-3.5">
                                        <dt class="text-sm text-slate-500">Nama lengkap</dt>
                                        <dd class="text-right text-sm font-semibold text-slate-900">
                                            <span x-text="form.name || '—'"></span>
                                            <button type="button" class="ml-2 text-xs font-semibold text-emerald-600 hover:underline" @click="goTo(1)">Ubah</button>
                                        </dd>
                                    </div>
                                    <div class="flex items-start justify-between gap-4 px-5 py-3.5">
                                        <dt class="text-sm text-slate-500">Email</dt>
                                        <dd class="break-all text-right text-sm font-semibold text-slate-900">
                                            <span x-text="form.email || '—'"></span>
                                            <button type="button" class="ml-2 shrink-0 text-xs font-semibold text-emerald-600 hover:underline" @click="goTo(1)">Ubah</button>
                                        </dd>
                                    </div>
                                    <div class="flex items-start justify-between gap-4 px-5 py-3.5">
                                        <dt class="text-sm text-slate-500">WhatsApp</dt>
                                        <dd class="text-right text-sm font-semibold text-slate-900">
                                            <span x-text="form.phone || '—'"></span>
                                            <button type="button" class="ml-2 text-xs font-semibold text-emerald-600 hover:underline" @click="goTo(1)">Ubah</button>
                                        </dd>
                                    </div>
                                    <div class="flex items-start justify-between gap-4 px-5 py-3.5">
                                        <dt class="text-sm text-slate-500">Tanggal kunjungan</dt>
                                        <dd class="text-right text-sm font-semibold text-slate-900">
                                            <span x-text="formattedDate"></span>
                                            <button type="button" class="ml-2 text-xs font-semibold text-emerald-600 hover:underline" @click="goTo(2)">Ubah</button>
                                        </dd>
                                    </div>
                                    <div class="flex items-start justify-between gap-4 px-5 py-3.5">
                                        <dt class="text-sm text-slate-500">Jumlah tamu</dt>
                                        <dd class="text-right text-sm font-semibold text-slate-900">
                                            <span x-text="form.guests ? form.guests + ' orang' : '—'"></span>
                                            <button type="button" class="ml-2 text-xs font-semibold text-emerald-600 hover:underline" @click="goTo(2)">Ubah</button>
                                        </dd>
                                    </div>
                                    <div class="flex items-start justify-between gap-4 px-5 py-3.5">
                                        <dt class="text-sm text-slate-500">Kategori fasilitas</dt>
                                        <dd class="text-right text-sm font-semibold text-slate-900">
                                            <span x-text="labels.category || '—'"></span>
                                            <button type="button" class="ml-2 text-xs font-semibold text-emerald-600 hover:underline" @click="goTo(3)">Ubah</button>
                                        </dd>
                                    </div>
                                    <div class="flex items-start justify-between gap-4 px-5 py-3.5">
                                        <dt class="text-sm text-slate-500">Fasilitas</dt>
                                        <dd class="text-right text-sm font-semibold text-emerald-700">
                                            <span x-text="labels.facility || '—'"></span>
                                            <button type="button" class="ml-2 text-xs font-semibold text-emerald-600 hover:underline" @click="goTo(3)">Ubah</button>
                                        </dd>
                                    </div>
                                </dl>

                                <div>
                                    <label class="pn-field-label" for="notes">Catatan Tambahan</label>
                                    <textarea id="notes" name="notes" rows="4" maxlength="1000"
                                        placeholder="Beritahu kami preferensi tenda, jadwal, atau kebutuhan khusus Anda"
                                        class="pn-field" :class="errs.notes && 'border-rose-400'">{{ old('notes') }}</textarea>
                                    <p class="mt-1.5 text-xs text-rose-600" x-show="errs.notes" x-text="errs.notes" x-cloak></p>
                                    <p class="mt-1.5 text-xs text-slate-400">Opsional namun berguna untuk permintaan khusus (alat BBQ, dekorasi, dsb.).</p>
                                </div>
                            </div>

                            {{-- Nav --}}
                            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                                <button type="button" x-show="step > 1" x-cloak @click="prev()"
                                    class="pn-btn pn-btn-outline justify-center">
                                    <i class="fas fa-arrow-left" aria-hidden="true"></i> Kembali
                                </button>
                                <span class="hidden text-xs text-slate-400 sm:block" x-show="step === 1">
                                    <i class="fas fa-lock mr-1" aria-hidden="true"></i> Informasi Anda dijaga kerahasiaannya.
                                </span>

                                <button type="button" x-show="step < 4" @click="next()"
                                    class="pn-btn pn-btn-primary justify-center">
                                    Lanjut <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </button>
                                <button type="submit" x-show="step === 4" x-cloak
                                    class="pn-btn pn-btn-primary justify-center">
                                    Kirim Permintaan Booking <i class="fas fa-paper-plane" aria-hidden="true"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Side panel --}}
                <aside class="space-y-5 lg:col-span-4">
                    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                        <p class="pn-eyebrow text-slate-400">Ringkasan</p>
                        <p class="mt-1 text-lg font-bold text-slate-900">Detail booking Anda</p>
                        <dl class="mt-4 space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-3">
                                <dt class="flex items-center gap-2 text-slate-500"><i class="fas fa-user w-4 text-center text-slate-300"></i> Nama</dt>
                                <dd class="max-w-[60%] truncate font-semibold text-slate-800" x-text="form.name || '—'"></dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="flex items-center gap-2 text-slate-500"><i class="fas fa-calendar w-4 text-center text-slate-300"></i> Tanggal</dt>
                                <dd class="font-semibold text-slate-800" x-text="formattedDate || '—'"></dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="flex items-center gap-2 text-slate-500"><i class="fas fa-users w-4 text-center text-slate-300"></i> Tamu</dt>
                                <dd class="font-semibold text-slate-800" x-text="form.guests ? form.guests + ' orang' : '—'"></dd>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <dt class="flex shrink-0 items-center gap-2 text-slate-500"><i class="fas fa-ticket w-4 text-center text-slate-300"></i> Fasilitas</dt>
                                <dd class="break-words text-right font-semibold text-emerald-700" x-text="labels.facility || '—'"></dd>
                            </div>
                        </dl>
                        <a href="{{ route('booking.status') }}" class="mt-5 flex w-full items-center justify-between gap-2 rounded-2xl bg-slate-900 px-5 py-3.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                            <span>Cek Status Booking</span>
                            <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </a>
                    </div>

                    <div class="rounded-3xl border border-emerald-200 bg-emerald-50/70 p-6 text-sm text-emerald-950">
                        <p class="flex items-center gap-2 font-bold"><i class="fas fa-comments text-emerald-600" aria-hidden="true"></i> Butuh bantuan?</p>
                        <p class="mt-2 text-emerald-900/80">Tim reservasi siap membantu memilih paket dan jadwal yang tepat sebelum Anda mengirim permintaan.</p>
                        <a href="{{ $waLink ?? 'https://wa.me/6282145671234' }}" target="_blank" rel="noopener noreferrer"
                            class="pn-btn pn-btn-primary mt-4 w-full justify-center !py-2.5">
                            <i class="fab fa-whatsapp" aria-hidden="true"></i> Chat WhatsApp
                        </a>
                    </div>

                    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                        <p class="flex items-center gap-2 text-sm font-bold text-slate-900"><i class="fas fa-clock text-emerald-600" aria-hidden="true"></i> Setelah mengirim?</p>
                        <ol class="mt-3 space-y-3 text-sm text-slate-600">
                            <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">1</span> Simpan kode booking Anda.</li>
                            <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">2</span> Tim kami menghubungi via WhatsApp maks. 1x24 jam.</li>
                            <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">3</span> Konfirmasi & pembayaran DP menutup reservasi.</li>
                        </ol>
                    </div>
                </aside>
            </div>

            @if ($groupedFacilities->isNotEmpty())
                <div class="mt-10 grid gap-4 md:grid-cols-3">
                    @foreach ($groupedFacilities as $category => $facilities)
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="mb-2 text-xs uppercase tracking-[0.3em] text-slate-400">Kategori</p>
                            <h3 class="text-lg font-semibold text-slate-900">{{ Str::title($category) }}</h3>
                            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                                @foreach ($facilities as $facility)
                                    <li class="flex items-center justify-between gap-2">
                                        <span>{{ $facility->name }}</span>
                                        <span class="shrink-0 text-xs font-medium {{ $facility->capacity_per_day ? 'text-emerald-600' : 'text-slate-400' }}">
                                            {{ $facility->capacity_per_day ? $facility->capacity_per_day . ' slot/hari' : 'Tanpa batas' }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-900">
                    Fasilitas belum tersedia. Silakan hubungi admin sebelum mengirim permintaan booking.
                </div>
            @endif
        </div>
    </section>

    <script>
        function bookingWizard(options = {}) {
            return {
                step: options.step || 1,
                maxStep: options.step || 1,
                stepTitles: { 1: 'Data Diri', 2: 'Jadwal', 3: 'Fasilitas', 4: 'Konfirmasi' },
                errs: {},
                form: { name: '', email: '', phone: '', visit_date: '', guests: '', facility_category: '', facility_id: '', notes: '' },
                labels: { category: '', facility: '' },

                init() {
                    Object.entries(options.serverErrors || {}).forEach(([key, message]) => {
                        if (message) this.errs[key] = message;
                    });

                    const ids = ['name', 'email', 'phone', 'visit_date', 'guests', 'facility_category', 'facility_id', 'notes'];
                    ids.forEach(id => {
                        const el = document.getElementById(id);
                        if (!el) return;
                        const sync = () => {
                            this.form[id] = el.value;
                            if (this.errs[id]) this.errs[id] = '';
                            if (id === 'facility_category' || id === 'facility_id') this.syncLabels();
                        };
                        el.addEventListener('input', sync);
                        el.addEventListener('change', sync);
                        this.form[id] = el.value;
                    });
                    this.syncLabels();
                },

                syncLabels() {
                    const cat = document.getElementById('facility_category');
                    const fac = document.getElementById('facility_id');
                    this.labels.category = (cat && cat.value && cat.selectedOptions[0]) ? cat.selectedOptions[0].dataset.displayLabel : '';
                    const opt = fac && fac.value ? fac.selectedOptions[0] : null;
                    this.labels.facility = opt ? (opt.dataset.baseLabel || '') : '';
                    this.labels.price = opt ? (opt.dataset.priceLabel || '') : '';
                },

                get formattedDate() {
                    if (!this.form.visit_date) return '';
                    try {
                        const d = new Date(this.form.visit_date + 'T00:00:00');
                        return d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                    } catch (e) {
                        return this.form.visit_date;
                    }
                },

                focusFirstError(n) {
                    const keysByStep = {
                        1: ['name', 'email', 'phone'],
                        2: ['visit_date', 'guests'],
                        3: ['facility_category', 'facility_id'],
                        4: ['notes'],
                    };
                    const key = (keysByStep[n] || []).find(k => this.errs[k]);
                    if (!key) return;
                    let el = document.getElementById(key);
                    if (el && el.offsetParent === null) {
                        const wrapper = document.querySelector(`[data-custom-select-target="${key}"] [data-custom-trigger]`);
                        if (wrapper) el = wrapper;
                    }
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        try { el.focus({ preventScroll: true }); } catch (e) { el.focus(); }
                    }
                },

                validateStep(n) {
                    const f = this.form;
                    const today = new Date().toISOString().slice(0, 10);
                    let ok = true;
                    const setErr = (key, msg) => { this.errs[key] = msg; ok = false; };
                    const clear = (keys) => keys.forEach(k => this.errs[k] = '');

                    if (n === 1) {
                        clear(['name', 'email', 'phone']);
                        if (!f.name.trim()) setErr('name', 'Nama lengkap wajib diisi.');
                        if (!f.email.trim()) setErr('email', 'Email wajib diisi.');
                        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.email.trim())) setErr('email', 'Format email tidak valid.');
                        if (!f.phone.trim()) setErr('phone', 'Nomor WhatsApp wajib diisi.');
                    } else if (n === 2) {
                        clear(['visit_date', 'guests']);
                        if (!f.visit_date) setErr('visit_date', 'Tanggal kunjungan wajib dipilih.');
                        else if (f.visit_date < today) setErr('visit_date', 'Tanggal kunjungan tidak boleh di masa lalu.');
                        if (!f.guests) setErr('guests', 'Jumlah tamu wajib dipilih.');
                    } else if (n === 3) {
                        clear(['facility_category', 'facility_id']);
                        if (!f.facility_category) setErr('facility_category', 'Pilih kategori fasilitas terlebih dahulu.');
                        if (!f.facility_id) setErr('facility_id', 'Pilih fasilitas yang akan dibooking.');
                    }

                    if (!ok) this.$nextTick(() => this.focusFirstError(n));
                    return ok;
                },

                next() {
                    if (!this.validateStep(this.step)) return;
                    this.step = Math.min(this.step + 1, 4);
                    this.maxStep = Math.max(this.maxStep, this.step);
                    this.scrollToForm();
                },

                prev() {
                    this.step = Math.max(this.step - 1, 1);
                    this.scrollToForm();
                },

                goTo(n) {
                    if (n > this.maxStep) return;
                    this.step = n;
                    this.scrollToForm();
                },

                scrollToForm() {
                    this.$nextTick(() => {
                        const el = this.$refs.form.closest('[x-data]');
                        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    });
                },

                onSubmit() {
                    for (let n = 1; n <= 3; n++) {
                        if (!this.validateStep(n)) {
                            this.step = n;
                            this.scrollToForm();
                            return;
                        }
                    }
                    this.$refs.form.submit();
                },
            };
        }
    </script>

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

            const setHint = (message, tone = 'text-slate-500') => {
                hint.textContent = message;
                hint.className = `text-xs mt-1.5 ${tone}`;
            };

            const resetPriceHint = () => {
                if (!priceHint) return;
                priceHint.textContent = 'Pilih fasilitas untuk melihat harga referensi.';
                priceHint.className = 'mt-3 text-xs font-medium text-slate-500';
            };

            const setPriceHint = (message, tone = 'text-emerald-600') => {
                if (!priceHint) return;
                priceHint.textContent = message;
                priceHint.className = `mt-3 text-xs font-medium ${tone}`;
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
                    setHint('Belum ada fasilitas di kategori ini. Hubungi admin.', 'text-amber-600');
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
                let tone = 'text-slate-500';

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
                            tone = 'text-rose-600';
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

                setHint('Memeriksa ketersediaan slot...', 'text-sky-600');

                try {
                    const response = await fetch(`${availabilityUrl}?date=${dateInput.value}`);
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data');
                    }
                    const payload = await response.json();
                    applyAvailability(payload.data || {});
                } catch (error) {
                    setHint('Tidak dapat memuat data ketersediaan. Coba lagi nanti.', 'text-rose-600');
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
