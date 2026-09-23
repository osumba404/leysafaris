<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Rules\PublicImagePath;
use App\Rules\PublicVideoPath;
use App\Support\HeroMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        $slides = HeroSlide::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.hero-slides.index', compact('slides'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['sort_order'] = (HeroSlide::max('sort_order') ?? -1) + 1;

        HeroSlide::create($data);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Hero slide created.');
    }

    public function update(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->update($this->validated($request));

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Hero slide updated.');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->delete();

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Hero slide deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $mediaType = $request->input('media_type', HeroMedia::TYPE_IMAGE);

        $rules = [
            'media_type' => ['required', Rule::in([
                HeroMedia::TYPE_IMAGE,
                HeroMedia::TYPE_VIDEO,
                HeroMedia::TYPE_EMBED,
            ])],
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', new PublicImagePath],
            'video_path' => ['nullable', new PublicVideoPath],
            'video_url' => ['nullable', 'string', 'max:500'],
        ];

        if ($mediaType === HeroMedia::TYPE_IMAGE) {
            $rules['image'] = ['required', new PublicImagePath];
        }

        if ($mediaType === HeroMedia::TYPE_VIDEO) {
            $rules['video_path'] = ['required', new PublicVideoPath];
        }

        if ($mediaType === HeroMedia::TYPE_EMBED) {
            $rules['video_url'] = ['required', 'string', 'max:500'];
        }

        $validated = $request->validate($rules) + [
            'is_active' => $request->boolean('is_active'),
        ];

        if ($mediaType === HeroMedia::TYPE_EMBED && HeroMedia::normalizeEmbedUrl($validated['video_url'] ?? null) === null) {
            throw ValidationException::withMessages([
                'video_url' => 'Please enter a valid YouTube, Vimeo, or embeddable video URL.',
            ]);
        }

        if ($mediaType === HeroMedia::TYPE_IMAGE) {
            $validated['video_path'] = null;
            $validated['video_url'] = null;
        } elseif ($mediaType === HeroMedia::TYPE_VIDEO) {
            $validated['video_url'] = null;
        } else {
            $validated['video_path'] = null;
        }

        return $validated;
    }
}
