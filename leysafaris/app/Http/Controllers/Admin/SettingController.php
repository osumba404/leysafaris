<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get();
        $grouped = $settings->groupBy('group');

        return view('admin.settings.index', compact('grouped', 'settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string', 'max:255'],
            'settings.*.value' => ['nullable'],
            'settings.*.group' => ['nullable', 'string', 'max:100'],
        ]);

        foreach ($validated['settings'] as $item) {
            Setting::set(
                $item['key'],
                $item['value'] ?? null,
                $item['group'] ?? 'general'
            );
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
