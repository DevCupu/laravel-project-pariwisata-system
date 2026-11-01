<?php

namespace App\Http\Controllers;

use App\Mail\BookingSubmittedMail;
use App\Models\Activity;
use App\Models\Booking;
use App\Models\CampingPackage;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(Request $request): View
    {
        $facilities = Facility::with('bookable')
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $facilities->each(function (Facility $facility) {
            $price = $this->describeFacilityPrice($facility->bookable);
            $facility->booking_price_amount = $price['amount'] ?? null;
            $facility->booking_price_label = $price['label'] ?? null;
        });

        $facilities = $facilities->groupBy('category');

        $prefilledFacility = null;

        if ($request->filled('facility')) {
            $identifier = trim($request->query('facility'));

            $prefilledFacility = Facility::where('is_active', true)
                ->where(function ($query) use ($identifier) {
                    $query->where('slug', $identifier);

                    if (ctype_digit($identifier)) {
                        $query->orWhere('id', (int) $identifier);
                    }
                })
                ->first();
        }

        return view('booking.index', [
            'facilityCategories' => $facilities,
            'prefilledFacility' => $prefilledFacility,
        ]);
    }

    public function status(Request $request): View
    {
        if (! $request->has('booking_code')) {
            return $this->renderStatusView($request);
        }

        $validator = Validator::make($request->all(), [
            'booking_code' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->renderStatusView($request, errors: $validator->errors());
        }

        $validated = $validator->validated();
        $code = Str::upper($validated['booking_code']);

        $booking = Booking::with('facility')->where('booking_code', $code)
            ->when($validated['email'] ?? null, fn ($query, $email) => $query->where('email', $email))
            ->first();

        return $this->renderStatusView($request, $booking, true);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'visit_date' => ['required', 'date', 'after_or_equal:today'],
            'guests' => ['required', 'integer', 'min:1', 'max:50'],
            'facility_category' => ['required', 'string', 'max:50'],
            'facility_id' => ['required', 'integer', 'exists:facilities,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $facility = Facility::where('is_active', true)->find($validated['facility_id']);

        if (! $facility) {
            return back()
                ->withErrors(['facility_id' => 'Fasilitas yang dipilih tidak tersedia.'])
                ->withInput();
        }

        if ($facility->category !== $validated['facility_category']) {
            return back()
                ->withErrors(['facility_category' => 'Fasilitas tidak sesuai dengan kategori yang dipilih.'])
                ->withInput();
        }

        $requestedGuests = (int) $validated['guests'];
        $usedQuota = Booking::where('facility_id', $facility->id)
            ->whereDate('visit_date', $validated['visit_date'])
            ->whereIn('status', Booking::ACTIVE_STATUSES)
            ->sum('guests');

        $capacity = (int) $facility->capacity_per_day;

        if ($capacity > 0 && ($usedQuota + $requestedGuests) > $capacity) {
            $remaining = max($capacity - $usedQuota, 0);
            $message = $remaining > 0
                ? 'Kuota untuk '.$facility->name.' tersisa '.$remaining.' slot pada tanggal tersebut.'
                : 'Kuota untuk '.$facility->name.' pada tanggal tersebut sudah penuh.';

            return back()
                ->withErrors(['facility_id' => $message])
                ->withInput();
        }

        $validated['facility_id'] = $facility->id;
        $validated['package'] = $facility->category;
        $validated['booking_code'] = $this->generateBookingCode();

        $booking = Booking::create($validated);
        $booking->loadMissing('facility');

        try {
            Mail::to($booking->email)->send(new BookingSubmittedMail($booking));
        } catch (\Throwable $exception) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email booking '.$booking->booking_code.': '.$exception->getMessage(), [
                'booking_id' => $booking->id,
                'email' => $booking->email,
            ]);
        }

        return back()
            ->with('booking_success', 'Terima kasih! Permintaan booking Anda telah kami terima dan sedang diproses. Tim kami akan menghubungi melalui WhatsApp atau email dalam 1x24 jam.')
            ->with('booking_code', $booking->booking_code);
    }

    public function availability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $facilities = Facility::where('is_active', true)
            ->withSum([
                'bookings as used_slots' => function ($query) use ($validated) {
                    $query->whereDate('visit_date', $validated['date'])
                        ->whereIn('status', Booking::ACTIVE_STATUSES);
                },
            ], 'guests')
            ->get();

        $payload = $facilities->mapWithKeys(function (Facility $facility) {
            $capacity = (int) $facility->capacity_per_day;
            $used = (int) ($facility->used_slots ?? 0);
            $remaining = $capacity > 0 ? max($capacity - $used, 0) : null;

            return [
                $facility->id => [
                    'capacity' => $capacity,
                    'used' => $used,
                    'remaining' => $remaining,
                    'is_full' => $capacity > 0 ? $remaining === 0 : false,
                    'category' => $facility->category,
                    'name' => $facility->name,
                ],
            ];
        });

        return response()->json(['data' => $payload]);
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'DPE-'.strtoupper(Str::random(6));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }

    private function renderStatusView(Request $request, ?Booking $booking = null, bool $hasSearched = false, ?MessageBag $errors = null): View
    {
        $view = view('booking.status', [
            'booking' => $booking,
            'hasSearched' => $hasSearched,
            'codeInput' => $request->input('booking_code'),
            'emailInput' => $request->input('email'),
        ]);

        if ($errors) {
            $errorBag = new ViewErrorBag;
            $errorBag->put('default', $errors);
            $view->with('errors', $errorBag);
        }

        return $view;
    }

    private function describeFacilityPrice(?Model $bookable): ?array
    {
        if (! $bookable) {
            return null;
        }

        if ($bookable instanceof CampingPackage && $bookable->price) {
            return [
                'amount' => $bookable->price,
                'label' => 'Rp '.number_format($bookable->price, 0, ',', '.').' / paket',
            ];
        }

        if ($bookable instanceof Activity && $bookable->price_from) {
            return [
                'amount' => $bookable->price_from,
                'label' => 'Mulai Rp '.number_format($bookable->price_from, 0, ',', '.').' / orang',
            ];
        }

        return null;
    }
}
