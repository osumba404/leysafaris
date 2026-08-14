@extends('layouts.admin')
@section('page_title', 'Footer Links')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Footer Links</h2>
        <a href="{{ route('admin.footer-links.create') }}" class="admin-btn admin-btn--primary admin-btn--sm"><i data-lucide="plus"></i> New Link</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Group</th><th>Label</th><th>Route / URL</th><th>Order</th><th></th></tr></thead>
            <tbody>
                @forelse ($links as $link)
                    <tr>
                        <td>{{ \App\Models\FooterLink::groupLabel($link->group) }}</td>
                        <td>{{ $link->label }}</td>
                        <td><code>{{ $link->route_name ?: $link->url }}</code></td>
                        <td>{{ $link->sort_order }}</td>
                        <td>
                            <a href="{{ route('admin.footer-links.edit', $link) }}" class="admin-btn admin-btn--secondary admin-btn--sm">Edit</a>
                            <form action="{{ route('admin.footer-links.destroy', $link) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--danger admin-btn--sm">Delete</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No footer links configured.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
