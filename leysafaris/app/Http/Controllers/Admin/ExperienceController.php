<?php

namespace App\Http\Controllers\Admin;

use App\Rules\PublicImagePath;
use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        $experiences = Experience::withCount('packages')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.experiences.index', compact('experiences'));
    }

    public function create(): View
    {
        return view('admin.experiences.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateExperience($request);
        $validated['slug'] = $this->resolveSlug($validated['name'], $validated['slug'] ?? null);

        Experience::create($validated + [
            'sort_order' => (Experience::max('sort_order') ?? -1) + 1,
        ]);

        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experience created successfully.');
    }

    public function show(Experience $experience): View
    {
        $experience->load(['packages' => fn ($q) => $q->orderBy('sort_order')]);

        return view('admin.experiences.show', compact('experience'));
    }

    public function edit(Experience $experience): View
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(Request $request, Experience $experience): RedirectResponse
    {
        $validated = $this->validateExperience($request, $experience->id);

        if (! empty($validated['slug'])) {
            $validated['slug'] = $this->resolveSlug($validated['name'], $validated['slug'], $experience->id);
        } else {
            unset($validated['slug']);
        }

        $experience->update($validated);

        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experience updated successfully.');
    }

    public function destroy(Experience $experience): RedirectResponse
    {
        $experience->delete();

        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experience deleted successfully.');
    }

    private function validateExperience(Request $request, ?int $ignoreId = null): array
    {
        $slugRule = ['nullable', 'string', 'max:255'];
        if ($ignoreId) {
            $slugRule[] = 'unique:experiences,slug,'.$ignoreId;
        } else {
            $slugRule[] = 'unique:experiences,slug';
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => $slugRule,
            'type' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', new PublicImagePath],
            'duration_hours' => ['nullable', 'integer', 'min:1'],
            'starting_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'is_published' => ['boolean'],
        ]);
    }

    private function resolveSlug(string $name, ?string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name);
        $candidate = $base;
        $counter = 1;

        while (Experience::where('slug', $candidate)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $base.'-'.$counter++;
        }

        return $candidate;
    }
}
