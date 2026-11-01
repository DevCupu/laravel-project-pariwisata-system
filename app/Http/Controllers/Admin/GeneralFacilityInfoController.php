<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralFacilityInfo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GeneralFacilityInfoController extends Controller
{
    public function index(Request $request): View
    {
        $query = GeneralFacilityInfo::query();

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        if ($search = $request->input('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('availability_note', 'like', "%{$search}%");
            });
        }

        $facilities = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate($this->adminPerPage())
            ->withQueryString();

        return view('admin.general-facilities.index', [
            'facilities' => $facilities,
            'searchQuery' => $search,
            'statusFilter' => $request->input('status'),
            'stats' => [
                'total' => GeneralFacilityInfo::count(),
                'active' => GeneralFacilityInfo::where('is_active', true)->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.general-facilities.create', [
            'facility' => new GeneralFacilityInfo,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $payload = $this->preparePayload(
            $this->validateFacility($request)
        );

        GeneralFacilityInfo::create($payload);

        return redirect()
            ->route('admin.general-facilities.index')
            ->with('admin_success', 'Fasilitas umum berhasil ditambahkan.');
    }

    public function edit(GeneralFacilityInfo $generalFacility): View
    {
        return view('admin.general-facilities.edit', [
            'facility' => $generalFacility,
        ]);
    }

    public function update(Request $request, GeneralFacilityInfo $generalFacility): RedirectResponse
    {
        $payload = $this->preparePayload(
            $this->validateFacility($request, $generalFacility->id),
            $generalFacility->id
        );

        $generalFacility->update($payload);

        return redirect()
            ->route('admin.general-facilities.edit', $generalFacility)
            ->with('admin_success', 'Fasilitas umum diperbarui.');
    }

    public function destroy(GeneralFacilityInfo $generalFacility): RedirectResponse
    {
        $generalFacility->delete();

        return redirect()
            ->route('admin.general-facilities.index')
            ->with('admin_success', 'Fasilitas umum dihapus.');
    }

    public function toggleStatus(GeneralFacilityInfo $generalFacility): RedirectResponse
    {
        $generalFacility->update(['is_active' => ! $generalFacility->is_active]);

        return back()->with('admin_success', 'Status fasilitas umum diperbarui.');
    }

    private function validateFacility(Request $request, ?int $ignoreId = null): array
    {
        $uniqueSlugRule = 'unique:general_facilities_info,slug';
        if ($ignoreId) {
            $uniqueSlugRule .= ','.$ignoreId;
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', $uniqueSlugRule],
            'icon' => ['nullable', 'string', 'max:64'],
            'description' => ['nullable', 'string'],
            'availability_note' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function preparePayload(array $payload, ?int $ignoreId = null): array
    {
        $payload['slug'] = $this->generateSlug($payload, $ignoreId);
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);
        $payload['sort_order'] = $payload['sort_order'] ?? 0;

        return $payload;
    }

    private function generateSlug(array $payload, ?int $ignoreId = null): string
    {
        $base = Str::slug(($payload['slug'] ?? null) ?: $payload['name']) ?: 'facility';
        $slug = $base;
        $counter = 1;

        while (
            GeneralFacilityInfo::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
