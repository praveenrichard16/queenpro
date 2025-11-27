<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalPageController extends Controller
{
    public function index(): View
    {
        $types = LegalPage::getTypes();
        $pages = LegalPage::query()->get()->keyBy('type');

        // Ensure all types exist in database (with default empty values)
        foreach ($types as $type => $label) {
            if (!isset($pages[$type])) {
                $pages[$type] = new LegalPage([
                    'type' => $type,
                    'title' => $label,
                    'content' => '',
                    'is_active' => true,
                ]);
            }
        }

        return view('admin.cms.legal-pages.index', [
            'pages' => $pages,
            'types' => $types,
        ]);
    }

    public function edit(string $type): View
    {
        $validTypes = array_keys(LegalPage::getTypes());

        if (!in_array($type, $validTypes)) {
            abort(404, 'Invalid legal page type.');
        }

        $page = LegalPage::query()
            ->where('type', $type)
            ->first();

        // Auto-create if doesn't exist
        if (!$page) {
            $page = LegalPage::create([
                'type' => $type,
                'title' => LegalPage::getTypes()[$type],
                'content' => '',
                'is_active' => true,
            ]);
        }

        return view('admin.cms.legal-pages.edit', [
            'page' => $page,
        ]);
    }

    public function update(Request $request, string $type): RedirectResponse
    {
        $validTypes = array_keys(LegalPage::getTypes());

        if (!in_array($type, $validTypes)) {
            abort(404, 'Invalid legal page type.');
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $page = LegalPage::query()
            ->where('type', $type)
            ->first();

        if ($page) {
            $page->update($data);
        } else {
            $data['type'] = $type;
            LegalPage::create($data);
        }

        return redirect()
            ->route('admin.cms.legal-pages.index')
            ->with('success', 'Legal page updated successfully.');
    }
}
