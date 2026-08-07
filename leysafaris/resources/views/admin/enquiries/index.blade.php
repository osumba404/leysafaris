@extends('layouts.admin')
@section('page_title', 'Enquiries')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Enquiries</h2>
        <a href="{{ route('admin.enquiries.create') }}" class="admin-btn admin-btn--primary"><i data-lucide="plus"></i> New Enquiry</a>
    </div>
    <form method="GET" style="display:flex;gap:0.75rem;margin-bottom:1rem;flex-wrap:wrap;">
        <select name="status" onchange="this.form.submit()">
            <option value="">All statuses</option>
            @foreach(['new','contacted','quote_sent','negotiation','confirmed','lost'] as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <select name="assigned_to" onchange="this.form.submit()">
            <option value="">All assignees</option>
            @foreach($admins as $admin)
                <option value="{{ $admin->id }}" @selected(request('assigned_to')==$admin->id)>{{ $admin->name }}</option>
            @endforeach
        </select>
    </form>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Email</th><th>Package</th><th>Status</th><th>Assigned</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($enquiries as $enquiry)
                <tr>
                    <td><a href="{{ route('admin.enquiries.show', $enquiry) }}"><strong>{{ $enquiry->name }}</strong></a></td>
                    <td>{{ $enquiry->email }}</td>
                    <td>{{ $enquiry->package?->title ?? '—' }}</td>
                    <td><span class="admin-badge admin-badge--{{ $enquiry->status === 'new' ? 'new' : 'published' }}">{{ $enquiry->status }}</span></td>
                    <td>{{ $enquiry->assignedTo?->name ?? '—' }}</td>
                    <td>{{ $enquiry->created_at->format('M j, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.enquiries.edit', $enquiry) }}" class="admin-btn admin-btn--secondary admin-btn--sm">Edit</a>
                        <a href="{{ route('admin.quotes.create', ['enquiry_id' => $enquiry->id]) }}" class="admin-btn admin-btn--primary admin-btn--sm">Quote</a>
                    </td>
                </tr>
                @empty<tr><td colspan="7">No enquiries.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
    <div class="admin-pagination">{{ $enquiries->links() }}</div>
</div>
@endsection
