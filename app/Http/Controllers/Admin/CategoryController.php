<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products', 'children')
            ->with('parent')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $category = new Category();
        $parents = Category::orderBy('name')->get();

        return view('admin.categories.form', compact('category', 'parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:categories,slug'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'icon' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:1024'],
            'image_alt_text' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        // Convert empty strings to null for nullable fields
        $data['parent_id'] = $data['parent_id'] ?? null;
        $data['parent_id'] = $data['parent_id'] === '' ? null : $data['parent_id'];
        $data['description'] = $data['description'] === '' ? null : ($data['description'] ?? null);
        $data['image_alt_text'] = $data['image_alt_text'] === '' ? null : ($data['image_alt_text'] ?? null);
        $data['meta_title'] = $data['meta_title'] === '' ? null : ($data['meta_title'] ?? null);
        $data['meta_description'] = $data['meta_description'] === '' ? null : ($data['meta_description'] ?? null);

        $payload = [
            'name' => $data['name'],
            'description' => $data['description'],
            'parent_id' => $data['parent_id'],
            'is_active' => $request->boolean('is_active', true),
            'image_alt_text' => $data['image_alt_text'],
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
        ];

        // Handle slug: use manual entry if provided, otherwise let model auto-generate
        if (!empty($data['slug'])) {
            $payload['slug'] = $data['slug'];
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $payload['image_path'] = Storage::url($imagePath);
        }

        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('categories/icons', 'public');
            $payload['icon_path'] = Storage::url($iconPath);
        }

        Category::create($payload);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        $parents = Category::where('id', '!=', $category->id)->orderBy('name')->get();

        return view('admin.categories.form', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:categories,slug,' . $category->id],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id', 'not_in:' . $category->id],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'icon' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:1024'],
            'image_alt_text' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        // Convert empty strings to null for nullable fields
        $data['parent_id'] = $data['parent_id'] ?? null;
        $data['parent_id'] = $data['parent_id'] === '' ? null : $data['parent_id'];
        $data['description'] = $data['description'] === '' ? null : ($data['description'] ?? null);
        $data['image_alt_text'] = $data['image_alt_text'] === '' ? null : ($data['image_alt_text'] ?? null);
        $data['meta_title'] = $data['meta_title'] === '' ? null : ($data['meta_title'] ?? null);
        $data['meta_description'] = $data['meta_description'] === '' ? null : ($data['meta_description'] ?? null);

        $payload = [
            'name' => $data['name'],
            'description' => $data['description'],
            'parent_id' => $data['parent_id'],
            'is_active' => $request->boolean('is_active', true),
            'image_alt_text' => $data['image_alt_text'],
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
        ];

        // Handle slug: use manual entry if provided, otherwise let model auto-generate on name change
        if (!empty($data['slug'])) {
            $payload['slug'] = $data['slug'];
        }

        if ($request->hasFile('image')) {
            $this->deleteStoredFile($category->image_path);
            $imagePath = $request->file('image')->store('categories', 'public');
            $payload['image_path'] = Storage::url($imagePath);
        }

        if ($request->hasFile('icon')) {
            $this->deleteStoredFile($category->icon_path);
            $iconPath = $request->file('icon')->store('categories/icons', 'public');
            $payload['icon_path'] = Storage::url($iconPath);
        }

        $category->update($payload);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->children()->exists()) {
            return back()->with('error', 'Cannot delete category with subcategories.');
        }

        if ($category->products()->exists()) {
            return back()->with('error', 'Cannot delete category with products assigned.');
        }

        $this->deleteStoredFile($category->image_path);
        $this->deleteStoredFile($category->icon_path);

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    protected function deleteStoredFile(?string $url): void
    {
        if (!$url) {
            return;
        }

        $relative = ltrim(str_replace('/storage/', '', $url), '/');

        if ($relative !== '') {
            Storage::disk('public')->delete($relative);
        }
    }
}

