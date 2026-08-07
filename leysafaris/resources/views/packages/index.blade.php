@extends('layouts.public')

@section('title', 'Safari Packages | Leyla Safari Tours')

@section('content')
    <section class="section" style="padding-top: calc(var(--header-height) + var(--trust-bar-height) + 2rem);">
        <div class="container">
            <div class="section-header">
                <span class="section-header__label">Our Safaris</span>
                <h1 class="section-header__title">Curated Safari Packages</h1>
                <p class="section-header__desc">Filter by destination, duration, experience type, and budget to find your perfect Kenyan adventure.</p>
            </div>

            <form class="filter-bar" method="GET" action="{{ route('packages.index') }}" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 2rem; padding: 1.5rem; background: var(--color-white); border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
                <div class="form-group">
                    <label for="destination">Destination</label>
                    <select id="destination" name="destination">
                        <option value="">All destinations</option>
                        @foreach ($destinations as $dest)
                            <option value="{{ $dest->id }}" @selected(request('destination') == $dest->id || request('destination') == $dest->slug)>{{ $dest->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="duration">Duration (days)</label>
                    <input type="number" id="duration" name="duration" min="1" max="30" value="{{ request('duration') }}" placeholder="Any">
                </div>
                <div class="form-group">
                    <label for="experience_type">Experience</label>
                    <select id="experience_type" name="experience_type">
                        <option value="">All types</option>
                        @foreach (['wildlife', 'migration', 'photography', 'luxury', 'family', 'honeymoon', 'adventure', 'beach'] as $type)
                            <option value="{{ $type }}" @selected(request('experience_type') === $type)>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="budget">Budget</label>
                    <select id="budget" name="budget">
                        <option value="">Any budget</option>
                        <option value="under_1000" @selected(request('budget') === 'under_1000')>Under $1,000</option>
                        <option value="1000_2500" @selected(request('budget') === '1000_2500')>$1,000 – $2,500</option>
                        <option value="2500_5000" @selected(request('budget') === '2500_5000')>$2,500 – $5,000</option>
                        <option value="5000_plus" @selected(request('budget') === '5000_plus')>$5,000+</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sort">Sort by</label>
                    <select id="sort" name="sort">
                        <option value="popular" @selected(request('sort', 'popular') === 'popular')>Most Popular</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                        <option value="duration_asc" @selected(request('sort') === 'duration_asc')>Duration: Shortest</option>
                        <option value="duration_desc" @selected(request('sort') === 'duration_desc')>Duration: Longest</option>
                        <option value="newest" @selected(request('sort') === 'newest')>Newest</option>
                    </select>
                </div>
                <div class="form-group" style="display: flex; align-items: flex-end; gap: 0.5rem;">
                    <button type="submit" class="btn btn--primary">Filter</button>
                    <a href="{{ route('packages.index') }}" class="btn btn--secondary" style="padding: 0.65rem 1rem; background: var(--color-mist); border-radius: var(--radius-sm);">Reset</a>
                </div>
            </form>

            @if ($packages->isEmpty())
                <p style="text-align: center; color: var(--color-text-muted); padding: 3rem 0;">No safaris match your filters. <a href="{{ route('packages.index') }}">View all packages</a> or <a href="{{ route('contact') }}">contact us</a> for a custom itinerary.</p>
            @else
                <div class="safari-grid">
                    @foreach ($packages as $package)
                        <article class="safari-card">
                            <a href="{{ route('packages.show', $package->slug) }}" style="display: block; color: inherit;">
                                <div class="safari-card__image">
                                    <img src="{{ asset($package->hero_image ?? 'images/savannah_sunset_tree.jpg') }}" alt="{{ $package->title }}" loading="lazy">
                                    @if ($package->is_featured)
                                        <span class="safari-card__badge">Featured</span>
                                    @endif
                                </div>
                                <div class="safari-card__body">
                                    <h2 class="safari-card__title">{{ $package->title }}</h2>
                                    <p class="safari-card__meta">
                                        <i data-lucide="calendar" aria-hidden="true"></i> {{ $package->duration_days }} Days
                                        @if ($package->destinations->isNotEmpty())
                                            <i data-lucide="map-pin" aria-hidden="true"></i> {{ $package->destinations->pluck('name')->take(2)->join(', ') }}
                                        @endif
                                    </p>
                                    <p class="safari-card__desc">{{ $package->short_description ?? Str::limit($package->tagline, 140) }}</p>
                                    @if ($package->starting_price)
                                        <p style="margin-top: 0.75rem; font-weight: 600; color: var(--color-savanna);">
                                            From {{ $package->currency ?? 'USD' }} {{ number_format($package->starting_price, 0) }}
                                            @if ($package->price_note)<span style="font-weight: 400; font-size: 0.85rem;"> · {{ $package->price_note }}</span>@endif
                                        </p>
                                    @endif
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                <div style="margin-top: 2rem;">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
<style>
    .filter-bar label { font-size: 0.8rem; font-weight: 500; color: var(--color-text-muted); display: block; margin-bottom: 0.35rem; }
    .filter-bar select, .filter-bar input { width: 100%; padding: 0.55rem 0.75rem; border: 1px solid var(--color-mist); border-radius: var(--radius-sm); background: var(--color-cream); }
</style>
@endpush
