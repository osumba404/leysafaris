<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ isset($blogPost) ? 'Edit: '.$blogPost->title : 'Create Blog Post' }}</h2>
        <a href="{{ route('admin.blog-posts.index') }}" class="admin-btn admin-btn--secondary admin-btn--sm">Back</a>
    </div>
    <form class="admin-form admin-form--grid" action="{{ isset($blogPost) ? route('admin.blog-posts.update', $blogPost) : route('admin.blog-posts.store') }}" method="POST">
        @csrf @if(isset($blogPost)) @method('PUT') @endif
        <div class="admin-form__group"><label for="title">Title *</label><input type="text" id="title" name="title" value="{{ old('title', $blogPost->title ?? '') }}" required></div>
        <div class="admin-form__group"><label for="slug">Slug</label><input type="text" id="slug" name="slug" value="{{ old('slug', $blogPost->slug ?? '') }}"></div>
        <div class="admin-form__group"><label for="status">Status *</label><select id="status" name="status" required>@foreach(['draft','published','archived'] as $s)<option value="{{ $s }}" @selected(old('status', $blogPost->status ?? 'draft')===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
        <div class="admin-form__group"><label for="published_at">Published At</label><input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', isset($blogPost) && $blogPost->published_at ? $blogPost->published_at->format('Y-m-d\TH:i') : '') }}"></div>
        <div class="admin-form__group admin-form__group--full">
            @include('admin.partials.image-field', [
                'name' => 'featured_image',
                'label' => 'Featured image',
                'value' => old('featured_image', $blogPost->featured_image ?? ''),
                'folder' => 'blog',
                'required' => false,
            ])
        </div>
        <div class="admin-form__group admin-form__group--full"><label for="excerpt">Excerpt</label><textarea id="excerpt" name="excerpt">{{ old('excerpt', $blogPost->excerpt ?? '') }}</textarea></div>
        <div class="admin-form__group admin-form__group--full"><label for="content">Content</label><textarea id="content" name="content" rows="12">{{ old('content', $blogPost->content ?? '') }}</textarea></div>
        <div class="admin-form__group"><label for="seo_title">SEO Title</label><input type="text" id="seo_title" name="seo_title" value="{{ old('seo_title', $blogPost->seo_title ?? '') }}"></div>
        <div class="admin-form__group admin-form__group--full"><label for="seo_description">SEO Description</label><textarea id="seo_description" name="seo_description">{{ old('seo_description', $blogPost->seo_description ?? '') }}</textarea></div>
        <div class="admin-form__actions admin-form__group--full"><button type="submit" class="admin-btn admin-btn--primary">{{ isset($blogPost) ? 'Update' : 'Create' }}</button></div>
    </form>
</div>
