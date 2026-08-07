@extends('layouts.public')

@section('title', ($post->seo_title ?? $post->title) . ' | Leyla Safari Tours')
@section('meta_description', $post->seo_description ?? Str::limit($post->excerpt, 160))

@section('content')
    <article class="section" style="padding-top: calc(var(--header-height) + var(--trust-bar-height) + 2rem);">
        <div class="container" style="max-width: 800px;">
            <header style="margin-bottom: 2rem;">
                @if ($post->published_at)
                    <time datetime="{{ $post->published_at->toDateString() }}" style="font-size: 0.85rem; color: var(--color-savanna);">
                        {{ $post->published_at->format('F j, Y') }}
                    </time>
                @endif
                <h1 style="font-family: var(--font-display); font-size: clamp(2rem, 4vw, 2.75rem); margin: 0.5rem 0 1rem; line-height: 1.2;">{{ $post->title }}</h1>
                @if ($post->author)
                    <p style="color: var(--color-text-muted); font-size: 0.9rem;">By {{ $post->author->name }}</p>
                @endif
            </header>

            @if ($post->featured_image)
                <figure style="margin-bottom: 2rem;">
                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" style="width: 100%; border-radius: var(--radius-md);">
                </figure>
            @endif

            @if ($post->excerpt)
                <p style="font-size: 1.15rem; color: var(--color-text-muted); margin-bottom: 2rem; line-height: 1.7;">{{ $post->excerpt }}</p>
            @endif

            <div class="blog-content" style="line-height: 1.8; font-size: 1.05rem;">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>
    </article>

    @if ($recentPosts->isNotEmpty())
        <section class="section" style="background: var(--color-sand);">
            <div class="container">
                <h2 class="section-header__title" style="margin-bottom: 1.5rem;">More from the Journal</h2>
                <div class="blog-grid">
                    @foreach ($recentPosts as $recent)
                        <article class="blog-card">
                            <a href="{{ route('blog.show', $recent->slug) }}" style="color: inherit; display: block;">
                                @if ($recent->featured_image)
                                    <div class="blog-card__image">
                                        <img src="{{ asset($recent->featured_image) }}" alt="{{ $recent->title }}" loading="lazy">
                                    </div>
                                @endif
                                <div class="blog-card__body">
                                    <h3 class="blog-card__title">{{ $recent->title }}</h3>
                                    <p class="blog-card__excerpt">{{ $recent->excerpt }}</p>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('styles')
<style>
    .blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
    .blog-card { background: var(--color-white); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); }
    .blog-card__image img { width: 100%; height: 160px; object-fit: cover; }
    .blog-card__body { padding: 1rem; }
    .blog-card__title { font-family: var(--font-display); font-size: 1.1rem; margin-bottom: 0.35rem; }
    .blog-card__excerpt { font-size: 0.85rem; color: var(--color-text-muted); }
</style>
@endpush
