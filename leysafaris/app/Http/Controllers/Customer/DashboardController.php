<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): RedirectResponse|View
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $enquiries = $user->enquiries()
            ->with(['package', 'quotes'])
            ->orderByDesc('created_at')
            ->get();

        $wishlist = $user->wishlists()
            ->with(['package.destinations'])
            ->orderByDesc('created_at')
            ->get();

        return view('customer.dashboard', compact('enquiries', 'wishlist'));
    }
}
