@extends('layouts.admin')
@section('page_title', 'Blog Posts')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Blog Posts</h2>
        <a href="{{ route('admin.blog-posts.create') }}" class="admin-btn admin-btn--primary"><i data-lucide="plus"></i> New Post</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Title</th><th>Author</th><th>Status</th><th>Published</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($posts as $post)
                <tr>
                    <td><strong>{{ $post->title }}</strong></td>
                    <td>{{ $post->author?->name ?? '-' }}</td>
                    <td><span class="admin-badge admin-badge--{{ $post->status }}">{{ $post->status }}</span></td>
                    <td>{{ $post->published_at?->format('M j, Y') ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.blog-posts.show', $post) }}" class="admin-btn admin-btn--secondary admin-btn--sm">View</a>
                        <a href="{{ route('admin.blog-posts.edit', $post) }}" class="admin-btn admin-btn--secondary admin-btn--sm">Edit</a>
                        <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--danger admin-btn--sm">Delete</button></form>
                    </td>
                </tr>
                @empty<tr><td colspan="5">No posts.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
    <div class="admin-pagination">{{ $posts->links() }}</div>
</div>
@endsection
