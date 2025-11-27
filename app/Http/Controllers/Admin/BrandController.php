<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $query = Brand::withCount('products');

        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        if (request('status') !== null) {
            $query->where('is_active', request('status'));
        }

        $brands = $query->orderBy('name')->paginate(20);

        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {
        $brand = new Brand();
        return view('admin.brands.form', compact('brand'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:brands,name'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'icon' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:1024'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image_alt_text' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        // Convert empty strings to null for nullable fields
        $data['description'] = $data['description'] === '' ? null : ($data['description'] ?? null);
        $data['image_alt_text'] = $data['image_alt_text'] === '' ? null : ($data['image_alt_text'] ?? null);
        $data['meta_title'] = $data['meta_title'] === '' ? null : ($data['meta_title'] ?? null);
        $data['meta_description'] = $data['meta_description'] === '' ? null : ($data['meta_description'] ?? null);

        $payload = [
            'name' => $data['name'],
            'slug' => Str::slug($data['name']) . '-' . Str::random(4),
            'description' => $data['description'],
            'is_active' => $request->boolean('is_active', true),
            'image_alt_text' => $data['image_alt_text'],
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
        ];

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands/logos', 'public');
            $payload['logo_path'] = Storage::url($logoPath);
        }

        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('brands/icons', 'public');
            $payload['icon_path'] = Storage::url($iconPath);
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('brands/images', 'public');
            $payload['image_path'] = Storage::url($imagePath);
        }

        Brand::create($payload);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.form', compact('brand'));
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:brands,name,' . $brand->id],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'icon' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:1024'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image_alt_text' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        // Convert empty strings to null for nullable fields
        $data['description'] = $data['description'] === '' ? null : ($data['description'] ?? null);
        $data['image_alt_text'] = $data['image_alt_text'] === '' ? null : ($data['image_alt_text'] ?? null);
        $data['meta_title'] = $data['meta_title'] === '' ? null : ($data['meta_title'] ?? null);
        $data['meta_description'] = $data['meta_description'] === '' ? null : ($data['meta_description'] ?? null);

        $payload = [
            'name' => $data['name'],
            'description' => $data['description'],
            'is_active' => $request->boolean('is_active', true),
            'image_alt_text' => $data['image_alt_text'],
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
        ];

        if ($brand->name !== $data['name']) {
            $payload['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        }

        if ($request->hasFile('logo')) {
            $this->deleteStoredFile($brand->logo_path);
            $logoPath = $request->file('logo')->store('brands/logos', 'public');
            $payload['logo_path'] = Storage::url($logoPath);
        }

        if ($request->hasFile('icon')) {
            $this->deleteStoredFile($brand->icon_path);
            $iconPath = $request->file('icon')->store('brands/icons', 'public');
            $payload['icon_path'] = Storage::url($iconPath);
        }

        if ($request->hasFile('image')) {
            $this->deleteStoredFile($brand->image_path);
            $imagePath = $request->file('image')->store('brands/images', 'public');
            $payload['image_path'] = Storage::url($imagePath);
        }

        $brand->update($payload);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        if ($brand->products()->exists()) {
            return back()->with('error', 'Cannot delete brand with products assigned.');
        }

        $this->deleteStoredFile($brand->logo_path);
        $this->deleteStoredFile($brand->icon_path);
        $this->deleteStoredFile($brand->image_path);

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
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
