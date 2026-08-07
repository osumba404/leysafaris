<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\Enquiry;
use App\Models\Experience;
use App\Models\Package;
use App\Models\Quote;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        $enquiriesByStatus = Enquiry::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $stats = [
            'packages' => Package::count(),
            'published_packages' => Package::where('status', 'published')->count(),
            'destinations' => Destination::count(),
            'experiences' => Experience::count(),
            'enquiries_total' => Enquiry::count(),
            'enquiries_new' => Enquiry::where('status', 'new')->count(),
            'quotes_total' => Quote::count(),
            'testimonials' => Testimonial::count(),
            'blog_posts' => BlogPost::count(),
            'customers' => User::where('role', 'customer')->count(),
        ];

        $recentEnquiries = Enquiry::with(['package', 'assignedTo'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $popularPackages = Package::published()
            ->orderByDesc('view_count')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'view_count']);

        return view('admin.dashboard', compact(
            'enquiriesByStatus',
            'stats',
            'recentEnquiries',
            'popularPackages'
        ));
    }
}
