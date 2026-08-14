@extends('layouts.admin')
@section('page_title', 'Hero Slides')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Homepage Hero Slides</h2>
        <a href="{{ route('admin.hero-slides.create') }}" class="admin-btn admin-btn--primary admin-btn--sm"><i data-lucide="plus"></i> New Slide</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Title</th><th>Image</th><th>Order</th><th>Active</th><th></th></tr></thead>
            <tbody>
                @forelse ($slides as $slide)
                    <tr>
                        <td>{{ $slide->title }}</td>
                        <td><code>{{ $slide->image }}</code></td>
                        <td>{{ $slide->sort_order }}</td>
                        <td>{{ $slide->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="admin-btn admin-btn--secondary admin-btn--sm">Edit</a>
                            <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete slide?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--danger admin-btn--sm">Delete</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No slides yet. Add one to enable the homepage carousel.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
