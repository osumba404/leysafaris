@extends('layouts.admin')
@section('page_title', $annualEvent->title)
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ $annualEvent->title }}</h2>
        <a href="{{ route('admin.annual-events.edit', $annualEvent) }}" class="admin-btn admin-btn--icon" title="Edit" aria-label="Edit"><i data-lucide="pencil"></i></a>
    </div>
    @if($annualEvent->hero_image)<img src="{{ asset($annualEvent->hero_image) }}" alt="" style="max-width:400px;border-radius:8px;margin-bottom:1rem;">@endif
    <dl class="admin-detail-grid">
        <div class="admin-detail-item"><dt>Event Date</dt><dd>{{ $annualEvent->event_date->format('F j, Y') }}</dd></div>
        <div class="admin-detail-item"><dt>Package</dt><dd>{{ $annualEvent->package?->title ?? '-' }}</dd></div>
        <div class="admin-detail-item"><dt>Early Bird</dt><dd>{{ $annualEvent->early_bird_price ? number_format($annualEvent->early_bird_price, 2).' (by '.$annualEvent->early_bird_deadline?->format('M j').')' : '-' }}</dd></div>
        <div class="admin-detail-item"><dt>Regular Price</dt><dd>{{ $annualEvent->regular_price ? number_format($annualEvent->regular_price, 2) : '-' }}</dd></div>
        <div class="admin-detail-item admin-form__group--full"><dt>Description</dt><dd>{!! nl2br(e($annualEvent->description)) !!}</dd></div>
    </dl>
</div>
<form action="{{ route('admin.annual-events.destroy', $annualEvent) }}" method="POST" class="admin-inline-form" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--icon admin-btn--icon-danger" title="Delete" aria-label="Delete"><i data-lucide="trash-2"></i></button></form>
@endsection
