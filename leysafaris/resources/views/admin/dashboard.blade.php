@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('content')
    <div class="admin-stats">
        <div class="admin-stat">
            <div class="admin-stat__label">Total Packages</div>
            <div class="admin-stat__value">{{ $stats['packages'] }}</div>
            <small>{{ $stats['published_packages'] }} published</small>
        </div>
        <div class="admin-stat">
            <div class="admin-stat__label">Destinations</div>
            <div class="admin-stat__value">{{ $stats['destinations'] }}</div>
        </div>
        <div class="admin-stat">
            <div class="admin-stat__label">Experiences</div>
            <div class="admin-stat__value">{{ $stats['experiences'] }}</div>
        </div>
        <div class="admin-stat">
            <div class="admin-stat__label">New Enquiries</div>
            <div class="admin-stat__value">{{ $stats['enquiries_new'] }}</div>
            <small>{{ $stats['enquiries_total'] }} total</small>
        </div>
        <div class="admin-stat">
            <div class="admin-stat__label">Quotes</div>
            <div class="admin-stat__value">{{ $stats['quotes_total'] }}</div>
        </div>
        <div class="admin-stat">
            <div class="admin-stat__label">Customers</div>
            <div class="admin-stat__value">{{ $stats['customers'] }}</div>
        </div>
        <div class="admin-stat">
            <div class="admin-stat__label">Testimonials</div>
            <div class="admin-stat__value">{{ $stats['testimonials'] }}</div>
        </div>
        <div class="admin-stat">
            <div class="admin-stat__label">Blog Posts</div>
            <div class="admin-stat__value">{{ $stats['blog_posts'] }}</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
        <div class="admin-card">
            <div class="admin-card__header">
                <h2 class="admin-card__title">Recent Enquiries</h2>
                <a href="{{ route('admin.enquiries.index') }}" class="admin-btn admin-btn--secondary admin-btn--sm">View All</a>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Package</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentEnquiries as $enquiry)
                            <tr>
                                <td><a href="{{ route('admin.enquiries.show', $enquiry) }}">{{ $enquiry->name }}</a></td>
                                <td>{{ $enquiry->package?->title ?? '-' }}</td>
                                <td><span class="admin-badge admin-badge--{{ $enquiry->status === 'new' ? 'new' : 'published' }}">{{ $enquiry->status }}</span></td>
                                <td>{{ $enquiry->created_at->format('M j') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4">No enquiries yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card__header">
                <h2 class="admin-card__title">Popular Packages</h2>
                <a href="{{ route('admin.packages.index') }}" class="admin-btn admin-btn--secondary admin-btn--sm">View All</a>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Package</th>
                            <th>Views</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($popularPackages as $package)
                            <tr>
                                <td><a href="{{ route('admin.packages.show', $package) }}">{{ $package->title }}</a></td>
                                <td>{{ number_format($package->view_count) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2">No packages yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($enquiriesByStatus->isNotEmpty())
        <div class="admin-card" style="margin-top: 1.25rem;">
            <h2 class="admin-card__title" style="margin-bottom: 1rem;">Enquiries by Status</h2>
            <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                @foreach ($enquiriesByStatus as $status => $count)
                    <div style="padding: 0.75rem 1.25rem; background: var(--admin-bg); border-radius: 8px;">
                        <span class="admin-badge admin-badge--{{ $status === 'new' ? 'new' : 'published' }}">{{ $status }}</span>
                        <strong style="margin-left: 0.5rem;">{{ $count }}</strong>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
