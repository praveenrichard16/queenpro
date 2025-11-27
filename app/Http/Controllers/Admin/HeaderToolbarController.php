<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderToolbarItem;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class HeaderToolbarController extends Controller
{
    public function index(): View
    {
        $items = HeaderToolbarItem::query()
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.cms.header-toolbar.index', [
            'items' => $items,
        ]);
    }

    public function create(): View
    {
        return view('admin.cms.header-toolbar.create', [
            'item' => new HeaderToolbarItem(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? (HeaderToolbarItem::max('sort_order') ?? 0) + 1;

        HeaderToolbarItem::create($data);

        return redirect()
            ->route('admin.cms.header.toolbar.index')
            ->with('success', 'Toolbar item created successfully.');
    }

    public function edit(HeaderToolbarItem $toolbar): View
    {
        return view('admin.cms.header-toolbar.edit', [
            'item' => $toolbar,
        ]);
    }

    public function update(Request $request, HeaderToolbarItem $toolbar): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active', true);

        $toolbar->update($data);

        return redirect()
            ->route('admin.cms.header.toolbar.index')
            ->with('success', 'Toolbar item updated successfully.');
    }

    public function destroy(HeaderToolbarItem $toolbar): RedirectResponse
    {
        $toolbar->delete();

        return redirect()
            ->route('admin.cms.header.toolbar.index')
            ->with('success', 'Toolbar item deleted successfully.');
    }

    public function settings(): View
    {
        $settings = [
            'toolbar_height' => Setting::getValue('toolbar_height', ''),
            'toolbar_background_color' => Setting::getValue('toolbar_background_color', 'gradient'),
            'toolbar_mode' => Setting::getValue('toolbar_mode', 'scrolling'),
            'toolbar_font_size' => Setting::getValue('toolbar_font_size', ''),
        ];

        return view('admin.cms.header-toolbar.settings', [
            'settings' => $settings,
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'toolbar_height' => ['nullable', 'string', 'max:50'],
            'toolbar_background_color' => ['nullable', 'string', 'max:255'],
            'toolbar_mode' => ['nullable', 'in:scrolling,fixed'],
            'toolbar_font_size' => ['nullable', 'string', 'max:50'],
        ]);

        // Trim and save values, use empty string if not provided
        $toolbarHeight = isset($validated['toolbar_height']) ? trim($validated['toolbar_height']) : '';
        Setting::setValue('toolbar_height', $toolbarHeight);
        
        Setting::setValue('toolbar_background_color', $validated['toolbar_background_color'] ?? 'gradient');
        Setting::setValue('toolbar_mode', $validated['toolbar_mode'] ?? 'scrolling');
        
        $toolbarFontSize = isset($validated['toolbar_font_size']) ? trim($validated['toolbar_font_size']) : '';
        Setting::setValue('toolbar_font_size', $toolbarFontSize);

        // Explicitly clear cache for toolbar settings to ensure fresh values
        Cache::forget('setting_toolbar_height');
        Cache::forget('setting_toolbar_background_color');
        Cache::forget('setting_toolbar_mode');
        Cache::forget('setting_toolbar_font_size');
        
        // Clear view cache to ensure Blade views are recompiled
        Artisan::call('view:clear');

        return redirect()
            ->route('admin.cms.header.toolbar.settings')
            ->with('success', 'Toolbar settings updated successfully.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'text' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50'],
            'text_color' => ['nullable', 'string', 'max:20', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'link' => ['nullable', 'url', 'max:2048'],
            'font_size' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
