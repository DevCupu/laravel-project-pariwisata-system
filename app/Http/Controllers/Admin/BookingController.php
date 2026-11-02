<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    private const STATUS_OPTIONS = [
        'pending' => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'cancelled' => 'Dibatalkan',
        'completed' => 'Selesai',
    ];

    private const SORT_OPTIONS = ['latest', 'oldest', 'visit_asc', 'visit_desc'];

    public function index(Request $request): View
    {
        $statusOptions = $this->statusOptions();

        $query = Booking::query()->with(['facility', 'menuItems']);

        $statusFilter = $request->input('status');
        if ($statusFilter && ! array_key_exists($statusFilter, $statusOptions)) {
            $statusFilter = null;
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $search = trim((string) $request->input('q'));

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $from = $this->normalizeDate($request->input('from'));
        $to = $this->normalizeDate($request->input('to'));

        if ($from) {
            $query->whereDate('visit_date', '>=', $from);
        }

        if ($to) {
            $query->whereDate('visit_date', '<=', $to);
        }

        $sort = $request->input('sort', 'latest');
        if (! in_array($sort, self::SORT_OPTIONS, true)) {
            $sort = 'latest';
        }

        match ($sort) {
            'oldest' => $query->oldest(),
            'visit_asc' => $query->orderBy('visit_date')->orderBy('id'),
            'visit_desc' => $query->orderByDesc('visit_date')->orderByDesc('id'),
            default => $query->latest(),
        };

        $bookings = $query
            ->paginate($this->adminPerPage())
            ->withQueryString();

        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
        ];

        return view('admin.booking.index', [
            'bookings' => $bookings,
            'stats' => $stats,
            'statusFilter' => $statusFilter,
            'statusOptions' => $statusOptions,
            'filters' => [
                'q' => $search,
                'status' => $statusFilter,
                'from' => $from,
                'to' => $to,
                'sort' => $sort,
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.booking.create', [
            'facilityGroups' => $this->facilityGroups(),
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $statusKeys = array_keys($this->statusOptions());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'visit_date' => ['required', 'date'],
            'guests' => ['required', 'integer', 'min:1', 'max:50'],
            'facility_id' => ['required', Rule::exists('facilities', 'id')],
            'package' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in($statusKeys)],
        ]);

        $facility = Facility::find($validated['facility_id']);

        if (! $facility) {
            return back()
                ->withErrors(['facility_id' => 'Fasilitas tidak ditemukan atau sudah dihapus.'])
                ->withInput();
        }

        if ($message = $this->checkCapacity($facility, $validated['visit_date'], (int) $validated['guests'])) {
            return back()->withErrors(['facility_id' => $message])->withInput();
        }

        if (! $validated['package']) {
            $validated['package'] = $facility->category;
        }

        $validated['booking_code'] = $this->generateBookingCode();

        $booking = Booking::create($validated);
        $booking->loadMissing('facility');

        if ($booking->status === 'confirmed') {
            $this->sendConfirmationEmail($booking);
        }

        return redirect()
            ->route('admin.bookings.index')
            ->with('admin_success', 'Booking baru berhasil dibuat.');
    }

    public function edit(Booking $booking): View
    {
        $booking->load(['facility', 'menuItems']);

        return view('admin.booking.edit', [
            'booking' => $booking,
            'facilityGroups' => $this->facilityGroups($booking->facility_id),
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $statusKeys = array_keys($this->statusOptions());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'visit_date' => ['required', 'date'],
            'guests' => ['required', 'integer', 'min:1', 'max:50'],
            'facility_id' => ['required', Rule::exists('facilities', 'id')],
            'package' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in($statusKeys)],
        ]);

        $facility = Facility::find($validated['facility_id']);

        if (! $facility) {
            return back()
                ->withErrors(['facility_id' => 'Fasilitas tidak ditemukan atau sudah dihapus.'])
                ->withInput();
        }

        if ($message = $this->checkCapacity($facility, $validated['visit_date'], (int) $validated['guests'], $booking)) {
            return back()->withErrors(['facility_id' => $message])->withInput();
        }

        if (! $validated['package']) {
            $validated['package'] = $facility->category;
        }

        $originalStatus = $booking->status;
        $booking->update($validated);
        $booking->loadMissing('facility');

        $emailFailed = false;

        if ($originalStatus !== 'confirmed' && $booking->status === 'confirmed') {
            $emailFailed = ! $this->sendConfirmationEmail($booking);
        }

        $redirect = redirect()
            ->route('admin.bookings.edit', $booking)
            ->with('admin_success', 'Data booking berhasil diperbarui.');

        if ($emailFailed) {
            $redirect->with('admin_error', $this->emailFailureMessage($booking));
        }

        return $redirect;
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys($this->statusOptions()))],
        ]);

        $previousStatus = $booking->status;
        $booking->update($validated);
        $booking->loadMissing('facility');

        $emailFailed = false;

        if ($previousStatus !== 'confirmed' && $booking->status === 'confirmed') {
            $emailFailed = ! $this->sendConfirmationEmail($booking);
        }

        $redirectQuery = array_filter([
            'q' => $request->input('q'),
            'status' => $request->input('status_filter'),
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'sort' => $request->input('sort'),
            'page' => $request->input('page'),
        ], fn ($value) => filled($value));

        $statusLabel = $this->statusOptions()[$validated['status']] ?? ucfirst($validated['status']);

        $redirect = redirect()
            ->route('admin.bookings.index', $redirectQuery)
            ->with('admin_success', 'Status booking '.$booking->booking_code.' diperbarui menjadi '.$statusLabel.'.');

        if ($emailFailed) {
            $redirect->with('admin_error', $this->emailFailureMessage($booking));
        }

        return $redirect;
    }

    public function destroy(Request $request, Booking $booking): RedirectResponse
    {
        $bookingCode = $booking->booking_code;
        $booking->delete();

        $redirectQuery = array_filter(
            $request->only(['q', 'status', 'from', 'to', 'sort']),
            fn ($value) => filled($value)
        );

        return redirect()
            ->route('admin.bookings.index', $redirectQuery)
            ->with('admin_success', 'Booking '.$bookingCode.' berhasil dihapus.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', Rule::in(['status', 'delete'])],
            'booking_ids' => ['required', 'array', 'min:1', 'max:100'],
            'booking_ids.*' => ['required', 'integer', 'distinct', Rule::exists('bookings', 'id')],
            'status' => ['nullable', 'required_if:action,status', Rule::in(array_keys($this->statusOptions()))],
        ]);

        $bookingIds = array_map('intval', $validated['booking_ids']);
        $redirectQuery = array_filter([
            'q' => $request->input('q'),
            'status' => $request->input('status_filter'),
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'sort' => $request->input('sort'),
        ], fn ($value) => filled($value));

        if ($validated['action'] === 'delete') {
            $deletedCount = DB::transaction(
                fn () => Booking::query()->whereKey($bookingIds)->delete()
            );

            return redirect()
                ->route('admin.bookings.index', $redirectQuery)
                ->with('admin_success', $deletedCount.' booking berhasil dihapus.');
        }

        $targetStatus = $validated['status'];
        $newlyConfirmed = collect();
        $updatedCount = DB::transaction(function () use ($bookingIds, $targetStatus, $newlyConfirmed) {
            $bookings = Booking::query()
                ->with(['facility', 'menuItems'])
                ->whereKey($bookingIds)
                ->lockForUpdate()
                ->get();

            $updated = 0;

            foreach ($bookings as $booking) {
                if ($booking->status === $targetStatus) {
                    continue;
                }

                $wasConfirmed = $booking->status === 'confirmed';
                $booking->update(['status' => $targetStatus]);
                $updated++;

                if (! $wasConfirmed && $targetStatus === 'confirmed') {
                    $newlyConfirmed->push($booking);
                }
            }

            return $updated;
        });

        $emailFailures = 0;

        foreach ($newlyConfirmed as $booking) {
            if (! $this->sendConfirmationEmail($booking)) {
                $emailFailures++;
            }
        }

        $statusLabel = $this->statusOptions()[$targetStatus] ?? ucfirst($targetStatus);
        $successMessage = $updatedCount > 0
            ? 'Status '.$updatedCount.' booking berhasil diubah menjadi '.$statusLabel.'.'
            : 'Tidak ada perubahan. Semua booking yang dipilih sudah berstatus '.$statusLabel.'.';

        $redirect = redirect()
            ->route('admin.bookings.index', $redirectQuery)
            ->with('admin_success', $successMessage);

        if ($emailFailures > 0) {
            $redirect->with(
                'admin_error',
                'Status tersimpan, tetapi email konfirmasi gagal dimasukkan ke antrean untuk '.$emailFailures.' booking. Periksa konfigurasi email dan queue.'
            );
        }

        return $redirect;
    }

    private function statusOptions(): array
    {
        return self::STATUS_OPTIONS;
    }

    private function normalizeDate(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        try {
            return \Illuminate\Support\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    private function facilityGroups(?int $includeFacilityId = null)
    {
        $query = Facility::query()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->orderBy('name');

        $facilities = $query->where('is_active', true)->get();

        if ($includeFacilityId && ! $facilities->contains('id', $includeFacilityId)) {
            $selected = Facility::find($includeFacilityId);

            if ($selected) {
                $facilities->push($selected);
                $facilities = $facilities->sortBy(function (Facility $facility) {
                    $sortOrder = str_pad((string) ($facility->sort_order ?? 0), 5, '0', STR_PAD_LEFT);

                    return $facility->category.'|'.$sortOrder.'|'.$facility->name;
                })->values();
            }
        }

        return $facilities->groupBy('category');
    }

    private function checkCapacity(Facility $facility, string $date, int $requestedGuests, ?Booking $ignore = null): ?string
    {
        $capacity = (int) $facility->capacity_per_day;

        if ($capacity <= 0) {
            return null;
        }

        $usedQuota = Booking::where('facility_id', $facility->id)
            ->whereDate('visit_date', $date)
            ->whereIn('status', Booking::ACTIVE_STATUSES)
            ->when($ignore, fn ($query) => $query->where('id', '!=', $ignore->id))
            ->sum('guests');

        if (($usedQuota + $requestedGuests) <= $capacity) {
            return null;
        }

        $remaining = max($capacity - $usedQuota, 0);

        return $remaining > 0
            ? 'Kuota tersisa '.$remaining.' slot pada tanggal tersebut. Kurangi jumlah tamu atau pilih tanggal lain.'
            : 'Kuota pada tanggal tersebut sudah penuh untuk fasilitas ini.';
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'ADM-'.Str::upper(Str::random(6));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }

    private function sendConfirmationEmail(Booking $booking): bool
    {
        try {
            Mail::to($booking->email)->send(new BookingConfirmedMail($booking));

            return true;
        } catch (\Throwable $exception) {
            Log::error('Gagal mengirim email konfirmasi booking '.$booking->booking_code.': '.$exception->getMessage(), [
                'booking_id' => $booking->id,
                'email' => $booking->email,
            ]);

            return false;
        }
    }

    private function emailFailureMessage(Booking $booking): string
    {
        return 'Status booking '.$booking->booking_code.' tersimpan, tetapi email konfirmasi ke '
            .$booking->email.' gagal dimasukkan ke antrean. Periksa konfigurasi queue dan email di server.';
    }
}
