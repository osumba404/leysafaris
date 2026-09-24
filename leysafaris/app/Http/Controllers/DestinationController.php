<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->trim()->toString();

        $destinations = Destination::published()
            ->search($search)
            ->withCount(['packages' => fn ($q) => $q->published()])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('destinations.index', compact('destinations', 'search'));
    }

    public function show(string $slug): View
    {
        $destination = Destination::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPackages = Package::published()
            ->whereHas('destinations', fn ($q) => $q->where('destinations.id', $destination->id))
            ->with('destinations')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->paginate(12);

        return view('destinations.show', compact('destination', 'relatedPackages'));
    }
}
