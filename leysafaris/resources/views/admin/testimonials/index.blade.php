@extends('layouts.admin')
@section('page_title', 'Testimonials')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Testimonials</h2>
        <a href="{{ route('admin.testimonials.create') }}" class="admin-btn admin-btn--primary admin-btn--sm"><i data-lucide="plus"></i> Add new</a>
    </div>
    <p class="admin-sort-status" data-sort-status></p>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th aria-label="Reorder"></th><th>Author</th><th>Package</th><th>Rating</th><th>Approved</th><th>Featured</th><th>Actions</th></tr></thead>
            <tbody data-sortable="{{ route('admin.reorder', 'testimonials') }}">
                @forelse ($testimonials as $testimonial)
                <tr data-sort-id="{{ $testimonial->id }}">
                    @include('admin.partials.sort-handle')
                    <td><strong>{{ $testimonial->author_name }}</strong>@if($testimonial->author_location)<br><small>{{ $testimonial->author_location }}</small>@endif</td>
                    <td>{{ $testimonial->package?->title ?? '-' }}</td>
                    <td>{{ $testimonial->rating }}/5</td>
                    <td>{{ $testimonial->is_approved ? 'Yes' : 'No' }}</td>
                    <td>{{ $testimonial->is_featured ? 'Yes' : 'No' }}</td>
                    <td>
                        @include('admin.partials.table-actions', [
                            'viewUrl' => route('admin.testimonials.show', $testimonial),
                            'editUrl' => route('admin.testimonials.edit', $testimonial),
                            'deleteUrl' => route('admin.testimonials.destroy', $testimonial),
                        ])
                    </td>
                </tr>
                @empty<tr><td colspan="7">No testimonials.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
