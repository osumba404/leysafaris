<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Package;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    public function contact(): View
    {
        $settings = Setting::allGrouped();

        return view('contact', compact('settings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'preferred_destinations' => ['nullable', 'string', 'max:500'],
            'travel_dates' => ['nullable', 'string', 'max:255'],
            'group_size' => ['nullable', 'integer', 'min:1', 'max:100'],
            'budget_range' => ['nullable', 'string', 'max:100'],
            'special_interests' => ['nullable', 'string', 'max:1000'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        if (! empty($validated['package_id'])) {
            Package::published()->findOrFail($validated['package_id']);
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'new';
        $validated['source'] = $request->input('source', 'website');

        Enquiry::create($validated);

        return back()->with('success', 'Thank you! Your enquiry has been received. We will contact you shortly.');
    }
}
