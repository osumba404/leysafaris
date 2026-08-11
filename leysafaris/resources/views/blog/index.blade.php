@extends('layouts.public')

@section('title', 'Safari Journal & Travel Guides | Leyla Safari Tours')
@section('meta_description', 'Kenya safari travel guides, destination tips and inspiration from Leyla Safari Tours - plan your Maasai Mara, Amboseli and East Africa adventure.')
@section('canonical', route('blog.index'))

@section('content')
    <section class="section page-top">
        <div class="container">
            <div class="section-header">
                <span class="section-header__label">Safari Journal</span>
                <h1 class="section-header__title">Stories from the Wild</h1>
                <p class="section-header__desc">Travel tips, wildlife insights, and safari inspiration from our team on the ground.</p>
            </div>

            <div class="blog-grid">
                @forelse ($posts as $post)
                    <article class="blog-card">
                        <a href="{{ route('blog.show', $post->slug) }}" style="color: inherit; display: block;">
                            @if ($post->featured_image)
                                <div class="blog-card__image">
                                    <x-lazy-img
                                        :src="$post->featured_image"
                                        :alt="$post->title"
                                        :width="400"
                                        :height="250"
                                    />
                                </div>
                            @endif
                            <div class="blog-card__body">
                                @if ($post->published_at)
                                    <time datetime="{{ $post->published_at->toDateString() }}" style="font-size: 0.8rem; color: var(--color-savanna);">
                                        {{ $post->published_at->format('F j, Y') }}
                                    </time>
                                @endif
                                <h2 class="blog-card__title">{{ $post->title }}</h2>
                                <p class="blog-card__excerpt">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}</p>
                                @if ($post->author)
                                    <span style="font-size: 0.85rem; color: var(--color-text-muted);">By {{ $post->author->name }}</span>
                                @endif
                            </div>
                        </a>
                    </article>
                @empty
                    <p style="grid-column: 1 / -1; text-align: center; color: var(--color-text-muted);">No journal posts yet. Check back soon!</p>
                @endforelse
            </div>

            @if ($posts->hasPages())
                <div style="margin-top: 2rem;">{{ $posts->links() }}</div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
<style>
    .blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; }
    .blog-card { background: var(--color-white); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); transition: transform var(--transition), box-shadow var(--transition); }
    .blog-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
    .blog-card__image img,
    .blog-card__image picture,
    .blog-card__image picture img { width: 100%; height: 200px; object-fit: cover; }
    .blog-card__body { padding: 1.25rem; }
    .blog-card__title { font-family: var(--font-display); font-size: 1.35rem; margin: 0.5rem 0; line-height: 1.3; }
    .blog-card__excerpt { font-size: 0.9rem; color: var(--color-text-muted); line-height: 1.6; }
</style>
@endpush
