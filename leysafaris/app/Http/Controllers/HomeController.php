<?php

namespace App\Http\Controllers;

use App\Models\AnnualEvent;
use App\Models\Destination;
use App\Models\Package;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredPackages = Package::published()
            ->featured()
            ->with('destinations')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $destinations = Destination::published()
            ->featured()
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        $testimonials = Testimonial::approved()
            ->with('package')
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        $allDestinations = Destination::published()
            ->orderBy('sort_order')
            ->limit(12)
            ->get();

        $annualEvents = AnnualEvent::published()
            ->with('package')
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->limit(4)
            ->get();

        $settings = Setting::allGrouped();

        return view('home.index', compact(
            'featuredPackages',
            'destinations',
            'allDestinations',
            'testimonials',
            'annualEvents',
            'settings'
        ));
    }
}
