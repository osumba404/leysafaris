@extends('layouts.admin')
@section('page_title', 'Experiences')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Experiences</h2>
        <a href="{{ route('admin.experiences.create') }}" class="admin-btn admin-btn--primary"><i data-lucide="plus"></i> New Experience</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Type</th><th>Duration</th><th>Price</th><th>Published</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($experiences as $experience)
                <tr>
                    <td><strong>{{ $experience->name }}</strong></td>
                    <td>{{ $experience->type ?? '-' }}</td>
                    <td>{{ $experience->duration_hours ? $experience->duration_hours.'h' : '-' }}</td>
                    <td>{{ $experience->starting_price ? number_format($experience->starting_price, 0) : '-' }}</td>
                    <td>{{ $experience->is_published ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('admin.experiences.show', $experience) }}" class="admin-btn admin-btn--secondary admin-btn--sm">View</a>
                        <a href="{{ route('admin.experiences.edit', $experience) }}" class="admin-btn admin-btn--secondary admin-btn--sm">Edit</a>
                        <form action="{{ route('admin.experiences.destroy', $experience) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--danger admin-btn--sm">Delete</button></form>
                    </td>
                </tr>
                @empty<tr><td colspan="6">No experiences.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
    <div class="admin-pagination">{{ $experiences->links() }}</div>
</div>
@endsection
