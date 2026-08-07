<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::published()
            ->with('author')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('blog.index', compact('posts'));
    }

    public function show(string $slug): View
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->with('author')
            ->firstOrFail();

        $recentPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get(['id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at']);

        return view('blog.show', compact('post', 'recentPosts'));
    }
}
