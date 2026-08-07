@extends('layouts.admin')
@section('page_title', 'Testimonials')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Testimonials</h2>
        <a href="{{ route('admin.testimonials.create') }}" class="admin-btn admin-btn--primary"><i data-lucide="plus"></i> New Testimonial</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Author</th><th>Package</th><th>Rating</th><th>Approved</th><th>Featured</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($testimonials as $testimonial)
                <tr>
                    <td><strong>{{ $testimonial->author_name }}</strong>@if($testimonial->author_location)<br><small>{{ $testimonial->author_location }}</small>@endif</td>
                    <td>{{ $testimonial->package?->title ?? '—' }}</td>
                    <td>{{ $testimonial->rating }}/5</td>
                    <td>{{ $testimonial->is_approved ? 'Yes' : 'No' }}</td>
                    <td>{{ $testimonial->is_featured ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="admin-btn admin-btn--secondary admin-btn--sm">View</a>
                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="admin-btn admin-btn--secondary admin-btn--sm">Edit</a>
                        <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--danger admin-btn--sm">Delete</button></form>
                    </td>
                </tr>
                @empty<tr><td colspan="6">No testimonials.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
    <div class="admin-pagination">{{ $testimonials->links() }}</div>
</div>
@endsection
