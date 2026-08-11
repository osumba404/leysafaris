@extends('layouts.public')

@section('title', ($destination->seo_title ?? $destination->name . ' Safari Guide') . ' | Leyla Safari Tours')
@section('meta_description', $destination->seo_description ?? Str::limit(strip_tags($destination->excerpt ?? ''), 155))
@section('meta_keywords', $destination->name . ', Kenya safari, ' . ($destination->region ?? $destination->country) . ', wildlife travel guide')
@section('canonical', route('destinations.show', $destination->slug))
@section('og_type', 'article')
@section('og_image', asset($destination->hero_image ?? 'images/pond_view.jpg'))

@push('structured_data')
@php
    $destSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'TouristDestination',
        'name' => $destination->name,
        'description' => Str::limit(strip_tags($destination->excerpt ?? ''), 300),
        'url' => route('destinations.show', $destination->slug),
        'image' => asset($destination->hero_image ?? 'images/pond_view.jpg'),
        'containedInPlace' => ['@type' => 'Country', 'name' => $destination->country ?? 'Kenya'],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($destSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
    <section class="hero hero--compact" aria-labelledby="dest-heading">
        <div class="hero__media">
            <x-optimized-img
                :src="$destination->hero_image ?? 'images/pond_view.jpg'"
                :alt="$destination->name . ' safari destination - Leyla Safari Tours'"
                :width="1920"
                :height="810"
                :priority="true"
            />
            <div class="hero__overlay"></div>
        </div>
        <div class="container hero__content">
            <p class="hero__eyebrow">{{ $destination->region ?? $destination->country }}</p>
            <h1 id="dest-heading" class="hero__title">{{ $destination->name }}</h1>
            <p class="hero__subtitle">{{ $destination->excerpt }}</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2.5rem;">
                <div>
                    @if ($destination->description)
                        <div class="destination-content" style="margin-bottom: 2rem;">{!! nl2br(e($destination->description)) !!}</div>
                    @endif

                    @if ($destination->signature_wildlife)
                        <div class="feature-card" style="margin-bottom: 2rem;">
                            <h2 class="feature-card__title">Signature Wildlife</h2>
                            <p class="feature-card__text">{{ $destination->signature_wildlife }}</p>
                        </div>
                    @endif

                    @if (! empty($destination->gallery))
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                            @foreach ($destination->gallery as $image)
                                <x-lazy-img
                                    :src="$image"
                                    :alt="$destination->name"
                                    :width="300"
                                    :height="160"
                                    style="border-radius: var(--radius-sm); height: 160px; object-fit: cover; width: 100%;"
                                />
                            @endforeach
                        </div>
                    @endif
                </div>

                <aside>
                    @if ($destination->best_time)
                        <div class="feature-card" style="margin-bottom: 1rem;">
                            <div class="feature-card__icon"><i data-lucide="sun"></i></div>
                            <h3 class="feature-card__title">Best Time to Visit</h3>
                            <p class="feature-card__text">{{ $destination->best_time }}</p>
                        </div>
                    @endif

                    @if (! empty($destination->facts))
                        <div class="feature-card">
                            <h3 class="feature-card__title">Quick Facts</h3>
                            <ul style="margin-top: 1rem; display: grid; gap: 0.75rem;">
                                @foreach ($destination->facts as $fact)
                                    <li style="display: flex; gap: 0.5rem; align-items: center; font-size: 0.9rem;">
                                        <i data-lucide="{{ $fact['icon'] ?? 'info' }}" style="width:16px;color:var(--color-savanna);"></i>
                                        {{ $fact['label'] ?? '' }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>

    @if ($relatedPackages->isNotEmpty())
        <section class="section safaris">
            <div class="container">
                <div class="section-header section-header--light">
                    <h2 class="section-header__title">Safaris in {{ $destination->name }}</h2>
                </div>
                <div class="safari-grid">
                    @foreach ($relatedPackages as $package)
                        <article class="safari-card">
                            <a href="{{ route('packages.show', $package->slug) }}" style="color: inherit;">
                                <div class="safari-card__image">
                                    <x-lazy-img
                                        :src="$package->hero_image ?? 'images/savannah_sunset_tree.jpg'"
                                        :alt="$package->title"
                                        :width="400"
                                        :height="267"
                                    />
                                </div>
                                <div class="safari-card__body">
                                    <h3 class="safari-card__title">{{ $package->title }}</h3>
                                    <p class="safari-card__meta">
                                        <i data-lucide="calendar"></i> {{ $package->duration_days }} Days
                                    </p>
                                    @if ($package->starting_price)
                                        <p style="margin-top: 0.5rem; color: var(--color-savanna); font-weight: 600;">
                                            From {{ $package->currency ?? 'USD' }} {{ number_format($package->starting_price, 0) }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
                @if ($relatedPackages->hasPages())
                    <div style="margin-top: 2rem;">{{ $relatedPackages->links() }}</div>
                @endif
            </div>
        </section>
    @endif
@endsection

@push('styles')
<style>
    .hero--compact { min-height: 45vh; }
    @media (max-width: 768px) {
        .section .container > div { grid-template-columns: 1fr !important; }
    }
</style>
@endpush
