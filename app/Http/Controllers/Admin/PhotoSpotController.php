<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotoSpot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PhotoSpotController extends Controller
{
    public function index(Request $request): View
    {
        $query = PhotoSpot::query();

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        if ($search = $request->input('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('location_text', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $spots = $query
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate($this->adminPerPage())
            ->withQueryString();

        $stats = [
            'total' => PhotoSpot::count(),
            'active' => PhotoSpot::where('is_active', true)->count(),
            'featured' => PhotoSpot::where('is_featured', true)->count(),
        ];

        return view('admin.photo-spots.index', [
            'spots' => $spots,
            'stats' => $stats,
            'statusFilter' => $request->input('status'),
            'searchQuery' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.photo-spots.create', [
            'spot' => new PhotoSpot,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSpot($request);

        $image = $validated['image'] ?? null;
        unset($validated['image']);

        $payload = $this->normalizeBooleanFlags(
            $this->ensureSlug($validated)
        );

        if ($image) {
            $path = $image->store('spots', 'public');
            // store relative path (e.g. "spots/abc.jpg") so Storage::url() works consistently
            $payload['image_path'] = $path;
        }

        PhotoSpot::create($payload);

        return redirect()
            ->route('admin.photo-spots.index')
            ->with('admin_success', 'Spot foto berhasil ditambahkan.');
    }

    public function edit(PhotoSpot $photoSpot): View
    {
        return view('admin.photo-spots.edit', [
            'spot' => $photoSpot,
        ]);
    }

    public function update(Request $request, PhotoSpot $photoSpot): RedirectResponse
    {
        $validated = $this->validateSpot($request, $photoSpot->id);

        $image = $validated['image'] ?? null;
        unset($validated['image']);

        $payload = $this->normalizeBooleanFlags(
            $this->ensureSlug($validated, $photoSpot->id)
        );

        if ($image) {
            // determine old stored path (strip possible leading /storage/ or leading slash)
            if ($photoSpot->image_path) {
                $old = Str::contains($photoSpot->image_path, '/storage/')
                    ? ltrim(Str::after($photoSpot->image_path, '/storage/'), '/')
                    : ltrim($photoSpot->image_path, '/');

                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }

            $path = $image->store('spots', 'public');
            $payload['image_path'] = $path;
        }

        $photoSpot->update($payload);

        return redirect()
            ->route('admin.photo-spots.edit', $photoSpot)
            ->with('admin_success', 'Spot foto berhasil diperbarui.');
    }

    public function destroy(PhotoSpot $photoSpot): RedirectResponse
    {
        $photoSpot->delete();

        return redirect()
            ->route('admin.photo-spots.index')
            ->with('admin_success', 'Spot foto dihapus.');
    }

    public function toggleStatus(PhotoSpot $photoSpot): RedirectResponse
    {
        $photoSpot->update(['is_active' => ! $photoSpot->is_active]);

        return back()->with('admin_success', 'Status spot foto diperbarui.');
    }

    public function toggleFeatured(PhotoSpot $photoSpot): RedirectResponse
    {
        $photoSpot->update(['is_featured' => ! $photoSpot->is_featured]);

        return back()->with('admin_success', 'Highlight spot foto diperbarui.');
    }

    private function validateSpot(Request $request, ?int $ignoreId = null): array
    {
        $uniqueSlugRule = 'unique:photo_spots,slug';
        if ($ignoreId) {
            $uniqueSlugRule .= ','.$ignoreId;
        }

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', $uniqueSlugRule],
            'location_text' => ['nullable', 'string', 'max:255'],
            'best_time' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'tips' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function ensureSlug(array $payload, ?int $ignoreId = null): array
    {
        $base = Str::slug(($payload['slug'] ?? null) ?: $payload['title']) ?: 'photo-spot';
        $slug = $base;
        $counter = 1;

        while (
            PhotoSpot::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        $payload['slug'] = $slug;

        return $payload;
    }

    private function normalizeBooleanFlags(array $payload): array
    {
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);
        $payload['is_featured'] = (bool) ($payload['is_featured'] ?? false);
        $payload['sort_order'] = $payload['sort_order'] ?? 0;

        return $payload;
    }
}
