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
        $menuItems = $menuItems ?? collect();
        $oldMenuQuantities = old('menu_items', []);
        $oldMenuQuantities = is_array($oldMenuQuantities) ? $oldMenuQuantities : [];
        $menuQuantities = $menuItems->mapWithKeys(fn ($item) => [
            $item->id => (int) ($oldMenuQuantities[$item->id] ?? 0),
        ])->all();
        $initialMenuOption = old('menu_option', 'without_menu');
        $menuItemsForWizard = $menuItems->map(fn ($item) => [
            'id' => (int) $item->id,
            'name' => $item->name,
            'price' => (int) $item->price,
        ])->values();
        $nestedMenuError = collect($errors->getBag('default')->getMessages())
            ->first(fn ($messages, $key) => Str::startsWith($key, 'menu_items.'));
        $menuItemsError = $errors->first('menu_items') ?: ($nestedMenuError[0] ?? null);
        $hasMenuErrors = $errors->has('menu_option') || filled($menuItemsError);

        $wizardStep = 1;
        if ($errors->any()) {
            if ($errors->hasAny(['name', 'email', 'phone'])) {
                $wizardStep = 1;
            } elseif ($errors->hasAny(['visit_date', 'guests'])) {
                $wizardStep = 2;
            } elseif ($errors->hasAny(['facility_category', 'facility_id', 'menu_option', 'menu_items']) || $hasMenuErrors) {
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
            'menu_option' => $errors->first('menu_option'),
            'menu_items' => $menuItemsError,
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
                menuOption: {{ \Illuminate\Support\Js::from($initialMenuOption) }},
                menuItems: {{ \Illuminate\Support\Js::from($menuItemsForWizard) }},
                menuQuantities: {{ \Illuminate\Support\Js::from($menuQuantities) }},
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
                                <p class="mt-0.5 text-emerald-800/80">Kategori otomatis diset ke <span class="font-semibold">{{ Str::title($prefilled->category) }}</span>. Silakan lengkapi langkah berikutnya untuk melihat kapasitas tamu.</p>
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
                                    <p class="text-sm text-slate-500">Tanggal kunjungan minimal hari ini. Kapasitas tamu akan dicek sesuai tanggal yang dipilih.</p>
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
                                    <p class="text-sm text-slate-500">Pilih kategori, lalu pilih fasilitas yang cocok. Sisa kapasitas mengikuti tanggal dan jumlah tamu Anda.</p>
                                </header>

                                <div class="space-y-7">
                                    <div>
                                        <p class="pn-field-label">Kategori fasilitas <span class="text-rose-500">*</span></p>
                                        <select id="facility_category" name="facility_category" required class="sr-only" tabindex="-1" aria-hidden="true"
                                            aria-describedby="facility-category-help facility-category-error"
                                            :aria-invalid="errs.facility_category ? 'true' : 'false'"
                                            :class="errs.facility_category && 'border-rose-400 focus:border-rose-500 focus:shadow-rose-500/10'">
                                            <option value="" data-display-label="Pilih kategori" @selected(blank($selectedCategory))>Pilih kategori</option>
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
                                        <div class="grid gap-3 sm:grid-cols-3" role="group" aria-label="Pilih kategori fasilitas"
                                            aria-describedby="facility-category-help facility-category-error"
                                            :aria-invalid="errs.facility_category ? 'true' : 'false'">
                                            @foreach ($groupedFacilities as $category => $facilities)
                                                @php
                                                    $categoryMinPrice = $facilities->pluck('booking_price_amount')->filter()->min();
                                                    $categoryIcon = match (Str::slug($category)) {
                                                        'cafe' => 'fa-mug-hot',
                                                        'camping' => 'fa-campground',
                                                        'wahana' => 'fa-person-hiking',
                                                        default => 'fa-layer-group',
                                                    };
                                                @endphp
                                                <button type="button" data-category-button data-category="{{ $category }}"
                                                    aria-pressed="{{ $selectedCategory === $category ? 'true' : 'false' }}"
                                                    class="rounded-2xl border border-slate-200 bg-white p-4 text-left shadow-sm transition hover:border-emerald-300 hover:bg-emerald-50/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/40 {{ $selectedCategory === $category ? 'border-emerald-500 bg-emerald-50/50 ring-2 ring-emerald-500/20' : '' }}">
                                                    <span class="flex items-start justify-between gap-3">
                                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                                                            <i class="fas {{ $categoryIcon }}" aria-hidden="true"></i>
                                                        </span>
                                                        <span data-category-check class="{{ $selectedCategory === $category ? '' : 'hidden' }} flex h-6 w-6 items-center justify-center rounded-full bg-emerald-600 text-xs text-white">
                                                            <i class="fas fa-check" aria-hidden="true"></i>
                                                        </span>
                                                    </span>
                                                    <span class="mt-3 block font-semibold text-slate-900">{{ Str::title(str_replace('-', ' ', $category)) }}</span>
                                                    <span class="mt-1 block text-xs text-slate-500">
                                                        {{ $facilities->count() }} pilihan
                                                        @if ($categoryMinPrice)
                                                            · mulai Rp {{ number_format($categoryMinPrice, 0, ',', '.') }}
                                                        @endif
                                                    </span>
                                                </button>
                                            @endforeach
                                        </div>
                                        <p id="facility-category-help" class="mt-1.5 text-xs text-slate-500">Pilihan fasilitas akan mengikuti kategori yang Anda pilih.</p>
                                        <p id="facility-category-error" class="mt-1.5 text-xs text-rose-600" x-show="errs.facility_category" x-text="errs.facility_category" aria-live="polite" x-cloak></p>
                                    </div>

                                    <div>
                                        <p class="pn-field-label">Pilih fasilitas <span class="text-rose-500">*</span></p>
                                        <select id="facility_id" name="facility_id" required class="sr-only" tabindex="-1" aria-hidden="true"
                                            aria-describedby="facility-help facility-availability-hint facility-error"
                                            :aria-invalid="errs.facility_id ? 'true' : 'false'"
                                            :class="errs.facility_id && 'border-rose-400 focus:border-rose-500 focus:shadow-rose-500/10'"
                                            @disabled(blank($selectedCategory) || ! $groupedFacilities->has($selectedCategory))
                                            data-availability-url="{{ route('booking.availability') }}">
                                            <option value="" @selected(blank($selectedFacilityId))>Pilih kategori terlebih dahulu</option>
                                            @foreach ($groupedFacilities as $category => $facilities)
                                                @foreach ($facilities as $facility)
                                                    @php
                                                        $baseLabel = trim($facility->name . ($facility->booking_price_label ? ' — ' . $facility->booking_price_label : ''));
                                                        $capacityLabel = $facility->capacity_per_day > 0
                                                            ? 'Maks. ' . $facility->capacity_per_day . ' tamu/hari'
                                                            : 'Tidak dibatasi per hari';
                                                        $optionDisplayLabel = $baseLabel . ' · ' . $capacityLabel;
                                                    @endphp
                                                    <option value="{{ $facility->id }}" data-category="{{ $category }}"
                                                        data-capacity="{{ $facility->capacity_per_day }}"
                                                        data-base-label="{{ $baseLabel }}"
                                                        data-description="{{ $facility->description }}"
                                                        data-price="{{ $facility->booking_price_amount ?? '' }}"
                                                        data-price-label="{{ $facility->booking_price_label ?? '' }}"
                                                        data-display-label="{{ $optionDisplayLabel }}"
                                                        {{ (string) $selectedFacilityId === (string) $facility->id ? 'selected' : '' }}>
                                                        {{ $optionDisplayLabel }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                        <div id="facility-card-grid" class="grid gap-3 sm:grid-cols-2" role="group" aria-label="Pilih fasilitas yang akan dibooking"
                                            aria-describedby="facility-help facility-availability-hint facility-error"
                                            :aria-invalid="errs.facility_id ? 'true' : 'false'">
                                            @foreach ($groupedFacilities as $category => $facilities)
                                                @foreach ($facilities as $facility)
                                                    @php
                                                        $capacityLabel = $facility->capacity_per_day > 0
                                                            ? 'Maks. ' . $facility->capacity_per_day . ' tamu/hari'
                                                            : 'Tidak dibatasi per hari';
                                                        $isSelectedFacility = (string) $selectedFacilityId === (string) $facility->id;
                                                        $isFacilityVisible = $selectedCategory === $category;
                                                    @endphp
                                                    <button type="button" data-facility-card data-facility-id="{{ $facility->id }}"
                                                        data-category="{{ $category }}" data-capacity-label="{{ $capacityLabel }}"
                                                        aria-pressed="{{ $isSelectedFacility ? 'true' : 'false' }}"
                                                        @disabled(! $isFacilityVisible)
                                                        @if (! $isFacilityVisible) hidden @endif
                                                        class="w-full rounded-2xl border border-slate-200 bg-white p-4 text-left shadow-sm transition hover:border-emerald-300 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/40 disabled:cursor-not-allowed disabled:opacity-50 {{ $isSelectedFacility ? 'border-emerald-500 bg-emerald-50/50 ring-2 ring-emerald-500/20' : '' }}">
                                                        <span class="flex items-start justify-between gap-3">
                                                            <span class="font-semibold text-slate-900">{{ $facility->name }}</span>
                                                            <span data-facility-check class="{{ $isSelectedFacility ? '' : 'hidden' }} flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-xs text-white">
                                                                <i class="fas fa-check" aria-hidden="true"></i>
                                                            </span>
                                                        </span>
                                                        <span class="mt-1 block min-h-10 text-xs leading-relaxed text-slate-500">
                                                            {{ $facility->description ?: 'Detail fasilitas dapat ditanyakan kepada tim kami.' }}
                                                        </span>
                                                        <span class="mt-3 flex flex-wrap items-center gap-2">
                                                            <span data-facility-capacity class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">{{ $capacityLabel }}</span>
                                                            @if ($facility->booking_price_label)
                                                                <span class="text-xs font-semibold text-emerald-700">{{ $facility->booking_price_label }}</span>
                                                            @endif
                                                        </span>
                                                    </button>
                                                @endforeach
                                            @endforeach
                                        </div>
                                        <p id="facility-empty-state" class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-5 text-center text-sm text-slate-500" @if (filled($selectedCategory)) hidden @endif>
                                            Pilih kategori di atas untuk melihat fasilitas yang tersedia.
                                        </p>
                                        <p id="facility-help" class="mt-1.5 text-xs text-slate-500">Kapasitas dihitung dalam jumlah tamu untuk tanggal yang dipilih.</p>
                                        <p id="facility-availability-hint" class="mt-1.5 text-sm text-slate-600" aria-live="polite">Pilih fasilitas untuk melihat sisa kapasitas.</p>
                                        <p id="facility-error" class="mt-1.5 text-xs text-rose-600" x-show="errs.facility_id" x-text="errs.facility_id" aria-live="polite" x-cloak></p>
                                    </div>
                                </div>

                                <section x-show="isCafe" x-cloak class="space-y-5 rounded-2xl border border-amber-200 bg-amber-50/70 p-5 sm:p-6" aria-labelledby="cafe-menu-title">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-amber-700">Reservasi kafe</p>
                                        <h3 id="cafe-menu-title" class="mt-1 text-lg font-bold text-slate-900">Ingin pesan menu sekarang?</h3>
                                        <p class="mt-1 text-sm text-slate-600">Anda bisa memilih menu sekarang atau reservasi meja saja dan pesan langsung di kafe.</p>
                                    </div>

                                    <fieldset id="menu_option" aria-describedby="menu-option-error">
                                        <legend class="sr-only">Pilih reservasi dengan menu atau tanpa menu</legend>
                                        <div class="grid gap-3 sm:grid-cols-2">
                                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 transition has-[:checked]:border-emerald-500 has-[:checked]:ring-2 has-[:checked]:ring-emerald-500/20">
                                                <input type="radio" name="menu_option" value="without_menu" x-model="form.menu_option" @checked($initialMenuOption === 'without_menu') @change="errs.menu_option = ''; errs.menu_items = '';" :required="isCafe" :disabled="!isCafe" class="mt-1 accent-emerald-600">
                                                <span>
                                                    <span class="block font-semibold text-slate-900">Reservasi meja saja</span>
                                                    <span class="mt-1 block text-xs leading-relaxed text-slate-500">Pilih menu dan bayar langsung di kafe.</span>
                                                </span>
                                            </label>
                                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 transition has-[:checked]:border-emerald-500 has-[:checked]:ring-2 has-[:checked]:ring-emerald-500/20">
                                                <input type="radio" name="menu_option" value="with_menu" x-model="form.menu_option" @checked($initialMenuOption === 'with_menu') @change="errs.menu_option = ''; errs.menu_items = '';" :required="isCafe" :disabled="!isCafe || {{ $menuItems->isEmpty() ? 'true' : 'false' }}" class="mt-1 accent-emerald-600">
                                                <span>
                                                    <span class="block font-semibold text-slate-900">Reservasi dengan menu</span>
                                                    <span class="mt-1 block text-xs leading-relaxed text-slate-500">Pilih makanan atau minuman yang ingin disiapkan.</span>
                                                </span>
                                            </label>
                                        </div>
                                    </fieldset>
                                    <p id="menu-option-error" class="text-xs text-rose-600" x-show="errs.menu_option" x-text="errs.menu_option" aria-live="polite" x-cloak></p>

                                    <div x-show="form.menu_option === 'with_menu'" x-cloak class="space-y-4">
                                        @if ($menuItems->isNotEmpty())
                                            <div class="grid gap-3 sm:grid-cols-2">
                                                @foreach ($menuItems as $menuItem)
                                                    <article class="rounded-xl border border-slate-200 bg-white p-4">
                                                        <div class="flex items-start justify-between gap-3">
                                                            <div>
                                                                <p class="font-semibold text-slate-900">{{ $menuItem->name }}</p>
                                                                @if ($menuItem->short_description)
                                                                    <p class="mt-1 text-xs leading-relaxed text-slate-500">{{ $menuItem->short_description }}</p>
                                                                @endif
                                                                <p class="mt-2 text-sm font-semibold text-emerald-700">Rp {{ number_format($menuItem->price, 0, ',', '.') }}</p>
                                                            </div>
                                                            <label class="w-24 shrink-0">
                                                                <span class="block text-xs font-semibold text-slate-600">Jumlah</span>
                                                                <input type="number" id="{{ $loop->first ? 'menu_items' : 'menu_items_'.$menuItem->id }}"
                                                                    name="menu_items[{{ $menuItem->id }}]" min="0" max="50" inputmode="numeric"
                                                                    x-model.number="menuQuantities[{{ $menuItem->id }}]" @input="errs.menu_items = ''"
                                                                    :disabled="!isCafe || form.menu_option !== 'with_menu'"
                                                                    class="pn-field mt-1 !px-2 !py-2 text-center" :class="errs.menu_items && 'border-rose-400'" aria-label="Jumlah {{ $menuItem->name }}"
                                                                    value="{{ $menuQuantities[$menuItem->id] ?? 0 }}">
                                                            </label>
                                                        </div>
                                                    </article>
                                                @endforeach
                                            </div>
                                            <p class="text-xs text-slate-500">Isi jumlah untuk menu yang dipilih; biarkan 0 untuk menu yang tidak dipesan.</p>
                                        @else
                                            <div class="rounded-xl border border-amber-200 bg-white px-4 py-3 text-sm text-amber-800">
                                                Menu kafe belum tersedia untuk dipesan online. Silakan pilih “Reservasi meja saja” dan pesan langsung di lokasi.
                                            </div>
                                        @endif
                                        <p class="text-xs text-rose-600" x-show="errs.menu_items" x-text="errs.menu_items" aria-live="polite" x-cloak></p>
                                    </div>
                                </section>
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

                                <div x-show="isCafe" x-cloak class="rounded-2xl border border-emerald-100 bg-emerald-50/60 px-5 py-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Pesanan kafe</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800" x-text="menuSummary"></p>
                                    <p x-show="form.menu_option === 'with_menu' && selectedMenuItems.length" class="mt-1 text-xs font-medium text-emerald-700" x-text="'Perkiraan total menu: ' + menuSubtotalLabel"></p>
                                    <button type="button" class="mt-2 text-xs font-semibold text-emerald-700 hover:underline" @click="goTo(3)">Ubah pilihan menu</button>
                                </div>

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
                                            {{ $facility->capacity_per_day ? $facility->capacity_per_day . ' tamu/hari' : 'Tidak dibatasi' }}
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
                stepTitles: { 1: 'Data Diri', 2: 'Jadwal', 3: 'Fasilitas & menu', 4: 'Konfirmasi' },
                errs: {},
                form: { name: '', email: '', phone: '', visit_date: '', guests: '', facility_category: '', facility_id: '', menu_option: options.menuOption || 'without_menu', notes: '' },
                labels: { category: '', facility: '' },
                isCafe: false,
                menuItems: options.menuItems || [],
                menuQuantities: options.menuQuantities || {},

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
                    this.isCafe = cat?.value === 'cafe';
                },

                get selectedMenuItems() {
                    return this.menuItems
                        .filter(item => Number(this.menuQuantities[item.id] || 0) > 0)
                        .map(item => {
                            const quantity = Number(this.menuQuantities[item.id]);
                            return { ...item, quantity, subtotal: quantity * Number(item.price) };
                        });
                },

                get menuSubtotal() {
                    return this.selectedMenuItems.reduce((total, item) => total + item.subtotal, 0);
                },

                get menuSubtotalLabel() {
                    return 'Rp ' + this.menuSubtotal.toLocaleString('id-ID');
                },

                get menuSummary() {
                    if (this.form.menu_option !== 'with_menu') return 'Reservasi meja saja; menu dipesan langsung di kafe.';
                    if (this.selectedMenuItems.length === 0) return 'Belum ada menu yang dipilih.';
                    return this.selectedMenuItems.map(item => `${item.name} x ${item.quantity}`).join(', ');
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
                        3: ['facility_category', 'facility_id', 'menu_option', 'menu_items'],
                        4: ['notes'],
                    };
                    const key = (keysByStep[n] || []).find(k => this.errs[k]);
                    if (!key) return;
                    let el;
                    if (key === 'facility_category') {
                        const categoryButtons = Array.from(document.querySelectorAll('[data-category-button]'));
                        el = categoryButtons.find(button => button.dataset.category === this.form.facility_category) || categoryButtons[0];
                    } else if (key === 'facility_id') {
                        const facilityButtons = Array.from(document.querySelectorAll('[data-facility-card]'));
                        el = facilityButtons.find(button => button.dataset.facilityId === this.form.facility_id && !button.disabled)
                            || facilityButtons.find(button => !button.hidden && !button.disabled);
                    } else {
                        el = key === 'menu_option'
                            ? document.querySelector('input[name="menu_option"]:not(:disabled)')
                            : document.getElementById(key);
                    }
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
                        clear(['facility_category', 'facility_id', 'menu_option', 'menu_items']);
                        if (!f.facility_category) setErr('facility_category', 'Pilih kategori fasilitas terlebih dahulu.');
                        else if (!f.facility_id) setErr('facility_id', 'Pilih fasilitas yang akan dibooking.');
                        else if (document.getElementById('facility_id')?.selectedOptions?.[0]?.disabled) {
                            setErr('facility_id', 'Kapasitas tidak cukup untuk jumlah tamu yang dipilih. Ubah jumlah tamu, fasilitas, atau tanggal.');
                        }

                        if (this.isCafe && !f.menu_option) {
                            setErr('menu_option', 'Pilih reservasi dengan menu atau tanpa menu.');
                        } else if (this.isCafe && f.menu_option === 'with_menu') {
                            const hasInvalidQuantity = this.menuItems.some(item => {
                                const value = this.menuQuantities[item.id];
                                const quantity = Number(value);
                                return value === '' || !Number.isInteger(quantity) || quantity < 0 || quantity > 50;
                            });

                            if (hasInvalidQuantity) {
                                setErr('menu_items', 'Masukkan jumlah bulat antara 0 dan 50. Gunakan 0 jika tidak memilih menu tersebut.');
                            } else if (this.selectedMenuItems.length === 0) {
                                setErr('menu_items', 'Pilih setidaknya satu menu untuk melanjutkan.');
                            }
                        }
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
            const guestsSelect = document.getElementById('guests');
            const hint = document.getElementById('facility-availability-hint');
            const availabilityUrl = facilitySelect?.dataset?.availabilityUrl;
            const facilityOptions = Array.from(facilitySelect.options).filter(opt => opt.dataset.category);
            const categoryButtons = Array.from(document.querySelectorAll('[data-category-button]'));
            const facilityCards = Array.from(document.querySelectorAll('[data-facility-card]'));
            const facilityEmptyState = document.getElementById('facility-empty-state');

            const syncCategoryButtons = () => {
                categoryButtons.forEach(button => {
                    const selected = button.dataset.category === categorySelect.value;
                    button.setAttribute('aria-pressed', selected ? 'true' : 'false');
                    button.classList.toggle('border-slate-200', !selected);
                    button.classList.toggle('border-emerald-500', selected);
                    button.classList.toggle('bg-white', !selected);
                    button.classList.toggle('bg-emerald-50/50', selected);
                    button.classList.toggle('ring-2', selected);
                    button.classList.toggle('ring-emerald-500/20', selected);
                    button.querySelector('[data-category-check]')?.classList.toggle('hidden', !selected);
                });
            };

            const syncFacilityCards = () => {
                const selectedCategory = categorySelect.value;
                const selectedId = facilitySelect.value;

                facilityCards.forEach(button => {
                    const option = facilityOptions.find(item => item.value === button.dataset.facilityId);
                    const visible = Boolean(selectedCategory) && button.dataset.category === selectedCategory;
                    const selected = button.dataset.facilityId === selectedId;
                    button.hidden = !visible;
                    button.disabled = !visible || !option || option.disabled;
                    button.setAttribute('aria-pressed', selected ? 'true' : 'false');
                    button.classList.toggle('border-slate-200', !selected);
                    button.classList.toggle('border-emerald-500', selected);
                    button.classList.toggle('bg-white', !selected);
                    button.classList.toggle('bg-emerald-50/50', selected);
                    button.classList.toggle('ring-2', selected);
                    button.classList.toggle('ring-emerald-500/20', selected);
                    button.querySelector('[data-facility-check]')?.classList.toggle('hidden', !selected);

                    const capacityLabel = button.querySelector('[data-facility-capacity]');
                    if (capacityLabel) {
                        const unavailable = Boolean(option?.disabled);
                        const hasAvailability = Boolean(option?.dataset?.availabilityText);
                        capacityLabel.textContent = option?.dataset?.availabilityText || button.dataset.capacityLabel;
                        capacityLabel.classList.toggle('bg-rose-50', unavailable);
                        capacityLabel.classList.toggle('text-rose-700', unavailable);
                        capacityLabel.classList.toggle('bg-emerald-50', hasAvailability && !unavailable);
                        capacityLabel.classList.toggle('text-emerald-700', hasAvailability && !unavailable);
                        capacityLabel.classList.toggle('bg-slate-100', !hasAvailability && !unavailable);
                        capacityLabel.classList.toggle('text-slate-600', !hasAvailability && !unavailable);
                    }
                });

                if (facilityEmptyState) {
                    const hasFacilities = facilityCards.some(button => button.dataset.category === selectedCategory);
                    facilityEmptyState.hidden = Boolean(selectedCategory) && hasFacilities;
                }
            };

            const setHint = (message, tone = 'text-slate-500') => {
                hint.textContent = message;
                hint.className = `mt-1.5 text-xs ${tone}`;
            };

            const filterFacilities = () => {
                const selectedCategory = categorySelect.value;
                let hasVisible = false;
                facilitySelect.disabled = !selectedCategory;

                facilityOptions.forEach(option => {
                    const match = !selectedCategory || option.dataset.category === selectedCategory;
                    option.hidden = !match;
                    if (!match && option.selected) {
                        facilitySelect.value = '';
                        facilitySelect.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    if (match) {
                        hasVisible = true;
                    }
                });

                syncCategoryButtons();
                syncFacilityCards();

                if (!selectedCategory) {
                    setHint('Pilih kategori terlebih dahulu untuk menampilkan fasilitas.');
                } else if (!hasVisible) {
                    setHint('Belum ada fasilitas di kategori ini. Hubungi admin.', 'text-amber-600');
                } else if (!dateInput.value) {
                    setHint('Pilih tanggal kunjungan untuk mengecek kapasitas tamu.');
                } else if (!facilitySelect.value) {
                    setHint('Pilih fasilitas untuk melihat kapasitas yang tersisa.');
                }
            };

            const applyAvailability = (data) => {
                const selectedId = facilitySelect.value;
                let selectedMessage = 'Pilih fasilitas untuk melihat kapasitas tamu yang tersisa.';
                let tone = 'text-slate-500';

                facilityOptions.forEach(option => {
                    const info = data?.[option.value];
                    if (!info) return;

                    const baseLabel = option.dataset.baseLabel || option.textContent.split(' (')[0];
                    const isFull = info.is_full;
                    const canAccommodateGuests = info.can_accommodate_guests;
                    const requestedGuests = Math.max(Number(guestsSelect?.value || 1), 1);
                    option.disabled = !canAccommodateGuests;
                    let capacityText = 'Tidak dibatasi per hari';
                    if (isFull) {
                        capacityText = 'Kapasitas penuh';
                    } else if (!canAccommodateGuests) {
                        capacityText = `Sisa ${info.remaining} tamu (perlu ${requestedGuests})`;
                    } else if (info.remaining !== null) {
                        capacityText = `${info.remaining} tamu tersisa`;
                    }
                    option.dataset.availabilityText = capacityText;
                    option.textContent = `${baseLabel} · ${capacityText}`;

                    if (option.value === selectedId) {
                        if (!canAccommodateGuests && isFull) {
                            selectedMessage = `Kapasitas ${info.name} penuh pada tanggal tersebut. Pilih fasilitas atau tanggal lain.`;
                            tone = 'text-rose-600';
                        } else if (!canAccommodateGuests) {
                            selectedMessage = `${info.name}: hanya tersisa ${info.remaining} tamu, belum cukup untuk ${requestedGuests} orang. Ubah jumlah tamu, fasilitas, atau tanggal.`;
                            tone = 'text-rose-600';
                        } else if (info.remaining !== null) {
                            selectedMessage = `${info.name}: tersisa ${info.remaining} dari ${info.capacity} kapasitas tamu pada tanggal ini.`;
                            tone = 'text-emerald-600';
                        } else {
                            selectedMessage = `${info.name}: kapasitas tamu tidak dibatasi per hari.`;
                            tone = 'text-emerald-600';
                        }
                    }
                });

                syncFacilityCards();
                setHint(selectedMessage, tone);
            };

            const fetchAvailability = async () => {
                if (!availabilityUrl || !dateInput.value) {
                    return;
                }

                setHint('Memeriksa kapasitas tamu...', 'text-sky-600');

                try {
                    const requestedGuests = Math.max(Number(guestsSelect?.value || 1), 1);
                    const response = await fetch(`${availabilityUrl}?date=${dateInput.value}&guests=${requestedGuests}`);
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
            });

            categoryButtons.forEach(button => {
                button.addEventListener('click', () => {
                    if (categorySelect.value === button.dataset.category) return;

                    categorySelect.value = button.dataset.category;
                    categorySelect.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });

            facilityCards.forEach(button => {
                button.addEventListener('click', () => {
                    const option = facilityOptions.find(item => item.value === button.dataset.facilityId);
                    if (!option || option.disabled || button.hidden) return;

                    facilitySelect.value = button.dataset.facilityId;
                    facilitySelect.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });

            facilitySelect?.addEventListener('change', () => {
                syncFacilityCards();
                fetchAvailability();
            });
            dateInput?.addEventListener('change', () => fetchAvailability());
            guestsSelect?.addEventListener('change', () => fetchAvailability());

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
            initCustomSelects();
        });
    </script>
@endsection
