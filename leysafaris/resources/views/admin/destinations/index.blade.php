@extends('layouts.admin')
@section('page_title', 'Destinations')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Destinations</h2>
        <a href="{{ route('admin.destinations.create') }}" class="admin-btn admin-btn--primary"><i data-lucide="plus"></i> New Destination</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Region</th><th>Packages</th><th>Published</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($destinations as $destination)
                <tr>
                    <td><strong>{{ $destination->name }}</strong></td>
                    <td>{{ $destination->region ?? $destination->country }}</td>
                    <td>{{ $destination->packages_count ?? 0 }}</td>
                    <td>{{ $destination->is_published ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('admin.destinations.show', $destination) }}" class="admin-btn admin-btn--secondary admin-btn--sm">View</a>
                        <a href="{{ route('admin.destinations.edit', $destination) }}" class="admin-btn admin-btn--secondary admin-btn--sm">Edit</a>
                        <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--danger admin-btn--sm">Delete</button></form>
                    </td>
                </tr>
                @empty<tr><td colspan="5">No destinations.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
    <div class="admin-pagination">{{ $destinations->links() }}</div>
</div>
@endsection
