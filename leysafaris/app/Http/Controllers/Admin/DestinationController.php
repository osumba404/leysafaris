<?php

namespace App\Http\Controllers\Admin;

use App\Rules\PublicImagePath;
use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        $destinations = Destination::withCount('packages')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.destinations.index', compact('destinations'));
    }

    public function create(): View
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDestination($request);
        $validated['slug'] = $this->resolveSlug($validated['name']);

        Destination::create($validated + [
            'sort_order' => (Destination::max('sort_order') ?? -1) + 1,
        ]);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination created successfully.');
    }

    public function show(Destination $destination): View
    {
        $destination->load(['packages' => fn ($q) => $q->orderBy('sort_order')]);

        return view('admin.destinations.show', compact('destination'));
    }

    public function edit(Destination $destination): View
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $validated = $this->validateDestination($request, $destination->id);
        unset($validated['slug']);

        $destination->update($validated);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination updated successfully.');
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        $destination->delete();

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination deleted successfully.');
    }

    private function validateDestination(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'region' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'best_time' => ['nullable', 'string', 'max:255'],
            'signature_wildlife' => ['nullable', 'string'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['nullable', new PublicImagePath],
            'facts' => ['nullable', 'array'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
        ]);

        $gallery = $this->normalizeGallery($validated['gallery'] ?? []);
        $validated['gallery'] = $gallery;
        $validated['hero_image'] = $gallery[0] ?? null;

        return $validated;
    }

    private function normalizeGallery(array $items): array
    {
        return array_values(array_filter(array_map(
            fn ($item) => is_string($item) ? trim($item) : '',
            $items
        )));
    }

    private function resolveSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $candidate = $base;
        $counter = 1;

        while (Destination::where('slug', $candidate)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $base.'-'.$counter++;
        }

        return $candidate;
    }
}
