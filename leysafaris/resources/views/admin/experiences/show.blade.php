@extends('layouts.admin')
@section('page_title', $experience->name)
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ $experience->name }}</h2>
        <a href="{{ route('admin.experiences.edit', $experience) }}" class="admin-btn admin-btn--icon" title="Edit" aria-label="Edit"><i data-lucide="pencil"></i></a>
    </div>
    <dl class="admin-detail-grid">
        <div class="admin-detail-item"><dt>Type</dt><dd>{{ $experience->type ?? '-' }}</dd></div>
        <div class="admin-detail-item"><dt>Duration</dt><dd>{{ $experience->duration_hours ? $experience->duration_hours.' hours' : '-' }}</dd></div>
        <div class="admin-detail-item"><dt>Price</dt><dd>{{ $experience->starting_price ? ($experience->currency ?? 'USD').' '.number_format($experience->starting_price, 2) : '-' }}</dd></div>
        <div class="admin-detail-item admin-form__group--full"><dt>Description</dt><dd>{!! nl2br(e($experience->description)) !!}</dd></div>
    </dl>
</div>
<form action="{{ route('admin.experiences.destroy', $experience) }}" method="POST" class="admin-inline-form" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--icon admin-btn--icon-danger" title="Delete" aria-label="Delete"><i data-lucide="trash-2"></i></button></form>
@endsection
