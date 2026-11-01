@extends('layouts.public')

@section('title', 'Cek Status Booking')
@section('meta_description', 'Pantau status permintaan booking Anda di Dante Pine Enrekang menggunakan kode booking, tanpa perlu masuk akun.')

@section('content')
    @include('partials.public.page-hero', [
        'title' => 'Status Reservasi Dante Pine',
        'subtitle' => 'Masukkan kode booking yang tercantum pada bukti permintaan untuk melihat progres penanganan terbaru. Panel ini dapat digunakan kapan pun tanpa perlu masuk ke akun.',
        'eyebrow' => 'Panel Pemantauan',
        'breadcrumb' => [['label' => 'Cek Status Booking']],
    ])

    <section class="pn-section pn-section--tint">
        <div class="pn-container space-y-10">
            @if ($errors->any())
                <div class="rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 shadow-sm">
                    <p class="font-semibold mb-2">Periksa kembali data yang diisi:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-2">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-200/80 p-6 md:p-8 space-y-6">
                    <div>
                        <p class="text-xs font-semibold tracking-[0.3em] text-gray-500 uppercase mb-2">Formulir Validasi</p>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-1">Cek Status Booking</h2>
                        <p class="text-sm text-gray-600">Gunakan kode booking yang Anda dapatkan setelah mengirim formulir.</p>
                    </div>
                    <form method="GET" action="{{ route('booking.status') }}" class="space-y-5">
                        <div>
                            <label class="block text-xs font-semibold tracking-widest text-gray-500 uppercase mb-2" for="booking_code">Kode
                                Booking</label>
                            <input type="text" id="booking_code" name="booking_code"
                                value="{{ old('booking_code', $codeInput) }}" required
                                class="w-full rounded-2xl border border-gray-300 px-4 py-3 uppercase tracking-wide text-gray-900 focus:ring-1 focus:ring-gray-900 focus:border-gray-900"
                                placeholder="Contoh: DPE-1A2B3C">
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <a href="{{ route('booking.form') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-arrow-left text-xs"></i>
                                Kembali ke Form
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800">
                                Lihat Status Booking
                                <i class="fas fa-search text-xs"></i>
                            </button>
                        </div>
                    </form>
                    <div class="text-xs text-gray-500 flex items-center gap-2">
                        <i class="fas fa-info-circle text-gray-700"></i>
                        Status diperbarui otomatis segera setelah admin menyelesaikan peninjauan internal.
                    </div>
                </div>

                <div class="space-y-5">
                    @if (!$hasSearched)
                        <div
                            class="h-full rounded-3xl border border-dashed border-gray-300 bg-white p-6 flex flex-col justify-center text-center text-gray-500">
                            <i class="fas fa-ticket-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="font-semibold text-gray-700 mb-2">Masukkan kode booking untuk melihat status terbaru.
                            </p>
                            <p class="text-sm">Anda bisa menemukan kode tersebut di notifikasi sukses setelah mengirim
                                formulir booking.</p>
                        </div>
                    @elseif ($booking)
                        @php
                            $statusAppearances = [
                                'pending' => [
                                    'panel' => 'bg-gradient-to-br from-amber-50 via-white to-white border border-amber-100 text-amber-900 shadow-amber-100/50',
                                    'icon' => 'fas fa-clock text-amber-500',
                                    'label' => 'Sedang Ditinjau',
                                ],
                                'confirmed' => [
                                    'panel' => 'bg-gradient-to-br from-emerald-50 via-white to-white border border-emerald-100 text-emerald-900 shadow-emerald-100/50',
                                    'icon' => 'fas fa-check-circle text-emerald-500',
                                    'label' => 'Sudah Dikonfirmasi',
                                ],
                                'cancelled' => [
                                    'panel' => 'bg-gradient-to-br from-stone-50 via-white to-white border border-stone-200 text-stone-700 shadow-stone-100/40',
                                    'icon' => 'fas fa-times-circle text-stone-500',
                                    'label' => 'Dibatalkan',
                                ],
                                'completed' => [
                                    'panel' => 'bg-gradient-to-br from-slate-50 via-white to-white border border-slate-200 text-slate-900 shadow-slate-100/50',
                                    'icon' => 'fas fa-flag-checkered text-slate-500',
                                    'label' => 'Selesai',
                                ],
                            ];
                            $badgeStyles = [
                                'pending' => 'bg-amber-100 text-amber-800',
                                'confirmed' => 'bg-emerald-100 text-emerald-800',
                                'cancelled' => 'bg-stone-100 text-stone-700',
                                'completed' => 'bg-slate-100 text-slate-800',
                            ];
                            $statusLabels = [
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'cancelled' => 'Cancelled',
                                'completed' => 'Selesai',
                            ];
                            $currentAppearance = $statusAppearances[$booking->status] ?? $statusAppearances['pending'];
                        @endphp
                        <div class="rounded-3xl border border-gray-200 bg-white shadow-xl overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold tracking-[0.3em] text-gray-500 uppercase mb-1">Kode Booking</p>
                                    <p class="text-2xl font-semibold text-gray-900 tracking-wide">
                                        {{ $booking->booking_code }}</p>
                                </div>
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $badgeStyles[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                                </span>
                            </div>
                            <div class="px-6 py-6 space-y-6">
                                <div class="rounded-3xl px-5 py-4 shadow-md {{ $currentAppearance['panel'] }}">
                                    <div class="flex items-start gap-4">
                                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/70 text-2xl">
                                            <i class="{{ $currentAppearance['icon'] }}"></i>
                                        </span>
                                        <div class="flex-1 space-y-1">
                                            <p class="text-xs font-semibold uppercase tracking-wide">
                                                {{ $currentAppearance['label'] }}
                                            </p>
                                            <p class="text-sm leading-relaxed">
                                                @if ($booking->status === 'pending')
                                                    Permintaan Anda sedang ditinjau oleh tim admin. Mohon tunggu maksimal 1x24 jam.
                                                @elseif ($booking->status === 'confirmed')
                                                    Booking dikonfirmasi! Tim kami akan segera menghubungi melalui WhatsApp atau email untuk detail lanjutan.
                                                @elseif ($booking->status === 'completed')
                                                    Reservasi Anda sudah selesai dan slot telah digunakan. Terima kasih telah berkunjung, semoga pengalaman Anda menyenangkan!
                                                @else
                                                    Booking dibatalkan. Silakan hubungi admin jika membutuhkan bantuan lebih lanjut.
                                                @endif
                                            </p>
                                            <p class="text-xs opacity-70">Terakhir diperbarui {{ $booking->updated_at?->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-4 text-sm text-gray-600 md:grid-cols-2">
                                    <div class="rounded-2xl bg-gray-50 px-4 py-3">
                                        <p class="text-xs uppercase tracking-wide text-gray-500">Nama Pemesan</p>
                                        <p class="text-base text-gray-900 font-semibold">{{ $booking->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $booking->email }} • {{ $booking->phone }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-gray-50 px-4 py-3">
                                        <p class="text-xs uppercase tracking-wide text-gray-500">Tanggal Kunjungan</p>
                                        <p class="text-base text-gray-900 font-semibold">
                                            {{ \Carbon\Carbon::parse($booking->visit_date)->translatedFormat('d M Y') }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $booking->guests }} tamu • Paket
                                            {{ $booking->package ? ucfirst($booking->package) : 'Belum dipilih' }}</p>
                                    </div>
                                </div>

                                @if ($booking->package === 'cafe' || $booking->facility?->category === 'cafe')
                                    <div class="rounded-2xl border border-amber-100 bg-amber-50 px-4 py-4 text-sm text-amber-950">
                                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide">Reservasi kafe</p>
                                        @if ($booking->menuItems->isNotEmpty())
                                            <ul class="space-y-1">
                                                @foreach ($booking->menuItems as $menuItem)
                                                    <li class="flex justify-between gap-3">
                                                        <span>{{ $menuItem->menu_name }} x {{ $menuItem->quantity }}</span>
                                                        <span class="shrink-0 font-medium">Rp {{ number_format($menuItem->unit_price * $menuItem->quantity, 0, ',', '.') }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p>Meja saja; Anda dapat memilih menu dan memesan langsung di kafe.</p>
                                        @endif
                                    </div>
                                @endif

                                @if ($booking->notes)
                                    <div
                                        class="rounded-2xl bg-blue-50 border border-blue-100 px-4 py-3 text-sm text-blue-900">
                                        <p class="text-xs uppercase tracking-wide font-semibold mb-1">Catatan Anda</p>
                                        <p>{{ $booking->notes }}</p>
                                    </div>
                                @endif

                                <div class="rounded-2xl border border-gray-200 bg-white px-4 py-4 text-sm text-gray-600">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Butuh Bantuan?</p>
                                            <p>Hubungi admin kami di WhatsApp resmi dan siapkan kode booking di atas.</p>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            <a href="https://wa.me/6281234567890" target="_blank"
                                                class="inline-flex items-center gap-2 rounded-2xl px-3.5 py-2 text-xs font-semibold text-gray-900 border border-gray-300">
                                                <i class="fab fa-whatsapp text-sm"></i>
                                                WhatsApp Admin
                                            </a>
                                            <a href="mailto:info@pariwisata.test"
                                                class="inline-flex items-center gap-2 rounded-2xl px-3.5 py-2 text-xs font-semibold text-gray-600 border border-gray-200">
                                                <i class="fas fa-envelope text-xs"></i>
                                                Kirim Email
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-3xl border border-rose-100 bg-rose-50 p-6 space-y-3 text-rose-700">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-exclamation-circle text-2xl"></i>
                                <div>
                                    <p class="font-semibold">Booking tidak ditemukan</p>
                                    <p class="text-sm">Periksa kembali kode yang dimasukkan atau sertakan email untuk
                                        memastikan validasi berhasil.</p>
                                </div>
                            </div>
                            <p class="text-sm">Jika merasa sudah benar, hubungi admin melalui WhatsApp resmi untuk
                                pengecekan manual.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-200 p-6 md:p-8 shadow-sm">
                <p class="text-xs font-semibold tracking-[0.3em] text-gray-500 uppercase mb-3">Referensi Status</p>
                <h3 class="text-2xl font-semibold text-gray-900 mb-6">Tahapan Pemrosesan Booking</h3>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 text-sm text-gray-600">
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3">
                        <p class="font-semibold text-gray-900">Pending</p>
                        <p>Permintaan diterima dan sedang diproses admin.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3">
                        <p class="font-semibold text-gray-900">Confirmed</p>
                        <p>Booking disetujui, tim akan menghubungi Anda.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3">
                        <p class="font-semibold text-gray-900">Cancelled</p>
                        <p>Booking tidak dapat diproses atau dibatalkan.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3">
                        <p class="font-semibold text-gray-900">Selesai</p>
                        <p>Aktivitas selesai dan slot kembali tersedia untuk tanggal berikutnya.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
