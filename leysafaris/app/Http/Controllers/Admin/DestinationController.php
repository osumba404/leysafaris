<?php

namespace App\Http\Controllers\Admin;

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
            ->paginate(20);

        return view('admin.destinations.index', compact('destinations'));
    }

    public function create(): View
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDestination($request);
        $validated['slug'] = $this->resolveSlug($validated['name'], $validated['slug'] ?? null);

        Destination::create($validated);

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

        if (! empty($validated['slug'])) {
            $validated['slug'] = $this->resolveSlug($validated['name'], $validated['slug'], $destination->id);
        } else {
            unset($validated['slug']);
        }

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
        $slugRule = ['nullable', 'string', 'max:255'];
        if ($ignoreId) {
            $slugRule[] = 'unique:destinations,slug,'.$ignoreId;
        } else {
            $slugRule[] = 'unique:destinations,slug';
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => $slugRule,
            'country' => ['nullable', 'string', 'max:100'],
            'region' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'best_time' => ['nullable', 'string', 'max:255'],
            'signature_wildlife' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'string', 'max:500'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['string', 'max:500'],
            'facts' => ['nullable', 'array'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function resolveSlug(string $name, ?string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name);
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
