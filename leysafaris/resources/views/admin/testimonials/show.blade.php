@extends('layouts.admin')
@section('page_title', $testimonial->author_name)
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ $testimonial->author_name }}</h2>
        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="admin-btn admin-btn--primary admin-btn--sm">Edit</a>
    </div>
    <p style="color:var(--admin-accent);margin-bottom:1rem;">@for($i=0;$i<$testimonial->rating;$i++)★@endfor</p>
    <blockquote style="font-style:italic;margin-bottom:1rem;">"{{ $testimonial->content }}"</blockquote>
    <dl class="admin-detail-grid">
        <div class="admin-detail-item"><dt>Location</dt><dd>{{ $testimonial->author_location ?? '—' }}</dd></div>
        <div class="admin-detail-item"><dt>Package</dt><dd>{{ $testimonial->package?->title ?? '—' }}</dd></div>
        <div class="admin-detail-item"><dt>Approved</dt><dd>{{ $testimonial->is_approved ? 'Yes' : 'No' }}</dd></div>
        <div class="admin-detail-item"><dt>Featured</dt><dd>{{ $testimonial->is_featured ? 'Yes' : 'No' }}</dd></div>
    </dl>
</div>
<form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--danger">Delete</button></form>
@endsection
