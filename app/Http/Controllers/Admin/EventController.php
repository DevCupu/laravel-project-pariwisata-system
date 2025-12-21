<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $query = Event::query();

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        if ($search = $request->input('q')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $events = $query->orderByDesc('is_featured')->orderByDesc('start_date')
            ->paginate($this->adminPerPage())->withQueryString();

        return view('admin.events.index', [
            'events' => $events,
            'searchQuery' => $search,
            'statusFilter' => $request->input('status'),
        ]);
    }

    public function create(): View
    {
        return view('admin.events.create', ['event' => new Event()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateEvent($request);

        $image = $validated['image'] ?? null;
        unset($validated['image']);

        $payload = $this->ensureSlug($validated);

        if ($image) {
            $path = $image->store('events', 'public');
            $payload['image_path'] = $path;
        }

        Event::create($payload);

        return redirect()->route('admin.events.index')->with('admin_success', 'Event berhasil ditambahkan.');
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', ['event' => $event]);
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $this->validateEvent($request, $event->id);

        $image = $validated['image'] ?? null;
        unset($validated['image']);

        $payload = $this->ensureSlug($validated, $event->id);

        if ($image) {
            if ($event->image_path) {
                $old = Str::contains($event->image_path, '/storage/')
                    ? ltrim(Str::after($event->image_path, '/storage/'), '/')
                    : ltrim($event->image_path, '/');

                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }

            $path = $image->store('events', 'public');
            $payload['image_path'] = $path;
        }

        $event->update($payload);

        return redirect()->route('admin.events.edit', $event)->with('admin_success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('admin.events.index')->with('admin_success', 'Event dihapus.');
    }

    public function toggleStatus(Event $event): RedirectResponse
    {
        $event->update(['is_active' => ! $event->is_active]);

        return back()->with('admin_success', 'Status event diperbarui.');
    }

    public function toggleFeatured(Event $event): RedirectResponse
    {
        $event->update(['is_featured' => ! $event->is_featured]);

        return back()->with('admin_success', 'Highlight event diperbarui.');
    }

    private function validateEvent(Request $request, ?int $ignoreId = null): array
    {
        $uniqueSlug = 'unique:events,slug';
        if ($ignoreId) {
            $uniqueSlug .= ',' . $ignoreId;
        }

        return $request->validate([
            'type' => ['required', 'in:event,promo'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', $uniqueSlug],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function ensureSlug(array $payload, ?int $ignoreId = null): array
    {
        $base = Str::slug($payload['slug'] ?? $payload['title']) ?: 'event';
        $slug = $base;
        $counter = 1;

        while (
            Event::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        $payload['slug'] = $slug;

        return $payload;
    }
}
