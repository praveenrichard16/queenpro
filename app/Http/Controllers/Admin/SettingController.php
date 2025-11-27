<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $settings = collect([
            'site_name' => Setting::getValue('site_name', config('app.name')),
            'site_tagline' => Setting::getValue('site_tagline'),
            'contact_email' => Setting::getValue('contact_email'),
            'contact_phone' => Setting::getValue('contact_phone'),
            'contact_street' => Setting::getValue('contact_street'),
            'contact_city' => Setting::getValue('contact_city'),
            'contact_state' => Setting::getValue('contact_state'),
            'contact_postal' => Setting::getValue('contact_postal'),
            'contact_country' => Setting::getValue('contact_country'),
            'default_country_code' => Setting::getValue('default_country_code', '91'),
            'site_logo' => Setting::getValue('site_logo'),
            'site_logo_dark' => Setting::getValue('site_logo_dark'),
            'site_logo_mobile' => Setting::getValue('site_logo_mobile'),
            'mobile_logo_width' => Setting::getValue('mobile_logo_width', '175'),
            'mobile_logo_height' => Setting::getValue('mobile_logo_height', '155'),
            'site_favicon' => Setting::getValue('site_favicon'),
            'google_analytics_id' => Setting::getValue('google_analytics_id'),
            'google_tag_manager_id' => Setting::getValue('google_tag_manager_id'),
            'google_search_console_verification' => Setting::getValue('google_search_console_verification'),
        ]);

        return view('admin.settings.general', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:255'],
            'contact_street' => ['nullable', 'string', 'max:255'],
            'contact_city' => ['nullable', 'string', 'max:255'],
            'contact_state' => ['nullable', 'string', 'max:255'],
            'contact_postal' => ['nullable', 'string', 'max:64'],
            'contact_country' => ['nullable', 'string', 'max:255'],
            'default_country_code' => ['nullable', 'string', 'max:5', 'regex:/^\d+$/'],
            'site_logo' => ['nullable', 'image', 'max:2048'],
            'site_logo_dark' => ['nullable', 'image', 'max:2048'],
            'site_logo_mobile' => ['nullable', 'image', 'max:2048'],
            'mobile_logo_width' => ['nullable', 'integer', 'min:50', 'max:500'],
            'mobile_logo_height' => ['nullable', 'integer', 'min:50', 'max:500'],
            'site_favicon' => ['nullable', 'image', 'max:1024'],
            'google_analytics_id' => ['nullable', 'string', 'max:255'],
            'google_tag_manager_id' => ['nullable', 'string', 'max:255'],
            'google_search_console_verification' => ['nullable', 'string'],
        ]);

        $uploadKeys = ['site_logo', 'site_logo_dark', 'site_logo_mobile', 'site_favicon'];
        foreach ($uploadKeys as $fileKey) {
            if ($request->hasFile($fileKey)) {
                // Delete old file if exists
                $oldPath = Setting::getValue($fileKey);
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file($fileKey)->store('settings', 'public');
                Setting::setValue($fileKey, $path);
            }
        }

        foreach (collect($validated)->except($uploadKeys) as $key => $value) {
            Setting::setValue($key, $value);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}

