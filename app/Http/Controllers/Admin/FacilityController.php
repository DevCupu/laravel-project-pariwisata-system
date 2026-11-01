<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date'],
        ]);
        $today = now()->toDateString();
        $nextActiveBookingDate = Booking::query()
            ->whereIn('status', Booking::ACTIVE_STATUSES)
            ->whereDate('visit_date', '>=', $today)
            ->min('visit_date');
        $selectedDate = $validated['date'] ?? $nextActiveBookingDate ?? $today;

        $query = Facility::query();

        $statusFilter = $request->input('status');
        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $usageForDate = function ($bookingQuery) use ($selectedDate) {
            $bookingQuery->whereDate('visit_date', $selectedDate)
                ->whereIn('status', Booking::ACTIVE_STATUSES);
        };
        $confirmedForDate = fn ($bookingQuery) => $bookingQuery
            ->whereDate('visit_date', $selectedDate)
            ->where('status', 'confirmed');
        $pendingForDate = fn ($bookingQuery) => $bookingQuery
            ->whereDate('visit_date', $selectedDate)
            ->where('status', 'pending');

        $facilities = $query
            ->withSum(['bookings as booked_guests' => $usageForDate], 'guests')
            ->withCount([
                'bookings as confirmed_booking_count' => $confirmedForDate,
                'bookings as pending_booking_count' => $pendingForDate,
            ])
            ->orderBy('category')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate($this->adminPerPage())
            ->withQueryString();

        $categoryOptions = Facility::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

        $stats = [
            'total' => Facility::count(),
            'active' => Facility::where('is_active', true)->count(),
            'inactive' => Facility::where('is_active', false)->count(),
        ];

        return view('admin.facilities.index', [
            'facilities' => $facilities,
            'categoryOptions' => $categoryOptions,
            'stats' => $stats,
            'statusFilter' => $statusFilter,
            'categoryFilter' => $category,
            'selectedDate' => $selectedDate,
        ]);
    }

    public function create(): View
    {
        return view('admin.facilities.create', [
            'facility' => new Facility,
            'categoryOptions' => $this->categoryOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateFacility($request);

        $payload = $this->mapPayload($validated);
        $payload['slug'] = $this->generateSlug($payload['name']);

        Facility::create($payload);

        return redirect()
            ->route('admin.facilities.index')
            ->with('admin_success', 'Fasilitas baru berhasil ditambahkan.');
    }

    public function edit(Facility $facility): View
    {
        return view('admin.facilities.edit', [
            'facility' => $facility,
            'categoryOptions' => $this->categoryOptions(),
        ]);
    }

    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $validated = $this->validateFacility($request, $facility->id);

        $payload = $this->mapPayload($validated);

        if ($facility->name !== $payload['name']) {
            $payload['slug'] = $this->generateSlug($payload['name'], $facility->id);
        }

        $facility->update($payload);

        return redirect()
            ->route('admin.facilities.edit', $facility)
            ->with('admin_success', 'Data fasilitas berhasil diperbarui.');
    }

    public function destroy(Request $request, Facility $facility): RedirectResponse
    {
        $facilityName = $facility->name;
        $facility->delete();

        $redirectQuery = array_filter(
            $request->only(['q', 'status', 'category', 'date']),
            fn ($value) => filled($value)
        );

        return redirect()
            ->route('admin.facilities.index', $redirectQuery)
            ->with('admin_success', 'Fasilitas '.$facilityName.' berhasil dihapus.');
    }

    public function toggleStatus(Request $request, Facility $facility): RedirectResponse
    {
        $facility->update(['is_active' => ! $facility->is_active]);

        return redirect()
            ->route('admin.facilities.index', $request->only('q', 'status', 'category', 'date', 'page'))
            ->with('admin_success', 'Status fasilitas '.$facility->name.' diperbarui.');
    }

    private function validateFacility(Request $request, ?int $facilityId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'capacity_per_day' => ['required', 'integer', 'min:0', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:5000'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function mapPayload(array $data): array
    {
        return [
            'name' => $data['name'],
            'category' => Str::slug($data['category']) ?: 'umum',
            'description' => $data['description'] ?? null,
            'capacity_per_day' => $data['capacity_per_day'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : true,
        ];
    }

    private function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'facility';
        $slug = $base;
        $counter = 1;

        while (
            Facility::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function categoryOptions(): array
    {
        return Facility::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();
    }
}
