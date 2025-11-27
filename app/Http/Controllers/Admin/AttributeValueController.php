<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttributeValueController extends Controller
{
    public function store(Request $request, Attribute $attribute): RedirectResponse
    {
        $data = $request->validate([
            'value' => ['required', 'string', 'max:255'],
            'display_value' => ['nullable', 'string', 'max:255'],
            'color_code' => ['nullable', 'string', 'max:7'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $payload = [
            'value' => $data['value'],
            'display_value' => $data['display_value'] ?? null,
            'color_code' => $data['color_code'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('attributes/values', 'public');
            $payload['image_path'] = Storage::url($imagePath);
        }

        $attribute->values()->create($payload);

        return back()->with('success', 'Attribute value added successfully.');
    }

    public function update(Request $request, AttributeValue $attributeValue): RedirectResponse
    {
        $data = $request->validate([
            'value' => ['required', 'string', 'max:255'],
            'display_value' => ['nullable', 'string', 'max:255'],
            'color_code' => ['nullable', 'string', 'max:7'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $payload = [
            'value' => $data['value'],
            'display_value' => $data['display_value'] ?? null,
            'color_code' => $data['color_code'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ];

        if ($request->hasFile('image')) {
            $this->deleteStoredFile($attributeValue->image_path);
            $imagePath = $request->file('image')->store('attributes/values', 'public');
            $payload['image_path'] = Storage::url($imagePath);
        }

        $attributeValue->update($payload);

        return back()->with('success', 'Attribute value updated successfully.');
    }

    public function destroy(AttributeValue $attributeValue): RedirectResponse
    {
        if ($attributeValue->products()->exists()) {
            return back()->with('error', 'Cannot delete attribute value with products assigned.');
        }

        $this->deleteStoredFile($attributeValue->image_path);
        $attributeValue->delete();

        return back()->with('success', 'Attribute value deleted successfully.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'values' => ['required', 'array'],
            'values.*.id' => ['required', 'exists:attribute_values,id'],
            'values.*.sort_order' => ['required', 'integer'],
        ]);

        foreach ($data['values'] as $item) {
            AttributeValue::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
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
