<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
        ]);

        Package::published()->findOrFail($validated['package_id']);

        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'package_id' => $validated['package_id'],
        ]);

        return back()->with('success', 'Package saved to your wishlist.');
    }

    public function destroy(Package $package): RedirectResponse
    {
        Wishlist::where('user_id', auth()->id())
            ->where('package_id', $package->id)
            ->delete();

        return back()->with('success', 'Package removed from your wishlist.');
    }
}
