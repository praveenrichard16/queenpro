<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BreadcrumbController extends Controller
{
    public function settings(): View
    {
        $settings = [
            'breadcrumb_background_image' => Setting::getValue('breadcrumb_background_image', ''),
            'breadcrumb_overlay_opacity' => Setting::getValue('breadcrumb_overlay_opacity', '0.5'),
            'breadcrumb_background_color' => Setting::getValue('breadcrumb_background_color', '#f3f4f6'),
        ];

        return view('admin.cms.breadcrumb.settings', [
            'settings' => $settings,
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'breadcrumb_background_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'breadcrumb_overlay_opacity' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'breadcrumb_background_color' => ['nullable', 'string', 'max:50'],
            'remove_background_image' => ['nullable', 'boolean'],
        ]);

        // Handle image removal (only if no new file is being uploaded)
        if ($request->has('remove_background_image') && $request->boolean('remove_background_image') && !$request->hasFile('breadcrumb_background_image')) {
            $oldImage = Setting::getValue('breadcrumb_background_image');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
            Setting::setValue('breadcrumb_background_image', '');
            Cache::forget('setting_breadcrumb_background_image');
        }

        // Handle image upload
        if ($request->hasFile('breadcrumb_background_image')) {
            $file = $request->file('breadcrumb_background_image');
            
            // Check if file is valid
            if (!$file->isValid()) {
                return back()->withInput()->withErrors(['breadcrumb_background_image' => 'The uploaded file is not valid.']);
            }

            // Check file size (in KB, max 5120 KB = 5MB)
            if ($file->getSize() > 5120 * 1024) {
                return back()->withInput()->withErrors(['breadcrumb_background_image' => 'The image size must not exceed 5MB.']);
            }

            try {
                // Delete old image if exists
                $oldImage = Setting::getValue('breadcrumb_background_image');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }

                // Ensure directory exists
                $directory = 'settings/breadcrumb';
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory, 0755, true);
                }

                // Store new image
                $path = $file->store($directory, 'public');
                
                if (!$path) {
                    return back()->withInput()->withErrors(['breadcrumb_background_image' => 'Failed to store the image. Please check storage permissions.']);
                }

                Setting::setValue('breadcrumb_background_image', $path);
                Cache::forget('setting_breadcrumb_background_image');
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['breadcrumb_background_image' => 'Failed to upload image: ' . $e->getMessage()]);
            }
        }

        // Save other settings
        if ($request->has('breadcrumb_overlay_opacity')) {
            Setting::setValue('breadcrumb_overlay_opacity', $request->input('breadcrumb_overlay_opacity'));
            Cache::forget('setting_breadcrumb_overlay_opacity');
        }

        if ($request->has('breadcrumb_background_color')) {
            Setting::setValue('breadcrumb_background_color', $request->input('breadcrumb_background_color'));
            Cache::forget('setting_breadcrumb_background_color');
        }

        return redirect()->route('admin.cms.breadcrumb.settings')
            ->with('success', 'Breadcrumb settings updated successfully.');
    }
}

