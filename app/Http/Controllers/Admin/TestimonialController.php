<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(Request $request): View
    {
        $query = Testimonial::query();

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        if ($search = $request->input('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('quote', 'like', "%{$search}%");
            });
        }

        $testimonials = $query
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate($this->adminPerPage())
            ->withQueryString();

        $stats = [
            'total' => Testimonial::count(),
            'active' => Testimonial::where('is_active', true)->count(),
            'featured' => Testimonial::where('is_featured', true)->count(),
        ];

        return view('admin.testimonials.index', [
            'testimonials' => $testimonials,
            'stats' => $stats,
            'statusFilter' => $request->input('status'),
            'searchQuery' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.testimonials.create', [
            'testimonial' => new Testimonial,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTestimonial($request);

        $avatar = $validated['avatar'] ?? null;
        unset($validated['avatar']);

        $payload = $this->normalizeFlags($validated);

        if ($avatar) {
            $payload['avatar_path'] = $avatar->store('testimonials', 'public');
        }

        Testimonial::create($payload);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('admin_success', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', [
            'testimonial' => $testimonial,
        ]);
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $this->validateTestimonial($request);

        $avatar = $validated['avatar'] ?? null;
        unset($validated['avatar']);

        $payload = $this->normalizeFlags($validated);

        if ($avatar) {
            if ($testimonial->avatar_path) {
                $old = Str::contains($testimonial->avatar_path, '/storage/')
                    ? ltrim(Str::after($testimonial->avatar_path, '/storage/'), '/')
                    : ltrim($testimonial->avatar_path, '/');

                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }

            $payload['avatar_path'] = $avatar->store('testimonials', 'public');
        }

        $testimonial->update($payload);

        return redirect()
            ->route('admin.testimonials.edit', $testimonial)
            ->with('admin_success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->avatar_path) {
            $old = Str::contains($testimonial->avatar_path, '/storage/')
                ? ltrim(Str::after($testimonial->avatar_path, '/storage/'), '/')
                : ltrim($testimonial->avatar_path, '/');

            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
        }

        $testimonial->delete();

        return redirect()
            ->route('admin.testimonials.index')
            ->with('admin_success', 'Testimoni dihapus.');
    }

    public function toggleStatus(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_active' => ! $testimonial->is_active]);

        return back()->with('admin_success', 'Status testimoni diperbarui.');
    }

    public function toggleFeatured(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_featured' => ! $testimonial->is_featured]);

        return back()->with('admin_success', 'Highlight testimoni diperbarui.');
    }

    private function validateTestimonial(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'max:5120'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function normalizeFlags(array $payload): array
    {
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);
        $payload['is_featured'] = (bool) ($payload['is_featured'] ?? false);
        $payload['sort_order'] = $payload['sort_order'] ?? 0;
        $payload['rating'] = $payload['rating'] ?? 5;

        return $payload;
    }
}
