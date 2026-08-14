@extends('layouts.admin')
@section('page_title', 'Annual Events')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Annual Events</h2>
        <a href="{{ route('admin.annual-events.create') }}" class="admin-btn admin-btn--primary admin-btn--sm"><i data-lucide="plus"></i> Add new</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Title</th><th>Date</th><th>Package</th><th>Published</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($events as $event)
                <tr>
                    <td><strong>{{ $event->title }}</strong></td>
                    <td>{{ $event->event_date->format('M j, Y') }}</td>
                    <td>{{ $event->package?->title ?? '-' }}</td>
                    <td>{{ $event->is_published ? 'Yes' : 'No' }}</td>
                    <td>
                        @include('admin.partials.table-actions', [
                            'viewUrl' => route('admin.annual-events.show', $event),
                            'editUrl' => route('admin.annual-events.edit', $event),
                            'deleteUrl' => route('admin.annual-events.destroy', $event),
                        ])
                    </td>
                </tr>
                @empty<tr><td colspan="5">No events.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
    <div class="admin-pagination">{{ $events->links() }}</div>
</div>
@endsection
