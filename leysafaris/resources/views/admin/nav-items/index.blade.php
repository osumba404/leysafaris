@extends('layouts.admin')
@section('page_title', 'Navigation')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Navigation Menu</h2>
        <a href="{{ route('admin.nav-items.create') }}" class="admin-btn admin-btn--primary admin-btn--sm"><i data-lucide="plus"></i> New Item</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Label</th><th>Route / URL</th><th>Order</th><th>Active</th><th></th></tr></thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->label }}@if($item->is_highlight) <span class="admin-badge admin-badge--published">Accent</span>@endif</td>
                        <td><code>{{ $item->route_name ?: $item->url }}</code></td>
                        <td>{{ $item->sort_order }}</td>
                        <td>{{ $item->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('admin.nav-items.edit', $item) }}" class="admin-btn admin-btn--secondary admin-btn--sm">Edit</a>
                            <form action="{{ route('admin.nav-items.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--danger admin-btn--sm">Delete</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No nav items. Default hardcoded links will show until you add items.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
