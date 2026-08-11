@extends('layouts.public')

@section('title', 'Safari Destinations in Kenya & East Africa | Leyla Safari Tours')
@section('meta_description', 'Explore Kenya safari destinations - Maasai Mara, Amboseli, Samburu, Serengeti, Bwindi & Diani Beach. Best times to visit, wildlife highlights and linked safari packages.')
@section('meta_keywords', 'Kenya safari destinations, Maasai Mara guide, Amboseli travel, Samburu safari, East Africa parks')
@section('canonical', route('destinations.index'))

@section('content')
    <section class="section page-top">
        <div class="container">
            <div class="section-header">
                <span class="section-header__label">Explore Kenya</span>
                <h1 class="section-header__title">Safari Destinations</h1>
                <p class="section-header__desc">From the Maasai Mara to the northern frontier - discover the parks and reserves that define Kenya's wild heart.</p>
            </div>

            <div class="destination-grid">
                @forelse ($destinations as $destination)
                    <a href="{{ route('destinations.show', $destination->slug) }}" class="destination-card">
                        <div class="destination-card__image">
                            <x-lazy-img
                                :src="$destination->hero_image ?? 'images/pond_view.jpg'"
                                :alt="$destination->name"
                                :width="500"
                                :height="333"
                            />
                        </div>
                        <div class="destination-card__body">
                            <h2 class="destination-card__title">{{ $destination->name }}</h2>
                            <p class="destination-card__region">{{ $destination->region ?? $destination->country }}</p>
                            <p class="destination-card__excerpt">{{ $destination->excerpt }}</p>
                            @if ($destination->packages_count ?? 0)
                                <p style="font-size: 0.85rem; color: var(--color-savanna); margin-top: 0.5rem;">
                                    {{ $destination->packages_count }} {{ Str::plural('safari', $destination->packages_count) }}
                                </p>
                            @endif
                        </div>
                    </a>
                @empty
                    <p style="grid-column: 1 / -1; text-align: center; color: var(--color-text-muted);">Destinations coming soon.</p>
                @endforelse
            </div>

            @if ($destinations->hasPages())
                <div style="margin-top: 2rem;">{{ $destinations->links() }}</div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
<style>
    .destination-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
    .destination-card { display: block; background: var(--color-white); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); transition: transform var(--transition), box-shadow var(--transition); color: inherit; }
    .destination-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
    .destination-card__image img { width: 100%; height: 200px; object-fit: cover; }
    .destination-card__body { padding: 1.25rem; }
    .destination-card__title { font-family: var(--font-display); font-size: 1.35rem; margin-bottom: 0.25rem; }
    .destination-card__region { font-size: 0.85rem; color: var(--color-savanna); margin-bottom: 0.5rem; }
    .destination-card__excerpt { font-size: 0.9rem; color: var(--color-text-muted); line-height: 1.5; }
</style>
@endpush
