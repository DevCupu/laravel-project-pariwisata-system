<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $query = Activity::query();

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        if ($search = $request->input('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $activities = $query
            ->orderBy('category')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate($this->adminPerPage())
            ->withQueryString();

        $categoryOptions = Activity::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

        $stats = [
            'total' => Activity::count(),
            'active' => Activity::where('is_active', true)->count(),
            'featured' => Activity::where('is_featured', true)->count(),
        ];

        return view('admin.activities.index', compact('activities', 'categoryOptions', 'stats'))
            ->with([
                'statusFilter' => $request->input('status'),
                'categoryFilter' => $category,
            ]);
    }

    public function create(): View
    {
        return view('admin.activities.create', [
            'activity' => new Activity(),
            'categoryOptions' => $this->categoryOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $payload = $this->ensureSlug($this->validateActivity($request));

        Activity::create($payload);

        return redirect()
            ->route('admin.activities.index')
            ->with('admin_success', 'Aktivitas baru berhasil ditambahkan.');
    }

    public function edit(Activity $activity): View
    {
        return view('admin.activities.edit', [
            'activity' => $activity,
            'categoryOptions' => $this->categoryOptions(),
        ]);
    }

    public function update(Request $request, Activity $activity): RedirectResponse
    {
        $payload = $this->ensureSlug($this->validateActivity($request, $activity->id), $activity->id);

        $activity->update($payload);

        return redirect()
            ->route('admin.activities.edit', $activity)
            ->with('admin_success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete();

        return redirect()
            ->route('admin.activities.index')
            ->with('admin_success', 'Aktivitas dihapus.');
    }

    public function toggleStatus(Activity $activity): RedirectResponse
    {
        $activity->update(['is_active' => ! $activity->is_active]);

        return redirect()
            ->back()
            ->with('admin_success', 'Status aktivitas diperbarui.');
    }

    public function toggleFeatured(Activity $activity): RedirectResponse
    {
        $activity->update(['is_featured' => ! $activity->is_featured]);

        return redirect()->back()->with('admin_success', 'Highlight aktivitas diperbarui.');
    }

    private function validateActivity(Request $request, ?int $ignoreId = null): array
    {
        $uniqueSlugRule = 'unique:activities,slug';
        if ($ignoreId) {
            $uniqueSlugRule .= ',' . $ignoreId;
        }

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', $uniqueSlugRule],
            'category' => ['required', 'string', 'max:80'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'price_from' => ['nullable', 'integer', 'min:0', 'max:10000000'],
            'min_age' => ['nullable', 'integer', 'min:0', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'hero_image_path' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function categoryOptions(): array
    {
        return Activity::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();
    }

    private function ensureSlug(array $payload, ?int $ignoreId = null): array
    {
        $base = Str::slug($payload['slug'] ?: $payload['title']) ?: 'activity';
        $slug = $base;
        $counter = 1;

        while (
            Activity::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        $payload['slug'] = $slug;

        return $payload;
    }
}
