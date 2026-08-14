@extends('layouts.admin')
@section('page_title', 'Destinations')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Destinations</h2>
        <a href="{{ route('admin.destinations.create') }}" class="admin-btn admin-btn--primary admin-btn--sm"><i data-lucide="plus"></i> Add new</a>
    </div>
    <p class="admin-sort-status" data-sort-status></p>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th aria-label="Reorder"></th><th>Name</th><th>Region</th><th>Packages</th><th>Published</th><th>Actions</th></tr></thead>
            <tbody data-sortable="{{ route('admin.reorder', 'destinations') }}">
                @forelse ($destinations as $destination)
                <tr data-sort-id="{{ $destination->id }}">
                    @include('admin.partials.sort-handle')
                    <td><strong>{{ $destination->name }}</strong></td>
                    <td>{{ $destination->region ?? $destination->country }}</td>
                    <td>{{ $destination->packages_count ?? 0 }}</td>
                    <td>{{ $destination->is_published ? 'Yes' : 'No' }}</td>
                    <td>
                        @include('admin.partials.table-actions', [
                            'viewUrl' => route('admin.destinations.show', $destination),
                            'editUrl' => route('admin.destinations.edit', $destination),
                            'deleteUrl' => route('admin.destinations.destroy', $destination),
                        ])
                    </td>
                </tr>
                @empty<tr><td colspan="6">No destinations.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
