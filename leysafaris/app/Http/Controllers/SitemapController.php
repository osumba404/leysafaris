<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\Experience;
use App\Models\Package;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('packages.index'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => route('destinations.index'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => route('experiences.index'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('about'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('blog.index'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => route('contact'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('faq.index'), 'priority' => '0.75', 'changefreq' => 'monthly'],
            ['loc' => route('practical.index'), 'priority' => '0.75', 'changefreq' => 'monthly'],
            ['loc' => route('travel-quiz.show'), 'priority' => '0.7', 'changefreq' => 'monthly'],
        ]);

        Package::published()->select('slug', 'updated_at')->each(function ($package) use ($urls) {
            $urls->push([
                'loc' => route('packages.show', $package->slug),
                'lastmod' => $package->updated_at?->toAtomString(),
                'priority' => '0.85',
                'changefreq' => 'weekly',
            ]);
        });

        Destination::published()->select('slug', 'updated_at')->each(function ($destination) use ($urls) {
            $urls->push([
                'loc' => route('destinations.show', $destination->slug),
                'lastmod' => $destination->updated_at?->toAtomString(),
                'priority' => '0.8',
                'changefreq' => 'monthly',
            ]);
        });

        BlogPost::published()->select('slug', 'updated_at')->each(function ($post) use ($urls) {
            $urls->push([
                'loc' => route('blog.show', $post->slug),
                'lastmod' => $post->updated_at?->toAtomString(),
                'priority' => '0.7',
                'changefreq' => 'monthly',
            ]);
        });

        return response()
            ->view('sitemap.index', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
