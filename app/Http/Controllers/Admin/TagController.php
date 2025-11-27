<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::withCount('products')->orderBy('name')->get();

        return view('admin.tags.index', compact('tags'));
    }

    public function create(): View
    {
        $tag = new Tag();

        return view('admin.tags.form', compact('tag'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:tags,slug'],
            'description' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        // Convert empty strings to null for nullable fields
        $data['description'] = $data['description'] === '' ? null : ($data['description'] ?? null);
        $data['meta_title'] = $data['meta_title'] === '' ? null : ($data['meta_title'] ?? null);
        $data['meta_description'] = $data['meta_description'] === '' ? null : ($data['meta_description'] ?? null);

        $payload = [
            'name' => $data['name'],
            'description' => $data['description'],
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
        ];

        // Handle slug: use manual entry if provided, otherwise let model auto-generate
        if (!empty($data['slug'])) {
            $payload['slug'] = $data['slug'];
        }

        Tag::create($payload);

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag): View
    {
        return view('admin.tags.form', compact('tag'));
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name,' . $tag->id],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:tags,slug,' . $tag->id],
            'description' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        // Convert empty strings to null for nullable fields
        $data['description'] = $data['description'] === '' ? null : ($data['description'] ?? null);
        $data['meta_title'] = $data['meta_title'] === '' ? null : ($data['meta_title'] ?? null);
        $data['meta_description'] = $data['meta_description'] === '' ? null : ($data['meta_description'] ?? null);

        $payload = [
            'name' => $data['name'],
            'description' => $data['description'],
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
        ];

        // Handle slug: use manual entry if provided, otherwise let model auto-generate on name change
        if (!empty($data['slug'])) {
            $payload['slug'] = $data['slug'];
        }

        $tag->update($payload);

        return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully.');
    }
}

