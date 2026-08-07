@extends('layouts.public')

@section('title', 'My Account | Leyla Safari Tours')

@section('content')
    <section class="section" style="padding-top: calc(var(--header-height) + var(--trust-bar-height) + 2rem);">
        <div class="container">
            <div class="section-header" style="text-align: left; margin-bottom: 2rem;">
                <span class="section-header__label">My Account</span>
                <h1 class="section-header__title">Welcome, {{ auth()->user()->name }}</h1>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <h2 style="font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 1rem;">My Enquiries</h2>
                    @forelse ($enquiries as $enquiry)
                        <div class="feature-card" style="margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 0.5rem;">
                                <strong>{{ $enquiry->package?->title ?? 'Custom Safari Enquiry' }}</strong>
                                <span style="font-size: 0.75rem; padding: 0.2rem 0.5rem; background: var(--color-mist); border-radius: 999px; text-transform: capitalize;">{{ str_replace('_', ' ', $enquiry->status) }}</span>
                            </div>
                            <p style="font-size: 0.85rem; color: var(--color-text-muted);">{{ $enquiry->created_at->format('M j, Y') }}</p>
                            @if ($enquiry->travel_dates)
                                <p style="font-size: 0.9rem; margin-top: 0.35rem;">Dates: {{ $enquiry->travel_dates }}</p>
                            @endif
                            @if ($enquiry->quotes->isNotEmpty())
                                <p style="font-size: 0.9rem; margin-top: 0.35rem; color: var(--color-moss);">{{ $enquiry->quotes->count() }} quote(s) received</p>
                            @endif
                        </div>
                    @empty
                        <p style="color: var(--color-text-muted);">No enquiries yet. <a href="{{ route('contact') }}">Start planning your safari</a>.</p>
                    @endforelse
                </div>

                <div>
                    <h2 style="font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 1rem;">Wishlist</h2>
                    @forelse ($wishlist as $item)
                        @if ($item->package)
                            <div class="feature-card" style="margin-bottom: 1rem; display: flex; gap: 1rem; align-items: center;">
                                @if ($item->package->hero_image)
                                    <img src="{{ asset($item->package->hero_image) }}" alt="" style="width: 80px; height: 60px; object-fit: cover; border-radius: var(--radius-sm);">
                                @endif
                                <div style="flex: 1;">
                                    <a href="{{ route('packages.show', $item->package->slug) }}" style="font-weight: 600;">{{ $item->package->title }}</a>
                                    <p style="font-size: 0.85rem; color: var(--color-text-muted);">{{ $item->package->duration_days }} days</p>
                                </div>
                                <form action="{{ route('account.wishlist.destroy', $item->package) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn--secondary" style="padding: 0.35rem 0.65rem; font-size: 0.8rem; background: var(--color-mist); border: none; border-radius: var(--radius-sm); cursor: pointer;" title="Remove">
                                        <i data-lucide="x"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @empty
                        <p style="color: var(--color-text-muted);">Your wishlist is empty. <a href="{{ route('packages.index') }}">Browse safaris</a>.</p>
                    @endforelse
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn--primary">Logout</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>@media (max-width: 768px) { .section .container > div { grid-template-columns: 1fr !important; } }</style>
@endpush
