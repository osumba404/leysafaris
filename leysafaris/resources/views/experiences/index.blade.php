@extends('layouts.public')

@section('title', 'Safari Experiences & Activities | Balloon, Culture & Wildlife | Leyla Safari Tours')
@section('meta_description', 'Discover Kenya safari experiences - game drives, hot air balloon flights, cultural visits, gorilla trekking and more. Add to your custom itinerary with Leyla Safari Tours.')
@section('canonical', route('experiences.index'))

@section('content')
    <section class="section page-top">
        <div class="container">
            <div class="section-header">
                <span class="section-header__label">Beyond Game Drives</span>
                <h1 class="section-header__title">Safari Experiences</h1>
                <p class="section-header__desc">Hot air balloons, bush walks, cultural visits, and more - elevate your safari with curated experiences.</p>
            </div>

            <div class="experience-grid">
                @forelse ($experiences as $experience)
                    <article class="feature-card" style="padding: 0; overflow: hidden;">
                        @if ($experience->image)
                            <x-lazy-img :src="$experience->image" :alt="$experience->name" :width="600" :height="360" style="width:100%;height:180px;object-fit:cover;" />
                        @endif
                        <div style="padding: 1.25rem;">
                            @if ($experience->type)
                                <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-savanna);">{{ $experience->type }}</span>
                            @endif
                            <h2 class="feature-card__title" style="margin-top: 0.35rem;">{{ $experience->name }}</h2>
                            <p class="feature-card__text">{{ $experience->excerpt ?? Str::limit($experience->description, 160) }}</p>
                            <div style="display: flex; gap: 1rem; margin-top: 1rem; font-size: 0.85rem; color: var(--color-text-muted);">
                                @if ($experience->duration_hours)
                                    <span><i data-lucide="clock" style="width:14px;display:inline;"></i> {{ $experience->duration_hours }}h</span>
                                @endif
                                @if ($experience->starting_price)
                                    <span>From {{ $experience->currency ?? 'USD' }} {{ number_format($experience->starting_price, 0) }}</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <p style="grid-column: 1 / -1; text-align: center; color: var(--color-text-muted);">Experiences coming soon.</p>
                @endforelse
            </div>

            @if ($experiences->hasPages())
                <div style="margin-top: 2rem;">{{ $experiences->links() }}</div>
            @endif

            <div style="text-align: center; margin-top: 3rem;">
                <p style="margin-bottom: 1rem; color: var(--color-text-muted);">Want to combine experiences into a custom itinerary?</p>
                <a href="{{ route('contact') }}" class="btn btn--primary">Plan Your Safari</a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .experience-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
</style>
@endpush
