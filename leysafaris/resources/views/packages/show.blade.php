@extends('layouts.public')

@section('title', $package->seo_title ?? ($package->title . ' Safari | ' . $package->duration_days . ' Days | Leyla Safari Tours'))
@section('meta_description', $package->seo_description ?? Str::limit(strip_tags($package->short_description ?? $package->tagline ?? ''), 155))
@section('meta_keywords', implode(', ', array_filter([$package->title, 'Kenya safari', $package->destinations->pluck('name')->join(', '), 'wildlife tour'])))
@section('canonical', route('packages.show', $package->slug))
@section('og_type', 'product')
@section('og_image', asset($package->hero_image ?? 'images/savannah_sunset_tree.jpg'))

@push('structured_data')
@php
    $tripSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'TouristTrip',
        'name' => $package->title,
        'description' => Str::limit(strip_tags($package->short_description ?? ''), 300),
        'url' => route('packages.show', $package->slug),
        'image' => asset($package->hero_image ?? 'images/savannah_sunset_tree.jpg'),
        'itinerary' => [
            '@type' => 'ItemList',
            'numberOfItems' => $package->packageDays->count(),
            'itemListElement' => $package->packageDays->values()->map(fn ($day, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => 'Day '.$day->day_number.': '.$day->title,
            ])->all(),
        ],
    ];
    if ($package->starting_price) {
        $tripSchema['offers'] = [
            '@type' => 'Offer',
            'price' => (string) $package->starting_price,
            'priceCurrency' => $package->currency ?? 'USD',
            'availability' => 'https://schema.org/InStock',
            'url' => route('contact'),
        ];
    }
