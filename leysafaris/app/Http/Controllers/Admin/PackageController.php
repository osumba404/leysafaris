<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Experience;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        $packages = Package::with('destinations')
            ->withCount('packageDays')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.packages.index', compact('packages'));
    }

    public function create(): View
    {
        $destinations = Destination::orderBy('name')->get();
        $experiences = Experience::orderBy('name')->get();

        return view('admin.packages.create', compact('destinations', 'experiences'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePackage($request);

        $validated['slug'] = $this->resolveSlug($validated['title'], $validated['slug'] ?? null);

        $package = Package::create($validated);

        $this->syncRelations($package, $request);
        $this->syncDays($package, $request->input('days', []));

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function show(Package $package): View
    {
        $package->load(['packageDays', 'destinations', 'experiences']);

        return view('admin.packages.show', compact('package'));
    }

    public function edit(Package $package): View
    {
        $package->load(['packageDays', 'destinations', 'experiences']);
        $destinations = Destination::orderBy('name')->get();
        $experiences = Experience::orderBy('name')->get();

        return view('admin.packages.edit', compact('package', 'destinations', 'experiences'));
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $validated = $this->validatePackage($request, $package->id);

        if (! empty($validated['slug'])) {
            $validated['slug'] = $this->resolveSlug($validated['title'], $validated['slug'], $package->id);
        } else {
            unset($validated['slug']);
        }

        $package->update($validated);

        $this->syncRelations($package, $request);
        $this->syncDays($package, $request->input('days', []));

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package): RedirectResponse
    {
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }

    private function validatePackage(Request $request, ?int $ignoreId = null): array
    {
        $slugRule = ['nullable', 'string', 'max:255'];
        if ($ignoreId) {
            $slugRule[] = 'unique:packages,slug,'.$ignoreId;
        } else {
            $slugRule[] = 'unique:packages,slug';
        }

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => $slugRule,
            'tagline' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'starting_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'price_note' => ['nullable', 'string', 'max:255'],
            'experience_types' => ['nullable', 'array'],
            'experience_types.*' => ['string', 'max:100'],
            'traveler_types' => ['nullable', 'array'],
            'traveler_types.*' => ['string', 'max:100'],
            'departure_style' => ['nullable', 'string', 'in:private,fixed,custom,group'],
            'highlights' => ['nullable', 'array'],
            'highlights.*' => ['string', 'max:500'],
            'inclusions' => ['nullable', 'array'],
            'inclusions.*' => ['string', 'max:500'],
            'exclusions' => ['nullable', 'array'],
            'exclusions.*' => ['string', 'max:500'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['string', 'max:500'],
            'hero_image' => ['nullable', 'string', 'max:500'],
            'pricing_notes' => ['nullable', 'string'],
            'practical_info' => ['nullable', 'string'],
            'route_map_image' => ['nullable', 'string', 'max:500'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'is_featured' => ['boolean'],
            'is_template' => ['boolean'],
            'status' => ['required', 'string', 'in:draft,published,archived'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'destination_ids' => ['nullable', 'array'],
            'destination_ids.*' => ['exists:destinations,id'],
            'experience_ids' => ['nullable', 'array'],
            'experience_ids.*' => ['exists:experiences,id'],
            'days' => ['nullable', 'array'],
            'days.*.day_number' => ['required', 'integer', 'min:1'],
            'days.*.title' => ['required', 'string', 'max:255'],
            'days.*.location' => ['nullable', 'string', 'max:255'],
            'days.*.morning' => ['nullable', 'string'],
            'days.*.afternoon' => ['nullable', 'string'],
            'days.*.evening' => ['nullable', 'string'],
            'days.*.narrative' => ['nullable', 'string'],
            'days.*.meals' => ['nullable', 'array'],
            'days.*.meals.*' => ['string', 'max:100'],
            'days.*.accommodation' => ['nullable', 'string', 'max:255'],
            'days.*.accommodation_note' => ['nullable', 'string'],
            'days.*.activities' => ['nullable', 'array'],
            'days.*.activities.*' => ['string', 'max:255'],
            'days.*.travel_notes' => ['nullable', 'string'],
            'days.*.wildlife_highlights' => ['nullable', 'string'],
            'days.*.image' => ['nullable', 'string', 'max:500'],
            'days.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function resolveSlug(string $title, ?string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $title);
        $candidate = $base;
        $counter = 1;

        while (Package::where('slug', $candidate)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $base.'-'.$counter++;
        }

        return $candidate;
    }

    private function syncRelations(Package $package, Request $request): void
    {
        $package->destinations()->sync($request->input('destination_ids', []));
        $package->experiences()->sync($request->input('experience_ids', []));
    }

    private function syncDays(Package $package, array $days): void
    {
        $package->packageDays()->delete();

        foreach ($days as $index => $day) {
            $package->packageDays()->create([
                'day_number' => $day['day_number'],
                'title' => $day['title'],
                'location' => $day['location'] ?? null,
                'morning' => $day['morning'] ?? null,
                'afternoon' => $day['afternoon'] ?? null,
                'evening' => $day['evening'] ?? null,
                'narrative' => $day['narrative'] ?? null,
                'meals' => $day['meals'] ?? null,
                'accommodation' => $day['accommodation'] ?? null,
                'accommodation_note' => $day['accommodation_note'] ?? null,
                'activities' => $day['activities'] ?? null,
                'travel_notes' => $day['travel_notes'] ?? null,
                'wildlife_highlights' => $day['wildlife_highlights'] ?? null,
                'image' => $day['image'] ?? null,
                'sort_order' => $day['sort_order'] ?? $index,
            ]);
        }
    }
}
