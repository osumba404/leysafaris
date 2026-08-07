@extends('layouts.admin')
@section('page_title', $quote->reference)
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ $quote->reference }} — {{ $quote->title }}</h2>
        <a href="{{ route('admin.quotes.edit', $quote) }}" class="admin-btn admin-btn--primary admin-btn--sm">Edit</a>
    </div>
    <dl class="admin-detail-grid">
        <div class="admin-detail-item"><dt>Enquiry</dt><dd><a href="{{ route('admin.enquiries.show', $quote->enquiry) }}">{{ $quote->enquiry?->name }}</a></dd></div>
        <div class="admin-detail-item"><dt>Package</dt><dd>{{ $quote->package?->title ?? '—' }}</dd></div>
        <div class="admin-detail-item"><dt>Total</dt><dd><strong>{{ $quote->currency }} {{ number_format($quote->total_amount, 2) }}</strong></dd></div>
        <div class="admin-detail-item"><dt>Status</dt><dd><span class="admin-badge admin-badge--{{ $quote->status }}">{{ $quote->status }}</span></dd></div>
        <div class="admin-detail-item"><dt>Valid Until</dt><dd>{{ $quote->valid_until?->format('M j, Y') ?? '—' }}</dd></div>
        <div class="admin-detail-item"><dt>Created By</dt><dd>{{ $quote->createdBy?->name ?? '—' }}</dd></div>
        @if($quote->notes)<div class="admin-detail-item admin-form__group--full"><dt>Notes</dt><dd>{!! nl2br(e($quote->notes)) !!}</dd></div>@endif
    </dl>
    @if(!empty($quote->line_items))
    <h3 style="margin-top:1.5rem;">Line Items</h3>
    <table class="admin-table"><thead><tr><th>Description</th><th>Amount</th></tr></thead>
    <tbody>@foreach($quote->line_items as $item)<tr><td>{{ $item['description'] ?? '' }}</td><td>{{ number_format($item['amount'] ?? 0, 2) }}</td></tr>@endforeach</tbody></table>
    @endif
</div>
<form action="{{ route('admin.quotes.destroy', $quote) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--danger">Delete</button></form>
@endsection
