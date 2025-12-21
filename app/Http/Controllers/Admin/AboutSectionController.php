<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AboutSectionController extends Controller
{
    public function index(): View
    {
        $sections = AboutSection::orderBy('display_order')->paginate($this->adminPerPage());

        return view('admin.about-sections.index', compact('sections'));
    }

    public function create(): View
    {
        return view('admin.about-sections.create', [
            'section' => new AboutSection(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateSection($request);

        $image = $request->file('image');

        unset($data['image'], $data['remove_image']);

        if ($image) {
            $data['image_path'] = $image->store('about-sections', 'public');
        }

        AboutSection::create($data);

        return redirect()
            ->route('admin.about-sections.index')
            ->with('status', 'Konten Tentang Kami berhasil ditambahkan.');
    }

    public function edit(AboutSection $aboutSection): View
    {
        return view('admin.about-sections.edit', [
            'section' => $aboutSection,
        ]);
    }

    public function update(Request $request, AboutSection $aboutSection): RedirectResponse
    {
        $data = $this->validateSection($request, $aboutSection);

        $image = $request->file('image');
        $removeImage = $data['remove_image'] ?? false;

        unset($data['image'], $data['remove_image']);

        if ($image) {
            if ($aboutSection->image_path) {
                Storage::disk('public')->delete($aboutSection->image_path);
            }
            $data['image_path'] = $image->store('about-sections', 'public');
        } elseif ($removeImage) {
            if ($aboutSection->image_path) {
                Storage::disk('public')->delete($aboutSection->image_path);
            }
            $data['image_path'] = null;
        }

        $aboutSection->update($data);

        return redirect()
            ->route('admin.about-sections.index')
            ->with('status', 'Konten Tentang Kami berhasil diperbarui.');
    }

    public function destroy(AboutSection $aboutSection): RedirectResponse
    {
        $aboutSection->delete();

        return redirect()
            ->route('admin.about-sections.index')
            ->with('status', 'Konten berhasil dihapus.');
    }

    private function validateSection(Request $request, ?AboutSection $aboutSection = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:120', Rule::unique('about_sections', 'slug')->ignore($aboutSection)],
            'content' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $validated['display_order'] = $validated['display_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['remove_image'] = $request->boolean('remove_image');

        return $validated;
    }
}
