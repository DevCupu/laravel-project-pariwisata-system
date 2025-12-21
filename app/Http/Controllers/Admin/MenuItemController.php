<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    public function index(Request $request): View
    {
        $query = MenuItem::query();

        if ($section = $request->input('section')) {
            $query->where('section', $section);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($search = $request->input('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $items = $query
            ->orderBy('section')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate($this->adminPerPage())
            ->withQueryString();

        $sections = MenuItem::query()->select('section')->distinct()->orderBy('section')->pluck('section')->toArray();
        $types = MenuItem::query()->select('type')->distinct()->orderBy('type')->pluck('type')->toArray();

        return view('admin.menu-items.index', [
            'items' => $items,
            'sectionOptions' => $sections,
            'typeOptions' => $types,
        ]);
    }

    public function create(): View
    {
        return view('admin.menu-items.create', [
            'menuItem' => new MenuItem(),
            'sectionOptions' => $this->sectionOptions(),
            'typeOptions' => $this->typeOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateMenuItem($request);

        $image = $validated['image'] ?? null;
        unset($validated['image']);

        $payload = $this->ensureSlug($validated);

        if ($image) {
            $path = $image->store('menu', 'public');
            $payload['image_path'] = $path;
        }

        MenuItem::create($payload);

        return redirect()
            ->route('admin.menu-items.index')
            ->with('admin_success', 'Menu kafe berhasil ditambahkan.');
    }

    public function edit(MenuItem $menuItem): View
    {
        return view('admin.menu-items.edit', [
            'menuItem' => $menuItem,
            'sectionOptions' => $this->sectionOptions(),
            'typeOptions' => $this->typeOptions(),
        ]);
    }

    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $validated = $this->validateMenuItem($request, $menuItem->id);

        $image = $validated['image'] ?? null;
        unset($validated['image']);

        $payload = $this->ensureSlug($validated, $menuItem->id);

        if ($image) {
            // delete old file if exists
            if ($menuItem->image_path) {
                $old = Str::contains($menuItem->image_path, '/storage/')
                    ? ltrim(Str::after($menuItem->image_path, '/storage/'), '/')
                    : ltrim($menuItem->image_path, '/');

                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }

            $path = $image->store('menu', 'public');
            $payload['image_path'] = $path;
        }

        $menuItem->update($payload);

        return redirect()
            ->route('admin.menu-items.edit', $menuItem)
            ->with('admin_success', 'Menu kafe diperbarui.');
    }

    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->delete();

        return redirect()
            ->route('admin.menu-items.index')
            ->with('admin_success', 'Menu kafe dihapus.');
    }

    public function toggleAvailability(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->update(['is_available' => ! $menuItem->is_available]);

        return redirect()->back()->with('admin_success', 'Ketersediaan menu diperbarui.');
    }

    private function validateMenuItem(Request $request, ?int $ignoreId = null): array
    {
        $uniqueSlugRule = 'unique:menu_items,slug';
        if ($ignoreId) {
            $uniqueSlugRule .= ',' . $ignoreId;
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', $uniqueSlugRule],
            'section' => ['required', 'string', 'max:80'],
            'type' => ['required', 'string', 'max:40'],
            'price' => ['required', 'integer', 'min:0', 'max:10000000'],
            'badge' => ['nullable', 'string', 'max:50'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'is_available' => ['nullable', 'boolean'],
        ]);
    }

    private function sectionOptions(): array
    {
        return MenuItem::query()->select('section')->distinct()->orderBy('section')->pluck('section')->toArray();
    }

    private function typeOptions(): array
    {
        return MenuItem::query()->select('type')->distinct()->orderBy('type')->pluck('type')->toArray();
    }

    private function ensureSlug(array $payload, ?int $ignoreId = null): array
    {
        $base = Str::slug($payload['slug'] ?: $payload['name']) ?: 'menu-item';
        $slug = $base;
        $counter = 1;

        while (
            MenuItem::where('slug', $slug)
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
