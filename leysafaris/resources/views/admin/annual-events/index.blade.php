@extends('layouts.admin')
@section('page_title', 'Annual Events')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Annual Events</h2>
        <a href="{{ route('admin.annual-events.create') }}" class="admin-btn admin-btn--primary"><i data-lucide="plus"></i> New Event</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Title</th><th>Date</th><th>Package</th><th>Published</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($events as $event)
                <tr>
                    <td><strong>{{ $event->title }}</strong></td>
                    <td>{{ $event->event_date->format('M j, Y') }}</td>
                    <td>{{ $event->package?->title ?? '—' }}</td>
                    <td>{{ $event->is_published ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('admin.annual-events.show', $event) }}" class="admin-btn admin-btn--secondary admin-btn--sm">View</a>
                        <a href="{{ route('admin.annual-events.edit', $event) }}" class="admin-btn admin-btn--secondary admin-btn--sm">Edit</a>
                        <form action="{{ route('admin.annual-events.destroy', $event) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--danger admin-btn--sm">Delete</button></form>
                    </td>
                </tr>
                @empty<tr><td colspan="5">No events.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
    <div class="admin-pagination">{{ $events->links() }}</div>
</div>
@endsection
