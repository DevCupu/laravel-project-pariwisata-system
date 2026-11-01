@extends('layouts.admin')

@section('title', 'Booking')
@section('page-title', 'Manajemen Booking')
@section('page-subtitle', 'Pantau permintaan booking terbaru dari pengunjung')

@section('content')
    @php
        $statusOptions = $statusOptions ?? [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
        ];
        $statusToneMap = config('admin.status_badges', []);
        $statusLabels = $statusOptions;

        $filters = $filters ?? [
            'q' => (string) request('q', ''),
            'status' => $statusFilter ?? request('status'),
            'from' => request('from'),
            'to' => request('to'),
            'sort' => request('sort', 'latest'),
        ];

        $hasFilters = filled($filters['q'])
            || filled($filters['status'])
            || filled($filters['from'])
            || filled($filters['to']);

        $buildUrl = function (array $overrides = []) {
            $query = array_merge(request()->only(['q', 'status', 'from', 'to', 'sort']), $overrides);

            return route('admin.bookings.index', array_filter($query, fn ($value) => filled($value)));
        };

        $waNumberFor = function ($booking) {
            $number = preg_replace('/[^0-9]/', '', $booking->phone ?? '');

            if ($number && str_starts_with($number, '0')) {
                $number = '62' . substr($number, 1);
            }

            return $number;
        };

        $waMessageFor = function ($booking) use ($statusLabels) {
            $statusLabel = $statusLabels[$booking->status] ?? ucfirst($booking->status);
            $statusNotes = match ($booking->status) {
                'pending' => 'Permintaan Anda sedang dalam proses verifikasi. Kami akan segera menghubungi Anda setelah ada pembaruan.',
                'confirmed' => 'Booking Anda telah kami konfirmasi. Mohon siapkan pembayaran/kelengkapan sesuai arahan tim.',
                'cancelled' => 'Pesanan ini dibatalkan sesuai informasi terakhir dari tim kami. Hubungi kami jika ingin menjadwalkan ulang.',
                'completed' => 'Terima kasih, proses kunjungan sudah selesai. Silakan sampaikan masukan bila diperlukan.',
                default => 'Status booking masih dalam pemantauan tim kami.',
            };

            $lines = array_filter([
                'Yth. ' . $booking->name . ',',
                '',
                'Kami dari Dante Pine Enrekang menyampaikan pembaruan terkait permintaan booking Anda.',
                '• Kode Booking: ' . $booking->booking_code,
                '• Tanggal Kunjungan: ' . \Illuminate\Support\Carbon::parse($booking->visit_date)->translatedFormat('d M Y'),
                $booking->facility ? '• Fasilitas: ' . $booking->facility->name : null,
                '• Status: ' . $statusLabel,
                '',
                $statusNotes,
                'Mohon mencantumkan kode booking ini pada bukti pembayaran atau saat konfirmasi keuangan.',
                '',
                'Silakan balas pesan ini jika memerlukan bantuan tambahan. Terima kasih.',
            ]);

            return urlencode(implode("\n", $lines));
        };

        $rowAccent = fn ($status) => match ($status) {
            'pending' => 'border-l-amber-400',
            'confirmed' => 'border-l-sky-400',
            'cancelled' => 'border-l-rose-400',
            'completed' => 'border-l-emerald-400',
            default => 'border-l-transparent',
        };
    @endphp

    <div class="space-y-5">
        {{-- Ringkasan status — sekaligus filter cepat --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-5">
            <x-ui.stat-card label="Total Booking" :value="number_format($stats['total'])" icon="fa-clipboard-list"
                tone="blue" :href="$buildUrl(['status' => null])" :active="blank($filters['status'])" />
            <x-ui.stat-card label="Menunggu" :value="number_format($stats['pending'])" icon="fa-clock" tone="amber"
                :href="$buildUrl(['status' => 'pending'])" :active="$filters['status'] === 'pending'" />
            <x-ui.stat-card label="Dikonfirmasi" :value="number_format($stats['confirmed'])" icon="fa-circle-check"
                tone="green" :href="$buildUrl(['status' => 'confirmed'])" :active="$filters['status'] === 'confirmed'" />
            <x-ui.stat-card label="Dibatalkan" :value="number_format($stats['cancelled'])" icon="fa-circle-xmark"
                tone="red" :href="$buildUrl(['status' => 'cancelled'])" :active="$filters['status'] === 'cancelled'" />
            <x-ui.stat-card label="Selesai" :value="number_format($stats['completed'])" icon="fa-flag-checkered"
                tone="slate" :href="$buildUrl(['status' => 'completed'])" :active="$filters['status'] === 'completed'" />
        </div>

        <x-ui.card title="Daftar Booking" :subtitle="number_format($bookings->total()) . ' permintaan' . ($hasFilters ? ' · filter aktif' : '')" :padded="false">
            @slot('actions')
                <x-ui.button :href="route('admin.bookings.create')">
                    <i class="fas fa-plus text-xs"></i>
                    Booking Baru
                </x-ui.button>
            @endslot

            {{-- Filter --}}
            <form method="GET" action="{{ route('admin.bookings.index') }}"
                class="space-y-3 border-b border-base-300/60 bg-base-200/30 px-4 py-4 sm:px-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative min-w-0 flex-1">
                        <label for="booking-search" class="sr-only">Cari booking</label>
                        <i
                            class="fas fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-base-content/40"></i>
                        <input id="booking-search" type="search" name="q" value="{{ $filters['q'] }}"
                            placeholder="Cari nama, email, kode booking"
                            class="input input-bordered input-sm w-full pl-9 pr-9">
                        @if (filled($filters['q']))
                            <a href="{{ $buildUrl(['q' => null]) }}" aria-label="Hapus pencarian"
                                class="btn btn-ghost btn-xs btn-circle absolute right-1.5 top-1/2 -translate-y-1/2">
                                <i class="fas fa-xmark text-xs"></i>
                            </a>
                        @endif
                    </div>

                    <div class="sm:w-40 sm:shrink-0">
                        <label for="booking-status" class="sr-only">Status booking</label>
                        <select id="booking-status" name="status" class="select select-bordered select-sm w-full py-0">
                            <option value="">Semua status</option>
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:w-48 sm:shrink-0">
                        <label for="booking-sort" class="sr-only">Urutkan</label>
                        <select id="booking-sort" name="sort" class="select select-bordered select-sm w-full py-0">
                            <option value="latest" @selected($filters['sort'] === 'latest')>Terbaru</option>
                            <option value="oldest" @selected($filters['sort'] === 'oldest')>Terlama</option>
                            <option value="visit_asc" @selected($filters['sort'] === 'visit_asc')>Kunjungan terdekat</option>
                            <option value="visit_desc" @selected($filters['sort'] === 'visit_desc')>Kunjungan terjauh</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="flex flex-1 items-center gap-2">
                        <div class="min-w-0 flex-1 sm:max-w-44">
                            <label for="booking-from" class="sr-only">Dari tanggal kunjungan</label>
                            <input id="booking-from" type="date" name="from" value="{{ $filters['from'] }}"
                                class="input input-bordered input-sm w-full py-0" title="Dari tanggal kunjungan">
                        </div>
                        <span class="shrink-0 text-xs font-medium text-base-content/40">s/d</span>
                        <div class="min-w-0 flex-1 sm:max-w-44">
                            <label for="booking-to" class="sr-only">Sampai tanggal kunjungan</label>
                            <input id="booking-to" type="date" name="to" value="{{ $filters['to'] }}"
                                class="input input-bordered input-sm w-full py-0" title="Sampai tanggal kunjungan">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:ml-auto sm:shrink-0">
                        <x-ui.button type="submit" size="sm" class="flex-1 sm:flex-none">
                            <i class="fas fa-filter text-[10px]"></i>
                            Terapkan
                        </x-ui.button>
                        @if ($hasFilters)
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-ghost btn-sm">Reset</a>
                        @endif
                    </div>
                </div>
            </form>

            @if ($hasFilters)
                <div
                    class="flex flex-wrap items-center gap-2 border-b border-base-300/60 bg-base-100 px-4 py-2.5 text-xs sm:px-6">
                    <span class="font-semibold text-base-content/45">Filter aktif:</span>

                    @if (filled($filters['q']))
                        <span class="badge badge-ghost badge-sm gap-1">
                            <i class="fas fa-magnifying-glass text-[9px]"></i>{{ $filters['q'] }}
                        </span>
                    @endif

                    @if (filled($filters['status']))
                        <span class="badge badge-ghost badge-sm">
                            {{ $statusLabels[$filters['status']] ?? $filters['status'] }}
                        </span>
                    @endif

                    @if (filled($filters['from']) || filled($filters['to']))
                        <span class="badge badge-ghost badge-sm">
                            {{ $filters['from'] ? \Illuminate\Support\Carbon::parse($filters['from'])->translatedFormat('d M Y') : '…' }}
                            –
                            {{ $filters['to'] ? \Illuminate\Support\Carbon::parse($filters['to'])->translatedFormat('d M Y') : '…' }}
                        </span>
                    @endif

                    <a href="{{ route('admin.bookings.index') }}"
                        class="ml-auto font-semibold text-primary hover:underline">Hapus semua</a>
                </div>
            @endif

            @if ($bookings->isEmpty())
                <x-ui.empty-state icon="fa-clipboard-list"
                    :title="$hasFilters ? 'Tidak ada booking yang cocok' : 'Belum ada data booking'"
                    :text="$hasFilters
                        ? 'Coba ubah kata kunci atau reset filter untuk melihat semua booking.'
                        : 'Permintaan booking baru dari pengunjung akan muncul di sini.'"
                    class="py-12">
                    @slot('actions')
                        <div class="flex flex-wrap items-center justify-center gap-2">
                            @if ($hasFilters)
                                <x-ui.button variant="secondary" :href="route('admin.bookings.index')">
                                    <i class="fas fa-rotate-left text-xs"></i>
                                    Reset filter
                                </x-ui.button>
                            @endif
                            <x-ui.button :href="route('admin.bookings.create')">
                                <i class="fas fa-plus text-xs"></i>
                                Booking Baru
                            </x-ui.button>
                        </div>
                    @endslot
                </x-ui.empty-state>
            @else
                {{-- Tabel (desktop) --}}
                <div class="hidden overflow-x-auto xl:block">
                    <table class="table w-full">
                        <caption class="sr-only">Daftar booking Dante Pine Enrekang</caption>
                        <thead>
                            <tr
                                class="border-b border-base-300/60 bg-base-200/40 text-[11px] uppercase tracking-wide text-base-content/50">
                                <th scope="col" class="px-4 py-3 text-left font-semibold">Booking</th>
                                <th scope="col" class="px-4 py-3 text-left font-semibold">Kontak</th>
                                <th scope="col" class="px-4 py-3 text-left font-semibold">Kunjungan</th>
                                <th scope="col" class="px-4 py-3 text-left font-semibold">Fasilitas</th>
                                <th scope="col" class="px-4 py-3 text-left font-semibold">Status</th>
                                <th scope="col" class="px-4 py-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200 text-sm">
                            @foreach ($bookings as $booking)
                                @php
                                    $badgeTone = $statusToneMap[$booking->status] ?? 'neutral';
                                    $statusLabel = $statusLabels[$booking->status] ?? ucfirst($booking->status);
                                    $waNumber = $waNumberFor($booking);
                                    $waMessage = $waMessageFor($booking);
                                @endphp
                                <tr class="align-top transition-colors hover:bg-base-200/40">
                                    <td class="border-l-2 {{ $rowAccent($booking->status) }} px-4 py-3.5">
                                        <span
                                            class="inline-block rounded-md bg-base-200 px-1.5 py-0.5 font-mono text-[11px] font-semibold tracking-tight text-base-content/70">
                                            {{ $booking->booking_code }}
                                        </span>
                                        <p class="mt-1 font-semibold text-base-content">{{ $booking->name }}</p>
                                        <p class="text-[11px] text-base-content/50">Dibuat
                                            {{ $booking->created_at->diffForHumans() }}</p>
                                    </td>

                                    <td class="px-4 py-3.5">
                                        <a href="mailto:{{ $booking->email }}"
                                            class="block max-w-[190px] truncate text-base-content/80 transition hover:text-primary"
                                            title="{{ $booking->email }}">{{ $booking->email }}</a>
                                        <a href="tel:{{ $booking->phone }}"
                                            class="mt-0.5 block text-xs text-base-content/50 transition hover:text-primary">{{ $booking->phone }}</a>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3.5">
                                        <p class="font-semibold text-base-content">
                                            {{ \Illuminate\Support\Carbon::parse($booking->visit_date)->translatedFormat('d M Y') }}
                                        </p>
                                        <p class="mt-0.5 text-xs text-base-content/60">{{ $booking->guests }} tamu</p>
                                        <p class="text-[11px] capitalize text-base-content/45">
                                            {{ $booking->package ?: 'Tanpa paket' }}</p>
                                    </td>

                                    <td class="px-4 py-3.5">
                                        @if ($booking->facility)
                                            <p class="font-medium text-base-content">{{ $booking->facility->name }}</p>
                                            <p class="mt-0.5 text-[11px] uppercase tracking-wide text-base-content/45">
                                                {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $booking->facility->category)) }}
                                                · Kuota {{ $booking->facility->capacity_per_day }}/hari
                                            </p>
                                        @else
                                            <p class="text-xs italic text-base-content/40">Fasilitas tidak tercatat</p>
                                        @endif

                                        @if ($booking->notes)
                                            <p class="mt-1 max-w-[220px] truncate rounded-md bg-base-200/70 px-1.5 py-0.5 text-[11px] text-base-content/60"
                                                title="{{ $booking->notes }}">
                                                <i class="fas fa-note-sticky mr-1 text-[9px]"></i>{{ $booking->notes }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3.5">
                                        <div class="flex flex-col items-start gap-2">
                                            <x-ui.badge :tone="$badgeTone" dot>{{ $statusLabel }}</x-ui.badge>

                                            <form method="POST" action="{{ route('admin.bookings.status', $booking) }}"
                                                data-confirm="Ubah status booking {{ $booking->booking_code }}?"
                                                data-status-form class="flex items-center gap-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="q" value="{{ $filters['q'] }}">
                                                <input type="hidden" name="status_filter" value="{{ $filters['status'] }}">
                                                <input type="hidden" name="from" value="{{ $filters['from'] }}">
                                                <input type="hidden" name="to" value="{{ $filters['to'] }}">
                                                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                                                <input type="hidden" name="page" value="{{ $bookings->currentPage() }}">

                                                <select name="status"
                                                    aria-label="Ubah status booking {{ $booking->booking_code }}"
                                                    class="select select-bordered select-sm w-32 py-0" data-status-select>
                                                    @foreach ($statusOptions as $value => $label)
                                                        <option value="{{ $value }}" @selected($booking->status === $value)>
                                                            {{ $label }}</option>
                                                    @endforeach
                                                </select>

                                                <button type="submit" data-status-save
                                                    class="btn btn-primary btn-xs btn-square hidden"
                                                    aria-label="Simpan status booking {{ $booking->booking_code }}">
                                                    <i class="fas fa-check text-[10px]"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3.5">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <x-ui.button size="sm" variant="soft-blue"
                                                :href="route('admin.bookings.edit', $booking)"
                                                title="Edit booking {{ $booking->booking_code }}">
                                                <i class="fas fa-pen text-[10px]"></i>
                                                <span class="hidden xl:inline">Edit</span>
                                            </x-ui.button>

                                            <button type="button" data-copy-booking="{{ $booking->booking_code }}"
                                                class="btn btn-ghost btn-sm btn-square" title="Salin kode booking"
                                                aria-label="Salin kode booking {{ $booking->booking_code }}">
                                                <i class="fas fa-copy text-xs"></i>
                                            </button>

                                            @if ($waNumber)
                                                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}"
                                                    target="_blank" rel="noopener"
                                                    class="btn btn-ghost btn-sm btn-square text-emerald-600 hover:bg-emerald-50"
                                                    title="Kirim WhatsApp ke {{ $booking->name }}"
                                                    aria-label="Kirim WhatsApp ke {{ $booking->name }}">
                                                    <i class="fab fa-whatsapp text-base"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Kartu (mobile & tablet) --}}
                <div class="divide-y divide-base-200 xl:hidden">
                    @foreach ($bookings as $booking)
                        @php
                            $badgeTone = $statusToneMap[$booking->status] ?? 'neutral';
                            $statusLabel = $statusLabels[$booking->status] ?? ucfirst($booking->status);
                            $waNumber = $waNumberFor($booking);
                            $waMessage = $waMessageFor($booking);
                        @endphp
                        <article class="space-y-3 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <span
                                        class="inline-block rounded-md bg-base-200 px-1.5 py-0.5 font-mono text-[11px] font-semibold tracking-tight text-base-content/70">
                                        {{ $booking->booking_code }}
                                    </span>
                                    <p class="mt-1 truncate font-semibold text-base-content">{{ $booking->name }}</p>
                                    <p class="text-[11px] text-base-content/50">Dibuat
                                        {{ $booking->created_at->diffForHumans() }}</p>
                                </div>
                                <x-ui.badge :tone="$badgeTone" dot class="shrink-0">{{ $statusLabel }}</x-ui.badge>
                            </div>

                            <dl class="grid grid-cols-2 gap-x-3 gap-y-2 text-xs">
                                <div>
                                    <dt class="text-[10px] uppercase tracking-wide text-base-content/45">Kunjungan</dt>
                                    <dd class="font-semibold text-base-content">
                                        {{ \Illuminate\Support\Carbon::parse($booking->visit_date)->translatedFormat('d M Y') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] uppercase tracking-wide text-base-content/45">Tamu</dt>
                                    <dd class="font-semibold text-base-content">{{ $booking->guests }} orang</dd>
                                </div>
                                <div class="col-span-2">
                                    <dt class="text-[10px] uppercase tracking-wide text-base-content/45">Fasilitas</dt>
                                    <dd class="text-base-content/80">
                                        {{ optional($booking->facility)->name ?? 'Fasilitas tidak tercatat' }}
                                        <span class="text-base-content/50">· {{ $booking->package ?: 'Tanpa paket' }}</span>
                                    </dd>
                                </div>
                                <div class="col-span-2">
                                    <dt class="text-[10px] uppercase tracking-wide text-base-content/45">Kontak</dt>
                                    <dd class="break-words text-base-content/80">
                                        {{ $booking->email }} · {{ $booking->phone }}</dd>
                                </div>
                                @if ($booking->notes)
                                    <div class="col-span-2">
                                        <dt class="text-[10px] uppercase tracking-wide text-base-content/45">Catatan</dt>
                                        <dd class="rounded-lg bg-base-200/70 px-2 py-1 text-base-content/70">
                                            {{ $booking->notes }}</dd>
                                    </div>
                                @endif
                            </dl>

                            <div class="flex flex-wrap items-center gap-2">
                                <x-ui.button size="sm" variant="soft-blue"
                                    :href="route('admin.bookings.edit', $booking)">
                                    <i class="fas fa-pen text-[10px]"></i> Edit
                                </x-ui.button>

                                <button type="button" data-copy-booking="{{ $booking->booking_code }}"
                                    class="btn btn-ghost btn-sm gap-1.5">
                                    <i class="fas fa-copy text-xs"></i> Salin
                                </button>

                                @if ($waNumber)
                                    <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank"
                                        rel="noopener"
                                        class="btn btn-ghost btn-sm gap-1.5 text-emerald-600 hover:bg-emerald-50">
                                        <i class="fab fa-whatsapp text-sm"></i> WhatsApp
                                    </a>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('admin.bookings.status', $booking) }}"
                                data-confirm="Ubah status booking {{ $booking->booking_code }}?" data-status-form
                                class="flex items-center gap-2 border-t border-base-200 pt-3">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="q" value="{{ $filters['q'] }}">
                                <input type="hidden" name="status_filter" value="{{ $filters['status'] }}">
                                <input type="hidden" name="from" value="{{ $filters['from'] }}">
                                <input type="hidden" name="to" value="{{ $filters['to'] }}">
                                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                                <input type="hidden" name="page" value="{{ $bookings->currentPage() }}">

                                <select name="status"
                                    aria-label="Ubah status booking {{ $booking->booking_code }}"
                                    class="select select-bordered select-sm w-full py-0" data-status-select>
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" @selected($booking->status === $value)>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>

                                <button type="submit" data-status-save
                                    class="btn btn-primary btn-sm hidden shrink-0">Simpan</button>
                            </form>
                        </article>
                    @endforeach
                </div>

                <div class="border-t border-base-300/60 px-4 py-4 sm:px-6">
                    {{ $bookings->withQueryString()->links('vendor.pagination.admin') }}
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const copyText = async (text) => {
                try {
                    await navigator.clipboard.writeText(text);
                    return true;
                } catch (error) {
                    const helper = document.createElement('textarea');
                    helper.value = text;
                    helper.setAttribute('readonly', '');
                    helper.style.position = 'fixed';
                    helper.style.opacity = '0';
                    document.body.appendChild(helper);
                    helper.select();
                    const copied = document.execCommand('copy');
                    helper.remove();
                    return copied;
                }
            };

            document.querySelectorAll('[data-copy-booking]').forEach((button) => {
                button.addEventListener('click', async () => {
                    const code = button.getAttribute('data-copy-booking');
                    if (!code) return;

                    const copied = await copyText(code);

                    if (typeof showToast === 'function') {
                        showToast(
                            copied ? 'Kode booking disalin: ' + code : 'Gagal menyalin kode',
                            copied ? 'success' : 'error'
                        );
                    }
                });
            });

            document.querySelectorAll('[data-status-form]').forEach((form) => {
                const select = form.querySelector('[data-status-select]');
                const save = form.querySelector('[data-status-save]');
                if (!select || !save) return;

                const initialValue = select.value;
                const sync = () => save.classList.toggle('hidden', select.value === initialValue);

                select.addEventListener('change', sync);
                sync();
            });
        });
    </script>
@endpush
