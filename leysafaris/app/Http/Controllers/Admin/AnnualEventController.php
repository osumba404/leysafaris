<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnnualEvent;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AnnualEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        $events = AnnualEvent::with('package')
            ->orderByDesc('event_date')
            ->paginate(20);

        return view('admin.annual-events.index', compact('events'));
    }

    public function create(): View
    {
        $packages = Package::orderBy('title')->get(['id', 'title']);

        return view('admin.annual-events.create', compact('packages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateEvent($request);
        $validated['slug'] = $this->resolveSlug($validated['title'], $validated['slug'] ?? null);

        AnnualEvent::create($validated);

        return redirect()->route('admin.annual-events.index')
            ->with('success', 'Annual event created successfully.');
    }

    public function show(AnnualEvent $annualEvent): View
    {
        $annualEvent->load('package');

        return view('admin.annual-events.show', compact('annualEvent'));
    }

    public function edit(AnnualEvent $annualEvent): View
    {
        $packages = Package::orderBy('title')->get(['id', 'title']);

        return view('admin.annual-events.edit', compact('annualEvent', 'packages'));
    }

    public function update(Request $request, AnnualEvent $annualEvent): RedirectResponse
    {
        $validated = $this->validateEvent($request, $annualEvent->id);

        if (! empty($validated['slug'])) {
            $validated['slug'] = $this->resolveSlug($validated['title'], $validated['slug'], $annualEvent->id);
        } else {
            unset($validated['slug']);
        }

        $annualEvent->update($validated);

        return redirect()->route('admin.annual-events.index')
            ->with('success', 'Annual event updated successfully.');
    }

    public function destroy(AnnualEvent $annualEvent): RedirectResponse
    {
        $annualEvent->delete();

        return redirect()->route('admin.annual-events.index')
            ->with('success', 'Annual event deleted successfully.');
    }

    private function validateEvent(Request $request, ?int $ignoreId = null): array
    {
        $slugRule = ['nullable', 'string', 'max:255'];
        if ($ignoreId) {
            $slugRule[] = 'unique:annual_events,slug,'.$ignoreId;
        } else {
            $slugRule[] = 'unique:annual_events,slug';
        }

        return $request->validate([
            'package_id' => ['nullable', 'exists:packages,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => $slugRule,
            'excerpt' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'event_date' => ['required', 'date'],
            'early_bird_deadline' => ['nullable', 'date', 'before_or_equal:event_date'],
            'early_bird_price' => ['nullable', 'numeric', 'min:0'],
            'regular_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'hero_image' => ['nullable', 'string', 'max:500'],
            'is_published' => ['boolean'],
        ]);
    }

    private function resolveSlug(string $title, ?string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $title);
        $candidate = $base;
        $counter = 1;

        while (AnnualEvent::where('slug', $candidate)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $base.'-'.$counter++;
        }

        return $candidate;
    }
}
