@extends('layouts.admin')
@section('page_title', 'Quotes')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Quotes</h2>
        <a href="{{ route('admin.quotes.create') }}" class="admin-btn admin-btn--primary"><i data-lucide="plus"></i> New Quote</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Reference</th><th>Enquiry</th><th>Package</th><th>Amount</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($quotes as $quote)
                <tr>
                    <td><strong>{{ $quote->reference }}</strong></td>
                    <td>{{ $quote->enquiry?->name ?? '-' }}</td>
                    <td>{{ $quote->package?->title ?? '-' }}</td>
                    <td>{{ $quote->currency }} {{ number_format($quote->total_amount, 2) }}</td>
                    <td><span class="admin-badge admin-badge--{{ $quote->status }}">{{ $quote->status }}</span></td>
                    <td>{{ $quote->created_at->format('M j, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.quotes.show', $quote) }}" class="admin-btn admin-btn--secondary admin-btn--sm">View</a>
                        <a href="{{ route('admin.quotes.edit', $quote) }}" class="admin-btn admin-btn--secondary admin-btn--sm">Edit</a>
                    </td>
                </tr>
                @empty<tr><td colspan="7">No quotes.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
    <div class="admin-pagination">{{ $quotes->links() }}</div>
</div>
@endsection
