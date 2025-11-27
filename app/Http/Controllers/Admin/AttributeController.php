<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AttributeController extends Controller
{
    public function index(): View
    {
        $query = Attribute::withCount(['values', 'products']);

        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        if (request('status') !== null) {
            $query->where('is_active', request('status'));
        }

        $attributes = $query->orderBy('name')->paginate(20);

        return view('admin.attributes.index', compact('attributes'));
    }

    public function create(): View
    {
        $attribute = new Attribute();
        return view('admin.attributes.form', compact('attribute'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:attributes,name'],
            'type' => ['required', 'in:select,text,color,image'],
            'is_active' => ['sometimes', 'boolean'],
            'values' => ['nullable', 'array'],
            'values.*.value' => ['required_with:values', 'string', 'max:255'],
            'values.*.display_value' => ['nullable', 'string', 'max:255'],
            'values.*.color_code' => ['nullable', 'string', 'max:7'],
            'values.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
        ]);

        $attribute = Attribute::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']) . '-' . Str::random(4),
            'type' => $data['type'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (isset($data['values']) && is_array($data['values'])) {
            foreach ($data['values'] as $index => $valueData) {
                if (empty($valueData['value'])) {
                    continue;
                }

                $valuePayload = [
                    'value' => $valueData['value'],
                    'display_value' => $valueData['display_value'] ?? null,
                    'color_code' => $valueData['color_code'] ?? null,
                    'sort_order' => $index,
                ];

                if (isset($valueData['image']) && $request->hasFile("values.{$index}.image")) {
                    $imagePath = $request->file("values.{$index}.image")->store('attributes/values', 'public');
                    $valuePayload['image_path'] = Storage::url($imagePath);
                }

                $attribute->values()->create($valuePayload);
            }
        }

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute created successfully.');
    }

    public function edit(Attribute $attribute): View
    {
        $attribute->load('values');
        return view('admin.attributes.form', compact('attribute'));
    }

    public function update(Request $request, Attribute $attribute): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:attributes,name,' . $attribute->id],
            'type' => ['required', 'in:select,text,color,image'],
            'is_active' => ['sometimes', 'boolean'],
            'values' => ['nullable', 'array'],
            'values.*.id' => ['nullable', 'exists:attribute_values,id'],
            'values.*.value' => ['required_with:values', 'string', 'max:255'],
            'values.*.display_value' => ['nullable', 'string', 'max:255'],
            'values.*.color_code' => ['nullable', 'string', 'max:7'],
            'values.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'values.*.sort_order' => ['nullable', 'integer'],
        ]);

        $attribute->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($attribute->name !== $data['name']) {
            $attribute->update(['slug' => Str::slug($data['name']) . '-' . Str::random(4)]);
        }

        // Handle values update
        if (isset($data['values']) && is_array($data['values'])) {
            $existingIds = collect($data['values'])->pluck('id')->filter()->all();
            $attribute->values()->whereNotIn('id', $existingIds)->delete();

            foreach ($data['values'] as $index => $valueData) {
                if (empty($valueData['value'])) {
                    continue;
                }

                $valuePayload = [
                    'value' => $valueData['value'],
                    'display_value' => $valueData['display_value'] ?? null,
                    'color_code' => $valueData['color_code'] ?? null,
                    'sort_order' => $valueData['sort_order'] ?? $index,
                ];

                if (isset($valueData['image']) && $request->hasFile("values.{$index}.image")) {
                    $oldValue = $attribute->values()->find($valueData['id'] ?? null);
                    if ($oldValue && $oldValue->image_path) {
                        $this->deleteStoredFile($oldValue->image_path);
                    }
                    $imagePath = $request->file("values.{$index}.image")->store('attributes/values', 'public');
                    $valuePayload['image_path'] = Storage::url($imagePath);
                }

                if (isset($valueData['id']) && $valueData['id']) {
                    $attribute->values()->where('id', $valueData['id'])->update($valuePayload);
                } else {
                    $attribute->values()->create($valuePayload);
                }
            }
        }

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute updated successfully.');
    }

    public function destroy(Attribute $attribute): RedirectResponse
    {
        if ($attribute->products()->exists()) {
            return back()->with('error', 'Cannot delete attribute with products assigned.');
        }

        foreach ($attribute->values as $value) {
            if ($value->image_path) {
                $this->deleteStoredFile($value->image_path);
            }
        }

        $attribute->delete();

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute deleted successfully.');
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
