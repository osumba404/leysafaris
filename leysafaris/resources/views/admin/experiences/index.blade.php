@extends('layouts.admin')
@section('page_title', 'Experiences')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Experiences</h2>
        <a href="{{ route('admin.experiences.create') }}" class="admin-btn admin-btn--primary admin-btn--sm"><i data-lucide="plus"></i> Add new</a>
    </div>
    <p class="admin-sort-status" data-sort-status></p>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th aria-label="Reorder"></th><th>Name</th><th>Type</th><th>Duration</th><th>Price</th><th>Published</th><th>Actions</th></tr></thead>
            <tbody data-sortable="{{ route('admin.reorder', 'experiences') }}">
                @forelse ($experiences as $experience)
                <tr data-sort-id="{{ $experience->id }}">
                    @include('admin.partials.sort-handle')
                    <td><strong>{{ $experience->name }}</strong></td>
                    <td>{{ $experience->type ?? '-' }}</td>
                    <td>{{ $experience->duration_hours ? $experience->duration_hours.'h' : '-' }}</td>
                    <td>{{ $experience->starting_price ? number_format($experience->starting_price, 0) : '-' }}</td>
                    <td>{{ $experience->is_published ? 'Yes' : 'No' }}</td>
                    <td>
                        @include('admin.partials.table-actions', [
                            'viewUrl' => route('admin.experiences.show', $experience),
                            'editUrl' => route('admin.experiences.edit', $experience),
                            'deleteUrl' => route('admin.experiences.destroy', $experience),
                        ])
                    </td>
                </tr>
                @empty<tr><td colspan="7">No experiences.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
