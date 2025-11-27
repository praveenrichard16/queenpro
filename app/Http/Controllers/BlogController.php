<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = BlogPost::query()
            ->with(['category', 'tags', 'author'])
            ->published()
            ->latest('published_at');

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($categorySlug = $request->input('category')) {
            $query->whereHas('category', fn ($catQuery) => $catQuery->where('slug', $categorySlug));
        }

        if ($tagSlug = $request->input('tag')) {
            $query->whereHas('tags', fn ($tagQuery) => $tagQuery->where('slug', $tagSlug));
        }

        $posts = $query->paginate(9)->withQueryString();
        $categories = BlogCategory::query()->where('is_active', true)->orderBy('name')->get();
        $featuredPost = BlogPost::query()
            ->published()
            ->where('is_featured', true)
            ->latest('published_at')
            ->with('category')
            ->first();

        return view('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'featuredPost' => $featuredPost,
            'selectedCategory' => $categorySlug,
            'selectedTag' => $tagSlug,
            'searchTerm' => $search,
        ]);
    }

    public function show(string $slug): View
    {
        $post = BlogPost::query()
            ->with(['category', 'tags', 'author'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $relatedPosts = BlogPost::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($query) => $query->where('blog_category_id', $post->blog_category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        $readingTime = max(1, (int) ceil(str_word_count(strip_tags($post->content ?? '')) / 200));

        return view('blog.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'readingTime' => $readingTime,
        ]);
    }

    public function category(string $slug, Request $request): View
    {
        $category = BlogCategory::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $posts = BlogPost::query()
            ->with(['category', 'tags', 'author'])
            ->published()
            ->where('blog_category_id', $category->id)
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $featuredPost = BlogPost::query()
            ->published()
            ->where('blog_category_id', $category->id)
            ->where('is_featured', true)
            ->latest('published_at')
            ->first();

        return view('blog.category', [
            'category' => $category,
            'posts' => $posts,
            'featuredPost' => $featuredPost,
        ]);
    }
}

