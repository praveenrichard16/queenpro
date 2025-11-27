<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function index(Request $request): View
    {
        $query = BlogPost::query()
            ->with(['category', 'author'])
            ->latest();

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category')) {
            $query->where('blog_category_id', $categoryId);
        }

        if ($status = $request->input('status')) {
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $posts = $query->paginate(15)->withQueryString();
        $categories = BlogCategory::query()->orderBy('name')->pluck('name', 'id');

        return view('admin.blog.posts.index', compact('posts', 'categories'));
    }

    public function create(): View
    {
        $categories = BlogCategory::query()->orderBy('name')->pluck('name', 'id');
        $tags = BlogTag::query()->orderBy('name')->pluck('name', 'id');

        return view('admin.blog.posts.form', [
            'post' => new BlogPost(),
            'categories' => $categories,
            'tags' => $tags,
            'selectedTags' => [],
        ]);
    }

    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['author_id'] = $request->user()->id;
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image_path'] = $request->file('featured_image')->store('blog/posts', 'public');
        }
        unset($data['featured_image']);

        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);

        $post = BlogPost::create($data);
        $post->tags()->sync($tagIds);

        return redirect()
            ->route('admin.blog.posts.edit', $post)
            ->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $post): View
    {
        $categories = BlogCategory::query()->orderBy('name')->pluck('name', 'id');
        $tags = BlogTag::query()->orderBy('name')->pluck('name', 'id');

        return view('admin.blog.posts.form', [
            'post' => $post,
            'categories' => $categories,
            'tags' => $tags,
            'selectedTags' => $post->tags()->pluck('blog_tags.id')->toArray(),
        ]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $post): RedirectResponse
    {
        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image_path) {
                Storage::disk('public')->delete($post->featured_image_path);
            }
            $data['featured_image_path'] = $request->file('featured_image')->store('blog/posts', 'public');
        }
        unset($data['featured_image']);

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);

        $post->update($data);
        $post->tags()->sync($tagIds);

        return redirect()
            ->route('admin.blog.posts.edit', $post)
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $post): RedirectResponse
    {
        if ($post->featured_image_path) {
            Storage::disk('public')->delete($post->featured_image_path);
        }

        $post->tags()->detach();
        $post->delete();

        return redirect()
            ->route('admin.blog.posts.index')
            ->with('success', 'Blog post deleted successfully.');
    }
}
