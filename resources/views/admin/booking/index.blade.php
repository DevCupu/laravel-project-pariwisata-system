@extends('layouts.admin')

@section('title', 'Booking')
@section('page-title', 'Manajemen Booking')
@section('page-subtitle', 'Pantau permintaan booking terbaru dari pengunjung')

@section('content')
    @php
        $statusOptions = $statusOptions ?? [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
            'completed' => 'Selesai',
        ];
        $statusBadgeClasses = [
            'pending' => 'bg-yellow-100 text-yellow-700',
            'confirmed' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-gray-100 text-gray-600',
            'completed' => 'bg-blue-100 text-blue-700',
        ];
    @endphp

    <div class="space-y-5">
        @if (session('admin_success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('admin_success') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500">Total Booking</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-4">
                <p class="text-xs uppercase tracking-wide text-yellow-600">Menunggu</p>
                <p class="mt-2 text-2xl font-semibold text-yellow-700">{{ number_format($stats['pending']) }}</p>
            </div>
            <div class="rounded-2xl border border-green-200 bg-green-50 p-4">
                <p class="text-xs uppercase tracking-wide text-green-600">Terkonfirmasi</p>
                <p class="mt-2 text-2xl font-semibold text-green-700">{{ number_format($stats['confirmed']) }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500">Dibatalkan</p>
                <p class="mt-2 text-2xl font-semibold text-gray-800">{{ number_format($stats['cancelled']) }}</p>
            </div>
            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                <p class="text-xs uppercase tracking-wide text-blue-600">Selesai</p>
                <p class="mt-2 text-2xl font-semibold text-blue-700">{{ number_format($stats['completed']) }}</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div
                class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Booking</h3>
                    <p class="text-sm text-gray-500">Total {{ $bookings->total() }} permintaan</p>
                </div>
                <div class="flex w-full flex-col gap-3 md:w-auto md:flex-row md:items-center">
                    <form method="GET" class="w-full md:w-80">
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Cari nama, email, atau kode booking"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 pl-10 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </form>
                    <form method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <select name="status" onchange="this.form.submit()"
                            class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected($statusFilter === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                    <a href="{{ route('admin.bookings.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                        <i class="fas fa-plus text-xs"></i>
                        Booking Baru
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[920px] w-full border-collapse text-[12px] text-gray-800">
                    <thead class="bg-slate-50 text-[10px] font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="border-b border-slate-200 px-3 py-2 text-left">Kode</th>
                            <th class="border-b border-slate-200 px-3 py-2 text-left">Pemesan</th>
                            <th class="border-b border-slate-200 px-3 py-2 text-left">Kontak</th>
                            <th class="border-b border-slate-200 px-3 py-2 text-left">Tanggal</th>
                            <th class="border-b border-slate-200 px-3 py-2 text-left">Paket</th>
                            <th class="border-b border-slate-200 px-3 py-2 text-left">Fasilitas</th>
                            <th class="border-b border-slate-200 px-3 py-2 text-left">Tamu</th>
                            <th class="border-b border-slate-200 px-3 py-2 text-left">Status</th>
                            <th class="border-b border-slate-200 px-3 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @php
                            $statusLabels = $statusOptions;
                        @endphp
                        @forelse ($bookings as $booking)
                            <tr class="transition hover:bg-slate-50">
                                <td class="border-b border-slate-100 px-3 py-2.5 align-top font-semibold text-gray-900 whitespace-nowrap">{{ $booking->booking_code }}</td>
                                <td class="border-b border-slate-100 px-3 py-2.5 align-top">
                                    <div class="space-y-0.5">
                                        <p class="font-medium text-gray-900 text-[13px]">{{ $booking->name }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                                    </div>
                                </td>
                                <td class="border-b border-slate-100 px-3 py-2.5 align-top">
                                    <div class="space-y-0.5 text-[12px]">
                                        <p class="text-gray-800">{{ $booking->email }}</p>
                                        <p class="text-gray-500">{{ $booking->phone }}</p>
                                    </div>
                                </td>
                                <td class="border-b border-slate-100 px-3 py-2.5 align-top whitespace-nowrap">
                                    <p class="text-[13px] font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($booking->visit_date)->translatedFormat('d M Y') }}</p>
                                </td>
                                <td class="border-b border-slate-100 px-3 py-2.5 align-top text-gray-700 capitalize">
                                    {{ $booking->package ?? '-' }}
                                </td>
                                <td class="border-b border-slate-100 px-3 py-2.5 align-top text-[12px] text-gray-700">
                                    @if ($booking->facility)
                                        <p class="font-semibold text-gray-900">{{ $booking->facility->name }}</p>
                                        <p class="text-[10px] uppercase tracking-wide text-gray-500">
                                            {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $booking->facility->category)) }}
                                        </p>
                                        <p class="text-[10px] text-gray-500">Kuota {{ $booking->facility->capacity_per_day }} slot/hari</p>
                                    @else
                                        <p class="text-[10px] text-gray-500">Fasilitas tidak tercatat</p>
                                    @endif

                                    @if ($booking->notes)
                                        <p class="mt-1 rounded-lg bg-gray-50 px-2 py-1 text-[11px] text-gray-600">
                                            Catatan: {{ \Illuminate\Support\Str::limit($booking->notes, 80) }}
                                        </p>
                                    @endif
                                </td>
                                <td class="border-b border-slate-100 px-3 py-2.5 align-top font-semibold text-gray-900">
                                    {{ $booking->guests }} org
                                </td>
                                <td class="border-b border-slate-100 px-3 py-2.5 align-top">
                                    @php
                                        $badgeClass = $statusBadgeClasses[$booking->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $badgeClass }}">
                                        {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="border-b border-slate-100 px-3 py-2.5 align-top">
                                    <div class="space-y-2">
                                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}"
                                            class="flex flex-col gap-1.5 text-[11px] md:flex-row md:items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="q" value="{{ request('q') }}">
                                            <input type="hidden" name="status_filter" value="{{ $statusFilter }}">
                                            <select name="status"
                                                class="rounded-lg border border-gray-200 px-2 py-1.5 text-[12px] focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                                @foreach ($statusOptions as $value => $label)
                                                    <option value="{{ $value }}" @selected($booking->status === $value)>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit"
                                                class="rounded-lg bg-blue-600 px-2.5 py-1.5 text-[11px] font-semibold text-white hover:bg-blue-700">
                                                Update
                                            </button>
                                        </form>

                                        @php
                                            $waNumber = preg_replace('/[^0-9]/', '', $booking->phone ?? '');
                                            if ($waNumber && str_starts_with($waNumber, '0')) {
                                                $waNumber = '62' . substr($waNumber, 1);
                                            }
                                            $statusFriendlyLabel = $statusLabels[$booking->status] ?? ucfirst($booking->status);
                                            $statusNotes = match ($booking->status) {
                                                'pending' => 'Permintaan Anda sedang dalam proses verifikasi. Kami akan segera menghubungi Anda setelah ada pembaruan.',
                                                'confirmed' => 'Booking Anda telah kami konfirmasi. Mohon siapkan pembayaran/kelengkapan sesuai arahan tim.',
                                                'cancelled' => 'Pesanan ini dibatalkan sesuai informasi terakhir dari tim kami. Hubungi kami jika ingin menjadwalkan ulang.',
                                                'completed' => 'Terima kasih, proses kunjungan sudah selesai. Silakan sampaikan masukan bila diperlukan.',
                                                default => 'Status booking masih dalam pemantauan tim kami.',
                                            };
                                            $friendlyDate = \Carbon\Carbon::parse($booking->visit_date)->translatedFormat('d M Y');
                                            $facilityLine = $booking->facility ? '• Fasilitas: ' . $booking->facility->name : null;
                                            $waLines = array_filter([
                                                'Yth. ' . $booking->name . ',',
                                                '',
                                                'Kami dari Dante Pine Enrekang menyampaikan pembaruan terkait permintaan booking Anda.',
                                                '• Kode Booking: ' . $booking->booking_code,
                                                '• Tanggal Kunjungan: ' . $friendlyDate,
                                                $facilityLine,
                                                '• Status: ' . $statusFriendlyLabel,
                                                '',
                                                $statusNotes,
                                                'Mohon mencantumkan kode booking ini pada bukti pembayaran atau saat konfirmasi keuangan.',
                                                '',
                                                'Silakan balas pesan ini jika memerlukan bantuan tambahan. Terima kasih.',
                                            ]);
                                            $waMessage = urlencode(implode("\n", $waLines));
                                        @endphp

                                        <div class="flex flex-wrap gap-1 text-[11px]">
                                            <a href="{{ route('admin.bookings.edit', $booking) }}"
                                                class="inline-flex items-center gap-1 rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1.5 font-semibold text-blue-700 hover:bg-blue-100">
                                                <i class="fas fa-pen text-[10px]"></i>
                                                Edit
                                            </a>
                                            <button type="button"
                                                class="copy-booking-code inline-flex items-center gap-1 rounded-lg border border-gray-200 px-2.5 py-1.5 font-semibold text-gray-700 hover:bg-gray-50"
                                                data-booking-code="{{ $booking->booking_code }}">
                                                <i class="fas fa-copy text-[10px]"></i>
                                                Salin Kode
                                            </button>
                                            @if ($waNumber)
                                                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1 rounded-lg border border-green-200 bg-green-50 px-2.5 py-1.5 font-semibold text-green-700 hover:bg-green-100">
                                                    <i class="fab fa-whatsapp text-[12px]"></i>
                                                    WhatsApp
                                                </a>
                                            @endif
                                        </div>

                                        <details class="rounded-xl border border-gray-100 bg-gray-50 px-2.5 py-1.5 text-[11px] text-gray-600">
                                            <summary class="cursor-pointer font-semibold text-gray-700">Ringkasan cepat</summary>
                                            <div class="mt-1.5 space-y-1">
                                                <p>Kode: <span class="font-mono text-gray-900">{{ $booking->booking_code }}</span></p>
                                                <p>Kontak: {{ $booking->phone ?: '-' }} / {{ $booking->email }}</p>
                                                <p>Catatan: {{ $booking->notes ? \Illuminate\Support\Str::limit($booking->notes, 100) : 'Tidak ada' }}</p>
                                            </div>
                                        </details>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada data booking.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 px-6 py-4">
                {{ $bookings->withQueryString()->links('vendor.pagination.admin') }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.copy-booking-code');
            if (!buttons.length) {
                return;
            }

            const showToast = (message) => {
                const toast = document.createElement('div');
                toast.textContent = message;
                toast.className = 'fixed bottom-6 right-6 z-50 rounded-xl bg-gray-900/90 px-4 py-2 text-sm font-semibold text-white shadow-lg animate-fade-in';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2500);
            };

            buttons.forEach((btn) => {
                btn.addEventListener('click', async () => {
                    const code = btn.dataset.bookingCode;
                    if (!code) {
                        return;
                    }
                    try {
                        await navigator.clipboard.writeText(code);
                        showToast('Kode booking disalin: ' + code);
                    } catch (error) {
                        showToast('Gagal menyalin kode');
                    }
                });
            });
        });
    </script>
@endsection
