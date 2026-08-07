@extends('layouts.admin')
@section('page_title', $enquiry->name)
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ $enquiry->name }}</h2>
        <div>
            <a href="{{ route('admin.enquiries.edit', $enquiry) }}" class="admin-btn admin-btn--secondary admin-btn--sm">Edit</a>
            <a href="{{ route('admin.quotes.create', ['enquiry_id' => $enquiry->id]) }}" class="admin-btn admin-btn--primary admin-btn--sm">Create Quote</a>
        </div>
    </div>
    <dl class="admin-detail-grid">
        <div class="admin-detail-item"><dt>Email</dt><dd><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></dd></div>
        <div class="admin-detail-item"><dt>Phone</dt><dd>{{ $enquiry->phone ?? '—' }}</dd></div>
        <div class="admin-detail-item"><dt>Status</dt><dd><span class="admin-badge admin-badge--{{ $enquiry->status === 'new' ? 'new' : 'published' }}">{{ $enquiry->status }}</span></dd></div>
        <div class="admin-detail-item"><dt>Package</dt><dd>{{ $enquiry->package?->title ?? 'Custom' }}</dd></div>
        <div class="admin-detail-item"><dt>Travel Dates</dt><dd>{{ $enquiry->travel_dates ?? '—' }}</dd></div>
        <div class="admin-detail-item"><dt>Group Size</dt><dd>{{ $enquiry->group_size ?? '—' }}</dd></div>
        <div class="admin-detail-item"><dt>Budget</dt><dd>{{ $enquiry->budget_range ?? '—' }}</dd></div>
        <div class="admin-detail-item"><dt>Assigned To</dt><dd>{{ $enquiry->assignedTo?->name ?? '—' }}</dd></div>
        <div class="admin-detail-item admin-form__group--full"><dt>Message</dt><dd>{!! nl2br(e($enquiry->message)) !!}</dd></div>
        @if($enquiry->admin_notes)<div class="admin-detail-item admin-form__group--full"><dt>Admin Notes</dt><dd>{!! nl2br(e($enquiry->admin_notes)) !!}</dd></div>@endif
    </dl>
</div>
@if($enquiry->quotes->isNotEmpty())
<div class="admin-card">
    <h3 class="admin-card__title" style="margin-bottom:1rem;">Quotes</h3>
    <table class="admin-table">
        <thead><tr><th>Reference</th><th>Title</th><th>Amount</th><th>Status</th><th></th></tr></thead>
        <tbody>@foreach($enquiry->quotes as $quote)<tr>
            <td>{{ $quote->reference }}</td><td>{{ $quote->title }}</td>
            <td>{{ $quote->currency }} {{ number_format($quote->total_amount, 2) }}</td>
            <td><span class="admin-badge admin-badge--{{ $quote->status }}">{{ $quote->status }}</span></td>
            <td><a href="{{ route('admin.quotes.show', $quote) }}" class="admin-btn admin-btn--secondary admin-btn--sm">View</a></td>
        </tr>@endforeach</tbody>
    </table>
</div>
@endif
<form action="{{ route('admin.enquiries.destroy', $enquiry) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--danger">Delete</button></form>
@endsection