@endphp
<script type="application/ld+json">{!! json_encode($tripSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
    <section class="hero hero--compact" aria-labelledby="package-heading">
        <div class="hero__media">
            <x-optimized-img
                :src="$package->hero_image ?? 'images/savannah_sunset_tree.jpg'"
                :alt="$package->title . ' - Kenya safari tour by Leyla Safari Tours'"
                :width="1920"
                :height="810"
                :priority="true"
            />
            <div class="hero__overlay"></div>
        </div>
        <div class="container hero__content">
            @if ($package->tagline)
                <p class="hero__eyebrow">{{ $package->tagline }}</p>
            @endif
            <h1 id="package-heading" class="hero__title">{{ $package->title }}</h1>
            <p class="hero__subtitle">
                <i data-lucide="calendar" style="width:18px;height:18px;display:inline;vertical-align:middle;"></i>
                {{ $package->duration_days }} Days
                @if ($package->destinations->isNotEmpty())
                    · {{ $package->destinations->pluck('name')->join(', ') }}
                @endif
                @if ($package->starting_price)
                    · From {{ $package->currency ?? 'USD' }} {{ number_format($package->starting_price, 0) }}
                @endif
            </p>
        </div>
    </section>

    <div class="package-layout">
        <div class="container package-layout__inner">
            <div class="package-main">
                @if ($package->short_description || $package->long_description)
                    <section class="section" style="padding-block: var(--space-lg);">
                        <div class="section-header" style="text-align: left; margin-bottom: var(--space-md);">
                            <h2 class="section-header__title">Overview</h2>
                        </div>
                        @if ($package->short_description)
                            <p style="font-size: 1.1rem; margin-bottom: 1rem;">{{ $package->short_description }}</p>
                        @endif
                        @if ($package->long_description)
                            <div class="package-content">{!! nl2br(e($package->long_description)) !!}</div>
                        @endif
                    </section>
                @endif

                @if (! empty($package->highlights))
                    <section class="section" style="padding-block: var(--space-md);">
                        <h2 class="section-header__title" style="margin-bottom: 1rem;">Highlights</h2>
                        <ul style="display: grid; gap: 0.75rem;">
                            @foreach ($package->highlights as $highlight)
                                <li style="display: flex; gap: 0.65rem; align-items: flex-start;">
                                    <i data-lucide="check-circle" style="width:18px;height:18px;color:var(--color-moss);flex-shrink:0;margin-top:3px;"></i>
                                    <span>{{ $highlight }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($package->packageDays->isNotEmpty())
                    <section class="section itineraries" style="padding-block: var(--space-lg);">
                        <div class="section-header" style="text-align: left;">
                            <span class="section-header__label">Day by Day</span>
                            <h2 class="section-header__title">Full Itinerary</h2>
                        </div>
                        <div class="accordion">
                            @foreach ($package->packageDays as $day)
                                <div class="accordion__item">
                                    <button class="accordion__trigger" aria-expanded="false" aria-controls="day-{{ $day->id }}">
                                        <span class="accordion__trigger-text">
                                            <i data-lucide="sun" aria-hidden="true"></i>
                                            Day {{ $day->day_number }}: {{ $day->title }}
                                            @if ($day->location)
                                                <small style="opacity:0.7;font-weight:400;"> - {{ $day->location }}</small>
                                            @endif
                                        </span>
                                        <i data-lucide="chevron-down" class="accordion__icon" aria-hidden="true"></i>
                                    </button>
                                    <div class="accordion__panel" id="day-{{ $day->id }}" hidden>
                                        @if ($day->image)
                                            <x-lazy-img
                                                :src="$day->image"
                                                :alt="'Day ' . $day->day_number"
                                                :width="800"
                                                :height="240"
                                                style="width:100%;max-height:240px;object-fit:cover;border-radius:var(--radius-sm);margin-bottom:1rem;"
                                            />
                                        @endif
                                        @if ($day->narrative)
                                            <p>{{ $day->narrative }}</p>
                                        @endif
                                        @if ($day->morning || $day->afternoon || $day->evening)
                                            <dl style="margin-top: 1rem; display: grid; gap: 0.75rem;">
                                                @if ($day->morning)
                                                    <div><dt style="font-weight:600;color:var(--color-savanna);">Morning</dt><dd>{{ $day->morning }}</dd></div>
                                                @endif
                                                @if ($day->afternoon)
                                                    <div><dt style="font-weight:600;color:var(--color-savanna);">Afternoon</dt><dd>{{ $day->afternoon }}</dd></div>
                                                @endif
                                                @if ($day->evening)
                                                    <div><dt style="font-weight:600;color:var(--color-savanna);">Evening</dt><dd>{{ $day->evening }}</dd></div>
                                                @endif
                                            </dl>
                                        @endif
                                        @if (! empty($day->meals))
                                            <p style="margin-top:0.75rem;"><strong>Meals:</strong> {{ implode(', ', $day->meals) }}</p>
                                        @endif
                                        @if ($day->accommodation)
                                            <p><strong>Accommodation:</strong> {{ $day->accommodation }}</p>
                                        @endif
                                        @if (! empty($day->activities))
                                            <p><strong>Activities:</strong> {{ implode(', ', $day->activities) }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <div class="inclusions-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; padding-block: var(--space-lg);">
                    @if (! empty($package->inclusions))
                        <div>
                            <h3 class="section-header__title" style="font-size: 1.25rem; margin-bottom: 1rem;">Included</h3>
                            <ul style="display: grid; gap: 0.5rem;">
                                @foreach ($package->inclusions as $item)
                                    <li style="display:flex;gap:0.5rem;"><i data-lucide="plus" style="width:16px;color:var(--color-moss);"></i>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (! empty($package->exclusions))
                        <div>
                            <h3 class="section-header__title" style="font-size: 1.25rem; margin-bottom: 1rem;">Not Included</h3>
                            <ul style="display: grid; gap: 0.5rem;">
                                @foreach ($package->exclusions as $item)
                                    <li style="display:flex;gap:0.5rem;"><i data-lucide="minus" style="width:16px;color:var(--color-terracotta);"></i>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                @if ($package->testimonials->isNotEmpty())
                    <section class="section" style="padding-block: var(--space-lg);">
                        <h2 class="section-header__title" style="margin-bottom: 1.5rem;">Guest Reviews</h2>
                        @foreach ($package->testimonials as $testimonial)
                            <blockquote class="feature-card" style="margin-bottom: 1rem;">
                                <p class="feature-card__text">"{{ $testimonial->content }}"</p>
                                <footer style="margin-top: 0.75rem; font-size: 0.85rem;">- {{ $testimonial->author_name }}</footer>
                            </blockquote>
                        @endforeach
                    </section>
                @endif

                @if ($relatedPackages->isNotEmpty())
                    <section class="section safaris" style="padding-block: var(--space-lg);">
                        <h2 class="section-header__title" style="margin-bottom: 1.5rem;">Related Safaris</h2>
                        <div class="safari-grid">
                            @foreach ($relatedPackages as $related)
                                <article class="safari-card">
                                    <a href="{{ route('packages.show', $related->slug) }}" style="color:inherit;">
                                        <div class="safari-card__image">
                                            <x-lazy-img
                                                :src="$related->hero_image ?? 'images/savannah_sunset_tree.jpg'"
                                                :alt="$related->title"
                                                :width="400"
                                                :height="267"
                                            />
                                        </div>
                                        <div class="safari-card__body">
                                            <h3 class="safari-card__title">{{ $related->title }}</h3>
                                            <p class="safari-card__meta">
                                                <i data-lucide="calendar"></i> {{ $related->duration_days }} Days
                                            </p>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="package-sidebar">
                <div class="package-cta">
                    @if ($package->starting_price)
                        <p class="package-cta__price">
                            <span>From</span>
                            <strong>{{ $package->currency ?? 'USD' }} {{ number_format($package->starting_price, 0) }}</strong>
                            <small>per person</small>
                        </p>
                    @endif
                    @if ($package->price_note)
                        <p class="package-cta__note">{{ $package->price_note }}</p>
                    @endif
                    <a href="{{ route('contact') }}?package_id={{ $package->id }}" class="btn btn--primary btn--full">
                        <i data-lucide="send"></i> Request a Quote
                    </a>
                    <p style="font-size: 0.8rem; text-align: center; margin-top: 0.75rem; color: var(--color-text-muted);">No obligation · Response within 24 hours</p>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .hero--compact { min-height: 50vh; }
    .hero--compact .hero__title { font-size: clamp(2rem, 5vw, 3.5rem); }
    .package-layout { padding-bottom: var(--space-xl); }
    .package-layout__inner { display: grid; grid-template-columns: 1fr 320px; gap: 2.5rem; align-items: start; }
    .package-sidebar { position: sticky; top: calc(var(--header-height) + 1.5rem); }
    .package-cta { background: var(--color-white); border-radius: var(--radius-md); padding: 1.5rem; box-shadow: var(--shadow-md); border: 1px solid var(--color-mist); }
    .package-cta__price { text-align: center; margin-bottom: 1rem; }
    .package-cta__price span { display: block; font-size: 0.85rem; color: var(--color-text-muted); }
    .package-cta__price strong { font-family: var(--font-display); font-size: 2rem; color: var(--color-primary); }
    .package-cta__price small { display: block; font-size: 0.8rem; color: var(--color-text-muted); }
    .package-cta__note { font-size: 0.85rem; text-align: center; margin-bottom: 1rem; color: var(--color-text-muted); }
    @media (max-width: 900px) {
        .package-layout__inner { grid-template-columns: 1fr; }
        .package-sidebar { position: static; order: -1; }
        .inclusions-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush
