@extends('layouts.public')

@section('title', 'Practical Information | Leyla Safari Tours')
@section('meta_description', 'Safari practical information - travel insurance, payments, flying doctors, responsible travel, guarantees, and when to visit Kenya with Leyla Safari Tours.')
@section('canonical', route('practical.index'))

@section('content')
    <section class="hero hero--compact" aria-labelledby="practical-heading">
        <div class="hero__media">
            <x-optimized-img src="images/modern_grass_thached_huts.jpg" alt="Luxury safari lodge in Kenya" :width="1920" :height="810" :priority="true" />
            <div class="hero__overlay"></div>
        </div>
        <div class="container hero__content">
            <p class="hero__eyebrow">Know Before You Go</p>
            <h1 id="practical-heading" class="hero__title">Practical<br><em>Information</em></h1>
        </div>
    </section>

    <section class="section page-top">
        <div class="container">
            <div class="why-us__grid">
                <article class="feature-card inspire-card">
                    <div class="feature-card__icon"><i data-lucide="shield-check"></i></div>
                    <h2 class="feature-card__title">Our Guarantees</h2>
                    <p class="feature-card__text">Transparent day-by-day itineraries before you commit. Clear pricing with no hidden fees. Nairobi-based support throughout your journey and a dedicated contact by phone and WhatsApp.</p>
                </article>
                <article class="feature-card inspire-card">
                    <div class="feature-card__icon"><i data-lucide="leaf"></i></div>
                    <h2 class="feature-card__title">Responsible Travel</h2>
                    <p class="feature-card__text">We partner with eco-certified lodges and community conservancies. Your safari supports wildlife protection, fair employment for local guides, and community-led tourism initiatives.</p>
                </article>
                <article class="feature-card inspire-card">
                    <div class="feature-card__icon"><i data-lucide="heart-pulse"></i></div>
                    <h2 class="feature-card__title">Flying Doctors & Safety</h2>
                    <p class="feature-card__text">We recommend AMREF Flying Doctors evacuation cover for East Africa. Our guides are first-aid trained and carry satellite communication in remote areas.</p>
                </article>
                <article class="feature-card inspire-card">
                    <div class="feature-card__icon"><i data-lucide="credit-card"></i></div>
                    <h2 class="feature-card__title">Easy Payments</h2>
                    <p class="feature-card__text">Secure your trip with a deposit via bank transfer, card, M-Pesa, or PayPal. We provide a clear payment schedule and confirmation letter before you travel.</p>
                </article>
                <article class="feature-card inspire-card">
                    <div class="feature-card__icon"><i data-lucide="umbrella"></i></div>
                    <h2 class="feature-card__title">Travel Insurance</h2>
                    <p class="feature-card__text">Comprehensive insurance covering medical evacuation, cancellation, and safari activities is required. We can recommend trusted providers when you book.</p>
                </article>
                <article class="feature-card inspire-card">
                    <div class="feature-card__icon"><i data-lucide="sun"></i></div>
                    <h2 class="feature-card__title">When to Visit Kenya</h2>
                    <p class="feature-card__text">July-October for the Great Migration. January-March for calving season and fewer crowds. Green season (April-June, November) offers lush landscapes and excellent value.</p>
                </article>
            </div>

            <div style="text-align: center; margin-top: 2.5rem; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('faq.index') }}" class="btn btn--secondary">Read All FAQs</a>
                <a href="{{ route('destinations.index') }}" class="btn btn--secondary">Destination Guides</a>
                <a href="{{ route('contact') }}" class="btn btn--primary">Request Travel Proposal</a>
            </div>
        </div>
    </section>
@endsection
