<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NavItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        $items = NavItem::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.nav-items.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.nav-items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        NavItem::create($this->validated($request));

        return redirect()->route('admin.nav-items.index')
            ->with('success', 'Navigation item created.');
    }

    public function edit(NavItem $navItem): View
    {
        return view('admin.nav-items.edit', compact('navItem'));
    }

    public function update(Request $request, NavItem $navItem): RedirectResponse
    {
        $navItem->update($this->validated($request));

        return redirect()->route('admin.nav-items.index')
            ->with('success', 'Navigation item updated.');
    }

    public function destroy(NavItem $navItem): RedirectResponse
    {
        $navItem->delete();

        return redirect()->route('admin.nav-items.index')
            ->with('success', 'Navigation item deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'route_name' => ['nullable', 'string', 'max:120'],
            'url' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_highlight' => ['nullable', 'boolean'],
        ]);

        return [
            ...$data,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
            'is_highlight' => $request->boolean('is_highlight'),
        ];
    }
}
