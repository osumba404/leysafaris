@extends('layouts.public')

@section('title', 'About Us | Leyla Safari Tours')

@section('content')
    <section class="hero hero--compact" aria-labelledby="about-heading">
        <div class="hero__media">
            <img src="{{ asset('images/modern_grass_thatched_huts.jpg') }}" alt="Luxury safari lodge in Kenya">
            <div class="hero__overlay"></div>
        </div>
        <div class="container hero__content">
            <p class="hero__eyebrow">Our Story</p>
            <h1 id="about-heading" class="hero__title">Born in Kenya.<br><em>Built on Passion.</em></h1>
        </div>
    </section>

    <section class="section why-us">
        <div class="container">
            <div class="section-header">
                <span class="section-header__label">About {{ $settings['site_name'] ?? 'Leyla Safari Tours' }}</span>
                <h2 class="section-header__title">Your Local Safari Partner</h2>
                <p class="section-header__desc">
                    {{ $settings['site_name'] ?? 'Leyla Safari Tours' }} is a Nairobi-based safari company founded by Kenyan guides who grew up
                    on the edge of the wild. We believe the best safaris are personal — built on local knowledge, honest pricing,
                    and a deep respect for the land and communities we work with.
                </p>
            </div>

            <div class="why-us__grid">
                <article class="feature-card">
                    <div class="feature-card__icon"><i data-lucide="heart"></i></div>
                    <h3 class="feature-card__title">Our Mission</h3>
                    <p class="feature-card__text">To share Kenya's extraordinary wildlife and landscapes with travellers who seek authentic, responsibly crafted adventures — while ensuring tourism benefits local communities and conservation.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-card__icon"><i data-lucide="users"></i></div>
                    <h3 class="feature-card__title">Our Team</h3>
                    <p class="feature-card__text">Every guide on our team is KPSGA-certified with years of field experience. From senior naturalists to logistics coordinators, we handle every detail so you can focus on the experience.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-card__icon"><i data-lucide="award"></i></div>
                    <h3 class="feature-card__title">Our Promise</h3>
                    <p class="feature-card__text">Transparent itineraries, no hidden costs, and a dedicated point of contact from your first enquiry to your return home. Your trust is our most valued asset.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-card__icon"><i data-lucide="leaf"></i></div>
                    <h3 class="feature-card__title">Conservation</h3>
                    <p class="feature-card__text">We partner with eco-certified lodges and community conservancies. A portion of every booking supports local wildlife protection initiatives.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section inquiry">
        <div class="container" style="text-align: center;">
            <h2 class="section-header__title" style="margin-bottom: 1rem;">Ready to Explore?</h2>
            <p class="section-header__desc" style="margin-bottom: 1.5rem;">Let us craft your perfect Kenyan safari.</p>
            <a href="{{ route('contact') }}" class="btn btn--primary">Get in Touch</a>
        </div>
    </section>
@endsection

@push('styles')
<style>.hero--compact { min-height: 45vh; }</style>
@endpush
