<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogTagRequest;
use App\Http\Requests\Admin\UpdateBlogTagRequest;
use App\Models\BlogTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BlogTagController extends Controller
{
    public function index(): View
    {
        $tags = BlogTag::query()
            ->withCount('posts')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.blog.tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('admin.blog.tags.form', ['tag' => new BlogTag()]);
    }

    public function store(StoreBlogTagRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $tag = BlogTag::create($data);

        return redirect()
            ->route('admin.blog.tags.index')
            ->with('success', "Tag \"{$tag->name}\" created successfully.");
    }

    public function edit(BlogTag $tag): View
    {
        return view('admin.blog.tags.form', compact('tag'));
    }

    public function update(UpdateBlogTagRequest $request, BlogTag $tag): RedirectResponse
    {
        $tag->update($request->validated());

        return redirect()
            ->route('admin.blog.tags.index')
            ->with('success', "Tag \"{$tag->name}\" updated successfully.");
    }

    public function destroy(BlogTag $tag): RedirectResponse
    {
        if ($tag->posts()->exists()) {
            return back()->with('error', 'Cannot delete a tag that is assigned to posts.');
        }

        $tag->delete();

        return redirect()
            ->route('admin.blog.tags.index')
            ->with('success', 'Tag deleted successfully.');
    }
}
