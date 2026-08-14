@extends('layouts.public')

@section('title', ($settings['site_name'] ?? 'Leyla Safari Tours') . ' | Premium Kenya Safari Tours & East Africa Travel')
@section('meta_description', 'Book authentic Kenya safari tours with Leyla Safari Tours - Maasai Mara migration, Amboseli elephants, Samburu & custom East Africa itineraries. Nairobi-based experts. Request a quote.')
@section('meta_keywords', 'Kenya safari tours, Maasai Mara safari, Amboseli tours, Nairobi safari company, East Africa travel, Leyla Safari Tours, wildlife safari Kenya, luxury safari packages')
@section('canonical', route('home'))
@section('og_type', 'website')
@section('og_image', asset('images/savannah_sunset_tree.jpg'))

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@graph": [
    {
      "@type": "TravelAgency",
      "@id": "{{ url('/') }}#organization",
      "name": "{{ $settings['site_name'] ?? 'Leyla Safari Tours' }}",
      "url": "{{ url('/') }}",
      "logo": "{{ \App\Support\SiteSettings::logoUrl($settings) ?? asset('images/savannah_sunset_tree.jpg') }}",
      "image": "{{ asset($settings['site_logo'] ?? 'images/savannah_sunset_tree.jpg') }}",
      "description": "Premium Kenya and East Africa safari tours from Nairobi - Maasai Mara, Amboseli, Samburu and tailor-made wildlife journeys.",
      "telephone": "{{ $settings['phone'] ?? '+254712345678' }}",
      "email": "{{ \App\Support\SiteSettings::list($settings, 'emails')[0] ?? 'info@leylasafaritours.com' }}",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Nairobi",
        "addressRegion": "Nairobi County",
        "addressCountry": "KE"
      },
      "areaServed": ["Kenya", "Uganda", "Tanzania", "Rwanda"],
      "priceRange": "$$$"
    },
    {
      "@type": "WebSite",
      "@id": "{{ url('/') }}#website",
      "url": "{{ url('/') }}",
      "name": "Leyla Safari Tours",
      "publisher": { "@id": "{{ url('/') }}#organization" },
      "inLanguage": "en-KE"
    }
  ]
}
</script>
@endpush

