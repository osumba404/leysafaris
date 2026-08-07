<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        $testimonials = Testimonial::with('package')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        $packages = Package::orderBy('title')->get(['id', 'title']);

        return view('admin.testimonials.create', compact('packages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTestimonial($request);

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    public function show(Testimonial $testimonial): View
    {
        $testimonial->load('package');

        return view('admin.testimonials.show', compact('testimonial'));
    }

    public function edit(Testimonial $testimonial): View
    {
        $packages = Package::orderBy('title')->get(['id', 'title']);

        return view('admin.testimonials.edit', compact('testimonial', 'packages'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $this->validateTestimonial($request);

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }

    private function validateTestimonial(Request $request): array
    {
        return $request->validate([
            'package_id' => ['nullable', 'exists:packages,id'],
            'author_name' => ['required', 'string', 'max:255'],
            'author_location' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'source' => ['nullable', 'string', 'max:100'],
            'source_url' => ['nullable', 'url', 'max:500'],
            'is_approved' => ['boolean'],
            'is_featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
