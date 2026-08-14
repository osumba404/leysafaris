<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TravelQuizController extends Controller
{
    public function show(): View
    {
        $settings = Setting::allGrouped();

        return view('travel-quiz.index', compact('settings'));
    }

    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'destination' => ['required', 'string', 'max:100'],
            'trip_style' => ['required', 'string', 'max:100'],
            'travel_month' => ['nullable', 'string', 'max:100'],
            'adults' => ['nullable', 'integer', 'min:1', 'max:20'],
            'children' => ['nullable', 'integer', 'min:0', 'max:20'],
            'budget' => ['nullable', 'string', 'max:100'],
        ]);

        $message = sprintf(
            "Travel quiz results:\n- Destination interest: %s\n- Trip style: %s\n- Preferred month: %s\n- Adults: %s, Children: %s\n- Budget: %s",
            $validated['destination'],
            $validated['trip_style'],
            $validated['travel_month'] ?? 'Flexible',
            $validated['adults'] ?? 2,
            $validated['children'] ?? 0,
            $validated['budget'] ?? 'Not specified'
        );

        return redirect()
            ->route('contact', [
                'message' => $message,
                'travel_dates' => $validated['travel_month'] ?? null,
                'group_size' => ($validated['adults'] ?? 2) + ($validated['children'] ?? 0),
            ])
            ->with('success', 'Great choices! Complete your details below and we will send a tailored proposal.');
    }
}