@section('content')
    @php
        $slides = ($heroSlides ?? collect())->isNotEmpty()
            ? $heroSlides
            : collect([(object) [
                'image' => 'images/savannah_sunset_tree.jpg',
                'eyebrow' => 'Tailor-made safaris · Nairobi experts',
                'title' => "Let's plan your dream trip together",
                'subtitle' => 'Private jeeps, world-class guides, and itineraries crafted around your dates, budget, and sense of adventure.',
            ]]);
        $firstSlide = $slides->first();
    @endphp

    <section class="hero hero--artistic" aria-labelledby="hero-heading" data-hero-slider>
        <div class="hero__media hero-slider">
            @foreach ($slides as $index => $slide)
                <div class="hero-slider__slide @if($index === 0) is-active @endif" data-hero-slide
                     data-eyebrow="{{ $slide->eyebrow }}"
                     data-title="{{ $slide->title }}"
                     data-subtitle="{{ $slide->subtitle }}">
                    <x-optimized-img
                        src="{{ $slide->image }}"
                        alt="{{ $slide->title }} - {{ $settings['site_name'] ?? 'Leyla Safari Tours' }}"
                        :width="1920"
                        :height="1080"
                        :priority="$index === 0"
                    />
                </div>
            @endforeach
            <div class="hero__overlay hero__overlay--artistic"></div>
            <div class="hero__grain" aria-hidden="true"></div>
            @if ($slides->count() > 1)
                <div class="hero-slider__dots" aria-hidden="true">
                    @foreach ($slides as $index => $slide)
                        <button type="button" class="hero-slider__dot @if($index === 0) is-active @endif" data-hero-dot aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="container hero__layout">
            <div class="hero__content theme-fixed">
                <p class="hero__eyebrow" data-hero-eyebrow>{{ $firstSlide->eyebrow }}</p>
                <h1 id="hero-heading" class="hero__title" data-hero-title>
                    {!! nl2br(e($firstSlide->title)) !!}
                </h1>
                <p class="hero__subtitle" data-hero-subtitle>{{ $firstSlide->subtitle }}</p>
                <ul class="value-pills" aria-label="Why travellers choose us">
                    <li><i data-lucide="check-circle"></i> 100% custom itineraries</li>
                    <li><i data-lucide="star"></i> {{ $settings['google_rating'] ?? '4.9' }}/5 guest reviews</li>
                    <li><i data-lucide="shield-check"></i> Transparent pricing</li>
                    <li><i data-lucide="phone"></i> Expert advice, your way</li>
                </ul>
            </div>

            <aside class="hero-proposal" aria-labelledby="hero-proposal-heading">
                <h2 id="hero-proposal-heading" class="hero-proposal__title">Request a Travel Proposal</h2>
                <p class="hero-proposal__note">No strings attached. We will design your trip - no cost, no commitment.</p>
                <form class="hero-proposal__form" action="{{ route('contact') }}" method="GET">
                    <div class="hero-proposal__row">
                        <label for="hero-adults">Adults</label>
                        <input type="number" id="hero-adults" name="adults" min="1" max="20" value="2">
                    </div>
                    <div class="hero-proposal__row">
                        <label for="hero-children">Children</label>
                        <input type="number" id="hero-children" name="children" min="0" max="20" value="0">
                    </div>
                    <div class="hero-proposal__row hero-proposal__row--full">
                        <label for="hero-dates">Estimated arrival</label>
                        <input type="text" id="hero-dates" name="travel_dates" placeholder="e.g. August 2026">
                    </div>
                    <button type="submit" class="btn btn--primary btn--full">
                        <i data-lucide="send"></i> Start Planning
                    </button>
                </form>
                <p class="hero-proposal__alt">Or <a href="{{ route('travel-quiz.show') }}">take our travel quiz</a></p>
            </aside>
        </div>
    </section>

    <div class="trust-strip theme-fixed" role="region" aria-label="Guest review ratings">
        <div class="container trust-strip__inner">
            <div class="trust-strip__badge">
                <i data-lucide="award"></i>
                <div>
                    <strong>TripAdvisor</strong>
                    <span>{{ $settings['tripadvisor_rating'] ?? '4.8' }}/5 · {{ $settings['tripadvisor_review_count'] ?? '89' }}+ reviews</span>
                </div>
            </div>
            <div class="trust-strip__badge">
                <i data-lucide="star"></i>
                <div>
                    <strong>Google Reviews</strong>
                    <span>{{ $settings['google_rating'] ?? '4.9' }}/5 · {{ $settings['google_review_count'] ?? '127' }}+ reviews</span>
                </div>
            </div>
            <div class="trust-strip__badge">
                <i data-lucide="map-pin"></i>
                <div>
                    <strong>Nairobi Based</strong>
                    <span>Local experts since 2026</span>
                </div>
            </div>
        </div>
    </div>

    @php
        $pressMentions = $settings['press_mentions'] ?? [];
        if (is_string($pressMentions)) {
            $decoded = json_decode($pressMentions, true);
            $pressMentions = is_array($decoded) ? $decoded : [];
        }
    @endphp
    @if (! empty($pressMentions))
        <div class="press-strip" aria-label="As featured in">
            <div class="container press-strip__inner">
                <span class="press-strip__label">As featured in</span>
                @foreach ($pressMentions as $mention)
                    <span class="press-strip__item">{{ $mention }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <section class="section why-us" id="why-us" aria-labelledby="why-us-heading">
        <div class="container">
            <div class="section-header">
                <span class="section-header__label">Why Leyla Safari</span>
                <h2 id="why-us-heading" class="section-header__title">Rooted in Kenya. Built on Trust.</h2>
                <p class="section-header__desc">
                    We are not a booking aggregator - we are safari specialists who know every track,
                    every season, and every camp that earns its place on our itineraries.
                </p>
            </div>

            <div class="why-us__grid">
                <article class="feature-card">
                    <div class="feature-card__icon"><i data-lucide="map"></i></div>
                    <h3 class="feature-card__title">Local Expertise</h3>
                    <p class="feature-card__text">Our guides are born and raised in Kenya, with decades of combined field experience across Maasai Mara, Amboseli, Samburu, and the coast.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-card__icon"><i data-lucide="leaf"></i></div>
                    <h3 class="feature-card__title">Sustainable Travel</h3>
                    <p class="feature-card__text">We partner only with eco-certified lodges and community conservancies, ensuring your adventure supports wildlife protection and local livelihoods.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-card__icon"><i data-lucide="sparkles"></i></div>
                    <h3 class="feature-card__title">Luxury Customization</h3>
                    <p class="feature-card__text">Every itinerary is tailored - from intimate honeymoon escapes to multi-generational family safaris with private vehicles and handpicked accommodations.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-card__icon"><i data-lucide="shield-check"></i></div>
                    <h3 class="feature-card__title">Transparent Pricing</h3>
                    <p class="feature-card__text">No hidden fees, no surprise add-ons. Every day of your journey is documented before you commit, because clarity builds confidence.</p>
                </article>
            </div>
        </div>
    </section>

    @if ($featuredPackages->isNotEmpty())
        <section class="section safaris theme-fixed" id="safaris" aria-labelledby="safaris-heading">
            <div class="container">
                <div class="section-header section-header--light">
                    <span class="section-header__label">Popular Itineraries</span>
                    <h2 id="safaris-heading" class="section-header__title">Get Inspired</h2>
                    <p class="section-header__desc">These routes are not set in stone - we are happy to make them perfect for you.</p>
                </div>

                <div class="trip-type-chips" aria-label="Filter by trip type">
                    <a href="{{ route('packages.index') }}" class="trip-type-chip is-active">All Safaris</a>
                    <a href="{{ route('packages.index', ['experience_type' => 'wildlife']) }}" class="trip-type-chip">Wildlife</a>
                    <a href="{{ route('packages.index', ['traveler_type' => 'honeymooners']) }}" class="trip-type-chip">Honeymoon</a>
                    <a href="{{ route('packages.index', ['traveler_type' => 'families']) }}" class="trip-type-chip">Family</a>
                    <a href="{{ route('packages.index', ['experience_type' => 'luxury']) }}" class="trip-type-chip">Luxury</a>
                </div>

                <div class="safari-grid">
                    @foreach ($featuredPackages as $package)
                        <article class="safari-card">
                            <a href="{{ route('packages.show', $package->slug) }}" class="safari-card__link">
                                <div class="safari-card__image">
                                    @if ($package->destinations->isNotEmpty())
                                        <span class="safari-card__country">{{ $package->destinations->first()->country ?? 'Kenya' }}</span>
                                    @endif
                                    <x-lazy-img
                                        :src="$package->hero_image ?? 'images/savannah_sunset_tree.jpg'"
                                        :alt="$package->title . ' - Kenya safari tour'"
                                        :width="600"
                                        :height="400"
                                    />
                                    @if ($package->is_featured)
                                        <span class="safari-card__badge">Signature</span>
                                    @endif
                                </div>
                                <div class="safari-card__body">
                                    <h3 class="safari-card__title">{{ $package->title }}</h3>
                                    <p class="safari-card__meta">
                                        <i data-lucide="calendar" aria-hidden="true"></i> {{ $package->duration_days }} Days
                                        @if ($package->destinations->isNotEmpty())
                                            <i data-lucide="map-pin" aria-hidden="true"></i> {{ $package->destinations->pluck('name')->take(2)->join(' & ') }}
                                        @endif
                                    </p>
                                    <p class="safari-card__desc">{{ $package->short_description ?? Str::limit($package->tagline, 120) }}</p>
                                    @if ($package->starting_price)
                                        <p class="safari-card__price">From {{ $package->currency ?? 'USD' }} {{ number_format($package->starting_price, 0) }}</p>
                                    @endif
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                <div style="text-align: center; margin-top: 2rem;">
                    <a href="{{ route('packages.index') }}" class="btn btn--primary">View All Safaris</a>
                </div>
            </div>
        </section>
    @endif

    @if ($featuredPackages->isNotEmpty())
        <section class="section itineraries" id="itineraries" aria-labelledby="itineraries-heading">
            <div class="container">
                <div class="section-header">
                    <span class="section-header__label">Day by Day</span>
                    <h2 id="itineraries-heading" class="section-header__title">Itinerary Transparency</h2>
                    <p class="section-header__desc">Know exactly what each day holds - clear, honest breakdowns of where you will be, what you will see, and where you will rest.</p>
                </div>

                <div class="accordion" role="region" aria-label="Safari itineraries">
                    @foreach ($featuredPackages->take(3) as $index => $package)
                        @php $package->loadMissing('packageDays'); @endphp
                        <div class="accordion__item">
                            <button class="accordion__trigger" aria-expanded="false" aria-controls="itin-{{ $package->id }}">
                                <span class="accordion__trigger-text">
                                    <i data-lucide="binoculars" aria-hidden="true"></i>
                                    {{ $package->duration_days }}-Day {{ $package->title }}
                                </span>
                                <i data-lucide="chevron-down" class="accordion__icon" aria-hidden="true"></i>
                            </button>
                            <div class="accordion__panel" id="itin-{{ $package->id }}" hidden>
                                @if ($package->packageDays->isNotEmpty())
                                    <ol class="itinerary-list">
                                        @foreach ($package->packageDays as $day)
                                            <li class="itinerary-day">
                                                <span class="itinerary-day__num">Day {{ $day->day_number }}</span>
                                                <div class="itinerary-day__content">
                                                    <strong>{{ $day->title }}</strong>
                                                    <p>{{ $day->narrative ?? $day->morning ?? '' }}</p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ol>
                                @else
                                    <p>{{ $package->short_description }}</p>
                                @endif
                                <p style="margin-top: 1rem;">
                                    <a href="{{ route('packages.show', $package->slug) }}" class="btn btn--primary btn--sm">View Full Itinerary</a>
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($allDestinations->isNotEmpty())
        <section class="section dest-mosaic" id="discover" aria-labelledby="discover-heading">
            <div class="container">
                <div class="section-header section-header--light">
                    <span class="section-header__label">Discover Africa</span>
                    <h2 id="discover-heading" class="section-header__title">Your Guide to Africa's Wonders</h2>
                    <p class="section-header__desc">Amazing landscapes, stunning wildlife, and vibrant cultures - East Africa has it all.</p>
                </div>
                <div class="dest-mosaic__grid">
                    @foreach ($allDestinations as $destination)
                        <a href="{{ route('destinations.show', $destination->slug) }}" class="dest-mosaic__item">
                            <x-lazy-img
                                :src="$destination->hero_image ?? 'images/pond_view.jpg'"
                                :alt="$destination->name"
                                :width="400"
                                :height="300"
                            />
                            <span class="dest-mosaic__label">{{ $destination->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section inspire-hub">
        <div class="container">
            <div class="section-header">
                <span class="section-header__label">Get Inspired</span>
                <h2 class="section-header__title">Discover & Explore</h2>
            </div>
            <div class="inspire-hub__grid">
                <a href="{{ route('experiences.index') }}" class="inspire-card inspire-card--link">
                    <div class="feature-card__icon"><i data-lucide="binoculars"></i></div>
                    <h3 class="feature-card__title">Activities</h3>
                    <p class="feature-card__text">Balloon safaris, cultural visits, gorilla trekking, and bush walks to elevate your journey.</p>
                </a>
                <a href="{{ route('destinations.index') }}" class="inspire-card inspire-card--link">
                    <div class="feature-card__icon"><i data-lucide="map-pin"></i></div>
                    <h3 class="feature-card__title">National Parks</h3>
                    <p class="feature-card__text">Maasai Mara, Amboseli, Samburu, and beyond - each park offers a unique wildlife story.</p>
                </a>
                <a href="{{ route('blog.index') }}" class="inspire-card inspire-card--link">
                    <div class="feature-card__icon"><i data-lucide="book-open"></i></div>
                    <h3 class="feature-card__title">Safari Journal</h3>
                    <p class="feature-card__text">Travel tips, migration timing guides, and inspiration from our team on the ground.</p>
                </a>
            </div>
        </div>
    </section>

    @if ($annualEvents->isNotEmpty())
        <section class="section" aria-labelledby="events-heading">
            <div class="container">
                <div class="section-header">
                    <span class="section-header__label">Upcoming Events</span>
                    <h2 id="events-heading" class="section-header__title">Seasonal Safaris</h2>
                </div>
                <div class="safari-grid">
                    @foreach ($annualEvents as $event)
                        <article class="safari-card">
                            <div class="safari-card__image">
                                <x-lazy-img
                                    :src="$event->hero_image ?? 'images/hot_air_baloon_and_zebras.jpg'"
                                    :alt="$event->title"
                                    :width="600"
                                    :height="400"
                                />
                            </div>
                            <div class="safari-card__body">
                                <h3 class="safari-card__title">{{ $event->title }}</h3>
                                <p class="safari-card__meta">
                                    <i data-lucide="calendar" aria-hidden="true"></i> {{ $event->event_date->format('F j, Y') }}
                                </p>
                                <p class="safari-card__desc">{{ $event->excerpt }}</p>
                                @if ($event->package)
                                    <a href="{{ route('packages.show', $event->package->slug) }}" class="btn btn--primary btn--sm">Learn More</a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section reviews" id="reviews" aria-labelledby="reviews-heading">
        <div class="container">
            <div class="section-header">
                <span class="section-header__label">What Travellers Say</span>
                <h2 id="reviews-heading" class="section-header__title">Genuine Reviews from Our Guests</h2>
            </div>

            @if ($testimonials->isNotEmpty())
                <div class="review-carousel" data-review-carousel>
                    <button type="button" class="review-carousel__nav review-carousel__nav--prev" aria-label="Previous review" data-carousel-prev>
                        <i data-lucide="chevron-left"></i>
                    </button>
                    <div class="review-carousel__track" data-carousel-track>
                        @foreach ($testimonials as $testimonial)
                            <blockquote class="review-card">
                                <div class="review-card__stars" aria-label="{{ $testimonial->rating }} out of 5 stars">
                                    @for ($i = 1; $i <= ($testimonial->rating ?? 5); $i++)
                                        <i data-lucide="star"></i>
                                    @endfor
                                </div>
                                <p class="review-card__text">"{{ Str::limit($testimonial->content, 280) }}"</p>
                                <footer class="review-card__footer">
                                    <strong>{{ $testimonial->author_name }}</strong>
                                    @if ($testimonial->author_location)
                                        <span>· {{ $testimonial->author_location }}</span>
                                    @endif
                                    @if ($testimonial->reviewed_at)
                                        <time datetime="{{ $testimonial->reviewed_at->toDateString() }}">{{ $testimonial->reviewed_at->format('F j, Y') }}</time>
                                    @endif
                                    @if ($testimonial->source)
                                        <span class="review-card__source">Published on {{ ucfirst($testimonial->source) }}</span>
                                    @endif
                                </footer>
                            </blockquote>
                        @endforeach
                    </div>
                    <button type="button" class="review-carousel__nav review-carousel__nav--next" aria-label="Next review" data-carousel-next>
                        <i data-lucide="chevron-right"></i>
                    </button>
                </div>
            @endif

            <div class="reviews__summary">
                <div class="review-widget review-widget--google">
                    <div class="review-widget__header">
                        <i data-lucide="star" aria-hidden="true"></i>
                        <h3 class="review-widget__title">Google Reviews</h3>
                    </div>
                    <div class="review-widget__mock">
                        <div class="review-widget__stars" aria-hidden="true">
                            @for ($i = 0; $i < 5; $i++) <i data-lucide="star"></i> @endfor
                        </div>
                        <span>{{ $settings['google_rating'] ?? '4.9' }} average · {{ $settings['google_review_count'] ?? '127' }} reviews</span>
                    </div>
                </div>
                <div class="review-widget review-widget--tripadvisor">
                    <div class="review-widget__header">
                        <i data-lucide="award" aria-hidden="true"></i>
                        <h3 class="review-widget__title">TripAdvisor</h3>
                    </div>
                    <div class="review-widget__mock">
                        <div class="review-widget__stars" aria-hidden="true">
                            @for ($i = 0; $i < 5; $i++) <i data-lucide="star"></i> @endfor
                        </div>
                        <span>Travellers' Choice · {{ $settings['tripadvisor_rating'] ?? '4.8' }} rating</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section guide-spotlight">
        <div class="container guide-spotlight__inner">
            <div class="guide-spotlight__copy">
                <span class="section-header__label">Your Guide During the Journey</span>
                <h2 class="section-header__title">Professionalism, Knowledge & Friendliness</h2>
                <p class="section-header__desc">Our driver-guides know the bush like no other - you sit back, we navigate the wild.</p>
                <div class="guide-spotlight__profile">
                    <h3>{{ $settings['lead_guide_name'] ?? 'James Ochieng' }}</h3>
                    <p>{{ $settings['lead_guide_bio'] ?? 'Lead safari guide with over fifteen years in the Maasai Mara and across Kenya.' }}</p>
                </div>
            </div>
            <div class="guide-spotlight__media">
                <x-lazy-img src="images/blacknwhite_safari_banner.jpg" alt="Safari guide with elephants on the plains" :width="600" :height="450" />
            </div>
        </div>
    </section>

    <section class="section expert-cta-wrap">
        <div class="container expert-cta">
            <div class="expert-cta__copy">
                <span class="section-header__label">Call an Expert</span>
                <h2 class="section-header__title">Receive a Free, No-Obligation Quote</h2>
                <p>Our Nairobi specialists are here to assist you - by phone, WhatsApp, or email.</p>
            </div>
            <div class="expert-cta__actions">
                <a href="tel:{{ preg_replace('/\s+/', '', $settings['phone'] ?? '+254712345678') }}" class="btn btn--primary">
                    <i data-lucide="phone"></i> {{ $settings['phone'] ?? '+254 712 345 678' }}
                </a>
                <a href="{{ route('contact') }}" class="btn btn--secondary">Request Travel Proposal</a>
            </div>
        </div>
    </section>

    <section class="section inquiry" id="inquiry" aria-labelledby="inquiry-heading">
        <div class="container inquiry__inner">
            <div class="inquiry__intro">
                <span class="section-header__label">Get in Touch</span>
                <h2 id="inquiry-heading" class="section-header__title">Start Your Journey</h2>
                <p class="section-header__desc">Tell us your dream safari and we will craft a personalised proposal - no obligation, no pressure, just expert guidance.</p>
                <div class="inquiry__contacts">
                    <a href="tel:{{ preg_replace('/\s+/', '', $settings['phone'] ?? '+254712345678') }}" class="inquiry__contact">
                        <i data-lucide="phone" aria-hidden="true"></i>
                        <span>{{ $settings['phone'] ?? '+254 712 345 678' }}</span>
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings['whatsapp'] ?? '254712345678') }}" class="inquiry__contact" target="_blank" rel="noopener noreferrer">
                        <i data-lucide="message-circle" aria-hidden="true"></i>
                        <span>Chat on WhatsApp</span>
                    </a>
                    @php $inquiryEmail = is_array($settings['emails'] ?? null) ? ($settings['emails'][1] ?? $settings['emails'][0] ?? 'inquiry@leylasafaritours.com') : 'inquiry@leylasafaritours.com'; @endphp
                    <a href="mailto:{{ $inquiryEmail }}" class="inquiry__contact">
                        <i data-lucide="mail" aria-hidden="true"></i>
                        <span>{{ $inquiryEmail }}</span>
                    </a>
                </div>
            </div>

            <form class="inquiry-form" action="{{ route('enquiries.store') }}" method="POST">
                @csrf
                <input type="hidden" name="source" value="homepage">

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Your name">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="+254...">
                </div>
                <div class="form-group">
                    <label for="travel_dates">Preferred Travel Dates</label>
                    <input type="text" id="travel_dates" name="travel_dates" value="{{ old('travel_dates') }}" placeholder="e.g. July 2026">
                </div>
                <div class="form-group form-group--full">
                    <label for="message">Tell us about your trip</label>
                    <textarea id="message" name="message" rows="3" placeholder="Dates, group size, special requests...">{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="btn btn--primary btn--full">
                    <i data-lucide="send" aria-hidden="true"></i>
                    Send Inquiry
                </button>
            </form>
        </div>
    </section>
@endsection

@push('styles')
<link rel="preload" as="image" href="{{ asset('images/savannah_sunset_tree.webp') }}" type="image/webp">
<style>
    .safari-card__link { display: block; color: inherit; }
    .safari-card__price { margin-top: 0.75rem; font-weight: 600; color: var(--color-savanna); }
    .destination-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
    .destination-card { display: block; background: var(--color-white); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); transition: transform var(--transition), box-shadow var(--transition); }
    .destination-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
    .destination-card__image img,
    .destination-card__image picture img { width: 100%; height: 180px; object-fit: cover; }
    .destination-card__body { padding: 1.25rem; }
    .destination-card__title { font-family: var(--font-display); font-size: 1.25rem; margin-bottom: 0.25rem; }
    .destination-card__region { font-size: 0.85rem; color: var(--color-savanna); margin-bottom: 0.5rem; }
    .destination-card__excerpt { font-size: 0.9rem; color: var(--color-text-muted); }
    .btn--sm { padding: 0.5rem 1rem; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.35rem; }
</style>
@endpush
