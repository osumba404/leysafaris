@extends('layouts.admin')
@section('page_title', $destination->name)
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ $destination->name }}</h2>
        <div>
            <a href="{{ route('destinations.show', $destination->slug) }}" class="admin-btn admin-btn--secondary admin-btn--sm" target="_blank">View Public</a>
            <a href="{{ route('admin.destinations.edit', $destination) }}" class="admin-btn admin-btn--primary admin-btn--sm">Edit</a>
        </div>
    </div>
    @if($destination->hero_image)<img src="{{ asset($destination->hero_image) }}" alt="" style="max-width:300px;border-radius:8px;margin-bottom:1rem;">@endif
    <dl class="admin-detail-grid">
        <div class="admin-detail-item"><dt>Slug</dt><dd>{{ $destination->slug }}</dd></div>
        <div class="admin-detail-item"><dt>Region</dt><dd>{{ $destination->region ?? '-' }}</dd></div>
        <div class="admin-detail-item"><dt>Best Time</dt><dd>{{ $destination->best_time ?? '-' }}</dd></div>
        <div class="admin-detail-item"><dt>Published</dt><dd>{{ $destination->is_published ? 'Yes' : 'No' }}</dd></div>
        <div class="admin-detail-item admin-form__group--full"><dt>Excerpt</dt><dd>{{ $destination->excerpt }}</dd></div>
        <div class="admin-detail-item admin-form__group--full"><dt>Description</dt><dd>{!! nl2br(e($destination->description)) !!}</dd></div>
    </dl>
    @if($destination->packages->isNotEmpty())
        <h3 style="margin-top:1.5rem;">Linked Packages</h3>
        <ul>@foreach($destination->packages as $p)<li><a href="{{ route('admin.packages.show', $p) }}">{{ $p->title }}</a></li>@endforeach</ul>
    @endif
</div>
<form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--danger">Delete</button></form>
@endsection
