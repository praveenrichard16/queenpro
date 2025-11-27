<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AboutPageController extends Controller
{
    public function edit(): View
    {
        $page = AboutPage::query()->first();

        // Auto-create if doesn't exist
        if (!$page) {
            $page = AboutPage::create([
                'title' => 'About Us',
                'content' => '',
                'is_active' => true,
            ]);
        }

        return view('admin.cms.about-us.edit', [
            'page' => $page,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'image_1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image_2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image_3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $page = AboutPage::query()->first();

        // Handle image uploads
        foreach (['image_1', 'image_2', 'image_3'] as $imageField) {
            if ($request->hasFile($imageField)) {
                // Delete old image if exists
                if ($page && $page->$imageField) {
                    $oldPath = ltrim(str_replace('/storage/', '', $page->$imageField), '/');
                    Storage::disk('public')->delete($oldPath);
                }

                // Store new image
                $path = $request->file($imageField)->store('about-us', 'public');
                $data[$imageField] = Storage::url($path);
            } else {
                // Keep existing image if not uploading new one
                if ($page && $page->$imageField) {
                    $data[$imageField] = $page->$imageField;
                }
            }
        }

        if ($page) {
            $page->update($data);
        } else {
            AboutPage::create($data);
        }

        return redirect()
            ->route('admin.cms.about-us.edit')
            ->with('success', 'About Us page updated successfully.');
    }
}

