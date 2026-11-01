<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampingPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CampingPackageController extends Controller
{
    public function index(Request $request): View
    {
        $query = CampingPackage::query();

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        if ($request->boolean('featured_only')) {
            $query->where('is_featured', true);
        }

        if ($search = $request->input('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('tagline', 'like', "%{$search}%");
            });
        }

        $packages = $query
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate($this->adminPerPage())
            ->withQueryString();

        $stats = [
            'total' => CampingPackage::count(),
            'active' => CampingPackage::where('is_active', true)->count(),
            'featured' => CampingPackage::where('is_featured', true)->count(),
        ];

        return view('admin.camping-packages.index', [
            'packages' => $packages,
            'stats' => $stats,
            'filters' => [
                'status' => $request->input('status'),
                'featured_only' => $request->boolean('featured_only'),
                'search' => $search,
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.camping-packages.create', [
            'package' => new CampingPackage,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePackage($request);

        $image = $validated['image'] ?? null;
        unset($validated['image']);

        $payload = $this->preparePayload($validated);

        if ($image) {
            $path = $image->store('camping', 'public');
            $payload['image_path'] = $path;
        }

        CampingPackage::create($payload);

        return redirect()
            ->route('admin.camping-packages.index')
            ->with('admin_success', 'Paket camping berhasil ditambahkan.');
    }

    public function edit(CampingPackage $campingPackage): View
    {
        return view('admin.camping-packages.edit', [
            'package' => $campingPackage,
        ]);
    }

    public function update(Request $request, CampingPackage $campingPackage): RedirectResponse
    {
        $validated = $this->validatePackage($request, $campingPackage->id);

        $image = $validated['image'] ?? null;
        unset($validated['image']);

        $payload = $this->preparePayload($validated, $campingPackage->id);

        if ($image) {
            // remove old file if present
            if ($campingPackage->image_path) {
                $old = Str::contains($campingPackage->image_path, '/storage/')
                    ? ltrim(Str::after($campingPackage->image_path, '/storage/'), '/')
                    : ltrim($campingPackage->image_path, '/');

                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }

            $path = $image->store('camping', 'public');
            $payload['image_path'] = $path;
        }

        $campingPackage->update($payload);

        return redirect()
            ->route('admin.camping-packages.edit', $campingPackage)
            ->with('admin_success', 'Paket camping diperbarui.');
    }

    public function destroy(CampingPackage $campingPackage): RedirectResponse
    {
        $campingPackage->delete();

        return redirect()
            ->route('admin.camping-packages.index')
            ->with('admin_success', 'Paket camping dihapus.');
    }

    public function toggleStatus(CampingPackage $campingPackage): RedirectResponse
    {
        $campingPackage->update(['is_active' => ! $campingPackage->is_active]);

        return back()->with('admin_success', 'Status paket camping diperbarui.');
    }

    public function toggleFeatured(CampingPackage $campingPackage): RedirectResponse
    {
        $campingPackage->update(['is_featured' => ! $campingPackage->is_featured]);

        return back()->with('admin_success', 'Highlight paket camping diperbarui.');
    }

    private function validatePackage(Request $request, ?int $ignoreId = null): array
    {
        $uniqueSlugRule = 'unique:camping_packages,slug';
        if ($ignoreId) {
            $uniqueSlugRule .= ','.$ignoreId;
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', $uniqueSlugRule],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'nights' => ['nullable', 'integer', 'min:1', 'max:30'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:500'],
            'includes' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function preparePayload(array $payload, ?int $ignoreId = null): array
    {
        $payload['slug'] = $this->generateSlug($payload, $ignoreId);
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);
        $payload['is_featured'] = (bool) ($payload['is_featured'] ?? false);
        $payload['sort_order'] = $payload['sort_order'] ?? 0;
        $payload['includes'] = $this->normalizeIncludes($payload['includes'] ?? null);

        return $payload;
    }

    private function generateSlug(array $payload, ?int $ignoreId = null): string
    {
        $base = Str::slug(($payload['slug'] ?? null) ?: $payload['name']) ?: 'camping-package';
        $slug = $base;
        $counter = 1;

        while (
            CampingPackage::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function normalizeIncludes(?string $raw): array
    {
        if (! $raw) {
            return [];
        }

        $lines = preg_split("/\r?\n/", $raw);

        return array_values(array_filter(array_map('trim', $lines)));
    }
}
