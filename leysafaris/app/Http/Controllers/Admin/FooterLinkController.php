<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FooterLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        $links = FooterLink::orderBy('group')->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.footer-links.index', compact('links'));
    }

    public function create(): View
    {
        return view('admin.footer-links.create');
    }

    public function store(Request $request): RedirectResponse
    {
        FooterLink::create($this->validated($request));

        return redirect()->route('admin.footer-links.index')
            ->with('success', 'Footer link created.');
    }

    public function edit(FooterLink $footerLink): View
    {
        return view('admin.footer-links.edit', compact('footerLink'));
    }

    public function update(Request $request, FooterLink $footerLink): RedirectResponse
    {
        $footerLink->update($this->validated($request));

        return redirect()->route('admin.footer-links.index')
            ->with('success', 'Footer link updated.');
    }

    public function destroy(FooterLink $footerLink): RedirectResponse
    {
        $footerLink->delete();

        return redirect()->route('admin.footer-links.index')
            ->with('success', 'Footer link deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'group' => ['required', 'string', 'in:explore,travel_info'],
            'label' => ['required', 'string', 'max:120'],
            'route_name' => ['nullable', 'string', 'max:120'],
            'url' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        return [
            ...$data,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
