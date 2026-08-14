@extends('layouts.admin')
@section('page_title', $blogPost->title)
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ $blogPost->title }}</h2>
        <div class="admin-table__actions">
            @if($blogPost->status==='published')<a href="{{ route('blog.show', $blogPost->slug) }}" class="admin-btn admin-btn--icon" target="_blank" title="View public site" aria-label="View public site"><i data-lucide="external-link"></i></a>@endif
            <a href="{{ route('admin.blog-posts.edit', $blogPost) }}" class="admin-btn admin-btn--icon" title="Edit" aria-label="Edit"><i data-lucide="pencil"></i></a>
        </div>
    </div>
    @if($blogPost->featured_image)<img src="{{ asset($blogPost->featured_image) }}" alt="" style="max-width:400px;border-radius:8px;margin-bottom:1rem;">@endif
    <dl class="admin-detail-grid">
        <div class="admin-detail-item"><dt>Status</dt><dd><span class="admin-badge admin-badge--{{ $blogPost->status }}">{{ $blogPost->status }}</span></dd></div>
        <div class="admin-detail-item"><dt>Author</dt><dd>{{ $blogPost->author?->name ?? '-' }}</dd></div>
        <div class="admin-detail-item"><dt>Published</dt><dd>{{ $blogPost->published_at?->format('M j, Y g:i A') ?? '-' }}</dd></div>
        <div class="admin-detail-item admin-form__group--full"><dt>Excerpt</dt><dd>{{ $blogPost->excerpt ?? '-' }}</dd></div>
        <div class="admin-detail-item admin-form__group--full"><dt>Content</dt><dd>{!! nl2br(e($blogPost->content)) !!}</dd></div>
    </dl>
</div>
<form action="{{ route('admin.blog-posts.destroy', $blogPost) }}" method="POST" class="admin-inline-form" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn--icon admin-btn--icon-danger" title="Delete" aria-label="Delete"><i data-lucide="trash-2"></i></button></form>
@endsection
