<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(Request $request): View
    {
        $query = Package::published()->with('destinations');

        if ($request->filled('destination')) {
            $query->whereHas('destinations', function ($q) use ($request) {
                $q->where('destinations.id', $request->integer('destination'))
                    ->orWhere('destinations.slug', $request->input('destination'));
            });
        }

        if ($request->filled('duration')) {
            $duration = $request->integer('duration');
            if ($request->filled('duration_max')) {
                $query->whereBetween('duration_days', [$duration, $request->integer('duration_max')]);
            } else {
                $query->where('duration_days', $duration);
            }
        } elseif ($request->filled('duration_min') || $request->filled('duration_max')) {
            $min = $request->integer('duration_min', 1);
            $max = $request->integer('duration_max', 365);
            $query->whereBetween('duration_days', [$min, $max]);
        }

        if ($request->filled('experience_type')) {
            $query->whereJsonContains('experience_types', $request->input('experience_type'));
        }

        if ($request->filled('traveler_type')) {
            $query->whereJsonContains('traveler_types', $request->input('traveler_type'));
        }

        if ($request->filled('departure_style')) {
            $query->where('departure_style', $request->input('departure_style'));
        }

        if ($request->filled('budget')) {
            match ($request->input('budget')) {
                'under_1000' => $query->where('starting_price', '<', 1000),
                '1000_2500' => $query->whereBetween('starting_price', [1000, 2500]),
                '2500_5000' => $query->whereBetween('starting_price', [2500, 5000]),
                '5000_plus' => $query->where('starting_price', '>=', 5000),
                default => null,
            };
        }

        match ($request->input('sort', 'popular')) {
            'price_asc' => $query->orderBy('starting_price'),
            'price_desc' => $query->orderByDesc('starting_price'),
            'duration_asc' => $query->orderBy('duration_days'),
            'duration_desc' => $query->orderByDesc('duration_days'),
            'newest' => $query->orderByDesc('created_at'),
            default => $query->orderByDesc('view_count'),
        };

        $query->orderBy('sort_order');

        $packages = $query->paginate(12)->withQueryString();

        $destinations = Destination::published()->orderBy('name')->get(['id', 'name', 'slug']);

        return view('packages.index', compact('packages', 'destinations'));
    }

    public function show(string $slug): View
    {
        $package = Package::published()
            ->where('slug', $slug)
            ->with(['packageDays', 'destinations', 'experiences', 'testimonials' => fn ($q) => $q->approved()])
            ->firstOrFail();

        $package->increment('view_count');

        $relatedPackages = Package::published()
            ->where('id', '!=', $package->id)
            ->whereHas('destinations', function ($q) use ($package) {
                $q->whereIn('destinations.id', $package->destinations->pluck('id'));
            })
            ->with('destinations')
            ->limit(4)
            ->get();

        if ($relatedPackages->isEmpty()) {
            $relatedPackages = Package::published()
                ->where('id', '!=', $package->id)
                ->with('destinations')
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        return view('packages.show', compact('package', 'relatedPackages'));
    }
}
