<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogCategoryRequest;
use App\Http\Requests\Admin\UpdateBlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(): View
    {
        $categories = BlogCategory::query()
            ->withCount('posts')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.blog.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.blog.categories.form', ['category' => new BlogCategory()]);
    }

    public function store(StoreBlogCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('icon')) {
            $data['icon_path'] = $request->file('icon')->store('blog/categories', 'public');
        }
        unset($data['icon']);

        BlogCategory::create($data);

        return redirect()
            ->route('admin.blog.categories.index')
            ->with('success', 'Blog category created successfully.');
    }

    public function edit(BlogCategory $category): View
    {
        return view('admin.blog.categories.form', compact('category'));
    }

    public function update(UpdateBlogCategoryRequest $request, BlogCategory $category): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('icon')) {
            if ($category->icon_path) {
                Storage::disk('public')->delete($category->icon_path);
            }
            $data['icon_path'] = $request->file('icon')->store('blog/categories', 'public');
        }
        unset($data['icon']);

        $category->update($data);

        return redirect()
            ->route('admin.blog.categories.index')
            ->with('success', 'Blog category updated successfully.');
    }

    public function destroy(BlogCategory $category): RedirectResponse
    {
        if ($category->posts()->exists()) {
            return back()->with('error', 'Cannot delete a category that has associated posts.');
        }

        if ($category->icon_path) {
            Storage::disk('public')->delete($category->icon_path);
        }

        $category->delete();

        return redirect()
            ->route('admin.blog.categories.index')
            ->with('success', 'Blog category deleted successfully.');
    }
}
