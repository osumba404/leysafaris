@extends('layouts.admin')

@section('page_title', $package->title)

@section('content')
    @php $package->loadMissing('packageDays'); @endphp
    <div class="admin-card">
        <div class="admin-card__header">
            <h2 class="admin-card__title">{{ $package->title }}</h2>
            <div>
                <a href="{{ route('packages.show', $package->slug) }}" class="admin-btn admin-btn--secondary admin-btn--sm" target="_blank">View Public</a>
                <a href="{{ route('admin.packages.edit', $package) }}" class="admin-btn admin-btn--primary admin-btn--sm">Edit</a>
            </div>
        </div>

        <dl class="admin-detail-grid">
            <div class="admin-detail-item"><dt>Slug</dt><dd>{{ $package->slug }}</dd></div>
            <div class="admin-detail-item"><dt>Status</dt><dd><span class="admin-badge admin-badge--{{ $package->status }}">{{ $package->status }}</span></dd></div>
            <div class="admin-detail-item"><dt>Duration</dt><dd>{{ $package->duration_days }} days</dd></div>
            <div class="admin-detail-item"><dt>Price</dt><dd>{{ $package->starting_price ? ($package->currency ?? 'USD') . ' ' . number_format($package->starting_price, 2) : '—' }}</dd></div>
            <div class="admin-detail-item"><dt>Views</dt><dd>{{ number_format($package->view_count) }}</dd></div>
            <div class="admin-detail-item"><dt>Featured</dt><dd>{{ $package->is_featured ? 'Yes' : 'No' }}</dd></div>
            <div class="admin-detail-item admin-form__group--full"><dt>Destinations</dt><dd>{{ $package->destinations->pluck('name')->join(', ') ?: '—' }}</dd></div>
            <div class="admin-detail-item admin-form__group--full"><dt>Short Description</dt><dd>{{ $package->short_description ?? '—' }}</dd></div>
        </dl>
    </div>

    @if ($package->packageDays->isNotEmpty())
        <div class="admin-card">
            <h3 class="admin-card__title" style="margin-bottom: 1rem;">Itinerary ({{ $package->packageDays->count() }} days)</h3>
            @foreach ($package->packageDays as $day)
                <div class="admin-day-block">
                    <strong>Day {{ $day->day_number }}: {{ $day->title }}</strong>
                    @if ($day->location)<br><small>{{ $day->location }}</small>@endif
                    @if ($day->narrative)<p style="margin-top: 0.5rem;">{{ $day->narrative }}</p>@endif
                </div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Delete this package?')">
        @csrf @method('DELETE')
        <button type="submit" class="admin-btn admin-btn--danger">Delete Package</button>
    </form>
@endsection
