@extends('layouts.public')

@section('title', ($settings['site_name'] ?? 'Leyla Safari Tours') . ' | Premium Kenya Safari Tours & East Africa Travel')
@section('meta_description', 'Book authentic Kenya safari tours with Leyla Safari Tours — Maasai Mara migration, Amboseli elephants, Samburu & custom East Africa itineraries. Nairobi-based experts. Request a quote.')
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
      "name": "Leyla Safari Tours",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/savannah_sunset_tree.jpg') }}",
      "image": "{{ asset('images/savannah_sunset_tree.jpg') }}",
      "description": "Premium Kenya and East Africa safari tours from Nairobi — Maasai Mara, Amboseli, Samburu and tailor-made wildlife journeys.",
      "telephone": "{{ $settings['phone'] ?? '+254712345678' }}",
      "email": "info@leylasafaritours.com",
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
    <section class="hero" aria-labelledby="hero-heading">
        <div class="hero__media">
            <x-optimized-img
                src="images/savannah_sunset_tree.jpg"
                alt="Golden sunset over the Kenyan savannah with acacia trees — Leyla Safari Tours"
                :width="1920"
                :height="1080"
                :priority="true"
            />
            <div class="hero__overlay"></div>
        </div>
        <div class="container hero__content">
            <p class="hero__eyebrow">Locally guided · Since 2026</p>
            <h1 id="hero-heading" class="hero__title">
                Where the Wild<br>
                <em>Meets Wonder</em>
            </h1>
            <p class="hero__subtitle">
                From the thunder of the Great Migration to silent mornings above the Mara,
                {{ $settings['site_name'] ?? 'Leyla Safari Tours' }} crafts journeys that stay with you long after you return home.
            </p>
            <div class="hero__scroll-hint" aria-hidden="true">
                <span>Explore</span>
                <i data-lucide="chevron-down"></i>
            </div>
        </div>
    </section>

    <section class="section why-us" id="why-us" aria-labelledby="why-us-heading">
        <div class="container">
            <div class="section-header">
                <span class="section-header__label">Why Leyla Safari</span>
                <h2 id="why-us-heading" class="section-header__title">Rooted in Kenya. Built on Trust.</h2>
                <p class="section-header__desc">
                    We are not a booking aggregator — we are safari specialists who know every track,
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
                    <p class="feature-card__text">Every itinerary is tailored — from intimate honeymoon escapes to multi-generational family safaris with private vehicles and handpicked accommodations.</p>
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
        <section class="section safaris" id="safaris" aria-labelledby="safaris-heading">
            <div class="container">
                <div class="section-header section-header--light">
                    <span class="section-header__label">Our Safaris</span>
                    <h2 id="safaris-heading" class="section-header__title">Curated Adventures</h2>
                    <p class="section-header__desc">Handpicked experiences designed around Kenya's greatest wildlife spectacles and landscapes.</p>
                </div>

                <div class="safari-grid">
                    @foreach ($featuredPackages as $package)
                        <article class="safari-card">
                            <a href="{{ route('packages.show', $package->slug) }}" class="safari-card__link">
                                <div class="safari-card__image">
                                    <x-lazy-img
                                        :src="$package->hero_image ?? 'images/savannah_sunset_tree.jpg'"
                                        :alt="$package->title . ' — Kenya safari tour'"
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
                    <p class="section-header__desc">Know exactly what each day holds — clear, honest breakdowns of where you will be, what you will see, and where you will rest.</p>
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

    @if ($destinations->isNotEmpty())
        <section class="section destinations" id="destinations" aria-labelledby="destinations-heading">
            <div class="container">
                <div class="section-header">
                    <span class="section-header__label">Destination Guides</span>
                    <h2 id="destinations-heading" class="section-header__title">Kenya's Wild Places</h2>
                    <p class="section-header__desc">Explore the parks, reserves, and landscapes that define East Africa's greatest safari destinations.</p>
                </div>

                <div class="destination-grid">
                    @foreach ($destinations as $destination)
                        <a href="{{ route('destinations.show', $destination->slug) }}" class="destination-card">
                            <div class="destination-card__image">
                                <x-lazy-img
                                    :src="$destination->hero_image ?? 'images/pond_view.jpg'"
                                    :alt="$destination->name . ' safari destination'"
                                    :width="500"
                                    :height="333"
                                />
                            </div>
                            <div class="destination-card__body">
                                <h3 class="destination-card__title">{{ $destination->name }}</h3>
                                <p class="destination-card__region">{{ $destination->region ?? $destination->country }}</p>
                                <p class="destination-card__excerpt">{{ $destination->excerpt }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div style="text-align: center; margin-top: 2rem;">
                    <a href="{{ route('destinations.index') }}" class="btn btn--primary">All Destinations</a>
                </div>
            </div>
        </section>
    @endif

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
                <span class="section-header__label">Guest Reviews</span>
                <h2 id="reviews-heading" class="section-header__title">Stories from the Savannah</h2>
            </div>

            @if ($testimonials->isNotEmpty())
                <div class="testimonial-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    @foreach ($testimonials as $testimonial)
                        <blockquote class="feature-card" style="margin: 0;">
                            <div style="color: var(--color-savanna); margin-bottom: 0.5rem;">
                                @for ($i = 1; $i <= ($testimonial->rating ?? 5); $i++)
                                    <i data-lucide="star" style="width:16px;height:16px;display:inline;"></i>
                                @endfor
                            </div>
                            <p class="feature-card__text">"{{ $testimonial->content }}"</p>
                            <footer style="margin-top: 1rem; font-size: 0.85rem; color: var(--color-text-muted);">
                                <strong>{{ $testimonial->author_name }}</strong>
                                @if ($testimonial->author_location)
                                    · {{ $testimonial->author_location }}
                                @endif
                            </footer>
                        </blockquote>
                    @endforeach
                </div>
            @endif

            <div class="reviews__widgets">
                <div class="review-widget review-widget--google">
                    <div class="review-widget__header">
                        <i data-lucide="star" aria-hidden="true"></i>
                        <h3 class="review-widget__title">Google Reviews</h3>
                    </div>
                    <div class="review-widget__placeholder" role="img" aria-label="Google reviews widget placeholder">
                        <i data-lucide="globe" aria-hidden="true"></i>
                        <p>Live Google review feed</p>
                        <span class="review-widget__note">Embed your Google review widget here</span>
                        <div class="review-widget__mock">
                            <div class="review-widget__stars" aria-hidden="true">
                                @for ($i = 0; $i < 5; $i++) <i data-lucide="star"></i> @endfor
                            </div>
                            <span>4.9 average · 127 reviews</span>
                        </div>
                    </div>
                </div>
                <div class="review-widget review-widget--tripadvisor">
                    <div class="review-widget__header">
                        <i data-lucide="award" aria-hidden="true"></i>
                        <h3 class="review-widget__title">TripAdvisor</h3>
                    </div>
                    <div class="review-widget__placeholder" role="img" aria-label="TripAdvisor reviews widget placeholder">
                        <i data-lucide="globe" aria-hidden="true"></i>
                        <p>Live TripAdvisor review feed</p>
                        <span class="review-widget__note">Embed your TripAdvisor review widget here</span>
                        <div class="review-widget__mock">
                            <div class="review-widget__stars" aria-hidden="true">
                                @for ($i = 0; $i < 5; $i++) <i data-lucide="star"></i> @endfor
                            </div>
                            <span>Travellers' Choice · 4.8 rating</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section inquiry" id="inquiry" aria-labelledby="inquiry-heading">
        <div class="container inquiry__inner">
            <div class="inquiry__intro">
                <span class="section-header__label">Get in Touch</span>
                <h2 id="inquiry-heading" class="section-header__title">Start Your Journey</h2>
                <p class="section-header__desc">Tell us your dream safari and we will craft a personalised proposal — no obligation, no pressure, just expert guidance.</p>
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
